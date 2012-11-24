<?php
class ClassFeedDispatch {
    public function dispatch($uid, $class_code, $add_time, $feed_id, $feed_type) {
        
        $mUserObjectHash = ClsFactory::Create('RModel.Common.mUserObjectHash');
        $user = $mUserObjectHash->getUserObjectHash($uid);
        
        if($user['client_type'] == CLIENT_TYPE_STUDENT) {
            $this->dispatchToParents($uid, $add_time, $feed_id, $feed_type);
        }
        
        return $this->dispatchToClassMembers($class_code, $add_time, $feed_id, $feed_type);
    }
    
    /**
     * 分发给班级的成员
     * @param $class_code
     */
    private function dispatchToClassMembers($class_code, $add_time, $feed_id, $feed_type) {
        if(empty($class_code) || empty($feed_id)) {
            return false;
        }
        
        import('@.Control.Api.FeedImpl.Dispatch.Scope.ClassMembersScope');
        $classMembersScope = new ClassMembersScope();
        
        return $classMembersScope->handle($class_code, $add_time, $feed_id, $feed_type);
    }
    
    /**
     * 分发给用户的家长
     * @param $uid
     */
    private function dispatchToParents($uid, $add_time, $feed_id, $feed_type) {
        if(empty($uid) || empty($feed_id)) {
            return false;
        }
        
        import('@.Control.Api.FeedImpl.Dispatch.Scope.ParentsScope');
        $parentsScope = new ParentsScope();
        
        return $parentsScope->handle($uid, $add_time, $feed_id, $feed_type);
    }
}