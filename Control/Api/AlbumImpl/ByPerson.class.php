<?php
import('@.Control.Api.AlbumImpl.Album');
/**
 * @author sailong
 *功能：个人相册Api
 *说明：与相册有关的增删改查操作
 */

class ByPerson extends Album {
    /**
     * 个人添加相册函数
     * @param $datas
     * @return Array
     */
    public function create($datas) {
        //判断是否为空
        if(empty($datas)) {
            return false;
        }
        //检查班级是否存在
        $this->uid_is_exist($datas['uid']);
        //添加相册信息
        $album_id = $this->add($datas, true);
        if(empty($album_id)) {
            return false;
        }
        $datas['album_id'] = $album_id;
        //添加相册关系
        $rel_id = $this->add_rel($datas);
        if(empty($rel_id)) {
            //删除相册信息
            $this->delete($album_id);
            return false;
        }
        //添加相册权限
        $grant_id = $this->add_grant($datas);
        if(empty($grant_id)) {
            //删除相册信息
            $this->delete($album_id);
            //删除相册关系
            $this->del_rel($rel_id);
            return false;
        }
        
        return $album_id;
    }
    
    
    /**
     * 通过个人uid获取相册列表信息
     * 
     * @param int $uid 用户账号
     * @param int $offset 
     * @param int $limit
     * 
     * return Array
     */
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
        
        $album_list = $this->parse($album_list, $uid);
        
