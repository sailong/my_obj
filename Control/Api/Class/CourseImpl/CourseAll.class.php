<?php
class CourseAll{
    public function __construct() {
        
       // echo '我是个构造方法';
    }    
    /*
     * 获取一天的所有课程 
     */
    public function getTimeCourse($weekday, $class_code) {
        $class_code = intval($class_code);
        if($weekday < 1 || $weekday > 5 || empty($class_code)) {
            return false;
        }
        
        $where = array("weekday=$weekday", "class_code=$class_code");
        $mClassCourse = ClsFactory::Create('Model.mClassCourse');
        $couse_list = $mClassCourse->getClassCourse($where, 'num_th', 0, 8); //一天最多8节课
        
        return !empty($couse_list) ? $couse_list : false;
    }
}