<?php
import('RData.RedisFeedKey');

class dMsgRequestList extends rBase {
    
    /**
     * 得到关于$uid的未查看的请求消息数量
     * @param bigint $uid
     */
    public function getRequestMsg($uid){
        if(empty($uid)) {
            return false;
        }
        
        $redis_key = RedisFeedKey::getUserReqMsgKey($uid);
        return $this->get($redis_key);
    }
    
    /**
     * 添加关于$uid的未查看的请求消息数量
     * @param bigint $uid
     * @param int $num
     */
    public function addRequestMsg($uid, $num){
        if(empty($uid) || empty($num)){
            return false;
        }
        
        $redis_key = RedisFeedKey::getUserReqMsgKey($uid);
        $old_num = $this->get($redis_key);
        return $this->set($redis_key, $num+$old_num);
    }
    
    /**
     * 清楚关于$uid的未查看的请求消息
     * @param bigint $uid
     */
    public function clearRequestMsg($uid){
        if(empty($uid)){
            return false;
        }
        
        $redis_key = RedisFeedKey::getUserReqMsgKey($uid);
        return $this->del($redis_key);
    }
    
    /**
     * 删除已经处理的信息提醒
     * @param $uid
     */
    public function delRequestMsg($uid){
        if(empty($uid)){
            return false;
        }
        
        $redis_key = RedisFeedKey::getUserReqMsgKey($uid);
        $num = $this->get($redis_key);
        
        return $this->set($redis_key, --$num);
    }
}
