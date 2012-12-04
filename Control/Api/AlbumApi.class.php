<?php
/**
 * author:sailong<shailong123@126.com>
 * 功能：Album manage
 * 说明：作为相册或照片操作的统一接口
 */

class AlbumApi extends ApiController {
    
    public function __construct() {
        parent::__construct();
    }    
    
    public function _initialize() {
        parent::_initialize();
    }
    
//班级相册------------------------------------------------------------------------------------------------------------    
    /**
     * 创建班级相册
     * @param array $data_arr
     * @return json  {album_id:$album_id,is_success:true}
     */
    public function createClass($data_arr) {
        if(empty($data_arr)) {
            $data_arr['album_name'] = $this->objInput->postStr('album_name');
            $data_arr['explain']    = $this->objInput->postStr('explain');
            $data_arr['grant']      = $this->objInput->postInt('grant');
            $data_arr['class_code'] = $this->objInput->postInt('class_code');
            $data_arr['uid']        = $this->objInput->postInt('uid');
        }
        if(empty($data_arr)) {
            $this->ajaxReturn(null, '', -1, 'json');
        }
        $by_class = ClsFactory::Create('@.Control.Api.AlbumImpl.ByClass');
        $json_list = $by_class->create($data_arr);
        if(empty($json_list)) {
            $this->ajaxReturn(null, '', -1, 'json');
        }
        
        return $this->ajaxReturn($json_list, '', 1, 'json');
        
    }
    
/**
     * 获取班级相册接口
     * @param int $class_code
     * @param int $offset
     * @param int $limit
     */
    public function getClass($class_code, $offset = null, $limit = null) {
        if(empty($class_code)) {
            return false;
        }
        
        if(empty($class_code)) {
            $this->ajaxReturn(null, '', -1, 'json');
        }
        $by_class = ClsFactory::Create('@.Control.Api.AlbumImpl.ByClass');
        $json_list = $by_class->getAlbumByClass($class_code, $offset, $limit);
        if(empty($json_list)) {
            $this->ajaxReturn(null, '', -1, 'json');
        }
        
        return $this->ajaxReturn($json_list, '', 1, 'json');
        
    }
    
    /**
     * 班级相册修改
     * @param $data_arr
     * @return json
     */
    public function updateClass($data_arr) {
        if(empty($data_arr)) {
            $data_arr['album_name'] = $this->objInput->postStr('album_name');
            $data_arr['explain']    = $this->objInput->postStr('explain');
            $data_arr['grant']      = $this->objInput->postInt('grant');
            $data_arr['class_code'] = $this->objInput->postInt('class_code');
            $data_arr['upd_account']= $this->user['client_account'];
            $data_arr['album_id']   = $this->objInput->postInt('album_id');
        }
        if(empty($data_arr) || $data_arr['upd_account']) {
            $this->ajaxReturn(null, '', -1, 'json');
        }
        
        $by_class = ClsFactory::Create('@.Control.Api.AlbumImpl.ByClass');
        $rs = $by_class->updAlbum($data_arr, $data_arr['album_id'], $data_arr['class_code']);
        if(empty($rs)) {
            $this->ajaxReturn(null, '', -1, 'json');
        }
        
        return $this->ajaxReturn(null, '', 1, 'json');
    }
    
    /*
     * 通过相册id获取相册信息
     * @$album_id int 相册id
     * @return json
     */
    
