<?php
class PhotocommentAction extends SnsController {
    public function _initialize() {
        parent::_initialize();
    }
	/**
     * 添加评论
     *
     */
    public function addPhotoComment() {
        $client_account = $this->objInput->postInt('add_uid');
        $content = $this->objInput->postStr("content");
        $photo_id = $this->objInput->postInt("photo_id");
        $up_id = $this->objInput->postInt("up_id");
        $album_id = $this->objInput->postInt("album_id");
        if(empty($client_account) || empty($content) || empty($photo_id) || empty($album_id)) {
            $this->ajaxReturn(null, '评论失败', -1, 'json');
        }
        import('@.Control.Api.AlbumImpl.PhotoInfo');
        $PhotoInfo = new PhotoInfo();
        $photo_info = $PhotoInfo->getPhotoByPhotoId($photo_id);
        if(empty($photo_info) || $photo_info[$photo_id]['album_id'] != $album_id) {
            $this->ajaxReturn(null, '评论失败', -1, 'json');
        }
        $level = 2;
        if($photo_id == $up_id){
            $level = 1;
        }
        $add_time = time();
        $data_arr = array(
            "up_id"=>$up_id,
            "photo_id"=>$photo_id,
            "content"=>$content,
            "client_account"=>$client_account,
            "add_time"=>$add_time,
            "level"=>$level
        );
        import('@.Control.Api.AlbumImpl.PhotoComments');
        $PhotoComments = new PhotoComments();
        $comment_id = $PhotoComments->addPhotoComments($data_arr);
        
        if(empty($comment_id)) {
            $this->ajaxReturn(null, '评论失败', -1, 'json');
        }
        import('@.Common_wmw.WmwFace');
        $data_arr['content'] = WmwFace::parseFace($data_arr['content']);
        $data_arr['comment_id']=$comment_id;
        $data_arr['add_date']=date('y-m-d H:i:s', $add_time);
        $this->ajaxReturn($data_arr, '评论成功', 1, 'json');
    }
}
