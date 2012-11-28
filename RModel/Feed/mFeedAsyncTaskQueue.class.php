<?php
class mFeedAsyncTaskQueue {
    private $_dFeedAsyncTask = null;
    
    public function __construct() {
        import('RData.Feed.dFeedAsyncTaskQueue');
        $this->_dFeedAsyncTask = new dFeedAsyncTaskQueue();
    }
    
    /**
     * feed任务出队列
     */
    public function addAsyncTask($datas) {
        if(empty($datas)) {
            return false;
        }
        
        return $this->_dFeedAsyncTask->addAsyncTask($datas);
    }
    
    /**
     * 获取计划任务
     */
    public function getAsyncTask() {
        return $this->_dFeedAsyncTask->getAsyncTask();
    }
}