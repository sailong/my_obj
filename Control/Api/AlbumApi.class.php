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
            $album_name = $this->objInput->postStr('album_name');
            $explain    = $this->objInput->postStr('explain');
            $grant      = $this->objInput->postInt('grant');
            $class_code = $this->objInput->postInt('class_code');
            $uid        = $this->objInput->postInt('uid');
        }else{
            $album_name = $data_arr['album_name'];
            $explain    = $data_arr['explain'];
            $grant      = $data_arr['grant'];
            $class_code = $data_arr['class_code'];
            $uid        = $data_arr['uid'];
        }
        //当前时间戳
        $current_time = time();
        
        if(empty($album_name) || empty($class_code) || empty($grant)) {
            return false;
        }
        //班级是否存在
        $mClassInfo = ClsFactory::Create('Model.mClassInfo');
        $class_info = $mClassInfo->getClassInfoBaseById($class_code);
        if(empty($class_info)) {
            return false;
        }
        
        //相册
        $album_data = array(
                    'album_name'    => $album_name,
                    'album_explain' => $explain,
                    'add_account'   => $uid,
                    'add_time'      => $current_time,
                    'upd_account'   => $uid,
                    'upd_time'      => $current_time,
              );
        $mAlbum = ClsFactory('Model.Album.mAlbum');
        $album_id = $mAlbum->addAlbum($album_data, true);
        
        if(empty($album_id)) {
            return false;
        }
        
        //相册权限初始化
        $grant_data = array(
                    'class_code' => $class_code,
                    'album_id'   => $album_id,
                    'grant'      => $grant
                );
        $mAlbumClassGrants = ClsFactory::Create('Model.Album.mAlbumClassGrants');
        $album_class_grant_id = $mAlbumClassGrants->addAlbumClassGrant($grant_data, true);
        if(empty($album_class_grant_id)) {
            //删除相册信息
            $mAlbum->delAlbumByAlbumId($album_id);
            return false;
        }
        
        //班级与相册关系
        $rel_data = array(
                    'class_code' => $class_code,
                    'album_id'   => $album_id,
                );
        $mAlbumClassRelation = ClsFactory::Create('Model.Album.mAlbumClassRelation');
        $rel_id = $mAlbumClassRelation->addAlbumClassRel($rel_data, true);
        if(empty($rel_id)) {
            //删除相册信息
            $mAlbum->delAlbumByAlbumId($album_id);
            //删除相册权限
            $mAlbumClassGrants->delAlbumClassGrantById($album_class_grant_id);
            return false;
        }
        
        $json_list = array(
                    'album_id'   => $album_id,
                    'is_success' => true 
        );
        
        return json_encode($json_list);
        
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
        //班级是否存在
        $mClassInfo = ClsFactory::Create('Model.mClassInfo');
        $class_info = $mClassInfo->getClassInfoBaseById($class_code);
        if(empty($class_info)) {
            return false;
        }
        //从关系得到相册album_id
        $mAlbumClassRelation = ClsFactory::Create('Model.Album.mAlbumClassRelation');
        $rel_list = $mAlbumClassRelation->getAlbumClassRelByClassCode($class_code, $offset, $limit);
        $rel_list = reset($rel_list);
        $album_ids_list = array();
        
        foreach($rel_list as $key=>$val) {
            $album_ids_list[$val['album_id']] = $val['album_id'];
        }
        unset($rel_list);
        
        //班级还没有相册
        if(empty($album_ids_list)) {
            return false;
        }
        
        $mAlbum = ClsFactory('Model.Album.mAlbum');
        $album_list = $mAlbum->getAlbumByAlbumId($album_ids_list);
        //没有相关相册的信息
        if(empty($album_list)) {
            return false;
        }
        
        $album_ids_list = array_keys($album_list);
        
        //获取相关相册的权限信息
        $mAlbumClassGrants = ClsFactory::Create('Model.Album.mAlbumClassGrants');
        $grant_list = $mAlbumClassGrants->getAlbumClassGrantByAlbumId($album_ids_list);
        
        //数据处理，将相册权限信息和相册信息合并
        foreach($album_list as $album_id=>$val) {
            $grant_info = reset($grant_list[$album_id]);
            $grant = $grant_info['grant'];
            $grant = !empty($grant) ? $grant : 0;
            $album_list[$album_id]['grant'] = $grant;
        }
        unset($grant_list);
        
        return json_decode($album_list);
        
    }
    
    /**
     * 班级相册修改
     * @param $data_arr
     * @return json
     */
    public function updateClass($data_arr) {
        if(empty($data_arr)) {
            $album_name = $this->objInput->postStr('album_name');
            $explain    = $this->objInput->postStr('explain');
            $grant      = $this->objInput->postInt('grant');
            $uid        = $this->objInput->postInt('uid');
            $album_id   = $this->objInput->postInt('album_id');
        }else{
            $album_name = $data_arr['album_name'];
            $explain    = $data_arr['explain'];
            $grant      = $data_arr['grant'];
            $uid        = $data_arr['uid'];
            $album_id   = $data_arr['album_id'];
        }
        //当前时间
        $current_time = time();
        
        if(empty($album_id)) {
            return false;
        }
        $data = array();
        if(!empty($album_name)) {
            $data['album_name'] = $album_name;
        }
        if(!empty($explain)) {
            $data['explain'] = $explain;
        }
        
        if(empty($uid)) {
            $data['upd_account'] = $uid;
        }
        //修改相册信息
        if(!empty($data)) {
           $data['upd_time'] = $current_time;
           $mAlbum = ClsFactory('Model.Album.mAlbum');
           $rs = $mAlbum->modifyAlbumByAlbumId($data, $album_id);
           if(empty($rs)) {
               return false;
           }
        }
        unset($data);
        //修改相册权限
        $data = array();
        if(!empty($grant)) {
            $data['grant'] = $grant;
            $mAlbumClassGrants = ClsFactory::Create('Model.Album.mAlbumClassGrants');
            $grant_list = $mAlbumClassGrants->getAlbumClassGrantByAlbumId($album_id);
            if(empty($grant_list)) {
                return false;
            }
            $grant_list = reset($grant_list);
            $grant_id = array_keys((array)$grant_list);
            $rs = $mAlbumClassGrants->modifyAlbumClassGrantById($data, $grant_id);
            if(empty($rs)) {
                return false;
            }
        }
        
        return true;
    }
    
    /*
     * 通过相册id获取相册信息
     * @$album_id int 相册id
     * @return json
     */
    
    public function getByAlbumId($album_id) {
        if(empty($album_id)) {
            return false;
        }
        
        //获取相处信息
        $mAlbum = ClsFactory::Create('Model.Album.mAlbum');
        $rs = $mAlbum->getAlbumByAlbumId($album_id);
        if(empty($rs)) {
            return false;
        }
        $grant = $this->getGrantByClassAlbumId($album_id);
        
        $rs[$album_id]['grant'] = $grant['grant'];
        
        return json_decode($rs);
    }
    /**
     * 通过相册id获取班级相册权限
     * @param $album_id
     * return array  一维数组
     */
    private function getGrantByClassAlbumId($album_id) {
        if(empty($album_id)) {
            return false;
        }
        $mAlbumClassGrants = ClsFactory::Create('Model.Album.mAlbumClassGrants');
        $rs = $mAlbumClassGrants->getAlbumClassGrantByAlbumId($album_id);
        if(empty($rs)) {
            return false;
        }
        $rs = reset($rs[$album_id]);
        
        return $rs;
    }