        return $album_list;
        
    }
    
    /**
     * 通过个人uid和相册album_id获取相册信息
     * 
     * @param $album_id 相册ID
     * @param $uid      用户账号
     * 
     * return Array
     */
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
        $rel = $mAlbumPersonRelation->getAlbumPersonRelByUidAlbumId($album_id, $uid);
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
    /**
     * 修改相册信息显示函数
     * @param $album_id
     * @param $uid
     * 
     * @return Array
     */
    public function showUpdAlbum($album_id, $uid) {
        $data_arr['album_arr'] = $this->getAlbumByUidAlbumId($album_id, $uid);
        $data_arr['grant_arr'] = $this->grant_arr();
        
        return $data_arr;
    }
    
    /**
     * 根据uid和album_id删除相册信息
     * @param $album_id
     * @param $uid
     * 
     * return Bool
     */
    public function delAlbummByPerson($album_id, $uid) {
        if(empty($album_id) || empty($uid)) {
            return false;
        }
        
        //删除班级相册关系
        $mAlbumClassRelation = ClsFactory::Create('Model.Album.mAlbumPersonRelation');
        $rs = $mAlbumClassRelation->delByAlbumId($album_id);
//        if(empty($rs)) {
//            echo "删除班级相册关系";
//        }
        //删除相册权限
        $mAlbumClassGrants = ClsFactory::Create('Model.Album.mAlbumPersonGrants');
        $rs = $mAlbumClassGrants->delByAlbumId($album_id);
//        if(empty($rs)) {
//            echo "删除相册权限失败";
//        }
        
        $mAlbumPhotos = ClsFactory::Create('Model.Album.mAlbumPhotos');
        //删除相册中照片的评论信息
        $photo_list = $mAlbumPhotos->getPhotosByAlbumId($album_id);
        $photo_ids = array_keys($photo_list[$album_id]);
        $mAlbumPhotoComments = ClsFactory::Create('Model.Album.mAlbumPhotoComments');
        $rs = $mAlbumPhotoComments->delByPhotoId($photo_ids);
//        if(empty($rs)) {
//            echo "删除相册评论失败";
//        }
        //删除照片信息
        $rs = $mAlbumPhotos->delByAlbumId($album_id);
//        if(empty($rs)) {
//            echo "删除相册照片信息失败";
//        }
        //删除相册信息
        $rs = $this->delete($album_id);
//        if(empty($rs)) {
//            echo "删除相册信息失败";
//        }
        //删除照片实体
        return true;
    }
    
    /**
     * 删除照片信息
     * @param $photo_id
     * @return bool
     */
    public function delPhotoByPhotoId($photo_id) {
        if(empty($photo_id)) {
            return false;
        }
        $mAlbumPhotoComments = ClsFactory::Create('Model.Album.mAlbumPhotoComments');
        $mAlbumPhotoComments->delByPhotoId($photo_id);
        $mAlbumPhotos = ClsFactory::Create('Model.Album.mAlbumPhotos');
        return $mAlbumPhotos->delPhotosByPhotoId($photo_id);
    }
    
	/**
     * 通过相册id获取班级相册权限
     * @param $album_id
     * return array  一维数组
     */
    private function getGrantByAlbumId($album_id) {
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
    /**
     * 通过评论comment_id删除评论信息
     * @param Int $comment_id
     * 
     * @return bool
     */
    public function delCommentById($comment_id) {
        if(empty($comment_id)) {
            return false;
        }
        $mAlbumPhotoComments = ClsFactory::Create('Data.Album.mAlbumPhotoComments');
        return $mAlbumPhotoComments->delCommentByCommentId($comment_id);
    }
    /**
     * 设置相册封面
     * @param String $album_img 图片名称
     * @param Int $album_id     相册Id
     */
    public function setAlbumImg($album_img, $album_id) {
        $data = array(
            'album_img'=>$album_img,
            'upd_time'=>time()
        );
        return $this->upd($data,$album_id);
    }
    
    /**
     * 移动相片到另一相册
     * @param $album_id
     * @param $photo_id
     * 
     * @return bool
     */
    public function movePhoto($album_id, $photo_id) {
        if(empty($album_id) && empty($album_id)) {
            return false;
        }
        $data = array(
            'album_id'=>$album_id
        );
        $mAlbumPhotos = ClsFactory::Create('Model.Album.mAlbumPhotos');
        $rs = $mAlbumPhotos->modifyPhotoByPhotoId($data,$photo_id);
        
        return $rs;
    }
    /**
     * 修改相册信息
     * @param Array $data
     * @param Int $album_id
     * @param Int $uid
     * @return bool true;
     */
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
        unset($rel);
        
        $data = $this->remove_empty($data);
        
        if(empty($data)) {
            return false;
        }
        
        //修改相册权限
        if($data['grant'] !== '' && isset($data['grant'])) {
            $grant_list = $this->get_grant_by_album_id($album_id);
            
            if(empty($grant_list)) {
                return false;
            }
           
            $grant_id = key($grant_list[$album_id]);
            if(empty($grant_id)) {
                return false;
            }
            $grant_list = $grant_list[$grant_id];
            $grant_list['grant'] =  $data['grant'];
            $mAlbumPersonGrants = ClsFactory::Create('Model.Album.mAlbumPersonGrants');
            $rs = $mAlbumPersonGrants->modifyAlbumPersonGrantById($grant_list, $grant_id);
            unset($data['grant']);
        }
        $this->upd($data, $album_id);
        
        return true;
    }
    /**
     * 数据输出格式
     * @param Array $album_list
     * @param Int $uid
     * 
     * @return Array
     */
    private function parse($album_list, $uid) {
          import('Model.mUser');
          $mUser = new mUser();
          $user_info = $mUser-> getUserBaseByUid($uid);
          $user_info = reset($user_info);
          
          foreach($album_list as $album_id_key=>$val) {
              $album_list[$album_id_key]['client_name'] =$user_info['client_name'];
          }
        
          return $album_list;
    }
    /**
     * 账号是否存在
     * @param Int $uid
     * 
     * @return bool
     */
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
    
    /**
     * 根据关系rel_id删除相册关系
     * @param Int $rel_id
     */
    private function del_rel($rel_id) {
        if(empty($rel_id)) {
            return false;
        }
        $mAlbumPersonRelation = ClsFactory::Create('Model.Album.mAlbumPersonRelation');
        return $mAlbumPersonRelation->delAlbumPersonRelById($rel_id);
    }
    /**
     * 根据权限grant_id删除相册权限
     * @param Int $grant_id
     * 
     * @return bool
     */
    private function del_grant($grant_id) {
        if(empty($grant_id)) {
            return false;
        }
        $mAlbumPersonGrants = ClsFactory::Create('Model.Album.mAlbumPersonGrants');
        return $mAlbumPersonGrants->delAlbumPersonGrantById($grant_id);
    }
    
    /**
     * 从关系得到相册列表
     * @param $uid
     * @param $offset
     * @param $limit
     * 
     * @return Album
     */
    private function get_album_ids_by_rel($uid, $offset = null, $limit = null) {
        if(empty($uid)) {
            return false;
        }
        
        $mAlbumPersonRelation = ClsFactory::Create('Model.Album.mAlbumPersonRelation');
        $rel_list = $mAlbumPersonRelation->getAlbumPersonRelByUid($uid, $offset, $limit);
        
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
    
    
    /**
     * 相册权限常量
     * @param Int $grant_id
     * 
     * @return String || Array
     */
    public function grant_arr($grant_id) {
        $grant_arr = array(
            0=>"公开",
            1=>"好友",
            2=>"仅主人"
        );
        if($grant_id != '') {
            return $grant_arr[$grant_id];
        }
        
        return $grant_arr;
    }
    
    /**
     * 通过相册album_id获取相册权限
     * @param Int||array $album_ids
     * 
     * @return Array
     */
    private function get_grant_by_album_id($album_ids) {
        if(empty($album_ids)) {
            return false;
        }
        //获取相关相册的权限信息
        $mAlbumPersonGrants = ClsFactory::Create('Model.Album.mAlbumPersonGrants');
        //三维
        $grant_list = $mAlbumPersonGrants->getAlbumPersonGrantByAlbumId($album_ids);
        if(empty($grant_list)) {
            return false;
        }
        //三维
        foreach($grant_list as $album_id=>$grant_val) {
            list($grant_id,$grant_info) = each($grant_val);
            $grant_info['grant_name'] = $this->grant_arr($grant_info['grant']);
            $grant_list[$album_id][$grant_id]=$grant_info;
        }
       
        return !empty($grant_list) ? $grant_list : false;
    }
    
    /**
     * 初始化班级相册权限
     * @param Array $datas
     * @return int 添加id或者false
     */
    private function add_grant($datas) {
        if(empty($datas) || !is_array($datas)) {
            return false;
        }
        
        $datas = $this->format_grant_data($datas);
        
        $mAlbumPersonGrants = ClsFactory::Create('Model.Album.mAlbumPersonGrants');
        $grant_id = $mAlbumPersonGrants->addAlbumPersonGrant($datas, true);
        
        return !empty($grant_id) ? $grant_id : false;
    }
    
    /**
     * 格式化相册权限信息
     * @param $data
     * 
     * @return Array
     */
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
    
    /**
     * 初始化班级相册关系
     * @param Array $datas
     * 
     * @return int 添加ID或false                                                                                                                                       
     */
    private function add_rel($datas) {
        if(empty($datas) || !is_array($datas)) {
            return false;
        }
        
        $datas = $this->format_rel_data($datas);
        
        $mAlbumPersonRelation = ClsFactory::Create('Model.Album.mAlbumPersonRelation');
        $album_perspon_grant_id = $mAlbumPersonRelation->addAlbumPersonRel($datas, true);
        
        return !empty($album_perspon_grant_id) ? $album_perspon_grant_id : false;
    }
    
    /**
     * 格式化相册关系信息
     * @param array $data
     * 
     * @return Array
     */
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
    
    /**
     * 合并相册和相册权限信息
     * @param $album_list
     * @param $grant_list
     * 
     * @return Array
     */
    private function merge_album_rel_data($album_list, $grant_list) {
      
        if(empty($album_list) || empty($grant_list)) {
            return false;
        }
        //数据处理，将相册权限信息和相册信息合并
        foreach($album_list as $album_id=>$val) {
            $grant_info = reset($grant_list[$album_id]);
            $grant_info['grant'] = !empty($grant_info['grant']) ? $grant_info['grant'] : 0;
            $album_list[$album_id] = array_merge($val,$grant_info);
        }
        unset($grant_list);
        
        return !empty($album_list) ? $album_list : false;
    }
   /**
    * 添加评论
    * @param Array $data
    * 
    * @return 评论 ID
    */
    public function addcommentPhoto($data) {
        if(empty($data)) {
            return false;
        }
        $new_data = array(
            'up_id'=>0,
            'photo_id'=>$data['photo_id'],
            'content'=>$data['content'],
            'client_account'=>$data['client_account'],
            'add_time'=>time(),
            'level'=>1
        );
        $mAlbumPhotoComments = ClsFactory::Create('Model.Album.mAlbumPhotoComments');
        return $mAlbumPhotoComments->addAlbumPhotoComment($new_data,true);
    }
    
    /**
     * //去空
     * @param $data
     * 
     * @return Array
     * 
     */
    private function remove_empty($data) {
        if(empty($data)) {
            return false;
        }
        foreach($data as $key=>$val) {
            if(empty($val)&&$val==='') {
                unset($data[$key]);
            }
        }
        
        return !empty($data) ? $data : false;
    }
    
}