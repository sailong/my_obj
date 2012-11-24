<?php
import('@.RData.RedisCommonKey');

class dClassFamilySet extends rBase {
    /**
     * 获取班级中的家长的成员列表
     * @param $class_code
     */
    public function getClassFamilySet($class_code) {
        if(empty($class_code)) {
            return false;
        }
        
        $this->loader($class_code);
        
        $redis_key = RedisCommonKey::getClassFamilySetKey($class_code);
        
        return $this->sMembers($redis_key);
    }
    
    /**
     * 获取在线的家长用户信息
     * @param $class_code
     */
    public function getOnlineClassFamilySet($class_code) {
        if(empty($class_code)) {
            return false;
        }
        
        $this->loader($class_code);
        
        $redis_key_live_user = RedisCommonKey::getLiveUserSetKey();
        $redis_key_class_family = RedisCommonKey::getClassFamilySetKey($class_code);
        
        return $this->sInter($redis_key_class_family, $redis_key_live_user);
    }
    
    /**
     * 添加班级的成员列表
     * @param $class_code
     * @param $family_accounts
     */
    public function addClassFamilySet($class_code, $family_accounts) {
        if(empty($class_code)) {
            return false;
        }
        
        $redis_key = RedisCommonKey::getClassFamilySetKey($class_code);
        
        $add_nums = 0;
        foreach((array)$family_accounts as $uid) {
            if($this->sAdd($redis_key, $uid)) {
                $add_nums++;
            }
        }
        
        return $add_nums ? $add_nums : false;
    }
    
    /**
     * 删除班级集合中的数据
     * @param $class_code
     * @param $family_accounts
     */
    public function delClassFamilySet($class_code, $family_accounts) {
        if(empty($class_code) || empty($family_accounts)) {
            return false;
        }
        
        $redis_key = RedisCommonKey::getClassFamilySetKey($class_code);
        
        $delete_nums = 0;        
        foreach((array)$family_accounts as $uid) {
            if($this->sRem($redis_key, $uid)) {
                $delete_nums++;
            }
        }
        
        return $delete_nums ? $delete_nums : false;
    }
    
    /**
     * 加载数据
     * @param $class_code
     */
    private function loader($class_code) {
        if(empty($class_code) || $this->isExistClassFamilySet($class_code)) {
            return false;
        }
        
        import('@.RData.Common.Loader.LoaderClassSet');
        $loaderObject = new loaderClassSet();
        return $loaderObject->load($class_code);
    }
    
    /**
     * 判断家长对应的集合是否存在
     * @param $class_code
     */
    private function isExistClassFamilySet($class_code) {
        if(empty($class_code)) {
            return false;
        }
        
        $redis_key = RedisCommonKey::getClassFamilySetKey($class_code);
        $keys = $this->keys($redis_key);
        
        return !empty($keys) ? true : false;
    }
}
