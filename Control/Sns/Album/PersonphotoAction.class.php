<?php
class PersonphotoAction extends SnsController {
    public function _initialize() {
        parent::_initialize();
    }
    /**
     * 用户相册列表
     */
    public function photolist() {
        $album_id = $this->objInput->getInt('album_id');
        $client_account = $this->objInput->getInt('client_account');
        $login_account = $this->user['client_account'];
        
        if(empty($client_account) || empty($album_id)) {
            $this->showError("数据错误",'/Sns/PersonIndex/Index');exit;
        }
        
        $is_true = $this->check_client_account($client_account);
        if(!$is_true) {
            $this->showError("数据错误",'/Sns/PersonIndex/Index');exit;
        }
        //检测登陆者是否有编辑的权限
        $is_edit = false;
       
        if($client_account == $login_account) {
            $is_edit = true;
        }
        
        import("@.Control/Api/AlbumApi");
        $AlbumApi = new AlbumApi();
        $album_list = $AlbumApi->getPersonAlbumByAlbumId($album_id, $client_account);
        if(empty($album_list)) {
            $this->ajaxReturn(null, '数据错误', -1, 'json');
        }
        foreach($album_list as $album_id_key=>$album_info) {
            $album_info['add_date'] = date('Y-m-d', $album_info['add_time']);
            $album_info['upd_date'] = date('Y-m-d', $album_info['upd_time']);
            $album_list[$album_id_key] = $album_info;
        }
        $album_list = reset($album_list);
        
        $this->assign('album_list', $album_list);
        $this->assign('is_edit', $is_edit);
        $this->assign('client_account', $client_account);
        $this->assign('album_id', $album_id);
        $this->assign('login_account', $login_account);
        
        $this->display('person_list_photo');
    }
    
    //瀑布流
    public function photoPlist() {
        $album_id = $this->objInput->getInt('album_id');
        $client_account = $this->objInput->getInt('client_account');
        $login_account = $this->user['client_account'];
        if(empty($client_account) || empty($album_id)) {
            $this->showError("数据错误",'/Sns/PersonIndex/Index');exit;
        }
        
        $is_true = $this->check_client_account($client_account);
        if(!$is_true) {
            $this->showError("数据错误",'/Sns/PersonIndex/Index');exit;
        }
        //检测登陆者是否有编辑的权限
        $is_edit = false;
       
        if($client_account == $login_account) {
            $is_edit = true;
        }
        import("@.Control/Api/AlbumApi");
        $AlbumApi = new AlbumApi();
        $album_list = $AlbumApi->getPersonAlbumByAlbumId($album_id, $client_account);
        if(empty($album_list)) {
            $this->ajaxReturn(null, '数据错误', -1, 'json');
        }
        foreach($album_list as $album_id_key=>$album_info) {
            $album_info['add_date'] = date('Y-m-d', $album_info['add_time']);
            $album_info['upd_date'] = date('Y-m-d', $album_info['upd_time']);
            $album_list[$album_id_key] = $album_info;
        }
        $album_list = reset($album_list);
        
        $this->assign('album_list', $album_list);
        
        $this->assign('is_edit', $is_edit);
        $this->assign('client_account', $client_account);
        $this->assign('album_id', $album_id);
        $this->assign('client_account', $client_account);
        $this->assign('login_account', $login_account);
        
        $this->display('person_list_photo_p');
    }
    
