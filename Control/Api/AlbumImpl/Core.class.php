<?php
/**
 * 相册的相关数据操作
 * @add 相册的添加
 * @delete 相册的删除
 * @author sailong
 *
 */
class Core {
    
    protected $_mAlbum = null;
    
    public function __construct() {
        $this->_mAlbum = ClsFactory::Create('Model.Album.mAlbum');
    }
    
    public function add($datas) {
        if(empty($datas) || !is_array($datas)) {
            return false;
        }
        $datas = $this->format_album_data($datas);
        $rs = $this->_mAlbum->addAlbum($datas, true);
        return $rs;
    }
    
    public function delete($album_id) {
        if(empty($album_id) || !is_array($album_id)) {
            return false;
        }
        
        $rs = $this->_mAlbum->delAlbumByAlbumId($album_id);
        
        return $rs;
    }
    
    public function get($album_id) {
        if(empty($album_id) || !is_array($album_id)) {
            return false;
        }
        
        $rs = $this->_mAlbum->getAlbumByAlbumId($album_id);
        
        return $rs;
    }
    public function upd($data, $album_id) {
        if(empty($data) || empty($album_id)) {
            return false;
        }
        $mAlbum = ClsFactory('Model.Album.mAlbum');
        $rs = $mAlbum->modifyAlbumByAlbumId($data, $album_id);
        
        return !empty($rs) ? $rs : false;
    }
    //初始化相册信息
    private function format_album_data($data) {
        if(empty($data)) {
            return false;
        }
        $current_time = time();
        $data = array(
            'album_name'    => $data['album_name'],
            'album_explain' => $data['explain'],
            'album_img'		=> '',
            'add_account'   => $data['uid'],
            'add_time'      => $current_time,
            'upd_account'   => $data['uid'],
            'upd_time'      => $current_time,
        );
              
        return $data;
    }
    
    
    
}