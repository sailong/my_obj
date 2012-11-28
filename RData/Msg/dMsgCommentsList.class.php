<?php
import('RData.RedisFeedKey');

class dMsgCommentsList extends rBase{

    /**
     * 得到关于$uid的未查看的评论消息数量
     * @param bigint $uid
     */
    public function getCommentsMsg($uid){
        if(empty($uid)) {
            return false;
        }
        
        $redis_key = RedisFeedKey::getUserCommentsMsgKey($uid);
        return $this->get($redis_key);
    }
    
    /**
     * 添加关于$uid的未查看的评论消息数量
     * @param bigint $uid
     * @param int $num
     */
    public function addCommentsMsg($uid, $num){
        if(empty($uid) || empty($num)){
            return false;
        }
        
        $redis_key = RedisFeedKey::getUserCommentsMsgKey($uid);
        $old_num = $this->get($redis_key);
        return $this->set($redis_key, $num+$old_num);
    }
    
    /**
     * 清楚关于$uid的未查看的评论消息
     * @param bigint $uid
     */
    public function clearCommentsMsg($uid){
        if(empty($uid)){
            return false;
        }
        
        $redis_key = RedisFeedKey::getUserCommentsMsgKey($uid);
        return $this->del($redis_key);
    }
}