<?php
import('RData.RedisFeedKey');

class dMsgExamList extends rBase{

    /**
     * 得到关于$uid的未查看的成绩消息数量
     * @param bigint $uid
     */
    public function getExamMsg($uid){
        if(empty($uid)) {
            return false;
        }
        
        $redis_key = RedisFeedKey::getUserExamMsgKey($uid);
        return $this->get($redis_key);
    }
    
    /**
     * 添加关于$uid的未查看的成绩消息数量
     * @param bigint $uid
     * @param int $num
     */
    public function addExamMsg($uid, $num){
        if(empty($uid) || empty($num)){
            return false;
        }
        
        $redis_key = RedisFeedKey::getUserExamMsgKey($uid);
        $old_num = $this->get($redis_key);
        return $this->set($redis_key, $num+$old_num);
    }
    
    /**
     * 清楚关于$uid的未查看的成绩消息
     * @param bigint $uid
     */
    public function clearExamMsg($uid){
        if(empty($uid)){
            return false;
        }
        
        $redis_key = RedisFeedKey::getUserExamMsgKey($uid);
        return $this->del($redis_key);
    }
}