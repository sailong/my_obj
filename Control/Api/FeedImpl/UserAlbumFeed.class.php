<?php
class UserAlbumFeed {
    
    public function getUserAlbumFeed($uid, $offset = 0, $limit = 10) {
        if(empty($uid)) {
            return false;
        }
        
        $mUserAlbumFeedZset = ClsFactory::Create('RModel.Feed.mUserAlbumFeedZset');
        list($zset_size, $min_feed_id) = $mUserAlbumFeedZset->getUserAlbumFeedZset($uid);
        
        if($offset < $zset_size) {
            $feed_list = $mUserAlbumFeedZset->getUserAlbumFeedZset($uid, $offset, $limit);
        } else if($min_feed_id > 0) {
            $offset = $offset - $zset_size;
            //2次的切换之间可能导致部分feed数据丢失
            if($offset < $limit) {
                $offset = 0;
                $limit = $offset + $limit;
            }
            
            $where_appends = array(
                'feed_id' => "feed_id < $min_feed_id",
            );
            
            import('@.RData.Feed.Loader.FetchDatabaseFeed');
            $fetchFeedObject = new FetchDatabaseFeed();
            
            $feed_list = $fetchFeedObject->getUserAlbumFeedFromDatabase($uid, $where_appends, $offset, $limit);
        }
        
        return $feed_list;
    }
}