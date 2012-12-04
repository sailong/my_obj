<?php
import('RData.RedisCommonKey');
import('RData.GlobalKeys');

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
        
        if(FEED_DEBUG) {
            return $this->getClassFamilySet($class_code);
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
        if(empty($class_code) || empty($family_accounts)) {
            return false;
        }
        
        $family_accounts = array_unique((array)$family_accounts);
        $chunk_arr = array_chunk($family_accounts, 200, true);
        unset($family_accounts);
        
        $redis_key = RedisCommonKey::getClassFamilySetKey($class_code);
        
        $add_nums = 0;
        foreach($chunk_arr as $key=>$chunk_list) {
            //批量添加班级家长成员
            $pipe = $this->multi(Redis::PIPELINE);
            foreach($chunk_list as $uid) {
                $pipe->sAdd($redis_key, $uid);
            }
            $replies = $pipe->exec();
            $add_nums += intval($this->getPipeSuccessNums($replies));
            
            unset($chunk_arr[$key]);
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
        
        $family_accounts = array_unique((array)$family_accounts);
        $chunk_arr = array_chunk($family_accounts, 200, true);
        unset($family_accounts);
        
        $redis_key = RedisCommonKey::getClassFamilySetKey($class_code);
        
        $delete_nums = 0;
        foreach($chunk_arr as $key=>$chunk_list) {
            $pipe = $this->multi(Redis::PIPELINE);
            foreach($chunk_list as $uid) {
                $pipe->sRem($redis_key, $uid);
            }
            $replies = $pipe->exec();
            $delete_nums += intval($this->getPipeSuccessNums($replies));
            
            unset($chunk_arr[$key]);
        }
        
        return $delete_nums ? $delete_nums : false;
    }
    
    /**
     * 加载数据
     * @param $class_code
     */
    private function loader($class_code) {
        if(empty($class_code)) {
            return false;
        }
        
        $redis_key = RedisCommonKey::getClassFamilySetKey($class_code);
        
        $GlobalKeys = ClsFactory::Create('RData.GlobalKeys');
        if(!$GlobalKeys->isExists($redis_key)) {
            
            $GlobalKeys->addKey($redis_key);
            
            import('RData.Common.Loader.LoaderClassSet');
            $loaderObject = new loaderClassSet();
            return $loaderObject->load($class_code);
        }
        
        return true;
    }
    
}
