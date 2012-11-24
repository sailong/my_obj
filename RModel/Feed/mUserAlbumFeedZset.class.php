<?php
class mUserAlbumFeedZset {
    protected $_dUserAlbumFeedZset = null;
    
    public function __construct() {
        import('@.RData.Feed.dUserAlbumFeedZset');
        
        $this->_dUserAlbumFeedZset = new dUserAlbumFeedZset();
    }
    
    /**
     * 获取用户的相册动态列表
     * @param $uid
     * @param $offset
     * @param $limit
     */
    public function getUserAlbumFeedZset($uid, $offset = 0, $limit = 10) {
        if(empty($uid)) {
            return false;
        }
        
        return $this->_dUserAlbumFeedZset->getUserAlbumFeedZset($uid, $offset, $limit);
    }
    
    
   /**
     * 获取好友相册中最小的feed_id值
     * @param $class_code
     */
    public function getUserAlbumFeedZsetMin($uid) {
        if(empty($uid)) {
            return false;
        }
        
        return $this->_dUserAlbumFeedZset->getUserAlbumFeedZsetMin($uid);
    }
    
    /**
     * 添加用户的相册动态列表
     * @param $uid
     * @param $feed_ids
     */
    public function addUserAlbumFeedZset($uid, $add_time, $feed_id) {
        if(empty($uid) || empty($feed_id)) {
            return false;
        }
        
        return $this->_dUserAlbumFeedZset->addUserAlbumFeedZset($uid, $add_time, $feed_id);
    }
    
    /**
     * 删除指定集合中的某些值
     * @param $class_code
     * @param $feed_ids
     */
    public function delUserAlbumFeedZset($uid, $feed_ids) {
        if(empty($uid) || empty($feed_ids)) {
            return false;
        }
        
        return $this->_dUserAlbumFeedZset->delUserAlbumFeedZset($uid, $feed_ids);
    }
}
