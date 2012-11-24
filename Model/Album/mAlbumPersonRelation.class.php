<?php
class mAlbumPersonRelation extends mBase {
    protected $_dAlbumPersonRelation = null;

    public function __construct() {
        $this->_dAlbumPersonRelation = ClsFacotry::Create('Data.Album.dAlbumPersonRelation');
    }
    
    public function addAlbumPersonRel($data, $is_return_id) {
        return $this->_dAlbumPersonRelation->addAlbumPersonRel($data, $is_return_id);
    }
    
    public function modifyAlbumPersonRelById($data, $id) {
        return $this->_dAlbumPersonRelation->modifyAlbumPersonRelById($data, $id);
    }
    
    public function delAlbumPersonRelById($id) {
        return $this->_dAlbumPersonRelation->delAlbumPersonRelById($id);
    }
    
    public function getAlbumPersonRelByUid($uid) {
        return $this->_dAlbumPersonRelation->getAlbumPersonRelByUid($uid);
    }
    
}