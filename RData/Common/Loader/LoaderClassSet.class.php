<?php
/**
 * 加载和单个班级相关的集合信息
 * @author Administrator
 * 注明：
 * 1. 在RModel/Common下的Redis类，数据请求的时候是凌驾于mysql数据服务之上的，即所有请求通用集合中的数据都只能从这里出去；
 * 2. 类图结构
 * 
 * 					----------
 * 					| 数据请求  |
 * 					----------		
 * 						|
 * 					---------------
 * 					|Redis数据服务  | ---->(a. 将mysql中的数据同步到redis对应的集合中去)
 * 					---------------
 * 
 */

class LoaderClassSet {
    /**
     * 对外接口，提供数据的加载服务
     */
    public function load($class_code) {
        if(empty($class_code)) {
            return false;
        }
        
        list($student_accounts, $teacher_accounts, $family_accounts) = $this->getDatasFromDatabase($class_code);
        
        $dClassStudentSet = ClsFactory::Create('RData.Common.dClassStudentSet');
        $dClassTeacherSet = ClsFactory::Create('RData.Common.dClassTeacherSet');
        $dClassFamilySet = ClsFactory::Create('RData.Common.dClassFamilySet');
        
        $dClassStudentSet->addClassStudentSet($class_code, $student_accounts);
        $dClassTeacherSet->addClassTeacherSet($class_code, $teacher_accounts);
        $dClassFamilySet->addClassFamilySet($class_code, $family_accounts);
        
        return true;
    }
    
    /**
     * 从数据获取分组后的数据
     * @param $class_code
     */
    private function getDatasFromDatabase($class_code) {
        if(empty($class_code)) {
            return false;
        }
        
        $mClientClass = ClsFactory::Create('Model.mClientClass');
        $client_class_arr = $mClientClass->getClientClassByClassCode($class_code);
        $client_class_list = & $client_class_arr[$class_code];
        
        $student_list = $teacher_list = $family_list = array();
        if(!empty($client_class_list)) {
            foreach($client_class_list as $client_class) {
                $client_type = intval($client_class['client_type']);
                if($client_type == CLIENT_TYPE_STUDENT) {
                    $student_list[] = $client_class['client_account'];
                } else if($client_type == CLIENT_TYPE_TEACHER) {
                    $teacher_list[] = $client_class['client_account'];
                } else if($client_type == CLIENT_TYPE_FAMILY) {
                    $family_list[] = $client_class['client_account'];
                }
            }
        }
        
        return array($student_list, $teacher_list, $family_list);
    }
}