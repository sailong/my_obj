<?php
class mMoodPersonRelation extends mBase{
    protected $_dMoodPersonRelation = null;
    
    public function __construct() {
        $this->_dMoodPersonRelation = ClsFactory::Create('Data.Mood.dMoodPersonRelation');
    }
    
    public function getMoodPersonRelationById($ids) {
        if(empty($ids)) {
            return false;
        }
        
        return $this->_dMoodPersonRelation->getMoodPersonRelationById($ids);
    }
    
    public function getMoodPersonRelationByClientAccount($client_accounts) {
        if(empty($client_accounts)) {
            return false;
        }
        
        return $this->_dMoodPersonRelation->getMoodPersonRelationByClientAccount($client_accounts);
    }
    
    public function addMoodPersonRelation($datas, $is_return_id = false) {
        if(empty($datas) || !is_array($datas)) {
            return false; 
        }
        
        return $this->_dMoodPersonRelation->addMoodPersonRelation($datas, $is_return_id);
    }
    
    public function modifyMoodPersonRelation($datas, $id) {
        if(empty($datas) || !is_array($datas) || empty($id)) {
            return false;
        }
        
        return $this->_dMoodPersonRelation->modifyMoodPersonRelation($datas, $id);
    }
    
    public function delMoodPersonRelation($id) {
        if(empty($id)) {
            return false;
        }
        
        return $this->_dMoodPersonRelation->delMoodPersonRelation($id);
    }
}