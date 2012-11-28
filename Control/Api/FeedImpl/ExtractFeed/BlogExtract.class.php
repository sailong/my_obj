<?php
import('@.Control.Api.FeedImpl.ExtractFeed.ExtractAbstract');

class BlogExtract extends ExtractAbstract {
 /**
     * @param $datas
     * fields:
     * blog_id
     * title
     * type_id
     * views
     * is_published
     * contentbg
     * summary
     * comments
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
    public function getFeedDatas($blog_datas) {
        $feed_datas = array(
            'feed_type'     => FEED_BLOG,
            'add_account'   => $blog_datas['add_account'],
            'add_time'		=> $blog_datas['add_time'],
            'feed_content'  => $this->formatContent($blog_datas),
            'img_url'		=> $this->getBlogFirstPhotoFromSummary($blog_datas['summary']),
        );
        
        return $feed_datas;
    }
    
    /**
     * 获取相册中的第一张照片
     * @param $album_id
     * 
     * todolist
     */
    private function getBlogFirstPhotoFromSummary($summary) {
        if(empty($summary)) {
            return false;
        }
        
        $pattern = "/(<img><\/img>)/";
        
        if(preg_match($pattern, $summary, $matches)) {
            return $matches[1];
        }
        
        return "";
    }
    
    /**
     * 提取日志中的内容
     * @param $blog_datas
     */
    private function formatContent($blog_datas) {
        if(empty($blog_datas)) {
            return false;
        }
        
        return "写了一个日志!";
    }
}
