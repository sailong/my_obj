<?php
import('@.Control.Api.ExtractFeed.ExtractAbstract');

class ExamExtract extends ExtractAbstract {
   /**
     * @param $datas
     * exam_id:
     * class_code
     * subject_id
     * exam_name
     * exam_time
     * add_account
     * add_time
     * summary
     * upd_account
     * upd_time
     * exam_good
     * exam_bad
     * exam_well
     * is_published
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
    public function getFeedDatas($exam_datas) {
        $feed_datas = array(
            'feed_type'     => FEED_EXAM,
            'add_account'   => $exam_datas['add_account'],
            'add_time'		=> $exam_datas['add_time'],
            'feed_content'  => $this->formatContent($exam_datas),
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
        
        return "发了一个考试!";
    }
}