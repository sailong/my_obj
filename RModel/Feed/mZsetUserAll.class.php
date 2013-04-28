<?php
import('@.RModel.Feed.mFeedBase');
class mZsetUserAll extends mFeedBase {
    
    public function __construct() {
        import('RData.Feed.dZsetUserAll');
        $this->_rdata = new dZsetUserAll();
    }
        
    /**
     * 加载与我相关全部动态信息
     * 1. 我产生的动态
     * 2. 我朋友的动态.
     * 3. 我所在班级的动态.
     * @param $id			  我们网帐号
     * @param $lastFeedId        最后查询结果的feed_id
     * @param $limit   
     */
    protected function loader($id, $lastFeedId = 0, $limit = 10) {
        if(empty($id)) {
            return false;
        }
        
        $mHashClient = ClsFactory::Create('RModel.Common.mHashClient');
	    $client_base = $mHashClient->getClientbyUid($id); 
	    $client_type = $client_base['client_type'];       
        
        
        $uids = array();


        // 获取好友动态        
        $mSetClientFriends = ClsFactory::Create('RModel.Common.mSetClientFriends');
	    $all_friends = $mSetClientFriends->getClientFriendsByUid($uid);        
        
	    //家长需要加入孩子帐号：
	    if ($client_type == CLIENT_TYPE_FAMILY) {
	        $mSetClientChildren = ClsFactory::Create('RModel.Common.mSetClientChildren');
	        $childrens = $mSetClientChildren->getClientChildrenByUid($id);
	        $all_friends = array_merge($all_friends, $childrens);
	    }
	    
        if (empty($all_friends)) return false;
        
        $all_count = count($all_friends);
        if ($all_count < 500) {
            $uids = $all_friends;
            unset($all_friends);
        } else {
            // 获取活跃用户库
            $mSetActiveUser = ClsFactory::Create('RModel.Common.mSetActiveUser');
            
            $active_uids = $mSetActiveUser->getActiveUserSet();
            
            //获取所有用户与活跃用户库的交集
            $r = array_intersect($all_friends, $active_uids);
            $r_count = count($r);
            
            if ($r_count > 500) {
                $uids = array_slice($r, 0, 500);
            } else {
                $r_diff = array_diff($all_friends, $r);
                $r_adds = array_merge($r, (array)$r_diff);
                $uids = array_slice($r_adds, 0, 500);
            }
            
        }
        array_unshift($uids, $id);
        $mFeedTimeLine = ClsFactory::Create('Model.Feed.mFeedTimeLine');
        
        $datas_from_friends = array();
        
        if (!empty($uids)) {
            $datas_from_friends = $mFeedTimeLine->getFeedByUids($uids, $lastFeedId, $limit);
        }
        
        //获取班级动态
	    $mHashClientClass = ClsFactory::Create('RModel.Common.mHashClientClass');
	    $client_class_datas = $mHashClientClass->getClientClassbyUid($uid);
	    
	    $class_codes = array();
	    foreach($client_class_datas as $key =>$val) {
	        $class_codes[] = $val['class_code'];
	    }
	    
        $datas_from_class = array();
        
        if (!empty($class_codes)) {
            $datas_from_class = $mFeedTimeLine->getFeedByClassCodes($class_codes, $lastFeedId, $limit);
        }
        
        
        //最后将好友动态和班级动态进行合并排序
        $datas_from_db = array_merge($datas_from_friends, $datas_from_class);
        
        $sort_arr = array();
        
        foreach ($datas_from_db as $key => $val) {
          $sort_arr[$key] = $val['feed_id'];
        }
        array_multisort($sort_arr, SORT_ASC, $datas_from_db);          
        
        $result = array();
        foreach ($datas_from_db as $key => $val) {
            $result[] = array(
                'value' => $val['feed_id'],
                'score' => $val['feed_id'],
            );
            if ($key >= $limit) break;
        }
        return $result;
    }
}