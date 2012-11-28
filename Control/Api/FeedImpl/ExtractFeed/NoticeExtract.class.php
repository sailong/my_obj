<?php
import('@.Control.Api.FeedImpl.ExtractFeed.ExtractAbstract');

class NoticeExtract extends ExtractAbstract {
	/**
     * @param $datas
     * notice_id
     * class_code
     * notice_title
     * notice_content
     * add_account
     * add_time
     * is_sms
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
    public function getFeedDatas($notice_datas) {
        $feed_datas = array(
            'feed_type'     => FEED_NOTICE,
            'add_account'   => $notice_datas['add_account'],
            'add_time'		=> $notice_datas['add_time'],
            'feed_content'  => $this->formatContent($notice_datas),
            'img_url'		=> '',
        );
        
        return $feed_datas;
    }
    
    /**
     * 提取日志中的内容
     * @param $blog_datas
     */
    private function formatContent($notice_datas) {
        if(empty($notice_datas)) {
            return false;
        }
        
        return "发了一个通告!";
    }
}
