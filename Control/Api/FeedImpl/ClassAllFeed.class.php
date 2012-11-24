<?php
class ClassAllFeed {
    
    public function getClassFeedAll($class_code, $offset = 0, $limit = 10) {
        if(empty($class_code)) {
            return false;
        }
        
        $mClassFeedAllZset = ClsFactory::Create('RModel.Feed.mClassFeedAllZset');
        list($zset_size, $min_feed_id) = $mClassFeedAllZset->getClassFeedAllZsetMin($class_code);
        
        if($offset < $zset_size) {
            $feed_list = $mClassFeedAllZset->getClassFeedAllZset($class_code, $offset, $limit);
        } else if($min_feed_id > 0) {
            //拿偏移量之前的数据
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
            
            $feed_list = $fetchFeedObject->getClassFeedAllFromDatabase($class_code, $where_appends, $offset, $limit);
        }
        
        return $feed_list;
    }
}