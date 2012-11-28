<?php
import('@.Control.Api.FeedImpl.ExtractFeed.ExtractAbstract');

class MoodExtract extends ExtractAbstract {
	/**
     * @param $datas
     * mood_id
     * content
     * is_sms
     * img_url
     * add_account
     * add_time
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
    public function getFeedDatas($mood_datas) {
        $feed_datas = array(
            'feed_type'     => FEED_MOOD,
            'add_account'   => $mood_datas['add_account'],
            'add_time'		=> $mood_datas['add_time'],
            'feed_content'  => $this->formatContent($mood_datas),
            'img_url'		=> !empty($mood_datas['img_url']) ? $mood_datas['img_url'] : '',
        );
        
        return $feed_datas;
    }
    
    /**
     * 提取日志中的内容
     * @param $blog_datas
     */
    private function formatContent($mood_datas) {
        if(empty($mood_datas)) {
            return false;
        }
        
        return "发了一个说说!";
    }
}