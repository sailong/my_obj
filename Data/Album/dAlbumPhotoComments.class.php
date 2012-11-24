<?php
class dAlbumPhotoComments extends dBase {
    protected $_pk = 'comment_id';
    protected $_tablename = 'wmw_album_photo_comments';
    protected $_fields = array(
                    'comment_id',
                    'up_id',
                    'photo_id',
                    'content',
                    'client_account',
                    'add_time',
                    'level',
                );
                
    protected $_index_list = array(
                    'photo_id',
                );
                
    public function addAlbumPhotoComment($data, $is_return_id) {
        return $this->add($data, $is_return_id);
    }
    
    public function modifyAlbumPhotoCommentByCommentId($data, $comment_id) {
        return $this->modify($data, $comment_id);
    }
    
    public function delAlbumPhotoCommentByCommentId($comment_id) {
        return $this->delete($comment_id);
    }
    
    public function getAlbumPhotoCommentByPhotoId($photo_id) {
        return $this->getInfoByFk($photo_id, 'photo_id');
    }
}