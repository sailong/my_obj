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
     * 批量添加班级的动态集合
     * @param $dataarr	array  数据格式:<p>
     * array(<p>
     * 		0 => array(
     * 				'class_code' => '班级code',
     * 				'feed_id' => '动态id',
     * 				'add_time' => '动态的添加时间'
     * 			),</p><p>
     * 		1 => array(
     * 				'class_code' => '班级code',
     * 				'feed_id' => '动态id',
     * 				'add_time' => '动态的添加时间'
     * 			),
     * )</p>
     * </p>
     */
    public function addClassFeedAllZsetBat($dataarr) {
        if(empty($dataarr) || !is_array($dataarr)) {
            return false;
        }
        
        //分组处理，每次批量处理最多200个命令
        $chunk_arr = array_chunk($dataarr, 200, true);
        unset($dataarr);
        
        $add_nums = 0;
        $class_code_list = array();
        foreach($chunk_arr as $key => $chunk_list) {
            $pipe = $this->multi(Redis::PIPELINE);
            foreach($chunk_list as $datas) {
                $class_code = $datas['class_code'];
                $add_time = $datas['add_time'];
                $feed_id = $datas['feed_id'];
                if(empty($feed_id) || empty($class_code)) {
                    continue;
                }
                
                $class_code_list[$class_code] = $class_code;
                
                $redis_key = RedisFeedKey::getClassFeedAllZsetKey($class_code);
                $pipe->zAdd($redis_key, $add_time, $class_code);
            }
            $replies = $pipe->exec();
            
            $add_nums += intval($this->getPipeSuccessNums($replies));
            
            unset($chunk_arr[$key]);
        }
        
        //如果添加成功，修整有序集合的长度
        foreach((array)$class_code_list as $class_code) {
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