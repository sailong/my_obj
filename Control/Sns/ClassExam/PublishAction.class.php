<?php
class PublishAction extends SnsController{
    public    $_checkClsssCode = true;
    protected $_class_code     = '';
    protected $_client_class   = '';
    
    public function __construct() {
        parent::__construct();
        $this->_class_code = $this->getClassCode();
        $this->_client_class = $this->getClientClass($this->_class_code);
        
    }
    
    /*
     * 添加班级成绩显示模板
     */
    public function index() {
        $class_code = $this->_class_code;
        $client_account = $this->user['client_account'];
        
        //判断用户是否有发布成绩的权限
        if($this->_client_class['client_type'] != 1) {
            $this->showError('您没有权限发布成绩', '/Sns/ClassExam/Exam/index/class_code/' . $class_code);
        }
        
        $subject_list = array();   //教师所教科目（班主任查询所有科目）
        $is_class_teacher = $this->isClassTeacher(); //判断是否在班主任
        if(!empty($is_class_teacher)) {
            $subject_list = $this->getSubjectAll($class_code);
        } else {
            $subject_list = $this->getSubjectByTeacher($class_code, $client_account);
        }
        
        //获取班级学生列表
        $student_list = $this->getStudents($class_code);
        
        $this->assign('class_code',   $class_code);
        $this->assign('subject_list', $subject_list);
        $this->assign('student_list', $student_list);
        
        $this->display('exam_publish');
        
    }
    
    /*
     * 发布成绩
     * 
     */
    public function publishExam() {
     
        
        
        
    }
    
    /**
     * 获取学生信息
     * 添加上学生姓名
     */
    public function getStudents($class_code) {
        if(empty($class_code)) {
            return array();
        }
        
        //通过班级成员关系表获取学生列表
	    $mClientClass = ClsFactory::Create('Model.mClientClass');
	    $tmp_client_class_list = $mClientClass->getClientClassByClassCode($class_code , array('client_type'=>CLIENT_TYPE_STUDENT));
	    $client_class_list = $tmp_client_class_list[$class_code];  //降维度
	    unset($tmp_client_class_list);
    	//获取学生的基本信息
	    $mUser = ClsFactory::Create('Model.mUser');
	    if(!empty($client_class_list)) {
	        $student_uids = array_unique(array_keys($client_class_list));
			$student_list = $mUser->getUserBaseByUid($student_uids);
	    }	    
	    //追加id属性  过滤掉姓名为空的学生
    	if(!empty($student_list)) {
    	    $i = 1;
    	    foreach($student_list as $uid=>$student) {
    	        if(empty($student['client_name'])) {
    	            unset($student_list[$uid]);
    	            continue;
    	        }
    	        $student['id'] = $i++;
    	        $student_list[$uid] = $student;
    	    }
	    }
	    
	    return !empty($student_list) ? $student_list : array();
    }
    
    /*
     * 获取本班所有科目 
     * 并格式好数据添加上教师名称
     */
    protected function getSubjectAll($class_code) {
        if(empty($class_code)) {
            return false;
        }

        $class_teacher_list = array();
        $mClassTeacher = ClsFactory::Create('Model.mClassTeacher');
        $tmp_class_teacher_list = $mClassTeacher->getClassTeacherByClassCode($class_code);
        $class_teacher_list = $tmp_class_teacher_list[$class_code];
        unset($tmp_class_teacher_list);
        
        $school_id = $this->user['class_info'][$class_code]['school_id'];
        $mSubjectInfo = ClsFactory::Create('Model.mSubjectInfo');
        $school_subject_list = $mSubjectInfo->getSubjectInfoBySchoolid($school_id);
        $tmp_subject_list = $school_subject_list[$school_id];
        unset($school_subject_list);
        $subject_list = $this->getClassTeacherName($tmp_subject_list, $class_teacher_list);
        
        return !empty($subject_list) ? $subject_list : false;
    }
    
