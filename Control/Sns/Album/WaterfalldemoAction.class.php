<?php
class WaterfalldemoAction extends SnsController{
    
    public function _initialize() {
        parent::_initialize();
    }
    
    public function demo() {
        $class_code = $this->objInput->getInt('class_code');
        $album_id = $this->objInput->getInt('album_id');
        $client_account = $this->objInput->getInt('client_account');
        $login_account = $this->user['client_account'];
        
        if(empty($class_code) || empty($album_id)) {
            $this->showError("数据错误",'/Sns/ClassIndex/Index');exit;
        }
        if(empty($client_account)) {
            $client_account = $login_account;
        }
        //检测登陆者是否有编辑的权限
        $is_edit = false;
        import("@.Control/Api/AlbumApi");
        $AlbumApi = new AlbumApi();
        $album_list = $AlbumApi->getClassAlbumByAlbumId($album_id, $class_code);
        if(empty($album_list)){
            $this->showError('数据错误','/Sns/ClassIndex/Index');exit;
        }
        $album_list = reset($album_list);
        $grant = $album_list['grant'];
        $tmp_class_code = $this->check_class_code($class_code);
        if($tmp_class_code == $class_code) {
            $class_role = $this->user['client_class'][$class_code]['teacher_class_role'];
            $class_admin = $this->user['client_class'][$class_code]['class_admin'];
            if(in_array($class_role, array(1,3)) || !empty($class_admin) || $login_account==$album_list['add_account']) {
                $is_edit = true;
            }
        }else{
            if($grant !== 0) {
                $this->showError("没有权限查看","/Sns/Album/Classalbum/albumlist/class_code/{$class_code}");exit;
            }
        }
        
        $this->assign('is_edit', $is_edit);
        $this->assign('class_code', $class_code);
        $this->assign('album_id', $album_id);
        $this->assign('client_account', $client_account);
        $this->assign('login_account', $login_account);
        
        $this->display('watedemo');
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