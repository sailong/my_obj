<?php
abstract class mFeedBase {
    protected $_rdata = null;
    
    public function __construct() {
        // extend me 初始化rdata
    }
    
    /**
     * 获取动态列表
     * 如果redis无数据，则有两种情况
     * 1. redis_key 为空，则读取数据库前10条,批量插入redis_key中
     * 2. redis_key 存在，未达到最大值，则从数据库中获取，并插入redis_key中.
     * 3. redis_key 存在，并且已达到最大值，则先获取reids最小值，并从数据库中获取，但不再插入redis_key中
     * 4. redis_key 存在，并且已达到最大值，从数据库获取，数据库无数据，则返回空数组，空数组代表已结束. 
     * 
     * @param $id
     * @param $offset
     * @param $limit
     * 
     * return array(
     * 		relation_id=>feed_id
     * )
     */
    public function getFeedById($id, $timeline = 0, $lastId = 0, $limit = 10) {
        if(empty($id)) {
            return false;
        }
        
        if (empty($lastId)) $lastId = 0;
        if (empty($limit)) $limit = 10;
        
        $datas_from_redis = $this->_rdata->zGetRange($id, $lastId, $limit);

        if (empty($datas_from_redis)) {
            // 如果为空有两种情况:
            $datas_from_db = $this->loader($id, $timeline, $lastId, $limit);
            
            if (empty($datas_from_db)) return false;
            
            $is_exist = $this->_rdata->isExist($id);
            
            if (!$is_exist) {
                
               //一种是key不存在，                         从数据库读取，并插入redis_key中
               $this->setFeeds($id, $datas_from_db);
               
               return $this->getFeedById($id, $lastId, $limit);
            } else {
                
               //一种是偏移量已经达到最大值,从数据库读取, 不插入redis_key中             
               return $datas_from_db;
            }
        } 
        
        if (!empty($datas_from_redis)) {
        
            // 如果不为空，也分两种情况
            
            $size = count($datas_from_redis);
            //一种是获取的数据达到limit个数，直接返回
            if ($size >= $limit) return $datas_from_redis;
            
            //一种是获取的数据未达到limit个数，需要从数据库中获取补全.还有判断是否已达到最大值,来决定是否插入redis_key中.
            if ($size < $limit) {

                  $lastId = end($datas_from_redis);
                  $limit = $limit - $size;
                  
                  $datas_from_db = $this->loader($id, $lastId, $limit);
                  
                  if (!empty($datas_from_db)) {
                      foreach ($datas_from_db as $item) {
                          $datas_from_redis["feed_id"][$item['id']] = $item['value'];
                          $datas_from_redis["timeline"][$item['id'].$item['value']] = $item["timeline"];
                      }
                  }          
                  
                  return $datas_from_redis;
            }
        }

        return false;
        
    }
    
   /**
     * 获取当前班级中最小的feed_id值
     * @param $id = class_code
     */
    public function getLastId($id) {
        if(empty($id)) {
            return false;
        }
        
        return $this->_rdata->zGetLastId($id);
    }
    
    /**
     * 添加班级的单个动态列表
     * @param $id = class_code
     * @param $feed_id
     */
    public function setFeed($id, $add_time, $feed_id) {
        if(empty($id) || empty($add_time) || empty($feed_id)) {
            return false;
        }
        
        return $this->_rdata->zSet($id, $add_time, $feed_id);
    }
    
    /**
     * 添加班级的多个动态列表
     * @param $id = class_code
     * @param $feeds	array  数据格式:<p>
     * array(<p>
     * 		0 => array(
     * 				'value' => '动态id',
     * 				'score' => '动态的添加时间'
     * 			),
     * 		1 => array(
     * 				'value' => '动态id',
     * 				'score' => '动态的添加时间'
     * 			),
     * )     
     */
    public function setFeeds($id, $feeds) {
        if(empty($id) || empty($feeds) || !is_array($feeds)) {
            return false;
        }
        
        return $this->_rdata->zSets($id, $feeds);
    }    
    
    
    /**
     * 删除指定集合中的某些值
     * @param $id = class_code
     * @param $feed_ids =  feed_id array
     */
    public function delFeeds($id, $feed_ids) {
        if(empty($id) || empty($feed_ids)) {
            return false;
        }
        
        return $this->_rdata->delZsets($id, $feed_ids);
    }
    
    /**
     * 加载班级动态信息
     * @param $id
     */
    abstract protected function loader($id, $timeline = 0, $lastId = 0, $limit = 10);
}