    /*
     *	获取当前老师在当前班级所教的所有科目 
     */
    protected function getSubjectByTeacher($class_code, $client_account) {
        if(empty($class_code) || empty($client_account)) {
            return false;
        }
        $mClassTeacher = ClsFactory::Create('Model.mClassTeacher');
        $tmp_class_teacher_list = $mClassTeacher->getClassTeacherByUid($client_account);
        
        if (empty($tmp_class_teacher_list)) {
            return false;
        }
        
        $class_teacher_list = $tmp_class_teacher_list[$client_account];
       
        //过滤出当前班级 老师所教科目
        foreach ($class_teacher_list as $key=>$class_teacher) {
            if ($class_code == $class_teacher['class_code']) {
                $new_teacher_list[$class_teacher['subject_id']] = $class_teacher;
            }
        }
        unset($class_teacher_list);

        $subject_ids = array_keys($new_teacher_list);
        $mSubjectInfo = ClsFactory::Create('Model.mSubjectInfo');
        $tmp_subject_list = $mSubjectInfo->getSubjectInfoById($subject_ids);
        
        $subject_list = $this->getClassTeacherName($tmp_subject_list, $new_teacher_list);

        return !empty($subject_list) ? $subject_list : false;
    }
    
    /*
     *	格式化科目信息数据（添加上老师名称） 
     */
    protected function getClassTeacherName($subject_list, $class_teacher_list) {
        if (empty($class_teacher_list) || !is_array($class_teacher_list)) {
            return false;
        }
        
        foreach($class_teacher_list as $key=>$class_teacher) {
            $teacher_ids[$class_teacher['subject_id']] = $class_teacher['client_account'];
        }

        $mUser = ClsFactory::Create('Model.mUser');
        $user_list = $mUser->getUserBaseByUid(array_unique($teacher_ids));
        
        //数据拼装 老师名称
        foreach ($subject_list as $subject_id=>$subject) {
            if(isset($teacher_ids[$subject_id])) {
                $subject['teacher_name'] = $user_list[$teacher_ids[$subject_id]]['client_name'];
            }
            $subject_list[$subject_id] = $subject;
        }
        unset($class_teacher_list);unset($teacher_ids);
        
        return $subject_list;
    }
    
    /*
     * 判断老师是否是班主任
     */
    protected function isClassTeacher() {
        $client_class = $this->_client_class;
        $teacher_class_role = $client_class['teacher_class_role'];
        
        //$teacher_class_role 1 班主任 3班级主任兼老师
        if($teacher_class_role == 1 || $teacher_class_role == 3){
            return true;
        } else {
            return false;
        }
    }
    
    
    
    
    
/*评语大师部分应用*******************************************************************************/

	//评语大师教师访问权限
	function pyComment(){
		$client_type = $this->user['client_type'];
		$client_type==1 ? $blnJurisdiction = true : $blnJurisdiction = false;
		$arrpytype = Constancearr::pytype();
		$pytypeatt = Constancearr::pytypeatt();
        $arrOut=array();
        $pytypeattarr=array();
        $p=0;
        foreach($arrpytype as $key=>&$value){
			  $arrOut[$p]['id']=$key;
			  $arrOut[$p]['name']=$value;
          $p++;
       }
	   $p=0;
        foreach($pytypeatt as $key1=>&$value1){
			  $pytypeattarr[$p]['id']=$key1;
			  $pytypeattarr[$p]['name']=$value1;
          $p++;
       }
       
		$class_code = $this->objInput->getInt('class_code');
		$this->assign('class_code',$class_code);
		$this->assign('arrpytype',$arrOut);
		$this->assign('pytypeatt',$pytypeattarr);
		
		$this->display('pyComment');
	}
	
	//评语大师
	function pyCommentOpen(){
		$rowsid = $this->objInput->getStr('rowsid');
		$client_type = $this->user['client_type'];
		$client_type==1 ? $blnJurisdiction = true : $blnJurisdiction = false;

		$arrpytype = Constancearr::pytype();
        $arrOut=array();
        $p=0;
        foreach($arrpytype as $key=>$value){
			  $arrOut[$p]['id']=$key;
			  $arrOut[$p]['name']=$value;
          $p++;
       }

		$this->assign('rowsid',$rowsid);
		$this->assign('arrpytype',$arrOut);
		
		$this->display('pyCommentOpen');
	}


