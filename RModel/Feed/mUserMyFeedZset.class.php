<?php

class mUserMyFeedZset {
    protected $_dUserMyFeedZset = null;
    
    public function __construct() {
        import('@.RData.Feed.dUserMyFeedZset');
        $this->_dUserMyFeedZset = new dUserMyFeedZset();
    }
    
	/**
     * 获取与我相关的动态信息
     * @param  $class_code
     * @param  $offset
     * @param  $limit
     */
    public function getUserMyFeedZset($uid, $offset = 0, $limit = 10) {
        if(empty($uid)) {
            return false;
        }
        
        return $this->_dUserMyFeedZset->getUserMyFeedZset($uid, $offset, $limit);
    }
    
   /**
     * 获取与我相关的动态中最小的feed_id值
     * @param $class_code
     */
    public function getUserMyFeedZsetMin($uid) {
        if(empty($uid)) {
            return false;
        }
        
        return $this->_dUserMyFeedZset->getUserMyFeedZsetMin($uid);
    }
    
    /**
     * 添加与我相关的动态信息
     * @param $uid
     * @param $feed_ids
     */
    public function addUserMyFeedZset($uid, $add_time, $feed_id) {
        if(empty($uid) || empty($feed_id)) {
            return false;
        }
        
        return $this->_dUserMyFeedZset->addUserMyFeedZset($uid, $add_time, $feed_id);
    }
    
    /**
     * 删除指定集合中的某些值
     * @param $class_code
     * @param $feed_ids
     */
    public function delUserMyFeedZset($uid, $feed_ids) {
        if(empty($uid) || empty($feed_ids)) {
            return false;
        }
        
        return $this->_dUserMyFeedZset->delUserMyFeedZset($uid, $feed_ids);
    }
    
}