<?php
import('RData.RedisCommonKey');

class dClassTeacherSet extends rBase {
    /**
     * 获取班级的教师成员集合
     * @param $class_code
     */
    public function getClassTeacherSet($class_code) {
        if(empty($class_code)) {
            return false;
        }
        
        $this->loader($class_code);
        
        $redis_key = RedisCommonKey::getClassTeacherSetKey($class_code);
        
        return $this->sMembers($redis_key);
    }
    
    /**
     * 获取在线的教师信息
     * @param $class_code
     */
    public function getOnlineClassTeacherSet($class_code) {
        if(empty($class_code)) {
            return false;
        }
        
        if(FEED_DEBUG) {
            return $this->getClassTeacherSet($class_code);
        }
        
        $this->loader($class_code);
        
        $redis_key_live_user = RedisCommonKey::getLiveUserSetKey();
        $redis_key_class_teacher = RedisCommonKey::getClassTeacherSetKey($class_code);
        
        return $this->sInter($redis_key_class_teacher, $redis_key_live_user);
    }
    
    /**
     * 添加班级的教师集合
     * @param  $class_code
     * @param  $teacher_accounts
     */
    public function addClassTeacherSet($class_code, $teacher_accounts) {
        if(empty($class_code) || empty($teacher_accounts)) {
            return false;
        }
        
        $teacher_accounts = array_unique((array)$teacher_accounts);
        
        $redis_key = RedisCommonKey::getClassTeacherSetKey($class_code);
        
        $pipe = $this->multi(Redis::PIPELINE);
        foreach($teacher_accounts as $uid) {
            $pipe->sAdd($redis_key, $uid);
        }
        $add_nums = $pipe->exec();
        
        return $add_nums ? $add_nums : false;
    }
    
	/**
     * 删除班级集合中的数据
     * @param $class_code
     * @param $family_accounts
     */
    public function delClassTeacherSet($class_code, $teacher_accounts) {
        if(empty($class_code) || empty($teacher_accounts)) {
            return false;
        }
        
        $teacher_accounts = array_unique((array)$teacher_accounts);
        
        $redis_key = RedisCommonKey::getClassTeacherSetKey($class_code);
        
        $pipe = $this->multi(Redis::PIPELINE);      
        foreach($teacher_accounts as $uid) {
            $pipe->sRem($redis_key, $uid);
        }
        $delete_nums = $pipe->exec();
        
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
        
        $redis_key = RedisCommonKey::getClassTeacherSetKey($class_code);
        
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
