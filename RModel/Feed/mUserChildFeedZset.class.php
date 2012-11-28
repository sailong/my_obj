<?php

class mUserChildFeedZset {
    protected $_dUserChildFeedZset = null;
    
    public function __construct() {
        import('RData.Feed.dUserChildFeedZset');
        
        $this->_dUserChildFeedZset = new dUserChildFeedZset();
    }
    
    /**
     * 获取用户的孩子动态列表
     * @param  $uid
     * @param  $offset
     * @param  $limit
     */
    public function getUserChildFeedZset($uid, $offset = 0, $limit = 10) {
        if(empty($uid)) {
            return false;
        }
        
        return $this->_dUserChildFeedZset->getUserChildFeedZset($uid, $offset, $limit);
    }
    
   /**
     * 获取孩子动态中最小的feed_id值
     * @param $class_code
     */
    public function getUserChildFeedZsetMin($uid) {
        if(empty($uid)) {
            return false;
        }
        
        return $this->_dUserChildFeedZset->getUserChildFeedZsetMin($uid);
    }
    
    /**
     * 添加用户的孩子动态信息
     * @param $uid
     * @param $feed_ids
     */
    public function addUserChildFeedZset($uid, $add_time, $feed_id) {
        if(empty($uid) || empty($feed_id)) {
            return false;
        }
        
        return $this->_dUserChildFeedZset->addUserChildFeedZset($uid, $add_time, $feed_id);
    }
    
    /**
     * 删除指定集合中的某些值
     * @param $class_code
     * @param $feed_ids
     */
    public function delUserChildFeedZset($uid, $feed_ids) {
        if(empty($uid) || empty($feed_ids)) {
            return false;
        }
        
        return $this->_dUserChildFeedZset->delUserChildFeedZset($uid, $feed_ids);
    }
}