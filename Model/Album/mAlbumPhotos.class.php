<?php
class mAlbumPhotos extends mBase {
    protected $_dAlbumPhotos = null;

    public function __construct() {
        $this->_dAlbumPhotos = ClsFactory::Create('Data.Album.dAlbumPhotos');
    }
    
    //添加照片
    public function addPhoto($data, $is_return_id) {
        return $this->_dAlbumPhotos->addPhoto($data, $is_return_id);
    }
    
    //修改照片
    public function modifyPhotoByPhotoId($data, $photo_id)　{
        return $this->_dAlbumPhotos->modifyPhotoByPhotoId($data, $photo_id);
    }
    
    //删除照片
    public function delPhotosByPhotoId($photo_id) {
        return $this->_dAlbumPhotos->delPhotosByPhotoId($photo_id);
    }
    
    //获得照片信息
    public function getPhotosByPhotoId($photo_ids) {
        return $this->_dAlbumPhotos->getPhotosByPhotoId($photo_ids);
    }
}