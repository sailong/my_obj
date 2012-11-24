<?php

class mUserFeedAllZset {
    protected $_dUserFeedAllZset = null;
    
    public function __construct() {
        import('@.RData.Feed.dUserFeedAllZset');
        $this->_dUserFeedAllZset = new dUserFeedAllZset();
    }
    
    /**
     * 获取用户的全部动态信息
     * @param $uid
     * @param $offset
     * @param $limit
     */
    public function getUserFeedAllZset($uid, $offset = 0, $limit = 0) {
        if(empty($uid)) {
            return false;
        }
        
        return $this->_dUserFeedAllZset->getUserFeedAllZset($uid, $offset, $limit);
    }
    
  /**
     * 获取用户动态中最小的feed_id值
     * @param $class_code
     */
    public function getUserFeedAllZsetMin($uid) {
        if(empty($uid)) {
            return false;
        }
        
        return $this->_dUserFeedAllZset->getUserFeedAllZsetMin($uid);
    }
    
    /**
     * 添加用户的全部动态集合
     * @param $uid
     * @param $feed_ids
     */
    public function addUserFeedAllZset($uid, $add_time, $feed_id) {
        if(empty($uid) || empty($feed_id)) {
            return false;
        }
        
        return $this->_dUserFeedAllZset->addUserFeedAllZset($uid, $add_time, $feed_id);
    }
    
    /**
     * 删除指定集合中的某些值
     * @param $class_code
     * @param $feed_ids
     */
    public function delUserFeedAllZset($uid, $feed_ids) {
        if(empty($uid) || empty($feed_ids)) {
            return false;
        }
        
        return $this->_dUserFeedAllZset->delUserFeedAllZset($uid, $feed_ids);
    }
}