<?php
class dAlbumPhotos extends dBase {
    protected $_pk = 'photo_id';
    protected $_tablename = 'wmw_album_photos';
    protected $_fileds = array(
                'photo_id',
                'album_id',
                'name',
                'file_big',
                'file_middle',
                'file_small',
                'description',
                'comments',
                'upd_account',
                'upd_time',
              );
              
    protected $_indxe_list = array(
                'album_id',
              );
    //添加照片
    public function addPhoto($data, $is_return_id) {
        return $this->add($data, $is_return_id);
    }
    
    //修改照片
    public function modifyPhotoByPhotoId($data, $photo_id)　{
        return $this->modify($data, $photo_id);
    }
    
    //删除照片
    public function delPhotosByPhotoId($photo_id) {
        return $this->delete($photo_id);
    }
    
    //获得照片信息
    public function getPhotosByPhotoId($photo_ids) {
        return $this->getInfoByPk($photo_ids);
    }
}