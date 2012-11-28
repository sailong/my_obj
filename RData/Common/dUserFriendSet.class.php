<?php
import('RData.RedisCommonKey');

class dUserFriendSet extends rBase {
    /**
     * 获取班级的学生集合
     * @param $class_code
     */
    public function getUserFriendSet($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $this->loader($uid);
        
        $redis_key = RedisCommonKey::getUserFriendSetKey($uid);
        
        return $this->sMembers($redis_key);
    }
    
    /**
     * 获取在线的好友列表
     * @param $uid
     */
    public function getOnlineUserFriendSet($uid) {
        if(empty($uid)) {
            return false;
        }
        
        if(FEED_DEBUG) {
            return $this->getUserFriendSet($uid);
        }
        
        $this->loader($uid);
        
        $redis_key_live_user = RedisCommonKey::getLiveUserSetKey();
        $redis_key_user_friend = RedisCommonKey::getUserFriendSetKey($uid);
        
        return $this->sInter($redis_key_user_friend, $redis_key_live_user);
    }
    
    /**
     * 添加班级的学生集合信息
     * @param $class_code
     * @param $student_accounts
     */
    public function addUserFriendSet($uid, $friend_accounts) {
        if(empty($uid) || empty($friend_accounts)) {
            return false;
        }
        
        $friend_accounts = array_unique((array)$friend_accounts);
        
        $redis_key = RedisCommonKey::getUserFriendSetKey($uid);
        
        $pipe = $this->multi(Redis::PIPELINE);
        foreach($friend_accounts as $friend_uid) {
            $pipe->sAdd($redis_key, $friend_uid);
        }
        $add_nums = $pipe->exec();
        
        return $add_nums ? $add_nums : false;
    }
    
	/**
     * 删除用户好友集合中的数据
     * @param $class_code
     * @param $family_accounts
     */
    public function delUserFriendSet($uid, $friend_accounts) {
        if(empty($uid) || empty($friend_accounts)) {
            return false;
        }
        
        $friend_accounts = array_unique((array)$friend_accounts);
        
        $redis_key = RedisCommonKey::getUserFriendSetKey($uid);
        
        $pipe = $this->multi(Redis::PIPELINE);       
        foreach($friend_accounts as $friend_uid) {
            $pipe->sRem($redis_key, $friend_uid);
        }
        $delete_nums = $pipe->exec();
        
        return $delete_nums ? $delete_nums : false;
    }
    
    /**
     * 加载用户相关的数据
     * @param $uid
     */
    private function loader($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $redis_key = RedisCommonKey::getUserFriendSetKey($uid);
        
        $GlobalKeys = ClsFactory::Create('RData.GlobalKeys');
        if(!$GlobalKeys->isExists($redis_key)) {
            
            $GlobalKeys->addKey($redis_key);
            
            import('RData.Common.Loader.LoaderUser');
            $loaderObject = new LoaderUser();
        
            return $loaderObject->load($uid);
        }
        
        return true;
    }
}
