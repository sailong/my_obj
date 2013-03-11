<?php
import('@.RModel.Feed.mFeedBase');
class mZsetClassAlbum extends mFeedBase {

    public function __construct() {
        import('RData.Feed.dZsetClassAll');
        $this->_rdata = new dZsetClassAll();
    }
    
    /**
     * 加载班级相册动态信息
     * @param $id			  班级账号
     * @param $timeline      最后查询结果的时间点
     * @param $lastId        最后查询结果的feed_id
     * @param $limit   
     */
    private function loader ($id, $timeline = 0, $lastId = 0, $limit = 10) {
        if(empty($id)) {
            return false;
        }
        
        $mFeedClassRelation = ClsFactory::Create('Model.Feed.mFeedClassRelation');
        
        $datas_from_db = $mFeedClassRelation->getFeedByClassCodeAndType($id, FEED_TYPE_ALBUM, $timeline, $lastId, $limit);
        
        $result = array();
        foreach ($datas_from_db as $key => $val) {
            $result[] = array(
            	'value' => $val['feed_id'],
                'score' => $val['timeline'],
                'id' => $val['id'],
            );
        }
        
        return $result;
    }
}