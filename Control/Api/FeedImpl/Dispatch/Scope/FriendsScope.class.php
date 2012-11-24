<?php
import('@.Control.Api.FeedImpl.Dispatch.Scope.ScopeBase');

class FriendsScope extends ScopeBase {
    
    public function handle($uid, $add_time, $feed_id, $feed_type) {
        $follows = $this->getFollows($uid);
        
        $this->dispatchUserAll($follows, $add_time, $feed_id);
        $this->dispatchUserMy($follows, $add_time, $feed_id);
        
        if($feed_type == FEED_ALBUM) {
            $this->dispatchUserAlbum($follows, $add_time, $feed_id);
        }
    }
    
    /**
     * 获取用户的好友关系
     * @param $uid
     */
    protected function getFollows($uid) {
        if(empty($uid)) {
            return false;
        }
        
        //获取用户的好友关系
        $mUserFriendSet = ClsFactory::Create('RModel.Feed.Common.mUserFriendSet');
        
        return $mUserFriendSet->getOnlineUserFriendSet($uid);
    }
    
}