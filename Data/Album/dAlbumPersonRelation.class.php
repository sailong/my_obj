<?php
class dAlbumPersonRelation extends dBase {
    protected $_pk = 'id';
    protected $_tablename = 'wmw_album_person_relation';
    protected $_fields = array(
                    'id',
                    'client_account',
                    'album_id',
    
                );
    protected $_index_list = array(
                    'client_account',
                    'album_id',
                );
                
    public function addAlbumPersonRel($data, $is_return_id) {
        return $this->add($data, $is_return_id);
    }
    
    public function modifyAlbumPersonRelById($data, $id) {
        return $this->modify($data, $id);
    }
    
    public function delAlbumPersonRelById($id) {
        return $this->delete($id);
    }
    
    public function getAlbumPersonRelByUid($uid, $offset = 0, $limit = 10) {
        $orderby = ' id desc';
        return $this->getInfoByFk($uid, 'client_account', $orderby, $offset, $limit);
    }
    
}