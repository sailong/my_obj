<?php

class UserAllFeed {
    
    /**
     * 获取用户的全部动态信息
     * @param $uid
     * @param $offset
     * @param $limit
     */
    public function getUserAllFeed($uid, $offset = 0, $limit = 10) {
        if(empty($uid)) {
            return false;
        }
        
        $mUserFeedAllZset = ClsFactory::Create('RModel.Feed.mUserFeedAllZset');
        list($zset_size, $min_feed_id) = $mUserFeedAllZset->getUserFeedAllZsetMin($uid);
        
        if($offset < $zset_size) {
            $feed_list = $mUserFeedAllZset->getUserFeedAllZset($uid, $offset, $limit);
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
            
            $feed_list = $fetchFeedObject->getUserAllFeedFromDatabase($uid, $where_appends, $offset, $limit);
        }
        
        return $feed_list;
    }
}