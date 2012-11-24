<?php
class UserFeedDispatch {
    protected $user = array();
    
    public function dispatch($uid, $add_time, $feed_id, $feed_type) {
        
        $mUserObjectHash = ClsFactory::Create('RModel.Common.mUserObjectHash');
        $this->user = $mUserObjectHash->getUserObjectHash($uid);
        
        $client_type = intval($this->user['client_type']);
        if($client_type == CLIENT_TYPE_STUDENT) {
            return $this->dispatchStudent($add_time, $feed_id, $feed_type);
        } else if($client_type == CLIENT_TYPE_TEACHER) {
            return $this->dispatchTeacher($add_time, $feed_id, $feed_type);
        } else if($client_type == CLIENT_TYPE_FAMILY) {
            return $this->dispatchFamily($add_time, $feed_id, $feed_type);
        }
        
        return false;
    }
    
    /**
     * 分发学生添加的动态信息
     */
    private function dispatchStudent($add_time, $feed_id, $feed_type) {
        $uid = $this->user['client_account'];
        
        import('@.Control.Api.FeedImpl.Dispatch.Scope.FriendsScope');
        $friendsScope = new FriendsScope();
        $friendsScope->handle($uid, $add_time, $feed_id, $feed_type);
        
        $class_code = key($this->user['class_info']);
        
        import('@.Control.Api.FeedImpl.Dispatch.Scope.ClassMembersScope');
        $classMembersScope = new ClassMembersScope();
        $classMembersScope->handle($class_code, $add_time, $feed_id, $feed_type);
        
        import('@.Control.Api.FeedImpl.Dispatch.Scope.ParentsScope');
        $parentsScope = new ParentsScope();
        $parentsScope->handle($uid, $add_time, $feed_id, $feed_type);
    }
    
    /**
     * 添加老师添加的动态信息
     */
    private function dispatchTeacher($add_time, $feed_id, $feed_type) {
        $uid = $this->user['client_account'];
        
        import('@.Control.Api.FeedImpl.Dispatch.Scope.FriendsScope');
        $friendsScope = new FriendsScope();
        $friendsScope->handle($uid, $add_time, $feed_id, $feed_type);
        
        import('@.Control.Api.FeedImpl.Dispatch.Scope.ClassMembersScope');
        $classMembersScope = new ClassMembersScope();
        
        $class_code_list = array_keys($this->user['class_info']);
        foreach((array)$class_code_list as $class_code) {
            $classMembersScope->handle($class_code, $add_time, $feed_id, $feed_type);
        }
        
        import('@.Control.Api.FeedImpl.Dispatch.Scope.ParentsScope');
        $parentsScope = new ParentsScope();
        $parentsScope->handle($uid, $add_time, $feed_id, $feed_type);
    }
    
    /**
     * 添加家长的动态信息
     */
    private function dispatchFamily($add_time, $feed_id, $feed_type) {
        $uid = $this->user['client_account'];
        
        import('@.Control.Api.FeedImpl.Dispatch.Scope.FriendsScope');
        $friendsScope = new FriendsScope();
        $friendsScope->handle($uid, $add_time, $feed_id, $feed_type);
    }
}