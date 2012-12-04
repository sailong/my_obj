<?php
import('@.Control.Api.AlbumImpl.Core');

class ByClass extends Core {
    
    public function create($datas) {
        //判断是否为空
        if(empty($datas)) {
            return false;
        }
        //检查班级是否存在
        $this->class_is_exist($datas['class_code']);
        
        //添加相册信息
        $album_id = $this->album_add($datas);
        if(empty($album_id)) {
            return false;
        }
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
            $mAlbumClassGrants = ClsFactory::Create('@.Model.Album.mAlbumClassGrants');
            $mAlbumClassGrants->delAlbumClassGrantById($grant_id);
            return false;
        }
        //返回json结果集
        $json_list = array(
    		'album_id'   => $album_id,
         );
        
        return$json_list;
    }
    //通过班级class_code获取相册信息
    public function getAlbumByClassCode($class_code, $offset = null, $limit = null) {
        if(empty($class_code)) {
            return false;
        }
        //班级是否存在
        $this->class_is_exist($class_code);
        
        $album_ids = $this->get_album_ids_by_rel($class_code, $offset, $limit);
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
    public function getAlbumByClassAlbumId($album_id, $class_code) {
        if(empty($class_code) || empty($album_id)) {
            return false;
        }
        //班级是否存在
        $this->class_is_exist($class_code);
        //通过相册album_id获取相册信息
        $album_list = $this->get($album_id);
        //是否有相关相册的信息
        if(empty($album_list)) {
            return false;
        }
        
        $mAlbum = ClsFactory::Create('Model.Album.mAlbumClassRelation');
        $rel = $mAlbum->getAlbumClassRelByClassAlbumId($album_id, $class_code);
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
    public function updAlbum($data, $album_id, $class_code) {
        if(empty($data) || empty($album_id)) {
            return false;
        }
        //检测相册是否存在
        $album_list = $this->get($album_id);
        if(empty($album_list)) {
            return false;
        }
        $mAlbum = ClsFactory::Create('Model.Album.mAlbumClassRelation');
        $rel = $mAlbum->getAlbumClassRelByClassAlbumId($album_id, $class_code);
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
            $mAlbumClassGrants = ClsFactory::Create('Model.Album.mAlbumClassGrants');
            $rs = $mAlbumClassGrants->modifyAlbumClassGrantById($data, $grant_id);
            if(empty($rs)) {
                return false;
            }
        }
        
        return true;
    }
    //从关系得到相册album_id
    private function get_album_ids_by_rel($class_code, $offset = null, $limit = null) {
        if(empty($class_code)) {
            return false;
        }
        
        $mAlbumClassRelation = ClsFactory::Create('Model.Album.mAlbumClassRelation');
        $rel_list = $mAlbumClassRelation->getAlbumClassRelByClassCode($class_code, $offset, $limit);
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
        $mAlbumClassGrants = ClsFactory::Create('@.Model.Album.mAlbumClassGrants');
        $grant_list = $mAlbumClassGrants->getAlbumClassGrantByAlbumId($album_ids);
        
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
        
        $mAlbumClassGrants = ClsFactory::Create('@.Model.Album.mAlbumClassGrants');
        $album_class_grant_id = $mAlbumClassGrants->addAlbumClassGrant($datas, true);
        
        return !empty($album_class_grant_id) ? $album_class_grant_id : false;
    }
    
    //格式化相册权限信息
    private function format_grant_data($data) {
        if(empty($data)) {
            return false;
        }
        
        //相册权限初始化
        $grant_data = array(
            'class_code' => $data['class_code'],
            'album_id'   => $data['album_id'],
            'grant'      => $data['grant']
        );
        
        return $grant_data;
    }
    
    //初始化班级相册关系
    public function rel_add($datas) {
        if(empty($datas) || !is_array($datas)) {
            return false;
        }
        
        $datas = $this->format_rel_data($datas);
        
        $mAlbumClassRelation = ClsFactory::Create('@.Model.Album.mAlbumClassRelation');
        return $mAlbumClassRelation->addAlbumClassRel($datas, true);
    }
    
    //格式化相册关系信息
    private function format_rel_data($data) {
        if(empty($data)) {
            return false;
        }
        
        $rel_data = array(
            'class_code' => $data['class_code'],
            'album_id'   => $data['album_id'],
        );
        
        return !empty($rel_data) ? $rel_data : false;
    }
    
    //班级是否存在
    private function class_is_exist($class_code) {
        if(empty($class_code)) {
            return false;
        }
        $mClassInfo = ClsFactory::Create('@.Model.mClassInfo');
        $class_info = $mClassInfo->getClassInfoBaseById($class_code);
        if(empty($class_info)) {
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