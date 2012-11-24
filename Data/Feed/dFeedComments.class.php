<?php
class dFeedComments extends dBase{
    protected $_tablename = 'wmw_feed_comments'; //主表
    protected $_fields = array(
        'comment_id',
        'up_id',
        'feed_id',
        'content',
        'client_account',
        'add_time',
    	'level',
    );
    protected $_pk = 'comment_id';
    protected $_index_list = array(
        'comment_id',
    	'feed_id',
    );
    
    public function getFeedCommentsById($comment_ids) {
        return $this->getInfoByPk($comment_ids);
    }
    
    public function addFeedComments($datas, $is_return_id) {
        return $this->add($datas, $is_return_id);
    }
    
    public function delFeedComments($comment_id) {
        return $this->delete($comment_id);
    }
    
    public function modifyFeedComment($datas, $comment_id) {
        return $this->modify($datas, $comment_id);
    }
}