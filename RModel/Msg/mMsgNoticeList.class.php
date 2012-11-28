<?php
class mMsgNoticeList {
    private $_dMsgNoticeList = nll;
    
    public function __construct() {
        $this->_dMsgNoticeList = ClsFactory::Create("RData.Msg.dMsgNoticeList");
    }
    
	/**
     * 得到关于$uid的未查看的公告消息数量
     * @param bigint $uid
     */
    public function getNoticeMsg($uid){
        if(empty($uid)) {
            return false;
        }
                                   
        return $this->_dMsgNoticeList->getNoticeMsg($uid);
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
        
        return $this->_dMsgNoticeList->addNoticeMsg($uid, $num);
    }
    
    /**
     * 清楚关于$uid的未查看的公告消息
     * @param bigint $uid
     */
    public function clearNoticeMsg($uid){
        if(empty($uid)){
            return false;
        }
                     
        return $this->_dMsgNoticeList->clearNoticeMsg($uid);
    }
}