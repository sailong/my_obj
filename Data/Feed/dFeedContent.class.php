<?php
class dFeedContent extends dBase{
    protected $_tablename = 'wmw_feed_content'; //主表
    protected $_fields = array(
        'feed_id',
        'feed_content',
        'img_url',
    );
    protected $_pk = 'feed_id';
    protected $_index_list = array(
        'feed_id',
    );
    
    public function getFeedContentById($feed_ids) {
        return $this->getInfoByPk($feed_ids);
    }
    
    public function addFeedContent($datas, $is_return_id) {
        return $this->add($datas, $is_return_id);
    }
    
    public function delFeedContent($feed_id) {
        return $this->delete($feed_id);
    }
    
    public function modifyFeedCotent($datas, $feed_id) {
        return $this->modify($datas, $feed_id);
    }
}