<?php
class mAlbumClassRelation extends mBase {
    protected $_dAlbumClassRelation = null;
   
    public function __construct() {
        $this->_dAlbumClassRelation = ClsFactory::Create('Data.Album.dAlbumClassRelation');
    }
                
    public function addAlbumClassRel($data, $is_return_id) {
        return $this->_dAlbumClassRelation->addAlbumClassRel($data, $is_return_id);
    }
    
    public function modifyAlbumClassRelById($data, $id) {
        return $this->_dAlbumClassRelation->modifyAlbumClassRelById($data, $id);
    }
    
    public function delAlbumClassRelById($id) {
        return $this->_dAlbumClassRelation->delAlbumClassRelById($id);
    }
    
    public function getAlbumClassRelByClassCode($class_code) {
        return $this->_dAlbumClassRelation->getAlbumClassRelByClassCode($class_code);
    }
}