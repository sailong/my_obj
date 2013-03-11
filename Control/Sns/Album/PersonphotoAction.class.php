<?php
class PersonphotoAction extends SnsController {
    public function _initialize() {
        parent::_initialize();
    }
    
    public function index() {
        $this->display('index');
    }
    
    public function addtpl() {
        $uid = $this->user['client_account'];
//        import('@.Control.Api.AlbumImpl.ByPerson');
//        $ByPerson = new ByPerson();
//        $album_list = $ByPerson->getAlbumByUid($uid);
        import("@.Control/Api/AlbumImpl/ByClass");
        $ByClass = new ByClass();
        
        $album_list = $ByClass->getOnlyAlbumListByClassCode(13415);
        //获取上传时的密钥处理
        import('@.Control.Sns.Album.Extra.UploadSecretkey');
        $UploadSecretKeyObject = new UploadSecretkey();
        $secret_key = $UploadSecretKeyObject->getSecretkey($uid);
        
        $this->assign('uid', $uid);
        $this->assign('secret_key', $secret_key);
        $this->assign('album_list', $album_list);
        
        $this->display('addphoto');
    }
    
    public function showone() {
        $album_id = $this->objInput->getInt('album_id');
        $photo_id = $this->objInput->getInt('photo_id');
        $account = $this->user['client_account'];
        if(empty($album_id)) {
            $this->showError('信息错误');
        }
        $mAlbumPhotos = ClsFactory::Create('Model.Album.mAlbumPhotos');
        $photolist = $mAlbumPhotos->getByAlbumId($album_id);
        $photolist = reset($photolist);

        
        foreach($photolist as $photo_id_key=>$val) {
            $path = Pathmanagement_sns::getAlbum($account);
            $photolist[$photo_id_key]['file_big'] = $path.$val['file_big'];
            $photolist[$photo_id_key]['file_middle'] = $path.$val['file_middle'];
            $photolist[$photo_id_key]['file_small'] = $path.$val['file_small'];
            $photolist[$photo_id_key]['upd_data'] = date('Y-m-d H:i:s', $val['upd_time']);
        }
        $first_img = $photolist[$photo_id];
        
        $this->assign('first_img', $first_img);
        $this->assign('photo_list', $photolist);
        
        $this->display('showone');
    }
    public function photolist() {
        $album_id = $this->objInput->getInt('album_id');
        $account = $this->user['client_account'];
        if(empty($album_id)) {
            $this->showError('信息错误');
        }
        import('@.Control.Api.AlbumImpl.ByPerson');
        $ByPerson = new ByPerson();
        $album_info = $ByPerson->getAlbumByUidAlbumId($album_id, $account);
        $album_info = reset($album_info);
        $mAlbumPhotos = ClsFactory::Create('Model.Album.mAlbumPhotos');
        $photolist = $mAlbumPhotos->getByAlbumId($album_id);
        $photolist = reset($photolist);
        $photo_ids = array_keys($photolist,true);
        $mAlbumPhotoComments = ClsFactory::Create('Model.Album.mAlbumPhotoComments');
        $py_list = $mAlbumPhotoComments->getAlbumPhotoCommentByPhotoId($photo_ids);
        $path = Pathmanagement_sns::getAlbum($account);
        
        $album_info['img_path'] = $path.$album_info['album_img'];
        $path = Pathmanagement_sns::getAlbum($account);
        $album_info['add_time'] = date('Y-m-d', $album_info['add_time']);
        $album_info['upd_time'] = date('Y-m-d', $album_info['upd_time']);
        foreach($photolist as $photo_id=>$val) {
            $photolist[$photo_id]['file_big'] = $path.$val['file_big'];
            $photolist[$photo_id]['file_middle'] = $path.$val['file_middle'];
            $photolist[$photo_id]['file_name'] = $val['file_middle'];
            $photolist[$photo_id]['file_small'] = $path.$val['file_small'];
            $photolist[$photo_id]['upd_data'] = date('Y-m-d H:i:s', $val['upd_time']);
            $photolist[$photo_id]['comments'] = !empty($val['comments']) ? $val['comments'] : 0;
        }
        
        $this->assign('path', $path);
        $this->assign('uid', $account);
        $this->assign('album_info', $album_info);
        $this->assign('album_id', $album_id);
        $this->assign('photo_list', $photolist);
        $this->display('perphotolist');
    }
    
    
}
