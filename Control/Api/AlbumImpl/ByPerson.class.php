<?php
import('@.Control.Api.AlbumImpl.Core');

class ByPerson extends Core {
    
    public function create($datas) {
        //判断是否为空
        if(empty($datas)) {
            return false;
        }
        //检查班级是否存在
        $this->uid_is_exist($datas['uid']);
        //添加相册信息
        $album_id = $this->album_add($datas);
        if(empty($album_id)) {
            return false;
        }
        $datas['album_id'] = $album_id;
        //添加相册关系
        $rel_id = $this->rel_add($datas);
        if(empty($rel_id)) {
            //删除相册信息
            $this->delete($album_id);
            return false;
        }
        //添加相册权限
        $grant_id = $this->grant_add($datas);
        if(empty($grant_id)) {
            //删除相册信息
            $this->delete($album_id);
            //删除相册权限
            $mAlbumPersonGrants = ClsFactory::Create('Model.Album.mAlbumPersonGrants');
            $mAlbumPersonGrants->delAlbumPersonGrantById($grant_id);
            return false;
        }
        //返回json结果集
        $json_list = array(
    		'album_id'   => $album_id,
         );
        
        return $json_list;
    }
    //通过班级class_code获取相册信息
    public function getAlbumByUid($uid, $offset = null, $limit = null) {
        if(empty($uid)) {
            return false;
        }
        //班级是否存在
        $this->uid_is_exist($uid);
        
        $album_ids = $this->get_album_ids_by_rel($uid, $offset, $limit);
        //该班级是否存在相册
        if(empty($album_ids)) {
            return false;
        }
        //通过相册album_id获取相册信息
        $album_list = $this->get($album_ids);
        //是否有相关相册的信息
        if(empty($album_list)) {
            return false;
        }
        $album_ids = array_keys($album_list);
        //获取相处权限
        $grant_list = $this->get_grant_by_album_id($album_ids);
        
        $album_list = $this->merge_album_rel_data($album_list,$grant_list);
        
        return $album_list;
        
    }
    
    //通过班级class_code和相册album_id获取相册信息
    public function getAlbumByUidAlbumId($album_id, $uid) {
        if(empty($uid) || empty($album_id)) {
            return false;
        }
        //班级是否存在
        $this->uid_is_exist($uid);
        //通过相册album_id获取相册信息
        $album_list = $this->get($album_id);
        //是否有相关相册的信息
        if(empty($album_list)) {
            return false;
        }
        
        $mAlbumPersonRelation = ClsFactory::Create('Model.Album.mAlbumPersonRelation');
        $rel = $mAlbumPersonRelation->getAlbumClassRelByUidAlbumId($album_id, $uid);
        //该班级是否存在相册
        if(empty($rel)) {
            return false;
        }
        
        $album_ids = array_keys($album_list);
        //获取相处权限
        $grant_list = $this->get_grant_by_album_id($album_ids);
        
        $album_list = $this->merge_album_rel_data($album_list,$grant_list);
        
        return $album_list;
        
    }
    //合并相册和相册权限信息
    private function merge_album_rel_data($album_list, $grant_list) {
        if(empty($album_list) || empty($grant_list)) {
            
        }
        //数据处理，将相册权限信息和相册信息合并
        foreach($album_list as $album_id=>$val) {
            $grant_info = reset($grant_list[$album_id]);
            $grant = $grant_info['grant'];
            $grant = !empty($grant) ? $grant : 0;
            $album_list[$album_id]['grant'] = $grant;
        }
        unset($grant_list);
        
        return !empty($album_list) ? $album_list : false;
    }
    //修改相册信息
    public function updAlbum($data, $album_id, $uid) {
        if(empty($data) || empty($album_id)) {
            return false;
        }
        //检测相册是否存在
        $album_list = $this->get($album_id);
        if(empty($album_list)) {
            return false;
        }
        $mAlbumPersonRelation = ClsFactory::Create('Model.Album.mAlbumPersonRelation');
        $rel = $mAlbumPersonRelation->getAlbumPersonRelByUidAlbumId($album_id, $uid);
        //该班级是否存在相册
        if(empty($rel)) {
            return false;
        }
        $data = $this->remove_empty($data);
        if(empty($data)) {
            return false;
        }
        $rs = $this->upd($data, $album_id);
        if(empty($rs)) {
            return false;
        }
        //修改相册权限
        if(!empty($data['grant']) && isset($data['grant'])) {
            $grant_list = $this->get_grant_by_album_id($album_id);
            if(empty($grant_list)) {
                return false;
            }
            $grant_list = reset($grant_list);
            $grant_id = array_keys((array)$grant_list);
            if(empty($grant_id)) {
                return false;
            }
            $mAlbumPersonGrants = ClsFactory::Create('Model.Album.mAlbumPersonGrants');
            $rs = $mAlbumPersonGrants->modifyAlbumPersonGrantById($data, $grant_id);
            if(empty($rs)) {
                return false;
            }
        }
        
        return true;
    }
    //从关系得到相册album_id
    private function get_album_ids_by_rel($uid, $offset = null, $limit = null) {
        if(empty($uid)) {
            return false;
        }
        
        $mAlbumPersonRelation = ClsFactory::Create('Model.Album.mAlbumPersonRelation');
        $rel_list = $mAlbumPersonRelation->getAlbumClassRelByClassCode($uid, $offset, $limit);
        $rel_list = reset($rel_list);
        $album_ids_list = array();
        if(empty($rel_list)) {
            return false;
        }
        foreach($rel_list as $key=>$val) {
            $album_ids_list[$val['album_id']] = $val['album_id'];
        }
        unset($rel_list);
        
        return $album_ids_list;
    }
    //通过相册album_id获取相册权限
    private function get_grant_by_album_id($album_ids) {
        if(empty($album_ids)) {
            return false;
        }
        //获取相关相册的权限信息
        $mAlbumPersonGrants = ClsFactory::Create('Model.Album.mAlbumPersonGrants');
        $grant_list = $mAlbumPersonGrants->getAlbumPersonGrantByAlbumId($album_ids);
        
        return !empty($grant_list) ? $grant_list : false;
    }
    public function album_add($datas) {
        if(empty($datas) || !is_array($datas)) {
            return false;
        }
        //todo数据处理        
        return $this->add($datas, true);
    }
    
