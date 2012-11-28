<?php
class mMsgExamList {
    private $_dMsgExamList = null;
    
    public function __construct() {
        $this->_dMsgExamList = ClsFactory::Create("RData.Msg.dMsgExamList");
    }
    
	/**
     * 得到关于$uid的未查看的成绩消息数量
     * @param bigint $uid
     */
    public function getExamMsg($uid){
        if(empty($uid)) {
            return false;
        }
        
        return $this->_dMsgExamList->getExamMsg($uid);
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
        
        return $this->_dMsgExamList->addExamMsg($uid, $num);
    }
    
    /**
     * 清楚关于$uid的未查看的成绩消息
     * @param bigint $uid
     */
    public function clearExamMsg($uid){
        if(empty($uid)){
            return false;
        }
        
        return $this->_dMsgExamList->clearExamMsg($uid);
    }
}