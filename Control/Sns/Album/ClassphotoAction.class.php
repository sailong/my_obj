<?php
class ClassphotoAction extends SnsController {
    public function _initialize() {
        parent::_initialize();
    }
   
    /**
     * 班级相册列表
     */
    public function photolist() {
        $class_code = $this->objInput->getInt('class_code');
        $album_id = $this->objInput->getInt('album_id');
        $client_account = $this->objInput->getInt('client_account');
        
        if(empty($class_code) || empty($album_id)) {
            $this->showError("数据错误",'/Sns/ClassIndex/Index');exit;
        }
        if(empty($client_account)) {
            $client_account = $this->user['client_account'];
        }
        
        $class_code = $this->check_class_code($class_code);
        if(empty($class_code)) {
            $this->showError("数据错误",'/Sns/ClassIndex/Index');exit;
        }
        //检测登陆者是否有编辑的权限
        $is_edit = false;
        $class_role = $this->user['client_class'][$class_code]['teacher_class_role'];
        if(in_array($class_role, array(1,2,3))) {
            $is_edit = true;
        }
        
        $login_account = $this->user['client_account'];
        if(empty($client_account)) {
            $client_account = $login_account;
        }
        
        import("@.Control/Api/AlbumApi");
        $AlbumApi = new AlbumApi();
        $album_list = $AlbumApi->getClassAlbumByAlbumId($album_id, $class_code);
        if(empty($album_list)){
            $this->showError('数据错误','/Sns/ClassIndex/Index');exit;
        }
        foreach($album_list as $album_id_key=>$album_info) {
            $album_info['add_date'] = date('Y-m-d', $album_info['add_time']);
            $album_info['upd_date'] = date('Y-m-d', $album_info['upd_time']);
            $album_list[$album_id_key] = $album_info;
        }
        $album_list = reset($album_list);
        
        $this->assign('album_list', $album_list);
        $this->assign('is_edit', $is_edit);
        $this->assign('class_code', $class_code);
        $this->assign('album_id', $album_id);
        $this->assign('client_account', $client_account);
        $this->assign('login_account', $login_account);
        
        $this->display('class_list_photo');
    }
    
    //瀑布流
    public function photoPlist() {
        $class_code = $this->objInput->getInt('class_code');
        $album_id = $this->objInput->getInt('album_id');
        $client_account = $this->objInput->getInt('client_account');
        
        if(empty($class_code) || empty($album_id)) {
            $this->showError("数据错误",'/Sns/ClassIndex/Index');exit;
        }
        $login_account = $this->user['client_account'];
        if(empty($client_account)) {
            $client_account = $login_account;
        }
        
        $class_code = $this->check_class_code($class_code);
        if(empty($class_code)) {
            $this->showError("数据错误",'/Sns/ClassIndex/Index');exit;
        }
        //检测登陆者是否有编辑的权限
        $is_edit = false;
        $class_role = $this->user['client_class'][$class_code]['teacher_class_role'];
        if(in_array($class_role, array(1,2,3))) {
            $is_edit = true;
        }
        
        $this->assign('is_edit', $is_edit);
        $this->assign('class_code', $class_code);
        $this->assign('album_id', $album_id);
        $this->assign('client_account', $client_account);
        $this->assign('login_account', $login_account);
        
        $this->display('class_list_photo_p');
    }
    
