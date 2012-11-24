<?php
class mAlbumPhotoComments extends mBase {
    protected $_dAlbumPhotoComments = null;

    public function __construct() {
        $this->_dAlbumPhotoComments = ClsFacotry::Create('Data.Album.dAlbumPhotoComments');
    }
    
    public function addAlbumPhotoComment($data, $is_return_id) {
        return $this->_dAlbumPhotoComments->addAlbumPhotoComment($data, $is_return_id);
    }
    
    public function modifyAlbumPhotoCommentByCommentId($data, $comment_id) {
        return $this->_dAlbumPhotoComments->modifyAlbumPhotoCommentByCommentId($data, $comment_id);
    }
    
    public function delAlbumPhotoCommentByCommentId($comment_id) {
        return $this->_dAlbumPhotoComments->delAlbumPhotoCommentByCommentId($comment_id);
    }
    
    public function getAlbumPhotoCommentByPhotoId($photo_id) {
        return $this->_dAlbumPhotoComments->getAlbumPhotoCommentByPhotoId($photo_id);
    }
}