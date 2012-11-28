<?php
import('RData.RedisCommonKey');

class dLiveUserSet extends rBase {
    /**
     * 获取在线用户的集合
     */
    public function getLiveUserSet() {
        $redis_key = RedisCommonKey::getLiveUserSetKey();
        
        return $this->sMembers($redis_key);
    }
    
    /**
     * 添加在线用户集合
     * @param $accounts
     */
    public function addLiveUserSet($accounts) {
        if(empty($accounts)) {
            return false;
        }
        
        $redis_key = RedisCommonKey::getLiveUserSetKey();
        
        $add_nums = 0;
        foreach((array)$accounts as $uid) {
            if($this->sAdd($redis_key, $uid)) {
                $add_nums++;
            }
        }
        
        return $add_nums ? $add_nums : false;
    }
    
}
