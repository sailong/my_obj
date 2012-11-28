<?php
class mFeedContent extends mBase{

	protected $_dFeedContent = null;
	
	public function __construct() {
		$this->_dFeedContent = ClsFactory::Create('Data.Feed.dFeedContent');
	}
	
    public function getFeedContentById($feed_ids) {
        if(empty($feed_ids)) {
            return false;
        }
        
        return $this->_dFeedContent->getFeedContentById($feed_ids);
    }
    
    public function addFeedContent($datas, $is_return_id = false) {
        if(empty($datas) || !is_array($datas)) {
            return false;
        }
        
        return $this->_dFeedContent->addFeedContent($datas, $is_return_id);
    }
    
    public function delFeedContent($feed_id) {
        if(empty($feed_id)) {
            return false;
        }
        
        return $this->_dFeedContent->delFeedContent($feed_id);
    }
    
    public function modifyFeedContent($datas, $feed_id) {
        if(empty($datas) || !is_array($datas) || empty($feed_id)) {
            return false;
        }
        
        return $this->_dFeedContent->modifyFeedContent($datas, $feed_id);
    }
}
