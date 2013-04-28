<?php
class mFeedTimeLine extends mBase{

	protected $_dFeedTimeLine = null;
	
	public function __construct() {
		$this->_dFeedTimeLine = ClsFactory::Create('Data.Feed.dFeedTimeLine');
	}
	
    /**
     * 通过我们网帐号信息获取个人动态信息
     * @param $uids   		  帐号id集合
     * @param $lastFeedId        最后查询结果的feed_id
     * @param $limit         
     */
    public function getFeedByUids($uids, $lastFeedId = 0, $limit = 10) {
        if(empty($uids)) {
            return false;
        }
        
        $where_arr = array();
        $where_arr[] = "client_account in('" . implode("','", (array)$uids) . "')";
                
        if($lastFeedId > 0) {
            $where_arr[] = "feed_id < $lastFeedId";
        }
        
        $where_arr[] = 'class_code = 0';
        
        return $this->_dFeedTimeLine->getInfo($where_arr, 'feed_id desc', 0, $limit);   
    } 

    /**
     * 通过我们网帐号信息获取全部动态信息
     * @param $uids   		  帐号id集合
     * @param $lastFeedId        最后查询结果的feed_id
     * @param $limit         
     */
    public function getAllFeedByUids($uids, $lastFeedId = 0, $limit = 10) {
        if(empty($uids)) {
            return false;
        }
        
        $where_arr = array();
        $where_arr[] = "client_account in('" . implode("','", (array)$uids) . "')";
                
        if($lastFeedId > 0) {
            $where_arr[] = "feed_id < $lastFeedId";
        }

        return $this->_dFeedTimeLine->getInfo($where_arr, 'feed_id desc', 0, $limit);
    } 
    
    /**
     * 通过班级信息获取动态信息
     * @param $class_codes       班级class_code集合
     * @param $lastFeedId        最后查询结果的feed_id
     * @param $limit         
     */
    public function getFeedByClassCodes($class_codes, $lastFeedId = 0, $limit = 10) {
        if(empty($uids)) {
            return false;
        }
        
            $where_arr = array();
        $where_arr[] = "class_code in('" . implode("','", (array)$class_codes) . "')";
        
        if($lastFeedId > 0) {
            $where_arr[] = "feed_id < $lastFeedId";
        }        
        
        return $this->_dFeedTimeLine->getInfo($where_arr, 'feed_id desc', 0, $limit);
    }     

    /**
     * 通过我们网帐号和动态类型信息获取动态信息
     * @param $uids   		  帐号id集合
     * @param $feed_type     动态类型: 1：说说 2：日志  3：相册 
     * @param $lastFeedId        最后查询结果的feed_id
     * @param $limit         
     */
    public function getFeedByUidsAndType($uids, $feed_type = 0, $lastFeedId = 0, $limit = 10) {
        if(empty($uids)) {
            return false;
        }
        
        $where_arr = array();
        $where_arr[] = "client_account in('" . implode("','", (array)$uids) . "')";
        
        if ($feed_type > 0) {
            $where_arr[] = " feed_type = $feed_type";
        }
        
        if($lastFeedId > 0) {
            $where_arr[] = "feed_id < $lastFeedId";
        }
        
        return $this->_dFeedTimeLine->getInfo($where_arr, 'feed_id desc', 0, $limit);
    }  
    
    /**
     * 通过我们网帐号和动态类型信息获取动态信息
     * @param $uids   		  帐号id集合
     * @param $feed_type     动态类型: 1：说说 2：日志  3：相册 
     * @param $lastFeedId        最后查询结果的feed_id
     * @param $limit         
     */
    public function getFeedByClassCodeAndType($class_codes, $feed_type = 0, $lastFeedId = 0, $limit = 10) {
        if(empty($uids)) {
            return false;
        }
        
        $where_arr = array();
        $where_arr[] = "class_code in('" . implode("','", (array)$class_codes) . "')";
        
        if ($feed_type > 0) {
            $where_arr[] = " feed_type = $feed_type";
        }
        
        if($lastFeedId > 0) {
            $where_arr[] = "feed_id < $lastFeedId";
        }
        
        return $this->_dFeedTimeLine->getInfo($where_arr, 'feed_id desc', 0, $limit);
    } 

    public function addTimeLine($datas, $is_return_id) {
        if(empty($datas) || !is_array($datas)) {
            return false;
        }
        
        return $this->_dFeedTimeLine->addTimeLine($datas, $is_return_id);
    }
    
    public function delTimeLine($id) {
        if(empty($id)) {
            return false;
        }
        
        return $this->_dFeedTimeLine->delTimeLine($id);
    }
    
    public function delTimelineByUidAndFeedId($uid, $feed_id, $id) {
        if(empty($uid)       || 
           empty($feed_id)   || 
           empty($id)) {
            return false;
        }
        
        $result = $this->getFeedByUidsAndFeedId($uid, $feed_id);
        if (empty($result)) return false;
        
        $id = key($result);
        
        
        return $this->delTimeline($id);
    }    

    public function modifyTimeline($datas, $id) {
        if(empty($datas) || !is_array($datas) || empty($id)) {
            return false;
        }
        
        return $this->_dFeedTimeLine->modifyTimeline($datas, $feed_id);
    }
    
    public function modifyTimelineByUidAndFeedId($uid, $feed_id, $datas, $id) {
        if(empty($uid)       || 
           empty($feed_id)   || 
           empty($datas)     || 
           !is_array($datas) || 
           empty($id)) {
            return false;
        }
        
        $result = $this->getFeedByUidsAndFeedId($uid, $feed_id);
        if (empty($result)) return false;
        
        $id = key($result);
        
        
        return $this->modifyTimeline($datas, $id);
    }   

    /**
     * 通过我们网帐号和动态类型信息获取动态信息
     * @param $uids   		  帐号id集合
     * @param $feed_id       动态id       
     */
    public function getFeedByUidsAndFeedId($uids, $feed_id) {
        if(empty($uids) || empty($feed_id)) {
            return false;
        }
        
        $where_arr = array();
        $where_arr[] = "client_account in('" . implode("','", (array)$uids) . "')";

        $where_arr[] = " feed_id = $feed_id";

        return $this->_dFeedTimeLine->getInfo($where_arr, 'feed_id desc', 0, 1);
    }
}
