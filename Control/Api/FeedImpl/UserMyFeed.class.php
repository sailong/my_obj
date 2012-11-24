<?php

class UserMyFeed {
    /**
     * 获取用户的全部动态信息
     * @param $uid
     * @param $offset
     * @param $limit
     */
    public function getUserMyFeed($uid, $offset = 0, $limit = 10) {
        if(empty($uid)) {
            return false;
        }
        
        $mUserMyFeedZset = ClsFactory::Create('RModel.Feed.mUserMyFeedZset');
        list($zset_size, $min_feed_id) = $mUserMyFeedZset->getUserMyFeedZsetMin($uid);
        
        if($offset < $zset_size) {
            $feed_list = $mUserMyFeedZset->getUserMyFeedZset($uid, $offset, $limit);
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
            
            $feed_list = $fetchFeedObject->getUserMyFeedFromDatabase($uid, $where_appends, $offset, $limit);
        }
        
        return $feed_list;
    }
}