<?php
import('@.RData.RedisCommonKey');

class dUserChildrenSet extends rBase {
    /**
     * 获取班级的学生集合
     * @param $class_code
     */
    public function getUserChildrenSet($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $this->loader($uid);
        
        $redis_key = RedisCommonKey::getUserChildrenSetKey($uid);
        
        return $this->sMembers($redis_key);
    }
    
    /**
     * 获取在线的孩子信息
     * @param $uid
     */
    public function getOnlineUserChildrenSet($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $this->loader($uid);
        
        $redis_key_live_user = RedisCommonKey::getLiveUserSetKey();
        $redis_key_user_child = RedisCommonKey::getUserChildrenSetKey($uid);
        
        return $this->sInter($redis_key_user_child, $redis_key_live_user);
    }
    
    /**
     * 添加班级的学生集合信息
     * @param $class_code
     * @param $student_accounts
     */
    public function addUserChildrenSet($uid, $child_accounts) {
        if(empty($uid) || empty($child_accounts)) {
            return false;
        }
        
        $redis_key = RedisCommonKey::getUserChildrenSetKey($uid);
        
        $add_nums = 0;
        foreach((array)$child_accounts as $child_uid) {
            if($this->sAdd($redis_key, $child_uid)) {
                $add_nums++;
            }
        }
        
        return $add_nums ? $add_nums : false;
    }
    
	/**
     * 删除用户的孩子集合中的数据
     * @param $class_code
     * @param $family_accounts
     */
    public function delUserChildrenSet($uid, $child_accounts) {
        if(empty($uid) || empty($child_accounts)) {
            return false;
        }
        
        $redis_key = RedisCommonKey::getUserChildrenSetKey($uid);
        
        $delete_nums = 0;        
        foreach((array)$child_accounts as $child_uid) {
            if($this->sRem($redis_key, $child_uid)) {
                $delete_nums++;
            }
        }
        
        return $delete_nums ? $delete_nums : false;
    }
    
    /**
     * 判断是否存在
     * @param $uid
     */
    private function isExistUserChildrenSet($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $redis_key = RedisCommonKey::getUserChildrenSetKey($uid);
        $keys = $this->keys($redis_key);
        
        return !empty($keys) ? $keys : false;
    }
    
    /**
     * 加载用户相关的数据
     * @param $uid
     */
    private function loader($uid) {
        if(empty($uid) || $this->isExistUserChildrenSet($uid)) {
            return false;
        }
        
        import('@.RData.Common.Loader.LoaderUser');
        $loaderObject = new LoaderUser();
        
        return $loaderObject->load($uid);
    }
    
}
