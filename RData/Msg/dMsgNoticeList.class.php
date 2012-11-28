<?php
import('RData.RedisFeedKey');

class dMsgNoticeList extends rBase{

    /**
     * 得到关于$uid的未查看的公告消息数量
     * @param bigint $uid
     */
    public function getNoticeMsg($uid){
        if(empty($uid)) {
            return false;
        }
                                   
        $redis_key = RedisFeedKey::getUserNoticeMsgKey($uid);
        return $this->get($redis_key);
    }
    
    /**
     * 添加关于$uid的未查看的公告消息数量
     * @param bigint $uid
     * @param int $num
     */
    public function addNoticeMsg($uid, $num){
        if(empty($uid) || empty($num)){
            return false;
        }
        
        $redis_key = RedisFeedKey::getUserNoticeMsgKey($uid);
        $old_num = $this->get($redis_key);
        return $this->set($redis_key, $num+$old_num);
    }
    
    /**
     * 清楚关于$uid的未查看的公告消息
     * @param bigint $uid
     */
    public function clearNoticeMsg($uid){
        if(empty($uid)){
            return false;
        }
                     
        $redis_key = RedisFeedKey::getUserNoticeMsgKey($uid);
        return $this->del($redis_key);
    }
}