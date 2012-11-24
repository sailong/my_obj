<?php
class mFeedComments extends mBase{

	protected $_dFeedComments = null;
	
	public function __construct() {
		$this->_dFeedComments = ClsFactory::Create('Data.Feed.dFeedComments');
	}
	
    public function getFeedCommentsById($comment_ids) {
        if(empty($comment_ids)) {
            return false;
        }
        
        return $this->_dFeedComments->getFeedCommentsById($comment_ids);
    }
    
    public function addFeedComments($datas, $is_return_id) {
        if(empty($datas) || !is_array($datas)) {
            return false;
        }
        
        return $this->_dFeedComments->addFeedComments($datas, $is_return_id);
    }
    
    public function delFeedComments($comment_id) {
        if(empty($comment_id)) {
            return false;
        }
        
        return $this->_dFeedComments->delFeedComments($comment_id);
    }
    
    public function modifyFeedComments($datas, $comment_id) {
        if(empty($datas) || !is_array($datas) || empty($comment_id)) {
            return false;
        }
        
        return $this->_dFeedComments->modifyFeedComments($datas, $comment_id);
    }
}
