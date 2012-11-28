<?php

class UserChildFeed {
    
    /**
     * 获取用户的全部动态信息
     * @param $uid
     * @param $offset
     * @param $limit
     */
    public function getUserChildFeed($uid, $offset = 0, $limit = 10) {
        if(empty($uid)) {
            
            FEED_DEBUG && trigger_error('获取个人孩子动态Feed时缺少必要参数!', E_USER_ERROR);
            
            return false;
        }
        
        $user = $this->getUser($uid);
        $client_type = $user['client_type'];
        
        if($client_type != CLIENT_TYPE_FAMILY) {
            return false;
        }
        
        $mUserChildFeedZset = ClsFactory::Create('RModel.Feed.mUserChildFeedZset');
        list($zset_size, $min_feed_id) = $mUserChildFeedZset->getUserChildFeedZsetMin($uid);
        
        if($offset < $zset_size) {
            $feed_list = $mUserChildFeedZset->getUserChildFeedZset($uid, $offset, $limit);
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
            $feed_list = $fetchFeedObject->getUserChildrenFeedFromDatabase($uid, $where_appends, $offset, $limit);
        }
        
        return $feed_list;
    }
}