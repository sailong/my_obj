<?php 
class mMsgRequestList{
    private $_dMsgRequestList = null;
    
    public function __construct(){
        $this->_dMsgRequestList = ClsFactory::Create("RData.Msg.dMsgRequestList");
    }
    
	/**
     * 得到关于$uid的未查看的请求消息数量
     * @param bigint $uid
     */
    public function getRequestMsg($uid){
        if(empty($uid)) {
            return false;
        }
        
        return $this->_dMsgRequestList->getRequestMsg($uid);
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
        
        return $this->_dMsgRequestList->addRequestMsg($uid, $num);
    }
    
    /**
     * 清楚关于$uid的未查看的请求消息
     * @param bigint $uid
     */
    public function clearRequestMsg($uid){
        if(empty($uid)){
            return false;
        }
        
        return $this->_dMsgRequestList->clearRequestMsg($uid);
    }
    
    /**
     * 删除已经处理的信息提醒
     * @param $uid
     */
    public function delRequestMsg($uid){
        if(empty($uid)){
            return false;
        }
        
        return $this->_dMsgRequestList->delRequestMsg($uid);
    }
}