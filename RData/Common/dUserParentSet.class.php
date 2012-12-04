<?php
import('RData.RedisCommonKey');

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
        
        if(FEED_DEBUG) {
            return $this->getUserParentSet($uid);
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
        
        $parent_accounts = array_unique((array)$parent_accounts);
        
        $redis_key = RedisCommonKey::getUserParentSetKey($uid);
        
        $pipe = $this->multi(Redis::PIPELINE);
        foreach($parent_accounts as $uid) {
            $pipe->sAdd($redis_key, $uid);
        }
        $replies = $pipe->exec();
        $add_nums = $this->getPipeSuccessNums($replies);
        
        return $add_nums ? $add_nums : false;
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
        
        $parent_accounts = array_unique((array)$parent_accounts);
        
        $redis_key = RedisCommonKey::getUserParentSetKey($uid);
        
        $pipe = $this->multi(Redis::PIPELINE);        
        foreach($parent_accounts as $parent_uid) {
            $pipe->sRem($redis_key, $parent_uid);
        }
        $replies = $pipe->exec();
        $delete_nums = $this->getPipeSuccessNums($replies);
        
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
        
        $redis_key = RedisCommonKey::getUserParentSetKey($uid);
        
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
