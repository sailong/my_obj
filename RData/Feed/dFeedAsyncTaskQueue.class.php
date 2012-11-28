<?php
import('RData.RedisFeedKey');

class dFeedAsyncTaskQueue extends rBase {
    /**
     * feed任务出队列
     */
    public function addAsyncTask($datas) {
        if(empty($datas)) {
            return false;
        }
        
        return $this->rPush(RedisFeedKey::getFeedAsyncTaskQueueKey(), json_encode($datas));
    }
    
    /**
     * 获取计划任务
     */
    public function getAsyncTask() {
        $datas = $this->lPop(RedisFeedKey::getFeedAsyncTaskQueueKey());
        
        return !empty($datas) ? json_decode($datas, true) : false;
    }
}