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
     * 批量添加用户的全部动态集合
     * @param $dataarr	array  数据格式:<p>
     * array(<p>
     * 		0 => array(
     * 				'uid' => '用户账号',
     * 				'feed_id' => '动态id',
     * 				'add_time' => '动态的添加时间'
     * 			),</p><p>
     * 		1 => array(
     * 				'uid' => '用户账号',
     * 				'feed_id' => '动态id',
     * 				'add_time' => '动态的添加时间'
     * 			),
     * )</p>
     * </p>
     */
    public function addUserFeedAllZsetBat($dataarr) {
        if(empty($dataarr) || !is_array($dataarr)) {
            return false;
        }
        
        $chunk_arr = array_chunk($dataarr, 200, true);
        unset($dataarr);
        
        $add_nums = 0;
        $uid_list = array();
        foreach($chunk_arr as $key=>$chunk_list) {
            $pipe = $this->multi(Redis::PIPELINE);
            foreach($chunk_list as $datas) {
                $uid = $datas['uid'];
                $add_time = $datas['add_time'];
                $feed_id = $datas['feed_id'];
                if(empty($uid) || empty($feed_id)) {
                    continue;
                }
                
                //记录添加的用户信息
                $uid_list[$uid] = $uid;
                
                $redis_key = RedisFeedKey::getUserFeedAllZsetKey($uid);
                $pipe->zAdd($redis_key, $add_time, $feed_id);
            }
            $replies = $pipe->exec();
            $add_nums += intval($this->getPipeSuccessNums($replies));
            
            unset($chunk_arr[$key]);
        }
        
        //如果添加成功，修整有序集合的长度
        foreach((array)$uid_list as $uid) {
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
        
        $pipe = $this->multi(Redis::PIPELINE);
        foreach($feed_ids as $feed_id) {
           $pipe->zDelete($redids_key, $feed_id);
        }
        $replies = $pipe->exec();
        $delete_nums = $this->getPipeSuccessNums($replies);
        
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