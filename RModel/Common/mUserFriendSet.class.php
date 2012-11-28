<?php

class mUserFriendSet {
    protected $_dUserFriendSet = null;
    
    public function __construct() {
        import('RData.Common.dUserFriendSet');
        $this->_dUserFriendSet = new dUserFriendSet();
    }
    
    /**
     * 获取班级的学生集合
     * @param $class_code
     */
    public function getUserFriendSet($uid) {
        if(empty($uid)) {
            return false;
        }
        
        return $this->_dUserFriendSet->getUserFriendSet($uid);
    }
    
    /**
     * 获取在线的好友列表
     * @param $uid
     */
    public function getOnlineUserFriendSet($uid) {
        if(empty($uid)) {
            return false;
        }
        
        return $this->_dUserFriendSet->getOnlineUserFriendSet($uid);
    }
    
    /**
     * 添加班级的学生集合信息
     * @param $class_code
     * @param $student_accounts
     */
    public function addUserFriendSet($uid, $friend_accounts) {
        if(empty($uid) || empty($friend_accounts)) {
            return false;
        }
        
        return $this->_dUserFriendSet->addUserFriendSet($uid, $friend_accounts);
    }
    
	/**
     * 删除用户好友集合中的数据
     * @param $class_code
     * @param $family_accounts
     */
    public function delUserFriendSet($uid, $friend_accounts) {
        if(empty($uid) || empty($friend_accounts)) {
            return false;
        }
        
        return $this->_dUserFriendSet->delUserFriendSet($uid, $friend_accounts);
    }
}
