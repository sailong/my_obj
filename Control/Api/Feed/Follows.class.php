<?php

class Follows {
    
    public function getFollows() {
        
    }
    
    /**
     * 通过数据库获取followslist
     */
    private function getFollowsForDatabase() {
        
    }
    
    private function initFollows() {

    }
    
    /**
     * 判断用户的follow关系是否存在于redis中
     */
    private function isExistsInRedis() {
        return $this->redis;
    }
    
    /**
     * 保存对应的follows关系
     */
    private function setFollows() {
        
    }
    
    /**
     * 获取数据在redis中存储的key格式
     */
    private function getFollowkeyInRedis() {
        //return     
    }
    
}