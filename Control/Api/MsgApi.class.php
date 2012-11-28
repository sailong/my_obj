<?php
class MsgApi extends ApiController{
	public function __construct() {
        parent::__construct();
    }
    
    public function _initialize(){
		parent::_initialize();
    }
    
    /**
     * 添加班级作业新消息
     * @param bigint $uid
     */
    public function addHomeworkMsg($uid){
        if(empty($uid)){
            return false;
        }
        
        $mMsgHomework = ClsFactory::Create("RModel.Msg.mMsgHomeworkList");
        $mMsgHomework->addHomeworkMsg($uid);
    }
    
    /**
     * 添加评论新消息
     * @param bigint $uid
     */
    public function addCommentsMsg($uid) {
        if(empty($uid)){
            return false;
        }
        
        $mMsgComments = ClsFactory::Create("RModel.Msg.mMsgCommentsList");
        $mMsgComments->addCommentsMsg($uid);
    }
    
    /**
     * 添加班级成绩新消息
     * @param bigint $uid
     */
    public function addExamMsg($uid){
        if(empty($uid)){
            return false;
        }
        
        $mMsgExam = ClsFactory::Create("RModel.Msg.mMsgExamList");
        $mMsgExam->addExamMsg($uid);
    }
    
    /**
     * 添加班级公告新消息
     * @param bigint $uid
     */
    public function addNoticeMsg($uid) {
        if(empty($uid)){
            return false;
        }
        
        $mMsgNotice = ClsFactory::Create("RModel.Msg.mMsgNoticeList");
        $mMsgNotice->addNoticeMsg($uid);
    }
    
    /**
     * 添加班级作业新消息
     * @param bigint $uid
     */
    public function addReqMsg($uid){
        if(empty($uid)){
            return false;
        }
        
        $mMsgRequest = ClsFactory::Create("RModel.Msg.mMsgRequestList");
        $mMsgRequest->addRequestMsg($uid);
    }
    
    /**
     * 清空作业新消息
     * @param bigint $uid
     */
    public function clearHomeworkMsg($uid){
        if(empty($uid)){
            return false;
        }
        
        $mMsgHomework = ClsFactory::Create("RModel.Msg.mMsgHomeworkList");
        $mMsgHomework->clearHomeworkMsg($uid);
    }
    
    /**
     * 清空评论新消息
     * @param bigint $uid
     */
    public function clearCommentsMsg($uid) {
        if(empty($uid)){
            return false;
        }
        
        $mMsgComments = ClsFactory::Create("RModel.Msg.mMsgCommentsList");
        $mMsgComments->clearCommentsMsg($uid);
    }
    
    /**
     * 清空班级成绩新消息
     * @param bigint $uid
     */
    public function clearExamMsg($uid){
        if(empty($uid)){
            return false;
        }
        
        $mMsgExam = ClsFactory::Create("RModel.Msg.mMsgExamList");
        $mMsgExam->clearExamMsg($uid);
    }
    
    /**
     * 清空班级公告新消息
     * @param bigint $uid
     */
    public function clearNoticeMsg($uid) {
        if(empty($uid)){
            return false;
        }
        
        $mMsgNotice = ClsFactory::Create("RModel.Msg.mMsgNoticeList");
        $mMsgNotice->clearNoticeMsg($uid);
    }
    
    /**
     * 清空请求新消息
     * @param bigint $uid
     */
    public function clearReqMsg($uid){
        if(empty($uid)){
            return false;
        }
        
        $mMsgRequest = ClsFactory::Create("RModel.Msg.mMsgRequestList");
        $mMsgRequest->clearRequestMsg($uid);
    }
    
    /**
     * 删除已经处理过得请求消息
     * @param bigint $uid
     */
    public function delReqMsg($uid){
        if(empty($uid)){
            return false;
        }
        
        $mMsgRequest = ClsFactory::Create("RModel.Msg.mMsgRequestList");
        $mMsgRequest->delRequestMsg($uid);
    }
    
    private function publishmsg(){
        
    }
    
    private function getliveuid() {
        
    }
    
    private function getUidByClassCode($class_code){
        if(empty($class_code) || $class_code < 0 || !is_nan($class_code)) {
            return false;
        }
    }
}