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
        
        $accounts = array_unique((array)$accounts);
        $chunk_arr = array_chunk($accounts, 200, true);
        unset($accounts);
        
        $redis_key = RedisCommonKey::getLiveUserSetKey();
        
        $add_nums = 0;
        foreach($chunk_arr as $key=>$chunk_list) {
            $pipe = $this->multi(Redis::PIPELINE);
            foreach($chunk_list as $uid) {
                $pipe->sAdd($redis_key, $uid);
            }
            $replies = $pipe->exec();
            $add_nums += intval($this->getPipeSuccessNums($replies));
            
            unset($chunk_arr[$key]);
        }
        
        return $add_nums ? $add_nums : false;
    }
    
}
