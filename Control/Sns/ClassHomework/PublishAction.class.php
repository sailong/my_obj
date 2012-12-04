<?php
class PublishAction extends SnsController {
    
    public function _initialize(){
        import('@.Common_wmw.Pathmanagement_sns');
        parent::_initialize();
    }
    
    /*
     * 班级作业
     */
    public function index() {
       
        $client_account = $this->getCookieAccount();
        $subject_infos = $this->getSubjectInfoByClientAccout($client_account);
        $this->assign('subject_infos',$subject_infos);
        $this->display('index');
    }
    
    /*
     * 根据科目id和帐号取出班级列表
     */
    public function class_info_json() {
        $subject_id = $this->objInput->postInt('subject_id');
        
        if(empty($subject_id)) {
            $this->ajaxReturn(null, '科目信息不能为空!', -1, 'json');
        }
        
        $mClassTeacher = ClsFactory::Create('Model.mClassTeacher');
        $class_teacher_list = $mClassTeacher->getClassInfoByuidAndsubjectid($this->user['client_account'], $subject_id);
        
        if(empty($class_teacher_list)) {
            $this->ajaxReturn(null, '该科目下没有任何班级!', -1, 'json');
        }
        
        //查询班级id
        $class_codes = array();
        foreach($class_teacher_list as $class_teacher) {
            $class_codes[] = $class_teacher['class_code'];
        }
        
        $mClassInfo = ClsFactory::Create('Model.mClassInfo');
        $class_info_list = $mClassInfo->getClassInfoBaseById($class_codes);
        if(empty($class_info_list)) {
            $this->ajaxReturn(null, '获取班级列表失败', -1, 'json');
        }
        
        $this->ajaxReturn($class_info_list, '获取班级列表成功', 1, 'json');
    }
    
    /*
     * 根据班级id获取班级学生信息
     */
    public function student_info_json() {
        $class_code = $this->objInput->postInt('class_code');
        $mClientClass = ClsFactory::Create('Model.mClientClass');
        $class_student_infos = $mClientClass->getStudentInfoByClassCodeAndType($class_code,CLIENT_TYPE_STUDENT);
        $client_accounts = array();
        foreach($class_student_infos as $client_class_id=>$val) {
            $client_accounts[] = $val['client_account'];
        }
        
        
        $mClientInfo = ClsFactory::Create('Model.mUser');
        $client_infos = $mClientInfo->getClientAccountById($client_accounts);
        
        foreach($client_infos as $account=>$val) {
            $val['client_headimg'] = Pathmanagement_sns::getHeadImg($account).$val['client_headimg'];
            $client_infos[$account] = $val;
        }
        
        if(!empty($client_infos)) {
            $this->ajaxReturn($client_infos, '获取学生列表成功', 1, 'json');
        }else {
            $this->ajaxReturn($client_infos, '获取学生列表失败', -1, 'json');
        }
    }
    
