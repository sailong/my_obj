<?php
class mClassFamilySet {
    protected $_dClassFamilySet = null;
    
    public function __construct() {
        import('@.RData.Feed.dClassFamilySet');
        $this->_dClassFamilySet = new dClassFamilySet();
    }
    
    /**
     * 获取班级中的家长的成员列表
     * @param $class_code
     */
    public function getClassFamilySet($class_code) {
        if(empty($class_code)) {
            return false;
        }
        
        return $this->_dClassFamilySet->getClassFamilySet($class_code);
    }
    
    /**
     * 获取在线的家长用户信息
     * @param $class_code
     */
    public function getOnlineClassFamilySet($class_code) {
        if(empty($class_code)) {
            return false;
        }
        
        return $this->_dClassFamilySet->getOnlineClassFamilySet($class_code);
    }
    
    /**
     * 添加班级的成员列表
     * @param $class_code
     * @param $family_accounts
     */
    public function addClassFamilySet($class_code, $family_accounts) {
        if(empty($class_code)) {
            return false;
        }
        
        return $this->_dClassFamilySet->addClassFamilySet($class_code, $family_accounts);
    }
    
    /**
     * 删除班级集合中的数据
     * @param $class_code
     * @param $family_accounts
     */
    public function delClassFamilySet($class_code, $family_accounts) {
        if(empty($class_code) || empty($family_accounts)) {
            return false;
        }
        
        return $this->_dClassFamilySet->delClassFamilySet($class_code, $family_accounts);
    }
}
