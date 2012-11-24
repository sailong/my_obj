<?php

class mClassStudentSet {
    protected $_dClassStudentSet = null;
    
    public function __construct() {
        import('@.RData.Feed.dClassStudentSet');
        $this->_dClassStudentSet = new dClassStudentSet();
    }
    
    /**
     * 获取班级的学生集合
     * @param $class_code
     */
    public function getClassStudentSet($class_code) {
        if(empty($class_code)) {
            return false;
        }
        
        return $this->_dClassStudentSet->getClassStudentSet($class_code);
    }
    
    /**
     * 获取在线的学生集合信息
     * @param $class_code
     */
    public function getOnlineClassStudentSet($class_code) {
        if(empty($class_code)) {
            return false;
        }
        
        return $this->_dClassStudentSet->getOnlineClassStudentSet($class_code);
    }
    
    /**
     * 添加班级的学生集合信息
     * @param $class_code
     * @param $student_accounts
     */
    public function addClassStudentSet($class_code, $student_accounts) {
        if(empty($class_code) || empty($student_accounts)) {
            return false;
        }
        
        return $this->_dClassStudentSet->addClassStudentSet($class_code, $student_accounts);
    }
    
	/**
     * 删除班级集合中的数据
     * @param $class_code
     * @param $family_accounts
     */
    public function delClassStudentSet($class_code, $student_accounts) {
        if(empty($class_code) || empty($student_accounts)) {
            return false;
        }
        
        return $this->_dClassStudentSet->delClassStudentSet($class_code, $student_accounts);
    }
}
