<?php
import('RData.RedisFeedKey');

class dMsgHomeworkList extends rBase {
    
    /**
     * 得到关于$uid的未查看的作业消息数量
     * @param bigint $uid
     */
    public function getHomeworkMsg($uid){
        if(empty($uid)) {
            return false;
        }
        
        $redis_key = RedisFeedKey::getUserHomeworkMsgKey($uid);
        return $this->get($redis_key);
    }
    
    /**
     * 添加关于$uid的未查看的作业消息数量
     * @param bigint $uid
     * @param int $num
     */
    public function addHomeworkMsg($uid, $num){
        if(empty($uid) || empty($num)){
            return false;
        }
        
        $redis_key = RedisFeedKey::getUserHomeworkMsgKey($uid);
        $old_num = $this->get($redis_key);
        
        return $this->set($redis_key, $num+$old_num);
    }
    
    /**
     * 清楚关于$uid的未查看的作业消息
     * @param bigint $uid
     */
    public function clearHomeworkMsg($uid){
        if(empty($uid)){
            return false;
        }
        
        $redis_key = RedisFeedKey::getUserHomeworkMsgKey($uid);
        return $this->del($redis_key);
    }
}
