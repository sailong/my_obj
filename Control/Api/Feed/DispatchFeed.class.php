<?php

class DispatchFeed {
    private $followsObject = null;
    public function dispatch() {
        //通过redis获取用户关注的follows列表
        
        
        //数据分发
        
    }
    
    
    private function getFollows() {
        return $this->followsObject->getFollows($uid);
    }
    
}