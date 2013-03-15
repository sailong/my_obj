<?php
/**
 * 相片的相关数据操作
 * 
 * @author sailong
 *
 */
class PhotoComment {
    
    protected $_mAlbumPhotoComments = null;
    
    public function __construct() {
        $this->_mAlbumPhotoComments = ClsFactory::Create('Model.Album.mAlbumPhotoComments');
    }
    
    /**
     * 添加评论
     */
    public function addComment($comment_data) {
        if(empty($comment_data)) {
            return false;
        }
        $comment_id = $this->_mAlbumPhotoComments->addAlbumPhotoComment($comment_data,true);
        
        !empty($comment_id) ? $comment_id : false;
    }
    
    /**
     * 获取评论信息
     */
    public function getCommentByUpId($up_id) {
        if(empty($up_id)) {
            return false;
        }
        
        $comment_list = $this->_mAlbumPhotoComments->
    }
    
    
}