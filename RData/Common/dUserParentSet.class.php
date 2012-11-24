<?php
import('@.RData.RedisCommonKey');

class dUserParentSet extends rBase {
    /**
     * 获取班级的学生集合
     * @param $class_code
     */
    public function getUserParentSet($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $this->loader($uid);
        
        $redis_key = RedisCommonKey::getUserParentSetKey($uid);
        
        return $this->sMembers($redis_key);
    }
    
    /**
     * 获取在线的家长列表
     * @param $uid
     */
    public function getOnlineUserParentSet($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $this->loader($uid);
        
        $redis_key_live_user = RedisCommonKey::getLiveUserSetKey();
        $redis_key_user_parent = RedisCommonKey::getUserParentSetKey($uid);
        
        return $this->sInter($redis_key_user_parent, $redis_key_live_user);
    }
    
    /**
     * 添加班级的学生集合信息
     * @param $class_code
     * @param $student_accounts
     */
    public function addUserParentSet($uid, $parent_accounts) {
        if(empty($uid) || empty($parent_accounts)) {
            return false;
        }
        
        $redis_key = RedisCommonKey::getUserParentSetKey($uid);
        
        foreach((array)$parent_accounts as $uid) {
            $this->sAdd($redis_key, $uid);
        }
        
        return true;
    }
    
	/**
     * 删除用户家长集合中的数据
     * @param $class_code
     * @param $family_accounts
     */
    public function delUserParentSet($uid, $parent_accounts) {
        if(empty($uid) || empty($parent_accounts)) {
            return false;
        }
        
        $redis_key = RedisCommonKey::getUserParentSetKey($uid);
        
        $delete_nums = 0;        
        foreach((array)$parent_accounts as $parent_uid) {
            if($this->sRem($redis_key, $parent_uid)) {
                $delete_nums++;
            }
        }
        
        return $delete_nums ? $delete_nums : false;
    }
    
	/**
     * 判断是否存在
     * @param $uid
     */
    private function isExistUserParentSet($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $redis_key = RedisCommonKey::getUserParentSetKey($uid);
        $keys = $this->keys($redis_key);
        
        return !empty($keys) ? $keys : false;
    }
    
    /**
     * 加载用户相关的数据
     * @param $uid
     */
    private function loader($uid) {
        if(empty($uid) || $this->isExistUserParentSet($uid)) {
            return false;
        }
        
        import('@.RData.Common.Loader.LoaderUser');
        $loaderObject = new LoaderUser();
        
        return $loaderObject->load($uid);
    }
}