//相册-------------------------------------------------------------------------------------------------    
    /**
     * 设置相册封面
     * @$data array 封面数据
     * @$album_id int 相册Id
     * @return json
     */
    public function setAlbumImg($data, $album_id) {
        if(empty($data) || empty($album_id)) {
            return false;
        }
        //检查相册是否存在
        $mAlbum = ClsFactory::Create('Model.Album.mAlbum');
        $rs = $mAlbum->getAlbumByAlbumId($album_id);
        
        if(empty($rs)) {
            return false;
        }
        
        $data_arr = array(
                    'album_img'   => $data['album_img'],
                    'upd_account' => $data['uid'],
                    'upd_time'    => time()
        );
        unset($data, $rs);
        
        $rs = $mAlbum->modifyAlbumByAlbumId($data_arr, $album_id);
        if(empty($rs)) {
            return false;
        }
        
        return true;
    }
    
//个人相册-------------------------------------------------------------------------------------------------
    /**
     * 创建个人相册
     * @param $data_arr
     * @return json {album_id:$album_id,is_success:true}
     */
    public function createPerson($data_arr) {
        if(empty($data_arr)) {
            $album_name = $this->objInput->postStr('album_name');
            $explain    = $this->objInput->postStr('explain');
            $grant      = $this->objInput->postInt('grant');
            $uid        = $this->objInput->postInt('uid');
        }else{
            $album_name = $data_arr['album_name'];
            $explain    = $data_arr['explain'];
            $grant      = $data_arr['grant'];
            $uid        = $data_arr['uid'];
        }
        if(empty($album_name) || empty($grant) || empty($uid)) {
            return false;
        }
        
        //当前时间戳
        $current_time = time();
        
        //帐号是否存在
        $mUser = ClsFactory::Create('Model.mUser');
        $user_info = $mUser->getClientAccountById($uid);
        if(empty($user_info)) {
            return false;
        }
        
        //初始化相册表
        $album_data = array(
                    'album_name'    => $album_name,
                    'album_explain' => $explain,
                    'add_account'   => $uid,
                    'add_time'      => $current_time,
                    'upd_account'   => $uid,
                    'upd_time'      => $current_time,
              );
        $mAlbum = ClsFactory('Model.Album.mAlbum');
        $album_id = $mAlbum->addAlbum($album_data, true);
        
        if(empty($album_id)) {
            return false;
        }
        
        //相册权限初始化
        $grant_data = array(
                    'client_account' => $uid,
                    'album_id'       => $album_id,
                    'grant'          => $grant,
    
                );
        $mAlbumPersonGrants = ClsFactory::Create('Model.Album.mAlbumPersonGrants');
        $album_person_grant_id = $mAlbumPersonGrants->addAlbumPersonGrant($grant_data, true);
        if(empty($album_person_grant_id)) {
            //删除相册信息
            $mAlbum->delAlbumByAlbumId($album_id);
            return false;
        }
        
        //个人与相册关系初始化
        $rel_data = array(
                    'client_account' => $uid,
                    'album_id'       => $album_id,
                );
        $mAlbumPersonRelation = ClsFactory::Create('Model.Album.mAlbumPersonRelation');
        $rel_id = $mAlbumPersonRelation->addAlbumPersonRel($rel_data, true);
        if(empty($rel_id)) {
            //删除相册信息
            $mAlbum->delAlbumByAlbumId($album_id);
            //删除相册权限
            $mAlbumPersonGrants->delAlbumPersonGrantById($album_person_grant_id);
            return false;
        }
        $json_list = array(
                    'album_id'   => $album_id,
                    'is_success' => true 
        );
        
        return json_encode($json_list);
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
        //从关系获取相册album_id
        $mAlbumPersonRelation = ClsFactory::Create('Model.Album.mAlbumPersonRelation');
        $rel_list = $mAlbumPersonRelation->getAlbumPersonRelByUid($uid, $offset, $limit);
        $rel_list = reset($rel_list);
        $album_ids_list = array();
        foreach($rel_list as $key=>$val) {
            $album_ids_list[$val['album_id']] = $val['album_id'];
        }
        unset($rel_list);
        //没有相关个人的相册
        if(empty($album_ids_list)) {
            return false;
        }
        
        $mAlbum = ClsFactory('Model.Album.mAlbum');
        $album_list = $mAlbum->getAlbumByAlbumId($album_ids_list);
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
            $grant = !empty($grant) ? $grant : 0;
            $album_list[$album_id]['grant'] = $grant;
        }
        unset($grant_list);
        
        return json_decode($album_list);
        
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
