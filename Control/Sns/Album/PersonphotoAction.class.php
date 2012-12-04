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
        import('@.Control.Api.AlbumApi');
        $album_api = new AlbumApi();
        $album_list = $album_api->getPerson($uid);
        
        $this->assign('album_list', $album_list);
        
        $this->display('upload');
    }
}
