<?php
class ExtractFeed {
    
    /**
     * 获取对应的动态内容
     * @param $entity_datas
     * @param $feed_type
     */
    public function getFeedDatas($entity_datas, $feed_type) {
        $extractObject = $this->createObject($feed_type);
        if(is_object($extractObject)) {
            return $extractObject->getFeedDatas($entity_datas);
        }
        
        return false;
    }
    
    /**
     * 创建提取动态内容的对象
     * @param $feed_type
     */
    private function createObject($feed_type) {
        switch($feed_type) {
            case FEED_ALBUM:
                import('@.Control.Api.FeedImpl.ExtractFeed.AlbumExtract');
                $extractObject = new AlbumExtract();
                break;
            case FEED_BLOG:
                import('@.Control.Api.FeedImpl.ExtractFeed.BlogExtract');
                $extractObject = new BlogExtract();
                break;
            case FEED_EXAM:
                import('@.Control.Api.FeedImpl.ExtractFeed.ExamExtract');
                $extractObject = new ExamExtract();
                break;
            case FEED_HOMEWORK:
                import('@.Control.Api.FeedImpl.ExtractFeed.HomeworkExtract');
                $extractObject = new HomeworkExtract();
                break;
            case FEED_MOOD:
                import('@.Control.Api.FeedImpl.ExtractFeed.MoodExtract');
                $extractObject = new MoodExtract();
                break;
            case FEED_NOTICE:
                import('@.Control.Api.FeedImpl.ExtractFeed.NoticeExtract');
                $extractObject = new NoticeExtract();
                break;
        }
        
        return is_object($extractObject) ? $extractObject : false;
    }
}