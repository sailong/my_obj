<?php
import('RData.RedisFeedKey');

class dClassFeedAllZset extends rBase {
    protected $zset_size = 100;
    /**
     * 获取班级的全部动态列表
     * @param $class_code
     * @param $offset
     * @param $limit
     */
    public function getClassFeedAllZset($class_code, $offset = 0, $limit = 10) {
        if(empty($class_code)) {
            return false;
        }
        
        $this->loader($class_code);
        
        $start_pos = $offset;
        $end_pos = $start_pos + $limit - 1;
        
        $redis_key = RedisFeedKey::getClassFeedAllZsetKey($class_code);
        
        return $this->zRevRange($redis_key, $start_pos, $end_pos);
    }
    
    /**
     * 获取当前班级中最小的feed_id值
     * @param $class_code
     */
    public function getClassFeedAllZsetMin($class_code) {
        if(empty($class_code)) {
            return false;
        }
        
        $redis_key = RedisFeedKey::getClassFeedAllZsetKey($class_code);
        
        $feed_ids = $this->zRange($redis_key, 0, 0);
        $min_feed_id = !empty($feed_ids) ? reset($feed_ids) : 0;
        
        return array($this->zset_size, $min_feed_id);
    }
    
    /**
     * 添加班级的全部动态列表
     * @param $class_code
     * @param $feed_ids
     */
    public function addClassFeedAllZset($class_code, $add_time, $feed_id) {
        if(empty($class_code) || empty($feed_id)) {
            return false;
        }
        
        $redis_key = RedisFeedKey::getClassFeedAllZsetKey($class_code);

        $add_nums = $this->zAdd($redis_key, $add_time, $feed_id);
        
        //如果添加成功，修整有序集合的长度
        if($add_nums > 0) {
            $this->clipClassFeedAllZset($class_code);
        }
        
        return $add_nums ? $add_nums : false;
    }
    
    /**
     * 删除指定集合中的某些值
     * @param $class_code
     * @param $feed_ids
     */
    public function delClassFeedAllZset($class_code, $feed_ids) {
        if(empty($class_code) || empty($feed_ids)) {
            return false;
        }
        
        $feed_ids = array_unique((array)$feed_ids);
        $redids_key = RedisFeedKey::getClassFeedAllZsetKey($class_code);
        
        $delete_nums = 0;
        foreach($feed_ids as $feed_id) {
           if($this->zDelete($redids_key, $feed_id)) {
               $delete_nums++;
           }
        }
        
        return $delete_nums ? $delete_nums : false;
    }
    
    /**
     * 清楚有序集合中多余的元素，使集合的长度保持一定
     * @param $class_code
     */
    private function clipClassFeedAllZset($class_code) {
        if(empty($class_code)) {
            return false;
        }
        
        $redis_key = RedisFeedKey::getClassFeedAllZsetKey($class_code);
        
        $zset_size = $this->zSize($redis_key);
        if($zset_size > $this->zset_size) {
            return $this->zRemRangeByRank($redis_key, 0, $zset_size - $this->zset_size - 1);
        }
        
        return 0;
    }
    
    /**
     * 加载班级动态信息
     * @param $class_code
     */
    private function loader($class_code) {
        if(empty($class_code)) {
            return false;
        }
        
        $redis_key = RedisFeedKey::getClassFeedAllZsetKey($class_code);
        
        $GlobalKeys = ClsFactory::Create('RData.GlobalKeys');
        if(!$GlobalKeys->isExists($redis_key)) {
            
            $GlobalKeys->addKey($redis_key);
            
            import('RData.Feed.Loader.LoaderFeed');
            $loaderObject = new LoaderFeed();
        
            return $loaderObject->loadClassFeed($class_code);
        }
        
        return true;
    }
    
}