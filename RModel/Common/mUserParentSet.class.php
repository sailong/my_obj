<?php

class mUserParentSet {
    protected $_dUserParentSet = null;
    
    public function __construct() {
        import('@.RData.Feed.dUserParentSet');
        $this->_dUserParentSet = new dUserParentSet();
    }
    
    /**
     * 获取班级的学生集合
     * @param $class_code
     */
    public function getUserParentSet($uid) {
        if(empty($uid)) {
            return false;
        }
        
        return $this->_dUserParentSet->getUserParentSet($uid);
    }
    
    /**
     * 获取在线的家长列表
     * @param $uid
     */
    public function getOnlineUserParentSet($uid) {
        if(empty($uid)) {
            return false;
        }
        
        return $this->_dUserParentSet->getOnlineUserParentSet($uid);
    }
    
    /**
     * 添加班级的学生集合信息
     * @param $class_code
     * @param $student_accounts
     */
    public function addUserParentSet($uid, $parent_accounts) {
        if(empty($uid) || empty($parent_accounts)) {
            return false;
        }
        
        return $this->_dUserParentSet->addUserParentSet($uid, $parent_accounts);
    }
    
	/**
     * 删除用户家长集合中的数据
     * @param $class_code
     * @param $family_accounts
     */
    public function delUserParentSet($uid, $parent_accounts) {
        if(empty($uid) || empty($parent_accounts)) {
            return false;
        }
        
        return $this->_dUserParentSet->delUserParentSet($uid, $parent_accounts);
    }
}
