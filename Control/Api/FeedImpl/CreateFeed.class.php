<?php
define('FEED_CONTEXT_PERSON', 1);
define('FEED_CONTEXT_CLASS', 2);

class CreateFeed {
    
    public function createPersonFeed($entity_datas, $feed_type, $uid) {
        if(empty($entity_datas) || empty($feed_type) || empty($uid)) {
            return false;
        }
        
        //提取feed信息
        $feed_datas = $this->extractFeed($entity_datas, $feed_type);
        
        //mysql入库操作
        $feed_id = $this->saveFeedToDatabase($feed_datas);
        
        if(!empty($feed_id)) {
            //加入feed处理的异步队列
            $queue_datas = array(
                'feed_id' => $feed_id,
                'context' => FEED_CONTEXT_PERSON,
                'feed_type' => $feed_type,
                'uid' => $uid,
            );
            
            //加入feed处理的异步队列
            $this->pushFeedToRedisQueue($queue_datas);
        }
        
        return $feed_id;
    }
    
    /**
     * 在班级中产生的动态
     * @param $entity_datas
     * @param $feed_type
     * @param $class_code
     */
    public function createClassFeed($entity_datas, $feed_type, $uid, $class_code) {
        //提取feed信息,将feed的信息整理到一个数组中去
        $feed_datas = $this->extractFeed($entity_datas, $feed_type);
        
        //mysql入库操作
        $feed_id = $this->saveFeedToDatabase($feed_datas);
        
        if(!empty($feed_id)) {
            //加入feed处理的异步队列
            $queue_datas = array(
                'feed_id' => $feed_id,
                'context' => FEED_CONTEXT_CLASS,
            	'feed_type' => $feed_type,
                'uid'	  => $uid,
                'class_code' => $class_code,
            );
            $this->pushFeedToRedisQueue($queue_datas);
        }
        
        return $feed_id;
    }
    
    /**
     * 提取实体中的动态信息
     * @param  $entity_datas
     * @param  $feed_type
     */
    private function extractFeed($entity_datas, $feed_type) {
        if(empty($entity_datas) || !is_array($entity_datas)) {
            return false;
        }
        
        import('@.Control.Api.FeedImpl.ExtractFeed');
        $extractObject = new ExtractFeed();
        
        return $extractObject->getFeedDatas($entity_datas, $feed_type);
    }
    
    /**
     * 将feed信息添加到数据库
     * @param $feed_datas
     */
    private function saveFeedToDatabase($feed_datas) {
        if(empty($feed_datas) || !is_array($feed_datas)) {
            return false;
        }
        
        $mFeed = ClsFactory::Create('Model.Feed.mFeed');
        $feed_id = $mFeed->addFeed($feed_datas, true);
        if(empty($feed_id)) {
            return false;
        }
        
        $feed_datas['feed_id'] = $feed_id;
        
        $mFeedContent = ClsFactory::Create('Model.Feed.mFeedContent');
        $mFeedContent->addFeedContent($feed_datas);
        
        return $feed_id;
    }
    
    /**
     * 将动态信息加载到动态的异步队列中去
     * @param $feed_id
     * @param $context
     */
    private function pushFeedToRedisQueue($queue_datas) {
        if(empty($queue_datas)) {
            return false;
        }
        
        $mFeedAsyncTaskQueue = ClsFactory::Create('RModel.Feed.mFeedAsyncTaskQueue');
        
        return $mFeedAsyncTaskQueue->addAsyncTask($queue_datas);
    }
}