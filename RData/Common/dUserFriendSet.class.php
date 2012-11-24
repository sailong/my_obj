<?php
import('@.RData.RedisCommonKey');

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
        
        $redis_key = RedisCommonKey::getUserFriendSetKey($uid);
        
        $add_nums = 0;
        foreach((array)$friend_accounts as $friend_uid) {
            if($this->sAdd($redis_key, $friend_uid)) {
                $add_nums++;
            }
        }
        
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
        
        $redis_key = RedisCommonKey::getUserFriendSetKey($uid);
        
        $delete_nums = 0;        
        foreach((array)$friend_accounts as $friend_uid) {
            if($this->sRem($redis_key, $friend_uid)) {
                $delete_nums++;
            }
        }
        
        return $delete_nums ? $delete_nums : false;
    }
    
   /**
     * 判断是否存在
     * @param $uid
     */
    private function isExistUserFriendSet($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $redis_key = RedisCommonKey::getUserFriendSetKey($uid);
        $keys = $this->keys($redis_key);
        
        return !empty($keys) ? $keys : false;
    }
    
    /**
     * 加载用户相关的数据
     * @param $uid
     */
    private function loader($uid) {
        if(empty($uid) || $this->isExistUserFriendSet($uid)) {
            return false;
        }
        
        import('@.RData.Common.Loader.LoaderUser');
        $loaderObject = new LoaderUser();
        
        return $loaderObject->load($uid);
    }
}
