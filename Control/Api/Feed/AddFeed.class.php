<?php

class AddFeed {
    private $dispatchObject = null;
    
    public function __construct() {
        $this->dispatchObject = new DispatchFeed();
    }
    
    public function addFeed() {
        //feed数据入库
        
        
        
        //分发数据到redis
        
    }
    
    private function dispatchFeed() {
        return $this->dispatchObject->dispatch();        
    }
}
