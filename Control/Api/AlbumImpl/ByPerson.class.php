<?php
import('@.Control.Api.AlbumImpl.Album');
/**
 * 
 * @author sailong
 *功能：个人相册API
 *说明：关于个人相册，相片的增删改查
 */
class ByPerson extends Album {
    /**
     * 创建个人相册
     * @param Array $datas
     * 
     * @return int 相册ID
     */
    public function create($datas, $client_account) {
        //判断是否为空
        if(empty($datas)) {
            return false;
        }
        
        //检查个人是否存在
        $this->client_is_exist($client_account);
        
        //添加相册信息
        $album_id = $this->add($datas);
        if(empty($album_id)) {
            return false;
        }
        $datas['album_id'] = $album_id;
        //添加相册关系
        $rel_id = $this->add_rel($datas, $client_account);
        if(empty($rel_id)) {
            //删除相册信息
            $this->delete($album_id);
            return false;
        }
        
        
        
        return $album_id;
    }
    
    
    
    /**
     * 根据个人client_account只获取相册表信息
     * 
     * @param int $client_account
     * 
     * @return array album_list
     */
    public function getOnlyAlbumListByClientAccount($client_account) {
        if(empty($client_account)) {
            return false;
        }
        //个人是否存在
        $this->client_is_exist($client_account);
        //从关系表中得到相册信息
        $rel_list = $this->get_album_ids_by_rel($client_account);
       
        $rel_list = $rel_list[$client_account];
        
        $album_ids = array();
        foreach($rel_list as $key=>$val) {
            $album_ids[$val['album_id']] = $val['album_id'];
        }
       
        //该个人是否存在相册
        if(empty($album_ids)) {
            return false;
        }
        //通过相册album_id获取相册信息
        $album_list = $this->get($album_ids);
        
        return !empty($album_list) ? $album_list : false;
    }
    
    
    /**
     * 通过个人client_account获取相册列表信息
     * @param $client_account
     * @param $offset
     * @param $limit
     * 
     * @return Array
     */
    public function getListByPerson($client_account, $offset = null, $limit = null) {
        if(empty($client_account)) {
            return false;
        }
        //个人是否存在
        $this->client_is_exist($client_account);
        //从关系表中得到相册信息
         $rel_list = $this->get_album_ids_by_rel($client_account, $offset, $limit);
       
        $rel_list = $rel_list[$client_account];
        
        $album_ids = array();
        foreach($rel_list as $key=>$val) {
            $album_ids[$val['album_id']] = $val['album_id'];
        }
        //该个人是否存在相册
        if(empty($album_ids)) {
            return false;
        }
        //通过相册album_id获取相册信息
        $album_list = $this->get($album_ids);
        //是否有相关相册的信息
        if(empty($album_list)) {
            return false;
        }
        
  
        //合并权限和相册信息
        $album_list = $this->merge_album_rel_data($album_list, $rel_list);
        $album_list = $this->parse($album_list);
        
        return !empty($album_list) ? $album_list : false;
        
    }
    /**
     * 输出格式
     * @param $album_list
     * 
     * @return Array
     */
    private function parse($album_list) {
          import('Model.mUser');
          $mUser = new mUser();
          
          foreach($album_list as $album_id=>$val) {
              $user_info = $mUser-> getUserBaseByUid($val['add_account']);
              $user_info = reset($user_info);
              $album_list[$album_id]['client_name'] =$user_info['client_name'];
          }
          
          return $album_list;
    }
   
    
    /**
     * 通过个人client_account和相册album_id获取相册信息
     * @param $album_id
     * @param $client_account
     * 
     * @return Array
     */
    public function getAlbumByPersonAlbumId($album_id, $client_account) {
        if(empty($client_account) || empty($album_id)) {
            return false;
        }
        
        //个人是否存在
        $this->client_is_exist($client_account);
        
        //通过相册album_id获取相册信息
        $album_list = $this->get($album_id);
        
        //是否有相关相册的信息
        if(empty($album_list)) {
            return false;
        }
        //检测相册关系是否存在
        $rel = $this->get_rel_by_album_id($album_id, $client_account);
        //该个人是否存在相册
        if(empty($rel)) {
            return false;
        }
        $album_list = $this->merge_album_rel_data($album_list,$rel);
        $album_list = $this->parse($album_list);
        
        return !empty($album_list) ? $album_list : false;
        
    }
    
    /**
     * 通过相册album_id和个人client_account获取相册关系信息
     * @param $album_id
     * @param $client_account
     * 
     * @return Array
     */
    public function get_rel_by_album_id($album_id, $client_account) {
        if(empty($album_id) && empty($client_account)) {
            return false;
        }
        $mAlbum = ClsFactory::Create('Model.Album.mAlbumPersonRelation');
        $rel = $mAlbum->getAlbumPersonRelByUidAlbumId($album_id, $client_account);
        
        return !empty($rel) ? $rel : false;
    }
    
