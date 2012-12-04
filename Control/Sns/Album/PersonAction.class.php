<?php
class PersonAction extends SnsController{
    public function _initialize() {
        parent::_initialize();
    }
    
    public function index() {
        import('@.Control.Api.AlbumApi');
        $albumobj = new AlbumApi();
        $album_list = $albumobj->getPerson($this->user['client_account'],0,10);
        $album_list = json_decode($album_list,true);
        $this->assign('album_list', $album_list);
        dump($album_list);
        $this->display('personlist');
    }
    public function toaddAlbum() {
        $this->display('addalbum');
    }
    public function addAlbum() {
        $album_name = $this->objInput->postStr('album_name');
        $explain    = $this->objInput->postStr('explain');
        $grant      = $this->objInput->postStr('grant');
        $uid        = $this->user['client_account'];
        
        //$albumobj = ClsFactory::Create('@.Control.Api.AlbumApi');
        $data = array(
            'album_name'=>$album_name,
            'explain'=>$explain,
            'grant'=>'1',
            'uid'=>$uid
        );
        import('@.Control.Api.AlbumApi');
        $albumobj = new AlbumApi();
        $rs = $albumobj->createPerson($data);
        dump($rs);
    }
}