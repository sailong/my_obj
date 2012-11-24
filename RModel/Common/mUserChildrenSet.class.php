<?php

class dUserChildrenSet {
    protected $_dUserChildrenSet = null;
    
    public function __construct() {
        import('@.RData.Feed.dUserChildrenSet');
        $this->_dUserChildrenSet = new dUserChildrenSet();
    }
    
    /**
     * 获取班级的学生集合
     * @param $class_code
     */
    public function getUserChildrenSet($uid) {
        if(empty($uid)) {
            return false;
        }
        
        return $this->_dUserChildrenSet->getUserChildrenSet($uid);
    }
    
    /**
     * 获取在线的孩子信息
     * @param $uid
     */
    public function getOnlineUserChildrenSet($uid) {
        if(empty($uid)) {
            return false;
        }
        
        return $this->_dUserChildrenSet->getOnlineUserChildrenSet($uid);
    }
    
    /**
     * 添加班级的学生集合信息
     * @param $class_code
     * @param $student_accounts
     */
    public function addUserChildrenSet($uid, $child_accounts) {
        if(empty($uid) || empty($child_accounts)) {
            return false;
        }
        
        return $this->_dUserChildrenSet->addUserChildrenSet($uid, $child_accounts);
    }
    
	/**
     * 删除用户的孩子集合中的数据
     * @param $class_code
     * @param $family_accounts
     */
    public function delUserChildrenSet($uid, $child_accounts) {
        if(empty($uid) || empty($child_accounts)) {
            return false;
        }
        
        return $this->_dUserChildrenSet->delUserChildrenSet($uid, $child_accounts);
    }
}
