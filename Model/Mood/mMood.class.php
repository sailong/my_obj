<?php
class mMood extends mBase{
    protected $_dMood = null;
    
    public function __construct() {
        $this->_dMood = ClsFactory::Create('Data.Mood.dMood');
    }
    
    public function getMoodById($mood_ids) {
        if(empty($mood_ids)) {
            return false;
        }
        
        return $this->_dMood->getMoodById($mood_ids);
    }
    
    public function getMoodByAddAccount($add_accounts) {
        if(empty($add_accounts)) {
            return false;
        }
        
        return $this->_dMood->getMoodByAddAccount($add_accounts);
    }
    
    public function addMood($datas, $is_return_id = false) {
        if(empty($datas) || !is_array($datas)) {
            return false; 
        }
        
        return $this->_dMood->addMood($datas, $is_return_id);
    }
    
    public function modifyMood($datas, $mood_id) {
        if(empty($datas) || !is_array($datas) || empty($mood_id)) {
            return false;
        }
        
        return $this->_dMood->modifyMood($datas, $mood_id);
    }
    
    public function delMood($mood_id) {
        if(empty($mood_id)) {
            return false;
        }
        
        return $this->_dMood->delMood($mood_id);
    }
}