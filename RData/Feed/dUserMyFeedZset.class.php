<?php
import('@.RData.RedisFeedKey');

class dUserMyFeedZset extends rBase {
    protected $zset_size = 100;
    
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
        
        $this->loader($uid);
        
        $start_pos = $offset;
        $end_pos = $start_pos + $limit - 1;
        
        $redis_key = RedisFeedKey::getUserMyFeedZsetKey($uid);
        
        return $this->zRevRange($redis_key, $start_pos, $end_pos);
    }
    
   /**
     * 获取与我相关的动态中最小的feed_id值
     * @param $class_code
     */
    public function getUserMyFeedZsetMin($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $redis_key = RedisFeedKey::getUserMyFeedZsetKey($uid);
        
        $min_feed_id = reset($this->zRange($redis_key, 0, 0));
        
        return array($this->zset_size, $min_feed_id);
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
        
        $redis_key = RedisFeedKey::getUserMyFeedZsetKey($uid);
        
        $add_nums = $this->zAdd($redis_key, $add_time, $feed_id);
        
        //如果添加成功，修整有序集合的长度
        if($add_nums > 0) {
            $this->clipUserMyFeedZset($uid);
        }
        
        return $add_nums ? $add_nums : false;
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
        
        $feed_ids = array_unique((array)$feed_ids);
        $redids_key = RedisFeedKey::getUserMyFeedZsetKey($uid);
        
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
    private function clipUserMyFeedZset($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $redis_key = RedisFeedKey::getUserMyFeedZsetKey($uid);
        
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
    private function isExistUserMyFeedZset($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $redis_key = RedisFeedKey::getUserMyFeedZsetKey($uid);
        $keys = $this->keys($redis_key);
        
        return !empty($keys) ? true : false;
    }
    
    /**
     * 加载班级动态信息
     * @param $class_code
     */
    private function loader($uid) {
        if(empty($uid) || $this->isExistUserMyFeedZset($uid)) {
            return false;
        }
        
        import('@.RData.Feed.Loader.LoaderFeed');
        $loaderObject = new LoaderFeed();
        
        return $loaderObject->loadUserFeed($uid);
    }
    
}