	//获取评语内容
	function showpyContentData(){
		$client_type = $this->user['client_type'];
		$client_type==1 ? $blnJurisdiction = true : $blnJurisdiction = false;
		$url = $this->objInput->getStr('url'); //成绩发布时选择来源
		$url == "cj" ?  $tag = "选择"  : $tag = "复制";
		$pytype = $this->objInput->getInt('pytype');
		$pyatt = $this->objInput->getInt('pyatt');
		$mPyInfo = ClsFactory::Create('Model.mPyInfo');	    
		$rsmpy_info = $mPyInfo->getpyCollectBypytypeatt($pytype, $pyatt);
		if($rsmpy_info){
		    foreach($rsmpy_info as $key=>$val){
				$outdata = "<li><span style='color:#FF3300'><a href=\"javascript:scpy('".$val['py_id']."');\">收藏</a></span><span><a href=\"javascript:copyText('".$val['py_content']."');\">".$tag."</a></span>".$val['py_content']."</li>";
				$Toutdata == "" ? $Toutdata = $outdata : $Toutdata=$Toutdata.$outdata;
			}
		}else{
			$Toutdata = "<li style='color:#FF3300'>抱歉，没有内容哦！！！</li>";
		}
		echo "<div class='commenbrbb'><ul>".$Toutdata."</ul></div>";
	}


	//获取评语内容 按搜索词
	function showpyContentDataKey(){
		$client_type = $this->user['client_type'];
		$client_type==1 ? $blnJurisdiction = true : $blnJurisdiction = false;
		//homepageAction::chkUserJurisdiction($blnJurisdiction,"ajax");

		$pytxt = trim(urldecode($this->objInput->postStr('pytxt')));
		if(get_magic_quotes_gpc()){
			$pytxt = stripslashes($pytxt);
		}
		$pytxt = htmlspecialchars($pytxt);
		$pytxt = str_replace("'", "&#039;", $pytxt);
		$mPyInfo = ClsFactory::Create('Model.mPyInfo');	    
		$rsmpy_info = $mPyInfo->getPycontentLikekey($pytxt);

		if($rsmpy_info){
		    foreach($rsmpy_info as $py_id => $py){
				$outdata = "<li><span style='color:#FF3300'><a href=\"javascript:scpy('".$py['py_id']."');\">收藏</a></span><span><a href=\"javascript:copyText('".$py['py_content']."');\">".$tag."</a></span>".$py['py_content']."</li>";
				$Toutdata == "" ? $Toutdata = $outdata : $Toutdata=$Toutdata.$outdata;
			}
		}else{
			$Toutdata = "<li style='color:#FF3300'>没有找到您要搜索的内容哦！！！</li>";
		}
		echo "<div class='commenbrbb'><ul>".$Toutdata."</ul></div>";
	}

	
	//按评语属性查看
	function showpybytypeatt(){
		$client_type = $this->user['client_type'];
		$client_type==1 ? $blnJurisdiction = true : $blnJurisdiction = false;
		$url = $this->objInput->getStr('url'); //成绩发布时选择来源
		$url == "cj" ?  $tag = "选择"  :$tag = "复制";
		$pytypeatt = $this->objInput->getInt('pytypeatt');
		
		$mPyInfo = ClsFactory::Create('Model.mPyInfo');	  
		$rsmpy_info = $mPyInfo->getpyCollectBypyatt($pytypeatt);
		if($rsmpy_info){
			$sortkeys = array();
			foreach($rsmpy_info as $key=>$value) {
	            $sortkeys[$key] = $value['py_id'];
	        }
			array_multisort($sortkeys , SORT_DESC , $rsmpy_info);

		    for($i=0;$i<count($rsmpy_info);$i++){
				$outdata = "<li><span style='color:#FF3300'><a href=\"javascript:scpy('".$rsmpy_info[$i]['py_id']."');\">收藏</a></span><span><a href=\"javascript:copyText('".$rsmpy_info[$i]['py_content']."');\">".$tag."</a></span>".$rsmpy_info[$i]['py_content']."</li>";
				$Toutdata == "" ? $Toutdata = $outdata : $Toutdata=$Toutdata.$outdata;
			}


		}else{
			$Toutdata = "<li style='color:#FF3300'>抱歉，没有内容哦！！！</li>";
		}
		echo "<div class='commenbrbb'><ul>".$Toutdata."</ul></div>";
	}


