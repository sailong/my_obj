<?php
import('RData.RedisFeedKey');

class dUserFeedAllZset extends rBase {
    protected $zset_size = 100;
    
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
        
        $this->loader($uid);
        
        $start_pos = $offset;
        $end_pos = $offset + $limit - 1;
        
        $redis_key = RedisFeedKey::getUserFeedAllZsetKey($uid);
        
        return $this->zRevRange($redis_key, $start_pos, $end_pos);
    }
    
   /**
     * 获取用户动态中最小的feed_id值
     * @param $class_code
     */
    public function getUserFeedAllZsetMin($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $redis_key = RedisFeedKey::getUserFeedAllZsetKey($uid);
        
        $feed_ids = $this->zRange($redis_key, 0, 0);
        $min_feed_id = !empty($feed_ids) ? reset($feed_ids) : 0;
        
        return array($this->zset_size, $min_feed_id);
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
        
        $redis_key = RedisFeedKey::getUserFeedAllZsetKey($uid);
        
        $add_nums = $this->zAdd($redis_key, $add_time, $feed_id);
        
        //如果添加成功，修整有序集合的长度
        if($add_nums > 0) {
            $this->clipUserFeedAllZset($uid);
        }
        
        return $add_nums ? $add_nums : false;
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
        
        $feed_ids = array_unique((array)$feed_ids);
        $redids_key = RedisFeedKey::getUserFeedAllZsetKey($uid);
        
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
    private function clipUserFeedAllZset($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $redis_key = RedisFeedKey::getUserFeedAllZsetKey($uid);
        
        $zset_size = $this->zSize($redis_key);
        if($zset_size > $this->zset_size) {
            return $this->zRemRangeByRank($redis_key, 0, $zset_size - $this->zset_size - 1);
        }
        
        return 0;
    }
    
	/**
     * 判断对应的key是否存在
     * @param $class_code
     */
    private function isExistUserFeedAllZset($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $redis_key = RedisFeedKey::getUserFeedAllZsetKey($uid);
        $keys = $this->keys($redis_key);
        
        return !empty($keys) ? true : false;
    }
    
    /**
     * 加载班级动态信息
     * @param $class_code
     */
    private function loader($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $redis_key = RedisFeedKey::getUserFeedAllZsetKey($uid);
        
        $GlobalKeys = ClsFactory::Create('RData.GlobalKeys');
        if(!$GlobalKeys->isExists($redis_key)) {
            
            $GlobalKeys->addKey($redis_key);
            
            import('RData.Feed.Loader.LoaderFeed');
            $loaderObject = new LoaderFeed();
        
            return $loaderObject->loadUserFeed($uid);
        }
        
        return true;
    }
    
}