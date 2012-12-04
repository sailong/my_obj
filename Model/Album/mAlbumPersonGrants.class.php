<?php
class mAlbumPersonGrants extends mBase {
    protected $_dAlbumPersonGrants = null;

    public function __construct() {
        $this->_dAlbumPersonGrants = ClsFactory::Create('Data.Album.dAlbumPersonGrants');
    }
    
    public function addAlbumPersonGrant($data, $is_return_id) {
        return $this->_dAlbumPersonGrants->addAlbumPersonGrant($data, $is_return_id);
    }
    
    public function modifyAlbumPersonGrantById($data, $id) {
        return $this->_dAlbumPersonGrants->modifyAlbumPersonGrantById($data, $id);
    }
    
    public function delAlbumPersonGrantById($id) {
        return $this->_dAlbumPersonGrants->delAlbumPersonGrantById($id);
    }
    
    public function getAlbumPersonGrantByUid($uid) {
        echo 333;die;
        return $this->_dAlbumPersonGrants->getAlbumPersonGrantByUid($uid);
    }
    
    public function getAlbumPersonGrantByAlbumId($album_id) {
        return $this->_dAlbumPersonGrants->getAlbumPersonGrantByAlbumId($album_id);
    }
}