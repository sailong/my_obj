<?php
/**
 * author:sailong<shailong123@126.com>
 * 功能：Album manage
 * 说明：作为相册或照片操作的统一接口
 */

class AlbumApi extends ApiController {
    public function _initialize() {
        parent::_initialize();
    }
    
    public function show_class($class_code) {
        
        if(empty($class_code)) {
            $class_code = $this->objInput->getInt('class_code');
        }
        if(empty($class_code)) {
            return false;
        }
        $this->assign('grants', $this->class_grants());
        echo WEB_ROOT_DIR  . '/Common_wmw/aaa.html';
        $this->display(WEB_ROOT_DIR  . '/Common_wmw/aaa.html');
    }
    
    private function class_grants() {
        return array(
                    0 => '公开',
                    1 => '本班',
                    2 => '本校'
                );
    }
    public function show_person(){
        
    }
    
    public function createClass($data_arr) {
        if(empty($data_arr)) {
            $data_arr['album_name'] = $this->objInput->postStr('album_name');
            $data_arr['explain']    = $this->objInput->postStr('explain');
            $data_arr['grant']      = $this->objInput->postInt('grant');
            $data_arr['class_code'] = $this->objInput->postInt('class_code');
        }
        if(empty($data_arr['album_name']) || empty($data_arr['class_code'])) {
            return false;
        }
        
        $addobj = ClsFactory::Create('@.Control.Api.Album.Add');
        
        $rs = $addobj->by_class($data_arr);
        
        return $rs;
        
    }
    
    public function createPerson($data_arr) {
        $album_name = $this->objInput->postStr('album_name');
        $explain    = $this->objInput->postStr('explain');
        $grant      = $this->objInput->postInt('grant');
        $uid        = $this->objInput->postInt('uid');
        if(empty($data_arr['album_name']) || empty($data_arr['uid'])) {
            return false;
        }
        $addobj = ClsFactory::Create('@.Control.Api.Album.Add');
        
        $rs = $addobj->by_class($data_arr);
        
        return $rs;
        
    }
    
    public function getClass($class_code, $offset = 0, $limit = 10) {
        
    }
    
    public function getPerson($uid, $offset = 0, $limit = 10) {
        
    }
    
}