    /**
     * 删除个人相册信息
     * @param $album_id
     * @param $client_account
     * 
     * @return bool
     */
    public function delAlbumByPerson($album_id, $client_account) {
        if(empty($album_id) || empty($client_account)) {
            return false;
        }
        
        //删除个人相册关系
        $mAlbumPersonRelation = ClsFactory::Create('Model.Album.mAlbumPersonRelation');
        $rs = $mAlbumPersonRelation->delByAlbumId($album_id);
        if(empty($rs)) {
            echo "删除个人相册关系失败";
            return false;
        }
        //删除相册权限
        $mAlbumPersonGrants = ClsFactory::Create('Model.Album.mAlbumPersonGrants');
        $rs = $mAlbumPersonGrants->delByAlbumId($album_id);
        if(empty($rs)) {
           echo "删除相册权限失败";
           return false;
        }
        
        $mAlbumPhotos = ClsFactory::Create('Model.Album.mAlbumPhotos');
        //删除相册中照片的评论信息
        $photo_list = $mAlbumPhotos->getPhotosByAlbumId($album_id);
        if(!empty($photo_list)) {
            $photo_ids = array_keys($photo_list[$album_id]);
            $mAlbumPhotoComments = ClsFactory::Create('Model.Album.mAlbumPhotoComments');
            $comments_list = $mAlbumPhotoComments->getAlbumPhotoCommentByPhotoId($photo_ids);
            
            if(!empty($comments_list)) {
                $rs = $mAlbumPhotoComments->delByPhotoId($photo_ids);
                if(empty($rs)) {
                    echo "删除相册评论失败";
                    return false;
                }
            }
        }
        
        //删除照片信息
        $photo_lists = $mAlbumPhotos->getPhotosByAlbumId($album_id);
        if(!empty($photo_lists)) {
            $rs = $mAlbumPhotos->delByAlbumId($album_id);
            if(empty($rs)) {
                echo "删除相册照片信息失败";
                return false;
            }
        }
        
        //删除相册信息
        $rs = $this->delete($album_id);
        if(empty($rs)) {
            echo "删除相册信息失败";
            return false;
        }
        //删除照片实体
        
        return true;
    }
    
    /**
     * 设置相册封面
     * @param String $album_img 图片名称
     * @param Int $album_id
     * 
     * @return bool
     */
    public function setAlbumImg($album_img, $album_id) {
        $data = array(
            'album_img'=>$album_img,
            'upd_time'=>time()
        );
        return $this->upd($data,$album_id);
    }
    
    /**
     * 添加照片信息
     * @param array $dataarr
     * 
     * @return boolean
     */
    
    public function addPersonPhoto($dataarr,$is_return_id=false) {
        if(empty($dataarr)) {
            return false;
        }
        
        return $this->addPhoto($dataarr,$is_return_id);
    }
    
    /**
     * 根据相册ID获取相片列表
     * @param int $album_id
     * 
     * @return array $photo_list
     */
    public function getPersonPhotoListByAlbumId($album_id, $offset=null, $limit=null) {
        if(empty($album_id)) {
            return false;
        }
        
        return $this->getPhotoListByAlbumId($album_id, $offset, $limit);
    }
    
    /**
     * 根据相册ID获取相片列表
     * @param int $album_id
     * 
     * @return array $photo_list
     */
    public function getPersonPhotoByPhotoId($photo_ids) {
        if(empty($photo_ids)) {
            return false;
        }
        
        return $this->getPhotoByPhotoId($photo_ids);
    }
    
    /**
     * 根据相册id获取相册中的相册数量
     * @param int $album_id
     * 
     * @return int $count;
     */
    public function getPersonPhotoCountByAlbumId($album_id) {
        if(empty($album_id)) {
            return false;
        }
        
        return $this->getPhotoCountByAlbumId($album_id);
    }
    /**
     * 修改相片信息
     * @param array photo_info
     * @param int   photo_id
     * 
     * @return boolean
     */
    public function updPersonPhotoByPhotoId($datas,$photo_id) {
        if(empty($datas) || empty($photo_id)) {
            return false;
        }
        return $this->updPhotoByPhotoId($datas,$photo_id);
    }
    
