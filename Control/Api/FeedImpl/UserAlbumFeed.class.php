<?php
class UserAlbumFeed {
    
    public function getUserAlbumFeed($uid, $offset = 0, $limit = 10) {
        if(empty($uid)) {
            
            FEED_DEBUG && trigger_error('获取个人好友相册Feed时缺少必要参数!', E_USER_ERROR);
            
            return false;
        }
        
        $mUserAlbumFeedZset = ClsFactory::Create('RModel.Feed.mUserAlbumFeedZset');
        list($zset_size, $min_feed_id) = $mUserAlbumFeedZset->getUserAlbumFeedZset($uid);
        
        if($offset < $zset_size) {
            $feed_list = $mUserAlbumFeedZset->getUserAlbumFeedZset($uid, $offset, $limit);
        } else {
            $offset = $offset - $zset_size;
            //2次的切换之间可能导致部分feed数据丢失
            if($offset < $limit) {
                $offset = 0;
                $limit = $offset + $limit;
            }
            
            $where_appends = array();
            if($min_feed_id > 0) {
                $where_appends = array(
                    'feed_id' => "feed_id < $min_feed_id",
                );
            }
            
            $fetchFeedObject = ClsFactory::Create('RData.Feed.Loader.FetchDatabaseFeed');
            $feed_list = $fetchFeedObject->getUserAlbumFeedFromDatabase($uid, $where_appends, $offset, $limit);
        }
        
        return $feed_list;
    }
}