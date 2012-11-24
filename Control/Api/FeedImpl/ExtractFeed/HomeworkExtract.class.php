<?php
import('@.Control.Api.ExtractFeed.ExtractAbstract');

class HomeworkExtract extends ExtractAbstract {
	/**
     * @param $datas
     * homework_id
     * class_code
     * subject_id
     * add_account
     * add_time
     * upd_account
     * upd_time
     * end_time
     * attachment
     * content
     * is_sms
     * accepters
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
    public function getFeedDatas($homework_datas) {
        $feed_datas = array(
            'feed_type'     => FEED_HOMEWORK,
            'add_account'   => $homework_datas['add_account'],
            'add_time'		=> $homework_datas['add_time'],
            'feed_content'  => $this->formatContent($homework_datas),
            'img_url'		=> '',
        );
        
        return $feed_datas;
    }
    
    /**
     * 提取日志中的内容
     * @param $blog_datas
     */
    private function formatContent($blog_datas) {
        if(empty($blog_datas)) {
            return false;
        }
        
        return "发了一个作业!";
    }
}