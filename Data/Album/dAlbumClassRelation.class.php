<?php
class dAlbumClassRelation extends dBase {
    protected $_pk = 'id';
    protected $_tablename = 'wmw_album_class_relation';
    protected $_fields = array(
                    'id',
                    'class_code',
                    'album_id',
                );
    protected $_index_list = array(
                    'class_code',
                    'album_id',
                );
                
    public function addAlbumClassRel($data, $is_return_id) {
        return $this->add($data, $is_return_id);
    }
    
    public function modifyAlbumClassRelById($data, $id) {
        return $this->modify($data, $id);
    }
    
    public function delAlbumClassRelById($id) {
        return $this->delete($id);
    }
    
    public function getAlbumClassRelByClassCode($class_code) {
        return $this->getInfoByFk($class_code,'class_code');
    }
}