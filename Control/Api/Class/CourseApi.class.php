<?php
/*
 * 关于课程表功能的api
 * 调用路径 /Api/Class/Course/showToday/class_code/696
 */
class CourseApi extends ApiController {
   /**
     * 
     * 固定函数
     */
    public function __construct() {
        parent::__construct();
    }    
    public function _initialize(){
		parent::_initialize();        
    }	    

	/**
	 * 获取今天所有课程的接口
	 * 如果当前时间为 周六周日 或者今天没有课程 返回 false;
	 * 
	 * @return json  array('day'=>array(1=>'语文',2=>'数学');)
	 */
    
    public function showToday() {
        $class_code = $this->objInput->getInt('class_code');
        if(empty($class_code)) {
            return false;
        }
        
        import('@.Control.Api.Class.CourseImpl.CourseAll');
        $courseAllObj = new CourseAll();
        
        $day = date('w', time());
        $course_list = $courseAllObj->getTimeCourse($day, $class_code);
        $new_course_list = $this->dataProcessing($course_list);
        
        exit(json_encode($new_course_list));
    }
    
    
	/**
	 * 获取明天所有课程的接口
	 * 
	 * 如果当前时间为 五六周六 或者今天没有课程 返回 false;
	 * @return json array('day'=>array(1=>'语文',2=>'数学');)
	 */
    
    public function showTomorrow() {
        $class_code = $this->objInput->getInt('class_code');
        if(empty($class_code)) {
            return false;
        }
        
        import('@.Control.Api.Class.CourseImpl.CourseAll');
        $courseAllObj = new CourseAll();
        $day = date('w', time() + 24*3600);
        
        $course_list =  $courseAllObj->getTimeCourse($day, $class_code);
        $new_course_list = $this->dataProcessing($course_list);

        exit(json_encode($new_course_list));
    }
    
    /*
     * 课程数据处理方便前台调用
     * 分出上下午 
     * 
     */
    
    private function dataProcessing($class_course_list) {
        if(empty($class_course_list) || !is_array($class_course_list)) {
            return false;
        }
        
        //整理数组为把第几节课作为键值方便分出上下午
        $new_class_course_list = array();
        foreach($class_course_list as $course) {
            $new_class_course_list[$course['num_th']] = $course;
        }

        //填充课程表数组 （为空的课节填充空array 方便前台遍历）
        $am_course_list = $pm_course_list = array();
        import("@.Common_wmw.WmwString");

        for($i = 1; $i <= 8 ; $i++) {
            $which_course = WmwString::getNumsUppercase($i);

            if ($i <= 4) {
                $am_course_list[$which_course] = isset($new_class_course_list[$i]) ? $new_class_course_list[$i] : array();
            } else {
                $pm_course_list[$which_course] = isset($new_class_course_list[$i]) ? $new_class_course_list[$i] : array();
            }
        }
        
        return array($am_course_list,$pm_course_list);
    }
    
}
