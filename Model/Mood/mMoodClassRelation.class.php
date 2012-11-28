<?php
class mMoodClassRelation extends mBase{
    protected $_dMoodClassRelation = null;
    
    public function __construct() {
        $this->_dMoodClassRelation = ClsFactory::Create('Data.Mood.dMoodClassRelation');
    }
    
    public function getMoodClassRelationById($ids) {
        if(empty($ids)) {
            return false;
        }
        
        return $this->_dMoodClassRelation->getMoodClassRelationById($ids);
    }
    
    public function getMoodClassRelationByClassCode($class_codes) {
        if(empty($class_codes)) {
            return false;
        }
        
        return $this->_dMoodClassRelation->getMoodClassRelationByClassCode($class_codes);
    }
    
    public function addMoodClassRelation($datas, $is_return_id = false) {
        if(empty($datas) || !is_array($datas)) {
            return false; 
        }
        
        return $this->_dMoodClassRelation->addMoodClassRelation($datas, $is_return_id);
    }
    
    public function modifyMoodClassRelation($datas, $id) {
        if(empty($datas) || !is_array($datas) || empty($id)) {
            return false;
        }
        
        return $this->_dMoodClassRelation->modifyMoodClassRelation($datas, $id);
    }
    
    public function delMoodClassRelation($id) {
        if(empty($id)) {
            return false;
        }
        
        return $this->_dMoodClassRelation->delMoodClassRelation($id);
    }
}