<?php
class mClassFeedAllZset {
    protected $_dClassFeedAllZset = null;
    
    public function __construct() {
        import('RData.Feed.dClassFeedAllZset');
        $this->_dClassFeedAllZset = new dClassFeedAllZset();
    }
    
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
        
        return $this->_dClassFeedAllZset->getClassFeedAllZset($class_code, $offset, $limit);
    }
    
   /**
     * 获取当前班级中最小的feed_id值
     * @param $class_code
     */
    public function getClassFeedAllZsetMin($class_code) {
        if(empty($class_code)) {
            return false;
        }
        
        return $this->_dClassFeedAllZset->getClassFeedAllZsetMin($class_code);
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
        
        return $this->_dClassFeedAllZset->addClassFeedAllZset($class_code, $add_time, $feed_id);
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
        
        return $this->_dClassFeedAllZset->delClassFeedAllZset($class_code, $feed_ids);
    }
}