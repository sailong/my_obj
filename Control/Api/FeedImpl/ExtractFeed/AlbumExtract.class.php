<?php
import('@.Control.Api.FeedImpl.ExtractFeed.ExtractAbstract');

/**
 * 提取相册中的动态信息
 * @author Administrator
 *
 */
class AlbumExtract extends ExtractAbstract {
    /**
     * @param $datas
     * fields:
     * album_id
     * album_name
     * album_explain
     * album_img
     * add_account
     * add_time
     * upd_account
     * upd_time
     */
    
    /**
     * feed
     * @param  $datas
     * feed_id
     * feed_type
     * add_account
     * add_time
     * feed_content
     * img_url
     */
    public function getFeedDatas($album_datas) {
        $feed_datas = array(
            'feed_type'     => FEED_ALBUM,
            'add_account'   => $album_datas['add_account'],
            'add_time'		=> $album_datas['add_time'],
            'feed_content'  => $this->formatContent($album_datas),
            'img_url'		=> $this->getAlbumFirstPhoto($album_datas['album_id']),
        );
        
        return $feed_datas;
    }
    
    /**
     * 获取相册中的第一张照片
     * @param $album_id
     * 
     * todolist
     */
    private function getAlbumFirstPhoto($album_id) {
        
    }
    
    private function formatContent($album_datas) {
        if(empty($album_datas)) {
            return false;
        }
        
        return "上传了一个相册!";
    }
    
}