<?php
import('@.RData.RedisCommonKey');

class dClassStudentSet extends rBase {
    /**
     * 获取班级的学生集合
     * @param $class_code
     */
    public function getClassStudentSet($class_code) {
        if(empty($class_code)) {
            return false;
        }
        
        $this->loader($class_code);
        
        $redis_key = RedisCommonKey::getClassStudentSetKey($class_code);
        
        return $this->sMembers($redis_key);
    }
    
    /**
     * 获取在线的学生集合信息
     * @param $class_code
     */
    public function getOnlineClassStudentSet($class_code) {
        if(empty($class_code)) {
            return false;
        }
        
        $this->loader($class_code);
        
        $redis_key_live_user = RedisCommonKey::getLiveUserSetKey();
        $redis_key_class_student = RedisCommonKey::getClassStudentSetKey($class_code);
        
        return $this->sInter($redis_key_class_student, $redis_key_live_user);
    }
    
    /**
     * 添加班级的学生集合信息
     * @param $class_code
     * @param $student_accounts
     */
    public function addClassStudentSet($class_code, $student_accounts) {
        if(empty($class_code) || empty($student_accounts)) {
            return false;
        }
        
        $redis_key = RedisCommonKey::getClassStudentSetKey($class_code);
        
        $add_nums = 0;
        foreach((array)$student_accounts as $uid) {
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
    public function delClassStudentSet($class_code, $student_accounts) {
        if(empty($class_code) || empty($student_accounts)) {
            return false;
        }
        
        $redis_key = RedisCommonKey::getClassStudentSetKey($class_code);
        
        $delete_nums = 0;        
        foreach((array)$student_accounts as $uid) {
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
        if(empty($class_code) || $this->isExistClassStudentSet($class_code)) {
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
    private function isExistClassStudentSet($class_code) {
        if(empty($class_code)) {
            return false;
        }
        
        $redis_key = RedisCommonKey::getClassStudentSetKey($class_code);
        $keys = $this->keys($redis_key);
        
        return !empty($keys) ? true : false;
    }
}
