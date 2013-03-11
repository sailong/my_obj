<?php
class dFeed extends dBase{
    protected $_tablename = 'wmw_feed'; //主表
    protected $_fields = array(
        'feed_id',
        'feed_type',
        'title',
    	'feed_content',
    	'from_id',
    	'add_account',
        'img_url',
    	'timeline',
    );
    protected $_pk = 'feed_id';
    protected $_index_list = array(
        'feed_id',
        'add_account'
    );
    
    public function getFeedById($feed_ids) {
        return $this->getInfoByPk($feed_ids);
    }

    public function addFeed($datas, $is_return_id) {
        return $this->add($datas, $is_return_id);
    }
    
    public function delFeed($feed_id) {
        return $this->delete($feed_id);
    }
    
    public function modifyFeed($datas, $feed_id) {
        return $this->modify($datas, $feed_id);
    }
}