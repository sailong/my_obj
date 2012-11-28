<?php
class mMsgCommentsList{
    private $_dMsgCommentsList = null;
    
    public function __construct() {
        $this->_dMsgCommentsList = ClsFactory::Create("RData.Msg.dMsgCommentsList");
    }
    
    
	/**
     * 得到关于$uid的未查看的评论消息数量
     * @param bigint $uid
     */
    public function getCommentsMsg($uid){
        if(empty($uid)) {
            return false;
        }
        
        return $this->_dMsgCommentsList->getCommentsMsg($uid);
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
        
        return $this->_dMsgCommentsList->addCommentsMsg($uid, $num);
    }
    
    /**
     * 清楚关于$uid的未查看的评论消息
     * @param bigint $uid
     */
    public function clearCommentsMsg($uid){
        if(empty($uid)){
            return false;
        }
        
        return $this->_dMsgCommentsList->clearCommentsMsg($uid);
    }
    
}