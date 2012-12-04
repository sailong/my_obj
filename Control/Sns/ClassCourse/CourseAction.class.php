<?php

class CourseAction extends SnsController {
    public $_checkClsssCode = true;
    
    public function __construct() {
        parent::__construct ();
    }
    
    /*
     * 课程表首页 （根据不同的账号类型调用不同的模板（学生，老师，家长））
     * 
     */
    public function index() {
        //从$this->user 中找到有用信息
        $class_code = $this->getClassCode();   //获取当前账号的当前班级
        $isEditCourse = $this->isEditCourse($class_code);

        //查询当前班级所有课程
        $mClassCourse = ClsFactory::Create('Model.mClassCourse');
        $class_course_list = $mClassCourse->getClassCourseByClassCode($class_code);
        $class_course_list = & $class_course_list[$class_code];
        
        //重新整理数组为2维数组
        $new_class_course_list = array();
        foreach($class_course_list as $course) {
            $new_class_course_list[$course['num_th']][$course['weekday']] = $course;
        }
        
        //填充课程表数组
        $am_course_list = $pm_course_list = array();
        //import("@.Common_wmw.WmwString");
        for($i = 1; $i <= 8 ; $i++) {
            //$which_course = WmwString::getNumsUppercase($i);
            for($j = 1; $j <= 5 ; $j++) {
                if ($i <= 4) {
                    $am_course_list[$i][$j] = isset($new_class_course_list[$i][$j]) ? $new_class_course_list[$i][$j] : array();
                } else {
                    $pm_course_list[$i][$j] = isset($new_class_course_list[$i][$j]) ? $new_class_course_list[$i][$j] : array();
                }
                
            }
        }

        //取出所有课程皮肤（以后多了可能会分页展示）
        $mClassCourseSkin = ClsFactory::Create('Model.mClassCourseSkin');
        $skin_list = $mClassCourseSkin->getClassCourseSkinList(null, 'skin_id', 0, 20);

        //取出当前账号对应课程皮肤
        $user_course_skin = array();
        $mClassCourseConfig = ClsFactory::Create('Model.mClassCourseConfig');
        $user_skin_list = $mClassCourseConfig->getClassCourseConfigById($this->user['client_account']); 
        $user_skin_id = $user_skin_list[$this->user['client_account']]['skin_id'];
        $user_course_skin = $skin_list[$user_skin_id];
        
        $this->assign('class_code', $class_code);
        $this->assign('am_course_list', $am_course_list);
        $this->assign('pm_course_list', $pm_course_list);
        $this->assign('skin_list', $skin_list);
        $this->assign('user_course_skin', $user_course_skin);
        
        $tpl = 'class_course';
        if($isEditCourse){
            $tpl = 'class_course_admin';
            //取出当前班级的所有科目
            $mSubjectInfo = ClsFactory::Create('Model.mSubjectInfo');
            $school_id = key($this->user['school_info']);
            $subject_list = $mSubjectInfo->getSubjectInfoBySchoolid($school_id);
            $subject_list = & $subject_list[$school_id];
            
            $this->assign('subject_list', $subject_list);
            
        }
        
        $this->display($tpl);
    }
    
    //ajax 修改个人课程表皮肤配置
    public function saveSkinAjax() {
        $skin_id = $this->objInput->postInt('skin');
        if(empty($skin_id)) {
            $this->ajaxReturn(null, '非法操作！', -1, 'JSON');
        }
        $data = array('skin_id' => $skin_id);
        $account = $this->user['client_account'];
        $mClassCourseConfig = ClsFactory::Create('Model.mClassCourseConfig');
        $user_skin_list = $mClassCourseConfig->getClassCourseConfigById($this->user['client_account']); 
        $old_skin = $user_skin_list[$this->user['client_account']];
        
        if (!empty($old_skin) && $old_skin['skin_id'] == $skin_id) {
            $this->ajaxReturn(null, '没有更改哦', 1, 'JSON');
        }
        
        if (empty($old_skin)) {
            $data['client_account'] = $this->user['client_account'];
            $is_success = $mClassCourseConfig->addClassCourseConfig($data); 
        } else {
            $is_success = $mClassCourseConfig->modifyClassCourseConfig($data, $account);
        }
        
        if(empty($is_success)) {
             $this->ajaxReturn(null, '系统繁忙稍后重试', -1, 'JSON');
        }
        
        $this->ajaxReturn(null, '修改成功', 1, 'JSON');
    }
    
    //ajax 修改课程表
    public function saveCourseAjax() {
        $class_code  = $this->objInput->postInt('class_code');
        $weekday     = $this->objInput->postInt('weekday');
        $num_th      = $this->objInput->postInt('num_th');
        $course_name = $this->objInput->postStr('course_name');

        //数据验证正确性
        if($class_code <= 0 || $weekday < 1 || $weekday > 5 || $num_th < 1 || $num_th > 8 || empty($course_name)) {
             $this->ajaxReturn(null, '非法数据', -1, 'JSON');
        }
        
        //验证用户是否具有修改权限
        $is_edit = $this->isEditCourse($class_code);
        if(empty($is_edit)) {
           $this->ajaxReturn(null, '您没有权限修改课程表', -1, 'JSON');
        }
        $where = array("class_code=$class_code", "weekday=$weekday", "num_th=$num_th");
        
        $mClassCourse = ClsFactory::Create('Model.mClassCourse');
        $old_course_list = $mClassCourse->getClassCourse($where, null, 0, 1);
        if (empty($old_course_list)) {
            //不存在就 添加课程
            $data = array(
                'class_code'  => $class_code,
                'weekday'     => $weekday,
                'num_th'      => $num_th,
                'name'        => $course_name,
                'upd_account' => $this->user['client_account'],
                'upd_time'    => time()
            );
            $is_success = $mClassCourse->addClassCourse($data);
           
        } else {
            //存在修改
            $course_list = reset($old_course_list);
            $data        = array('name' => $course_name);
            $is_success  = $mClassCourse->modifyClassCourse($data, $course_list['course_id']);
        }

        if(empty($is_success)) {
            $this->ajaxReturn(null, '系统繁忙请稍后重试', -1, 'JSON');
        }
       
        $this->ajaxReturn(null, '修改成功', 1, 'JSON');
    }
    /*
     * 判断当前用户在当前班级是否有修改课程表权限
     */
    private function isEditCourse($class_code) {
        if(empty($class_code)) {
            return false;
        }
        
        //老师, 班级管理员具有修改课程表权限
        $client_class = $this->getClientClass($class_code);  //获取当前用户的当前班级关系
        if(empty($client_class)){
            return false;
        }

        return ($client_class['class_admin'] == 1 || $client_class['client_type'] == 1) ? true :  false;
    }
    
    /*
     * 旧数据处理 只处理一次
     * 
     */
    public function OldData() {
        
        //旧数据处理
        import('@.Control.Sns.OldData');
        $old_data_obj = new OldData();
        //旧课程表数据处理
        //$old_data_obj->oldCourse();
        //旧课程皮肤表数据处理
        //$old_data_obj->oldcourseSkin();
        //旧个人课程皮肤配置表
        //$old_data_obj->oldcourseConfig();
        
        echo('旧数据都处理完成了哦！');
    }
    

    
    
}

?>