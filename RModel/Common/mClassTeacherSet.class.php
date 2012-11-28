<?php

class mClassTeacherSet {
    protected $_dClassTeacherSet = null;
    
    public function __construct() {
        import('RData.Common.dClassTeacherSet');
        $this->_dClassTeacherSet = new dClassTeacherSet();
    }
    /**
     * 获取班级的教师成员集合
     * @param $class_code
     */
    public function getClassTeacherSet($class_code) {
        if(empty($class_code)) {
            return false;
        }
        
        return $this->_dClassTeacherSet->getClassTeacherSet($class_code);
    }
    
    /**
     * 获取在线的教师信息
     * @param $class_code
     */
    public function getOnlineClassTeacherSet($class_code) {
        if(empty($class_code)) {
            return false;
        }
        
        return $this->_dClassTeacherSet->getOnlineClassTeacherSet($class_code);
    }
    
    /**
     * 添加班级的教师集合
     * @param  $class_code
     * @param  $teacher_accounts
     */
    public function addClassTeacherSet($class_code, $teacher_accounts) {
        if(empty($class_code) || empty($teacher_accounts)) {
            return false;
        }
        
        return $this->_dClassTeacherSet->addClassTeacherSet($class_code, $teacher_accounts);
    }
    
	/**
     * 删除班级集合中的数据
     * @param $class_code
     * @param $family_accounts
     */
    public function delClassTeacherSet($class_code, $teacher_accounts) {
        if(empty($class_code) || empty($teacher_accounts)) {
            return false;
        }
        
        return $this->_dClassTeacherSet->delClassTeacherSet($class_code, $teacher_accounts);
    }
}