    /**
     * 获取班级相片
     */
    public function getPhotosByAlbumId() {
        $album_id   = $this->objInput->getInt('album_id');
        $client_account = $this->objInput->getInt('client_account');
        $page       = $this->objInput->getInt('page');
        if($page !== false) {
            $limit = 20;
            $offset = null;
            $page = max(1,$page);
            $offset = ($page-1)*$limit;
        }
        
        if(empty($client_account)) {
            $this->ajaxReturn(null, '', -1, 'json');
        }
        
        import("@.Control/Api/AlbumApi");
        $AlbumApi = new AlbumApi();
        $album_list = $AlbumApi->getPersonAlbumByAlbumId($album_id, $client_account);
        if(empty($album_list)) {
            $this->ajaxReturn(null, '数据错误', -1, 'json');
        }
        import("@.Control/Api/AlbumImpl/PhotoInfo");
        $PhotoInfo = new PhotoInfo();
        $photo_list = $PhotoInfo->getPhotoListByAlbumId($album_id, $offset, $limit);
        
        if(empty($photo_list)) {
            $this->ajaxReturn(null, '没有了', -1, 'json');
        }
        import("@.Common_wmw.Pathmanagement_sns");
        $img_path = Pathmanagement_sns::getAlbum($client_account);
        foreach($photo_list as $photo_id=>$photo_val) {
            $photo_val['big_img'] = $img_path.$photo_val['file_big'];
            $photo_val['middle_img'] = $img_path.$photo_val['file_middle'];
            $photo_val['small_img'] = $img_path.$photo_val['file_small'];
            $photo_val['img_path'] = $img_path;
            $photo_list[$photo_id] = $photo_val;
        }
        $this->ajaxReturn($photo_list, '', 1, 'json');
    }
    /**
     * 单张照片
     */
    public function photo() {
        
        $client_account = $this->objInput->getInt('client_account');
        $album_id = $this->objInput->getInt('album_id');
        $photo_id = $this->objInput->getInt('photo_id');
        
        $login_account = $this->user['client_account'];
        
        if(empty($client_account) || empty($album_id)) {
            $this->showError("数据错误",'/Sns/PersonIndex/Index');exit;
        }
        
        //检测登陆者是否有编辑的权限
        $is_edit = false;
       
        if($client_account == $login_account) {
            $is_edit = true;
        }
        import('@.Control.Api.AlbumApi');
        $AlbumApi = new AlbumApi();
        $albumInfo = $AlbumApi->getPersonAlbumByAlbumId($album_id,$client_account);
        if(empty($albumInfo)) {
            $this->showError("数据错误",'/Sns/PersonIndex/Index');exit;
        }
        import('@.Control.Api.AlbumImpl.PhotoInfo');
        $PhotoInfo = new PhotoInfo();
        $photolist = $PhotoInfo->getPhotoListByAlbumId($album_id,0,20);
  
        foreach($photolist as $photo_id_key=>$val) {
            $path = Pathmanagement_sns::getAlbum($client_account);
            $photolist[$photo_id_key]['file_big'] = $path.$val['file_big'];
            $photolist[$photo_id_key]['file_middle'] = $path.$val['file_middle'];
            $photolist[$photo_id_key]['file_small'] = $path.$val['file_small'];
            $photolist[$photo_id_key]['upd_data'] = date('Y-m-d', $val['upd_time']);
            $photolist[$photo_id_key]['add_data'] = date('Y-m-d', $val['upd_time']);
        }
        
        $first_img = $photolist[$photo_id];
        
        
        //登录者头像信息
        $img_path = Pathmanagement_sns::getHeadImg($login_account);
        $head_img_url =$img_path.$this->user['client_headimg'];
        if(!file_exists( WEB_ROOT_DIR.$head_img_url)){
            $head_img_url = '/Public/uc/images/user_headpic/head_pic.jpg';
        }
        
        $this->assign('photo_list', $photolist);
        $this->assign('album', $albumInfo[$album_id]);
        
        $this->assign('is_edit', $is_edit);
        $this->assign('photo_id', $photo_id);
        $this->assign('album_id', $album_id);
        $this->assign('client_account', $client_account);
        $this->assign('login_account', $login_account);
        $this->assign('client_name', $this->user['client_name']);
        $this->assign('head_img', $head_img_url);
        
        $this->display('person_photo_show');
    }
    
	/**
     * 上传照片页面
     */
    public function uplaodPhoto() {
        $client_account = $this->objInput->getInt('client_account');
        $album_id = $this->objInput->getInt('album_id');
        if(!$this->check_client_account($client_account)) {
            echo "用户不存在！";exit;
        }
        
        //获取上传时的密钥处理
        import('@.Control.Sns.Album.Extra.UploadSecretkey');
        $UploadSecretKeyObject = new UploadSecretkey();
        $secret_key = $UploadSecretKeyObject->getSecretkey($this->user['client_account']);
        
        $this->assign('secret_key', $secret_key);
        $this->assign('login_account', $this->user['client_account']);
        $this->assign('client_account', $client_account);
        
        $this->display('person_upload');
    }
	/**
     * 获取班级相册列表
     */
    public function getAlbumList() {
        $client_account = $this->objInput->getInt('client_account');
        
        if(!$this->check_client_account($client_account)) {
            $this->ajaxReturn(null, '数据错误', -1, 'json');
        }
        import("@.Control/Api/AlbumApi");
        $AlbumApi = new AlbumApi();
        $album_list = $AlbumApi->getPersonAlbumListByUid($client_account, 0, 100);
        if(empty($album_list)) {
            $this->ajaxReturn(null, '没有相册', -1, 'json');
        }
        $this->ajaxReturn($album_list, '成功获取数据', 1, 'json');
    }
	/**
     * 检测用户编号是否存在
     * @param int $client_account
     * 
     * @return  $client_account为存在，false为不存在
     */
    private function check_client_account($client_account) {
        if(empty($client_account)) {
            return false;
        }
        $mUserVm = ClsFactory::Create('RModel.mUserVm');
        $is_client = $mUserVm->getClientAccountById($client_account);
        if(empty($is_client)) {
            return false;
        }
        
        return true;
    }
}
