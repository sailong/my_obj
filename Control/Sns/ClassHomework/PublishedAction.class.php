<?php
class PublishedAction extends SnsController {
    
    public function _initialize(){
        import('@.Common_wmw.Pathmanagement_sns');
        parent::_initialize();
    }
    
    /*
     * 依据当前帐号获取班级作业列表
     */
    public function index() {
        if($this->user['client_type'] == CLIENT_TYPE_TEACHER) {
            $client_account = $this->user['client_account'];
            $subject_id = $this->objInput->postInt('subject_id');
            $timetype = $this->objInput->postStr('timetype');
            $startdate = $this->objInput->postStr('startdate');
            $enddate = $this->objInput->postStr('enddate');
            
            $startdate_str = strtotime($startdate);
            $enddate_str = strtotime($enddate);
            if(empty($enddate_str)) {
                $enddate_str = strtotime(date('Y-m-d'));
            }
            
            $end_time = strtotime("+1 day", $enddate_str);
            
            $subject_infos = $this->getSubjectInfoByClientAccout($client_account);
            
            $homeworkInfos = $this->getHomeworkList($subject_id,$timetype,$startdate_str,$end_time,$subject_infos);
            
            $this->assign('subject_id',$subject_id);
            $this->assign('timetype',$timetype);
            $this->assign('startdate',$startdate);
            $this->assign('enddate',$enddate);
            $this->assign('homeworkInfos',$homeworkInfos);
            $this->assign('subject_infos',$subject_infos);
            $this->display('search_index');
       }
    }
    
	/**
     * 班级作业附件下载
     */
    public function download_file() {
       $homework_id = $this->objInput->getInt('homework_id');
       
       $path = "";
       $down_file = ClsFactory::Create('@.Common_wmw.WmwDownload');
       $down_file->downfile($filename, '班级作业下载');
    }
    
    
    /*
     * 学生、家长登录显示作业列表
     */
    public function getHomeworkInfosByStudent() {
        if($this->user['client_type'] == CLIENT_TYPE_STUDENT || $this->user['client_type'] == CLIENT_TYPE_FAMILY) {
            
            $subject_id = $this->objInput->postInt('subject_id');
            $timetype = $this->objInput->postStr('timetype');
            $startdate = $this->objInput->postStr('startdate');
            $enddate = $this->objInput->postStr('enddate');
            
            $startdate_str = strtotime($startdate);
            $enddate_str = strtotime($enddate);
            if(empty($enddate_str)) {
                $enddate_str = strtotime(date('Y-m-d'));
            }
            
            $end_time = strtotime("+1 day", $enddate_str);
            
            $class_code = $this->objInput->getInt('class_code');
            if(empty($class_code)) {
                $class_code = key($this->user['class_info']);
            }
            
            //获取班级科目
            $mClassTeacher = ClsFactory::Create('Model.mClassTeacher');
            $subejct_infos = $mClassTeacher->getClassTeacherByClassCode($class_code);
            
            $subject_ids = array();
            foreach($subejct_infos[$class_code] as $class_teacher_id=>$val) {
                $subject_ids[] = $val['subject_id'];
            }
            
            $mSubjectInfo = ClsFactory::Create('Model.mSubjectInfo');
            $subejct_infos = $mSubjectInfo->getSubjectInfoById($subject_ids);
            
            $homeworkInfos = $this->getHomeworkList($subject_id,$timetype,$startdate_str,$end_time,$subejct_infos);
            
            $this->assign('subject_id',$subject_id);
            $this->assign('timetype',$timetype);
            $this->assign('startdate',$startdate);
            $this->assign('enddate',$enddate);
            $this->assign('homeworkInfos',$homeworkInfos);
            $this->assign('subject_infos',$subejct_infos);
            $this->display('student_search_index');
        }
        
    }
    
    
    //根据班级作业id查询发送对象列表
    public function accepters_json() {
        $homeworkid = $this->objInput->postInt('homework_id');
        $mClassHomeworkSend = ClsFactory::Create('Model.mClassHomeworkSend');
        $accepters_list = $mClassHomeworkSend->getHomeworkSendByhomeworkid($homeworkid);
        $mUser = ClsFactory::Create('Model.mUser');
        
        $countarr = array('y' => 0,'n' => 0); //y标识已看 n标识未看
        foreach($accepters_list[$homeworkid] as $id=>$val) {
            if($val['is_view'] == 0) {
                $countarr['n']++;
            }elseif($val['is_view'] == 1) {
                $countarr['y']++;
            }
            
            $client_name_arr = array_shift($mUser->getClientAccountById($val['client_account']));
            $val['client_name'] = $client_name_arr['client_name'];
            $accepters_list[$homeworkid][$id] = $val;
        }
        $accepters_list['count'] = $countarr;
        
        if(!empty($accepters_list)) {
            $this->ajaxReturn($accepters_list, '获取对象成功', 1, 'json');
        } else {
            $this->ajaxReturn($accepters_list, '获取对象失败', -1, 'json');
        }
    }
    
    
    /*
     * 根据老师帐号获取科目列表
     */
    private function getSubjectInfoByClientAccout($client_account) {
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
     * 班级作业搜索功能公用方法
     */
    private function getHomeworkList($subject_id,$timetype,$startdate,$enddate,$subject_infos) {
        if(!empty($timetype) && $timetype == 'JZY') {
            $whererarr = array(
                'add_time >= ' .$startdate . ' and add_time < ' . $enddate,
            );
        }elseif(!empty($timetype) && $timetype == 'FBZY') {
            $whererarr = array(
            	'end_time >= ' . $startdate . ' and end_time < ' . $enddate,
            );
        }else {
            $whererarr = array();
        }
        
        $date = strtotime(date('Y-m-d'));
        $mClassHomework = ClsFactory::Create('Model.mClassHomework');
        $homeworkInfos = $mClassHomework->getClassHomework($whererarr);
        
      //根据帐号获获取姓名
        $mUser = ClsFactory::Create('Model.mUser');
        
        foreach($homeworkInfos as $homeworkid=>$homeworkval) {
                $homeworkval['subject_name'] = $subject_infos[$homeworkval['subject_id']]['subject_name']; 
                $homeworkval['add_time'] = date('Y-m-d H:i:s',$homeworkval['add_time']);
                $client_name_arr = array_shift($mUser->getClientAccountById($homeworkval['add_account']));
                $homeworkval['client_name'] = $client_name_arr['client_name'];
                if($homeworkval['end_time'] < $date) {
                    $homeworkval['status'] = '已过期';
                } else{
                     $homeworkval['status'] = '正常';
                }
                
                $homeworkInfos[$homeworkid] = $homeworkval;
        }
        
        return !empty($homeworkInfos) ? $homeworkInfos : false;
        
    }
    
    
}