    /**
     * 获取班级相片
     */
    public function getPhotosByAlbumId() {
        $album_id   = $this->objInput->getInt('album_id');
        $class_code = $this->objInput->getInt('class_code');
        $client_account = $this->objInput->getInt('client_account');
        $page       = $this->objInput->getInt('page');
        if($page !== false) {
            $limit = 12;
            $offset = null;
            $page = max(1,$page);
            $offset = ($page-1)*$limit;
        }
        
        if(empty($class_code)) {
            $this->ajaxReturn(null, '', -1, 'json');
        }
        
        import("@.Control/Api/AlbumApi");
        $AlbumApi = new AlbumApi();
        $album_list = $AlbumApi->getClassAlbumByAlbumId($album_id, $class_code);
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
        $class_code = $this->objInput->getInt('class_code');
        $album_id = $this->objInput->getInt('album_id');
        $photo_id = $this->objInput->getInt('photo_id');
        $client_account = $this->objInput->getInt('client_account');
        
        if(empty($class_code) || empty($album_id)) {
            $this->showError("数据错误",'/Sns/ClassIndex/Index');exit;
        }
        
        $login_account = $this->user['client_account'];
        if(empty($client_account)) {
            $client_account = $login_account;
        }
        
        $class_code = $this->check_class_code($class_code);
        if(empty($class_code)) {
            $this->showError("数据错误",'/Sns/ClassIndex/Index');exit;
        }
        
        //检测登陆者是否有编辑的权限
        $is_edit = false;
        $class_role = $this->user['client_class'][$class_code]['teacher_class_role'];
        if(in_array($class_role, array(1,2,3))) {
            $is_edit = 'true';
        }
        import('@.Control.Api.AlbumApi');
        
        $AlbumApi = new AlbumApi();
        
        $albumInfo = $AlbumApi->getClassAlbumByAlbumId($album_id,$class_code);
        if(empty($albumInfo)) {
            $this->showError("数据错误",'/Sns/ClassIndex/Index');exit;
        }
        import('@.Control.Api.AlbumImpl.PhotoInfo');
        $PhotoInfo = new PhotoInfo();
        $photolist = $PhotoInfo->getPhotoListByAlbumId($album_id, 0, 20);
  
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
        $img_path = Pathmanagement_sns::getHeadImg($this->user['client_account']);
        $head_img_url =$img_path.$this->user['client_headimg'];
        if(!file_exists( WEB_ROOT_DIR.$head_img_url)){
            $head_img_url = '/Public/uc/images/user_headpic/head_pic.jpg';
        }
        
        $this->assign('photo_list', $photolist);
        $this->assign('album', $albumInfo[$album_id]);
        
        $this->assign('is_edit', $is_edit);
        $this->assign('class_code', $class_code);
        $this->assign('photo_id', $photo_id);
        $this->assign('album_id', $album_id);
        $this->assign('client_account', $client_account);
        $this->assign('login_account', $login_account);
        $this->assign('client_name', $this->user['client_name']);
        $this->assign('head_img', $head_img_url);
        
        $this->display('class_photo_show');
    }
    
    /**
     * 上传照片页面
     */
    public function uplaodPhoto() {
        $class_code = $this->objInput->getInt('class_code');
        $album_id = $this->objInput->getInt('album_id');
        $class_code = $this->check_class_code($class_code);
        if(empty($class_code)) {
            $this->showError("数据错误",'/Sns/ClassIndex/Index');exit;
        }
        
        
        //获取上传时的密钥处理
        import('@.Control.Sns.Album.Extra.UploadSecretkey');
        $UploadSecretKeyObject = new UploadSecretkey();
        $secret_key = $UploadSecretKeyObject->getSecretkey($this->user['client_account']);
        
        $this->assign('secret_key', $secret_key);
        $this->assign('album_id',$album_id);      
        $this->assign('client_account', $this->user['client_account']);
        $this->assign('class_code', $class_code);
        
        $this->display('class_upload');
    }
    
    /**
     * 获取班级相册列表
     */
    public function getAlbumList() {
        $class_code = $this->objInput->getInt('class_code');
        
        if(!$this->check_class_code($class_code)) {
            $this->ajaxReturn(null, '数据错误', -1, 'json');
        }
        import("@.Control/Api/AlbumApi");
        $AlbumApi = new AlbumApi();
        $album_list = $AlbumApi->getClassAlbumListByClassCode($class_code, 0, 100);
        if(empty($album_list)) {
            $this->ajaxReturn(null, '没有相册', -1, 'json');
        }
        $this->ajaxReturn($album_list, '成功获取数据', 1, 'json');
    }
    
	/**
     * 检测班级编号是否存在
     * @param int $class_code
     * 
     * @return  $class_code为存在，false为不存在
     */
    private function check_class_code($class_code) {
        //获取当前用户所有班级编号
        $class_codes = array_keys($this->user['client_class']);
        if(empty($class_codes)) {
            return false;
        }
        if(in_array($class_code, $class_codes)) {
            return $class_code;
        }else{
            //获取默认班级编号
            $class_code = reset($class_codes);
        }
        
        return $class_code;
    }
}