    /*
     * 发布班级作业信息
     */
    public function write_homework() {
        $homeworkContent = $this->objInput->postStr('content');
        $homeworktime = $this->objInput->postStr('homeworkdate');
        $accepters_accounts = $this->objInput->postStr('student_ids');
        $client_account = $this->getCookieAccount();
        $subject_id = $this->objInput->postInt('subject_id');
        $is_sms = $this->objInput->postInt('is_sms');
        if(empty($accepters_accounts)) {
            $accepters = '全班同学';
        } else {
            $accepters = count(explode(',',$accepters_accounts)).'人';
        }
        
        $y = date('Y');
        $m = date('m');
        $d = date('d');
        
         //班级作业附件上传
        if(!empty($_FILES['file_name']['name'])) {
            import('@.Common_wmw.WmwUpload');
            $uploadObject = new WmwUpload();
            $options = array(
                'allow_type' => array('excel','txt','ppt','doc','docx','pdf','rar','zip','xls'),
                'attachmentspath' => Pathmanagement_sns::uploadHomework() . $client_account . '/'."$y/$m/$d", //解析规则例：attchment/homework/11070004/2012/12/03/*.txt
                'renamed' => true,
                'ifresize' => true,
                //文件上传类的大小使用的单位是:kb，在这里需要转换
                'max_size' => 1024,
            );
            
            $upload_rs = $uploadObject->upfile('file_name', $options);
            if(empty($upload_rs)) {
                exit;
            }
        }
        
        $dataarr = array(
            'content' => $homeworkContent,
            'class_code' => $this->user['class_code'],
            'end_time' => strtotime($homeworktime),
            'accepters' => $accepters,
            'add_account' => $client_account,
            'add_time' => strtotime("$y-$m-$d H:i:s"),
            'subject_id' => $subject_id,
            'attachment' => $upload_rs['getfilename'],
        );
        
        //班级作业入库
        $mClassHomework = ClsFactory::Create('Model.mClassHomework');
        $homework_id = $mClassHomework->addHomework($dataarr,'true');
        
        //判断是否发送短信
        if($is_sms == 1 && $this->user['client_type'] == CLIENT_TYPE_TEACHER && !empty($homework_id)) {
            $dataarr['is_sms'] = $is_sms;
            //获取当前学校的运营策略    
            $operationStrategy = $this->getOperationStrategy();
            
            
            $mFamilyRelation = ClsFactory::Create('Model.mFamilyRelation');
            $FamilyInfos = array_shift($mFamilyRelation->getFamilyRelationByUid($accepters_accounts));
            //获取家长帐号
            $parents_account = array();
            foreach($FamilyInfos as $relation_id=>$relationinfo) {
                $parents_account[] = $relationinfo['family_account'];
            }
            
            //通过家长账号获得business_phones
			$mBusinesphone  = ClsFactory::Create('Model.mBusinessphone');
			$phone_list = $mBusinesphone->getbusinessphonebyalias_id($parents_account);
			//屏蔽短信内容样式及图片
			import('@.Common_wmw.WmwString');
			$ClassHomeworkContent = strip_tags(WmwString::unhtmlspecialchars($homeworkContent));
			
			import('@.Control.Api.Smssend.Smssendapi');
            $smssendapi_obj = new Smssendapi();
            foreach($phone_list as $account_phone_id1=> $send) {
                $addSmsSendResult = $smssendapi_obj->send($send['account_phone_id2'], $ClassHomeworkContent, $operationStrategy);
            }
            
            //发送对象表入库
            $accepters_account_arr = explode(',',$accepters_accounts);
            if(!empty($homework_id)) {
                $dataarr = array();
                foreach ($accepters_account_arr as $val) {
                    $dataarr_send[] = array(
                        'homework_id' => $homework_id,
                        'client_account' => $val,
                        'add_time' => time()
                    );
                }
                
            }
            
            $mClassHomeworkSend = ClsFactory::Create('Model.mClassHomeworkSend');
            $resault_send = $mClassHomeworkSend->addHomeworkSend($dataarr_send);
            
        }
        
        $this->showSuccess('班级作业发布成功!','/Sns/ClassHomework/Classhomework');
    }
    
    
    
    /*
     * 根据老师帐号获取科目列表
     */
    private  function getSubjectInfoByClientAccout($client_account) {
        $mClassTeacher  = ClsFactory::Create('Model.mSchoolTeacher');
        $subject_relation_infos = $mClassTeacher->getSchoolTeacherByTeacherUid($client_account);
        //获取当前老师所班级id及科目id
        $subject_ids = array();
        foreach($subject_relation_infos[$client_account] as $val) {
            $subject_ids[] = $val['subject_id'];
        }
        
        if(!empty($subject_ids)) {
            $mSubject_info = CLsFactory::Create('Model.mSubjectInfo');
            $subject_infos = $mSubject_info->getSubjectInfoById($subject_ids);
        }
        
        return !empty($subject_infos) ? $subject_infos : false;
    }
    
    
    /*
     * 公共方法获取当前用户的运营策略
     */
    private function getOperationStrategy () {
        //获取当前用户所在学校的运营策略
	    $schoolinfo = array_shift($this->user['school_info']);
	    $schoolid = $schoolinfo['school_id'];
		$mSchoolInfo = ClsFactory::Create('Model.mSchoolInfo');
		$schoolInfo = $mSchoolInfo->getSchoolInfoById($schoolid);
		$operationStrategy = intval($schoolInfo[$schoolid]['operation_strategy']);//获取该学校的运营策略
		
		return !empty($operationStrategy) ? $operationStrategy : false;
    }
    
    /*
     * 编辑器上传图片通用方法
     */
    public function uploadPath() {
        import("@.Common_wmw.Pathmanagement_sns");
        $uploadPath = Pathmanagement_sns::uploadXheditor();
        $showPath = Pathmanagement_sns::getXheditorimgPath();
        import('@.Control.Api.XheditorApi');
        $uploadobj = new XheditorApi();
        $uploadobj->upload($uploadPath,$showPath);
    }
    
    
}