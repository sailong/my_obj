<?php
import('@.Control.Api.FeedImpl.Dispatch.Scope.ScopeBase');

class ParentsScope extends ScopeBase {

    public function handle($uid, $add_time, $feed_id, $feed_type) {
        $parent_follows = $this->getFollows($uid);
        
        //推送到家长的全部动态
        $this->dispatchUserAll($parent_follows, $add_time, $feed_id);
        //推送到家长的孩子动态
        $this->dispatchUserChild($parent_follows, $add_time, $feed_id);
        //推送到家长的与我相关的动态
        $this->dispatchUserMy($parent_follows, $add_time, $feed_id);
    }
    
    /**
     * 获取用户的家长信息
     * @param $uid
     */
    protected function getFollows($uid) {
        if(empty($uid)) {
            return false;
        }
        
        //获取用户的家长信息
        $mUserParentSet = ClsFactory::Create('RModel.Common.mUserParentSet');
        
        return $mUserParentSet->getOnlineUserParentSet($uid);
    }
    
}