<?php

class mUserObjectHash {
    protected $_dUserObjectHash = null;
    
    public function __construct() {
        import('RData.Common.dUserObjectHash');
        $this->_dUserObjectHash = new dUserObjectHash();
    }
    
    /**
     * 获取班级的学生集合
     * @param $class_code
     */
    public function getUserObjectHash($uid) {
        if(empty($uid)) {
            return false;
        }
        
        return $this->_dUserObjectHash->getUserObjectHash($uid);
    }
    
    /**
     * 添加班级的学生集合信息
     * @param $class_code
     * @param $student_accounts
     */
    public function addUserObjectHash($uid, $user_datas) {
        if(empty($uid)) {
            return false;
        }
        
        return $this->_dUserObjectHash->addUserObjectHash($uid, $user_datas);
    }
    
    /**
     * 更新用户的数据
     * @param $uid
     * @param $datas
     */
    public function modifyUserObjectHash($uid, $datas) {
        if(empty($uid) || empty($datas) || !is_array($datas)) {
            return false;
        }
        
        return $this->_dUserObjectHash->modifyUserObjectHash($uid, $datas);
    }
}