    //初始化班级相册权限
    public function grant_add($datas) {
        if(empty($datas) || !is_array($datas)) {
            return false;
        }
        
        $datas = $this->format_grant_data($datas);
        
        $mAlbumPersonGrants = ClsFactory::Create('Model.Album.mAlbumPersonGrants');
        $grant_id = $mAlbumPersonGrants->addAlbumPersonGrant($datas, true);
        
        return !empty($grant_id) ? $grant_id : false;
    }
    
    //格式化相册权限信息
    private function format_grant_data($data) {
        if(empty($data)) {
            return false;
        }
        
        //相册权限初始化
        $grant_data = array(
            'client_account' => $data['uid'],
            'album_id'       => $data['album_id'],
            'grant'          => $data['grant']
        );
        
        return $grant_data;
    }
    
    //初始化班级相册关系
    public function rel_add($datas) {
        if(empty($datas) || !is_array($datas)) {
            return false;
        }
        
        $datas = $this->format_rel_data($datas);
        
        $mAlbumPersonRelation = ClsFactory::Create('Model.Album.mAlbumPersonRelation');
        return $mAlbumPersonRelation->addAlbumPersonRel($datas, true);
    }
    
    //格式化相册关系信息
    private function format_rel_data($data) {
        if(empty($data)) {
            return false;
        }
        
        $rel_data = array(
            'client_account' => $data['uid'],
            'album_id'   => $data['album_id'],
        );
        
        return !empty($rel_data) ? $rel_data : false;
    }
    
    //账号是否存在
    private function uid_is_exist($uid) {
        if(empty($uid)) {
            return false;
        }
        $mUser = ClsFactory::Create('Model.mUser');
        $user_info = $mUser->getClientAccountById($uid);
        if(empty($user_info)) {
            return false;
        }
        
        return true;
    }
    //去空
    private function remove_empty($data) {
        if(empty($data)) {
            return false;
        }
        foreach($data as $key=>$val) {
            if(empty($val)) {
                unset($data[$key]);
            }
        }
        
        return !empty($data) ? $data : false;
    }
    
    
}