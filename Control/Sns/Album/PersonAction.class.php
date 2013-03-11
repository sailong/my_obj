<?php
class PersonAction extends SnsController{
    public function _initialize() {
        parent::_initialize();
    }
    
    public function index() {
        $uid = $this->user['client_account'];
        import('@.Control.Api.AlbumImpl.ByPerson');
        $ByPerson = new ByPerson();
        $album_list = $ByPerson->getAlbumByUid($uid,0,10);
        $path = Pathmanagement_sns::getAlbum($uid);
        foreach($album_list as $album_id=>$val) {
            if(!empty($val['album_img'])) {
                $album_list[$album_id]['album_img'] = $path.$val['album_img'];
            }
            $tmp = file_exists(Pathmanagement_sns::uploadAlbum($uid).'/'.$val['album_img']);
            if(!$tmp) {
                $album_list[$album_id]['album_img']='';
            }
        }
        dump($album_list);
        $this->assign('uid', $uid);
        $this->assign('album_list', $album_list);
        
        //$this->display('class_list_album');//personlist
        $this->display('personlist');//
    }
    
    public function toaddAlbum() {
        $this->display('addalbum');
    }
    
    public function addAlbum() {
        $album_name = $this->objInput->postStr('album_name');
        $explain    = $this->objInput->postStr('explain');
        $grant      = $this->objInput->postStr('grants');
        $uid        = $this->user['client_account'];
        
        $data = array(
            'album_name'=>$album_name,
            'album_explain'=>$explain,
            'grant'=>'1',
            'uid'=>$uid
        );
        import('@.Control.Api.AlbumImpl.ByPerson');
        $ByPerson = new ByPerson();
        $rs = $ByPerson->create($data);
        
        $this->toaddAlbum();
    }
}