	//我的评语库信息查看
	function mypyComment(){
		$client_type = $this->user['client_type'];
		$client_type==1? $blnJurisdiction = true : $blnJurisdiction = false;
		$mMypyCollect = ClsFactory::Create('Model.mMypyCollect');	    
		$uid = $this->getCookieAccount();
		$rsmpy_info = $mMypyCollect->getMyPycollectByaccount($uid);
		$new_rsmpy_info = &$rsmpy_info[$uid];
		unset($rsmpy_info);

		$class_code = $this->objInput->getInt('class_code');
		$this->assign('class_code',$class_code);
		foreach($new_rsmpy_info as $key=>&$val){
			$val['add_date'] = date('Y-m-d H:i:s',$val['add_time']);
		}
		$this->assign('rsmpy_info',$new_rsmpy_info);
		
		$this->display('mypyComment');
	}

	//删除我的评语
	function mypydelete(){
		$client_type = $this->user['client_type'];
		$client_type==1 ? $blnJurisdiction = true : $blnJurisdiction = false;
		$pyid = $this->objInput->getInt('pyid');
		if(!empty($pyid)){
			$mMypyCollect = ClsFactory::Create('Model.mMypyCollect');	    
			$mMypyCollect->delMyCollect($pyid);
			echo "suucess";exit;
			
		}else{
			echo "fail";exit;
		}
	}


	//我的评语库成绩使用
	function mypyCommentOpen(){
		$client_type = $this->user['client_type'];
		$rowsid = $this->objInput->getInt('rowsid');
		$client_type==1 ? $blnJurisdiction = true : $blnJurisdiction = false;
		$mMypyCollect = ClsFactory::Create('Model.mMypyCollect');	
		$uid = $this->getCookieAccount();    
		$rsmpy_info = $mMypyCollect->getMyPycollectByaccount($uid);
		$new_rsmpy_info = &$rsmpy_info[$uid];
		unset($rsmpy_info);
		$this->assign('rowsid',$rowsid);
		$this->assign('rsmpy_info',$new_rsmpy_info);
		
		$this->display('mypyCommentOpen');
	}


	//收藏系统评语到我的评语库
	function scpyContentData(){
		$client_type = $this->user['client_type'];
		$client_type==1 ? $blnJurisdiction = true : $blnJurisdiction = false;
		$pyid = $this->objInput->getInt('pyid');
		$mMypyCollect = ClsFactory::Create('Model.mMypyCollect');
		$uid = $this->getCookieAccount();
		$rsmpy_info = $mMypyCollect->getMyPycollectByaccount($uid);
		$new_rsmpy_info = &$rsmpy_info[$uid];
		if(!empty($new_rsmpy_info)){
			if(count($new_rsmpy_info)>=self::PYCOUNT){
				echo "moreerror";exit;//最多收藏30个评语
			}
		}
		if(!empty($pyid)){
			$mPyInfo = ClsFactory::Create('Model.mPyInfo');	    
			$new_rsmpy_info = $mPyInfo->getPyInfoById($pyid);
			if(!empty($new_rsmpy_info)) {
			    $py_content = array_shift($new_rsmpy_info);
				$data['py_content']=$py_content['py_content'];
				$data['add_time']= time();
				$data['client_account']=$uid;
				$mMypyInfo = $mMypyCollect->addMyPyCollect($data, true);
				if($mMypyInfo){
					echo "suucess";exit;
				}else{
					echo "fail";exit;
				}
			}else{
				echo "fail";exit;
			}
		}else{
			echo "fail";exit;
		}
	}
/*评语大师部分应用end*******************************************************************************/
    
}