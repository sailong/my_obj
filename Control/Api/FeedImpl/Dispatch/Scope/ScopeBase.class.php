<?php
abstract class ScopeBase {
    abstract public function handle($mixed_id, $add_time, $feed_id, $feed_type);
    
    abstract protected function getFollows($mixed_id);
    
    /**
     * 将feed信息分发到班级成员的全部动态信息队列中
     * @param $follows
     * @param $add_time
     * @param $feed_id
     */
    public function dispatchUserAll($follows, $add_time, $feed_id) {
        if(empty($follows) || empty($feed_id)) {
            return false;
        }
        
        $mUserFeedAllZset = ClsFactory::Create('RModel.Feed.mUserFeedAllZset');
        
        $add_nums = 0;
        foreach($follows as $uid) {
            if($mUserFeedAllZset->addUserFeedAllZset($uid, $add_time, $feed_id)) {
                $add_nums++;
            }
        }
        
        return $add_nums;
    }
    
    /**
     * 将动态信息加载到班级的动态信息中
     * @param $follows
     * @param $feed_datas
     */
    public function dispactchClassFeed($class_code, $add_time, $feed_id) {
        if(empty($class_code) || empty($feed_id)) {
            return false;
        }
        
        $mClassFeedAllZset = ClsFactory::Create('RModel.Feed.mClassFeedAllZset');
        
        return $mClassFeedAllZset->addClassFeedAllZset($class_code, $add_time, $feed_id);
    }
    
    /**
     * 分发到和我相关的动态
     * @param  $follows
     * @param  $feed_id
     */
    public function dispatchUserMy($follows, $add_time, $feed_id) {
        if(empty($follows) || empty($feed_id)) {
            return false;
        }
        
        $mUserMyFeedZset = ClsFactory::Create('RModel.Feed.mUserMyFeedZset');
        
        $add_nums = 0;
        foreach((array)$follows as $uid) {
            if($mUserMyFeedZset->addUserMyFeedZset($uid, $add_time, $feed_id)) {
                $add_nums++;
            } 
        }
        
        return $add_nums;
    }
    
    /**
     * 分发到孩子动态
     * @param $follows
     * @param $feed_id
     */
    public function dispatchUserChild($follows, $add_time, $feed_id) {
        if(empty($follows) || empty($feed_id)) {
            return false;
        }
        
        $mUserChildrenSet = ClsFactory::Create('RModel.Feed.mUserChildSet');
        
        $add_nums = 0;
        foreach((array)$follows as $uid) {
            if($mUserChildrenSet->addUserChildFeedZset($uid, $add_time, $feed_id)) {
                $add_nums++;
            }
        }
        
        return $add_nums;
    }
    
	/**
     * 分发到孩子动态
     * @param $follows
     * @param $feed_id
     */
    public function dispatchUserAlbum($follows, $add_time, $feed_id) {
        if(empty($follows) || empty($feed_id)) {
            return false;
        }
        
        $mUserAlbumFeedSet = ClsFactory::Create('RModel.Feed.mUserAlbumFeedSet');
        
        $add_nums = 0;
        foreach((array)$follows as $uid) {
            if($mUserAlbumFeedSet->getUserAlbumFeedZset($uid, $add_time, $feed_id)) {
                $add_nums++;
            }
        }
        
        return $add_nums;
    }
    
}