    public function getByAlbumIdClassCode($album_id, $class_code) {
        if(empty($album_id)) {
            return false;
        }
        
        //获取相处信息
        $by_class = ClsFactory::Create('@.Control.Api.AlbumImpl.ByClass');
        $rs = $by_class->getAlbumByClassAlbumId($album_id, $class_code);
        if(empty($rs)) {
            return false;
        }
        
        return $this->ajaxReturn($rs, '', 1, 'json');
    }
    
//相册-------------------------------------------------------------------------------------------------    
    /**
     * 设置相册封面
     * @$data array 封面数据
     * @$album_id int 相册Id
     * @return json
     */
    public function setClassAlbumImg($data, $album_id, $class_code) {
        if(empty($data) || empty($album_id)) {
            return false;
        }
        //检查相册是否存在
        $by_class = ClsFactory::Create('@.Control.Api.AlbumImpl.ByClass');
        $rs = $by_class->updAlbum($data, $album_id, $class_code);
        
        if(empty($rs)) {
            return false;
        }
        
        return $this->ajaxReturn($rs, '', 1, 'json');
    }
    
/**
     * 设置相册封面
     * @$data array 封面数据
     * @$album_id int 相册Id
     * @return json
     */
    public function setPersonAlbumImg($data, $album_id, $uid) {
        if(empty($data) || empty($album_id)) {
            return false;
        }
        //检查相册是否存在
        $by_class = ClsFactory::Create('@.Control.Api.AlbumImpl.ByPerson');
        $rs = $by_class->updAlbum($data, $album_id, $uid);
        
        if(empty($rs)) {
            return false;
        }
        
        return $this->ajaxReturn($rs, '', 1, 'json');
    }
    
//个人相册-------------------------------------------------------------------------------------------------
    /**
     * 创建个人相册
     * @param $data_arr
     * @return json {album_id:$album_id,is_success:true}
     */
    public function createPerson($data_arr) {
        if(empty($data_arr)) {
            $data_arr['album_name'] = $this->objInput->postStr('album_name');
            $data_arr['explain']    = $this->objInput->postStr('explain');
            $data_arr['grant']      = $this->objInput->postInt('grant');
            $data_arr['uid']        = $this->objInput->postInt('uid');
        }
        
        if(empty($data_arr)) {
            $this->ajaxReturn(null, '', -1, 'json');
        }
        
        import('@.Control.Api.AlbumImpl.ByPerson');
        $albumObj = new ByPerson();
        
        $json_list = $albumObj->create($data_arr);
        if(empty($json_list)) {
            $this->ajaxReturn(null, '', -1, 'json');
        }
        
        return $this->ajaxReturn($json_list, '', 1, 'json');
        
    }
    
    
    /**
     * 获取个人相册接口
     * @param int $uid
     * @param int $offset
     * @param int $limit
     */
    public function getPerson($uid, $offset = null, $limit = null) {
        if(empty($uid)) {
            return false;
        }
        //帐号是否存在
        $mUser = ClsFactory::Create('Model.mUser');
        $user_info = $mUser->getClientAccountById($uid);
        if(empty($user_info)) {
            return false;
        }
        unset($user_info);
        //从关系获取相册album_id
        $mAlbumPersonRelation = ClsFactory::Create('Model.Album.mAlbumPersonRelation');
        $rel_list = $mAlbumPersonRelation->getAlbumPersonRelByUid($uid, $offset, $limit);
        $rel_list = reset($rel_list);
        $album_ids_list = $add_accounts = array();
        foreach($rel_list as $key=>$val) {
            $album_ids_list[$val['album_id']] = $val['album_id'];
            $add_accounts[$val['client_account']] = $val['client_account'];
        }
        unset($rel_list);
        //没有相关个人的相册
        if(empty($album_ids_list)) {
            return false;
        }
        $mAlbum = ClsFactory::Create('Model.Album.mAlbum');
        $album_list = $mAlbum->getAlbumByAlbumId($album_ids_list);
        $mUser = ClsFactory::Create('Model.mUser');
        $client_infos = $mUser->getUserBaseByUid($add_accounts);
        unset($add_accounts);
       
        //没有相关相册的信息
        if(empty($album_list)) {
            return false;
        }
        
        $album_ids_list = array_keys($album_list);
        $mAlbumPersonGrants = ClsFactory::Create('Model.Album.mAlbumPersonGrants');
        $grant_list = $mAlbumPersonGrants->getAlbumPersonGrantByAlbumId($album_ids_list);
        
       
        //数据处理，将相册权限信息和相册信息合并
        foreach($album_list as $album_id=>$val) {
            $grant_info = reset($grant_list[$album_id]);
           
            $grant = $grant_info['grant'];
            $grant = !empty($grant) ? $grant : 1;
            
            $album_list[$album_id]['grant'] = $grant;
            $album_list[$album_id]['client_name'] = $client_infos[$val['add_account']]['client_name'];
        }
        
        unset($grant_list);
        
        return $album_list;
        //$this->ajaxReturn($album_list, '', 1, 'json');
        
    }
    
    /*
     * 通过相册id获取相册信息
     * @$album_id int 相册id
     * @return json
     */
    
    public function getByPersonAlbumId($album_id) {
        if(empty($album_id)) {
            return false;
        }
        
        //获取相处信息
        $mAlbum = ClsFactory::Create('Model.Album.mAlbum');
        $rs = $mAlbum->getAlbumByAlbumId($album_id);
        if(empty($rs)) {
            return false;
        }
        $grant = $this->getGrantByPersonAlbumId($album_id);
        
        $rs[$album_id]['grant'] = $grant['grant'];
        
        return json_decode($rs);
    }
    /**
     * 通过相册id获取班级相册权限
     * @param $album_id
     * return array  一维数组
     */
    private function getGrantByPersonAlbumId($album_id) {
        if(empty($album_id)) {
            return false;
        }
        $mAlbumPersonGrants = ClsFactory::Create('Model.Album.mAlbumPersonGrants');
        $rs = $mAlbumPersonGrants->getAlbumPersonGrantByAlbumId($album_id);
        if(empty($rs)) {
            return false;
        }
        $rs = reset($rs[$album_id]);
        
        return $rs;
    }
    
}