    /**getPersonPhotoCountByAlbumId
     * 删除照片信息
     * @param int $photo_id
     * 
     * @return bool
     */
    public function deletePhotoByPhotoId($photo_id) {
        if(empty($photo_id)) {
            return false;
        }
        
        return $this->delPhotoByPhotoId($photo_id);
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
     * @param $data
     * @param $album_id
     * @param $client_account
     * 
     * @return bool
     * 
     */
    public function updAlbum($data, $album_id, $client_account) {
        if(empty($data) || empty($album_id)) {
            return false;
        }
        //检测相册是否存在
        $album_list = $this->get($album_id);
        if(empty($album_list)) {
            return false;
        }
        $mAlbum = ClsFactory::Create('Model.Album.mAlbumPersonRelation');
        $rel = $mAlbum->getAlbumPersonRelByUidAlbumId($album_id, $client_account);
        //该个人是否存在相册
        if(empty($rel)) {
            return false;
        }
        $data = $this->remove_empty($data);
        if(empty($data)) {
            return false;
        }
        $album_rs = $this->upd($data, $album_id);
        
        
        $grant_rs = $mAlbum->modifyAlbumPersonRelById(array('grant'=>$data['grant']), key($rel));
       
        if((!empty($grant_rs) || !empty($album_rs)) || (!empty($grant_rs) && !empty($album_rs))) {
            return true;
        }
        
        return false;
    }
    /**
     * 获取相册评论
     */
    public function getPersonPhotoCommentByUpId($up_id,$offset=null,$limit=null) {
        if(empty($up_id)) {
            return false;
        }
        $level = 1;
        $comment_list = $this->getCommentListByUpId($up_id,$level,$offset,$limit);
        
        return $comment_list;
    }
    /**
     * 获取二级评论
     */
    public function getPersonPhotoSecCommentByUpId($up_id,$offset=null,$limit=null) {
        if(empty($up_id)) {
            return false;
        }
        $level = 2;
        $comment_list = $this->getCommentListByUpId($up_id,$level,$offset,$limit);
        
        return $comment_list;
    }
    /**
     * 相册权限常量                                  提取到公共配置文件中*******************************************
     * @param int $grant_id
     * 
     * @return Array || String
     */
    public function grant_arr($grant_id) {
        $grant_arr = array(
            0=>"公开（所有人可见）",
            1=>"本人",
            2=>"好友"
        );
        if(empty($grant_id) && $grant_id==null) {
            return $grant_arr;
        }
        
        return $grant_arr[$grant_id];
    }
    
    
    /**
     * 个人是否存在
     * @param int $client_account
     * 
     * @return bool
     */
    private function client_is_exist($client_account) {
        if(empty($client_account)) {
            return false;
        }
        $mUser = ClsFactory::Create('Model.mUser');
        $client_info = $mUser->getClientAccountById($client_account);
        if(empty($client_info)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * 初始化个人相册关系
     * @param $datas
     * 
     * @return int 相册关系ID
     */
    private function add_rel($datas, $client_account) {
        if(empty($datas) || !is_array($datas) || empty($client_account)) {
            return false;
        }
        
        $datas = $this->format_rel_data($datas, $client_account);
        
        $mAlbumPersonRelation = ClsFactory::Create('Model.Album.mAlbumPersonRelation');
        return $mAlbumPersonRelation->addAlbumPersonRel($datas, true);
    }
    
    /**
     * 从关系得到相册album_id
     * @param $client_account
     * @param $offset
     * @param $limit
     * 
     * @return Array
     */
    private function get_album_ids_by_rel($client_account, $offset = null, $limit = null) {
        if(empty($client_account)) {
            return false;
        }
        
        $mAlbumPersonRelation = ClsFactory::Create('Model.Album.mAlbumPersonRelation');
        $rel_list = $mAlbumPersonRelation->getAlbumPersonRelByUid($client_account, $offset, $limit);
        if(empty($rel_list)) {
            return false;
        }
        
        return $rel_list;
    }
    /**
     * 格式化相册关系信息
     * @param Array $data
     * 
     * @return Array
     */
    private function format_rel_data($data, $client_account) {
        if(empty($data) || empty($client_account)) {
            return false;
        }
        
        $rel_data = array(
            'client_account' => $client_account,
            'album_id'   => $data['album_id'],
        	'grant'   => $data['grant']
        );
        
        return !empty($rel_data) ? $rel_data : false;
    }
    
    /**
     * 删除个人相册关系
     * @param $rel_id
     * 
     * @return bool
     */
    private function del_rel($rel_id) {
        if(empty($rel_id)) {
            return false;
        }
        
        $mAlbumPersonRelation = ClsFactory::Create('Model.Album.mAlbumPersonRelation');
        return $mAlbumPersonRelation->delAlbumPersonRelById($rel_id);
    }
    
    
    /**
     * 合并相册和相册权限信息
     * @param $album_list
     * @param $rel_list
     * 
     * @return Array
     */
    private function merge_album_rel_data($album_list, $rel_list) {
        if(empty($album_list) || empty($rel_list)) {
            return false;
        }
        
        $new_rel_list = array();
        //合并权限和相册信息
        foreach($rel_list as $rel_id=>$rel_val) {
            $rel_val['grant_name'] = $this->grant_arr($rel_val['grant']);
            $new_rel_list[$rel_val['album_id']]['grant_name'] = $rel_val['grant_name'];
            $new_rel_list[$rel_val['album_id']]['grant'] = $rel_val['grant'];
            unset($rel_list[$rel_id]);
        }
        
        //数据处理，将相册权限信息和相册信息合并
        foreach($album_list as $album_id=>$val) {
            $new_rel_list[$album_id]['grant'] = !empty($new_rel_list[$album_id]['grant']) ? $new_rel_list[$album_id]['grant'] : 0;
            $album_list[$album_id] = array_merge($album_list[$album_id],$new_rel_list[$album_id]);
        }
        unset($new_rel_list);
        
        return !empty($album_list) ? $album_list : false;
    }
    /**
     * 去空
     * @param Array $data
     * 
     * @return bool || Array
     */
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