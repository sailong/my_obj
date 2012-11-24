<?php

class dLiveUserSet {
    protected $_dLiveUserSet = null;
    
    public function __construct() {
        import('@.RData.Feed.dLiveUser');
        $this->_dLiveUserSet = new dLiveUserSet();
    }
    
    /**
     * 获取在线用户的集合
     */
    public function getLiveUserSet() {
        return $this->_dLiveUserSet->getLiveUserSet();
    }
    
    /**
     * 添加在线用户集合
     * @param $accounts
     */
    public function addLiveUserSet($accounts) {
        if(empty($accounts)) {
            return false;
        }
        
        return $this->_dLiveUserSet->addLiveUserSet($accounts);
    }
    
}
