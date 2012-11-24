<?php
class mFeed extends mBase{

	protected $_dFeed = null;
	
	public function __construct() {
		$this->_dFeed = ClsFactory::Create('Data.Feed.dFeed');
	}
	
    public function getFeedById($feed_ids) {
        if(empty($feed_ids)) {
            return false;
        }
        
        return $this->_dFeed->getFeedById($feed_ids);
    }
    
    /**
     * 通过添加人的账号信息获取动态信息
     * @param $add_accounts
     * @param $where_appends
     * @param $offset
     * @param $limit
     */
    public function getFeedByAddAccount($add_accounts, $where_appends, $offset = 0, $limit = 10) {
        if(empty($add_accounts)) {
            return false;
        }
        
        $where_arr = array(
            'add_account' => "add_account in('" . implode("','", (array)$add_accounts) . "')",
        );
        if(!empty($where_appends)) {
            $where_arr = array_merge($where_arr, (array)$where_appends);
        }
        
        return $this->_dFeed->getInfo($where_arr, 'feed_id desc', $offset, $limit);
    }
    
    public function addFeed($datas, $is_return_id) {
        if(empty($datas) || !is_array($datas)) {
            return false;
        }
        
        return $this->_dFeed->addFeed($datas, $is_return_id);
    }
    
    public function delFeed($feed_id) {
        if(empty($feed_id)) {
            return false;
        }
        
        return $this->_dFeed->delFeed($feed_id);
    }
    
    public function modifyFeed($datas, $feed_id) {
        if(empty($datas) || !is_array($datas) || empty($feed_id)) {
            return false;
        }
        
        return $this->_dFeed->modifyFeed($datas, $feed_id);
    }
}
