<?php
class mMsgHomeworkList {
    private $_dMsgHomeworkList = null;
    
    public function __construct() {
        $this->_dMsgHomeworkList = ClsFactory::Create("RData.Msg.dMsgHomeworkList");        
    }
    
	/**
     * 得到关于$uid的未查看的作业消息数量
     * @param bigint $uid
     */
    public function getHomeworkMsg($uid){
        if(empty($uid)) {
            return false;
        }
        
        return $this->_dMsgHomeworkList->getHomeworkMsg($uid);
    }
    
    /**
     * 添加关于$uid的未查看的作业消息数量
     * @param bigint $uid
     * @param int $num
     */
    public function addHomeworkMsg($uid, $num = 1){
        if(empty($uid) || empty($num)){
            return false;
        }
        
        return $this->_dMsgHomeworkList->addHomeworkMsg($uid, $num);
    }
    
    /**
     * 清楚关于$uid的未查看的作业消息
     * @param bigint $uid
     */
    public function clearHomeworkMsg($uid){
        if(empty($uid)){
            return false;
        }
        
        return $this->_dMsgHomeworkList->clearHomeworkMsg($uid);
    }
}