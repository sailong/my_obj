<?php
import('RData.RedisCommonKey');

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
        
        if(FEED_DEBUG) {
            return $this->getClassStudentSet($class_code);
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
        
        $student_accounts = array_unique((array)$student_accounts);
        $chunk_arr = array_chunk($student_accounts, 200, true);
        unset($student_accounts);
        
        $redis_key = RedisCommonKey::getClassStudentSetKey($class_code);
        
        $add_nums = 0;
        foreach($chunk_arr as $key=>$chunk_list) {
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
    public function delClassStudentSet($class_code, $student_accounts) {
        if(empty($class_code) || empty($student_accounts)) {
            return false;
        }
        
        $student_accounts = array_unique((array)$student_accounts);
        $chunk_arr = array_chunk($student_accounts, 200, true);
        unset($student_accounts);
        
        $redis_key = RedisCommonKey::getClassStudentSetKey($class_code);
        
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
        
        $redis_key = RedisCommonKey::getClassStudentSetKey($class_code);
        
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
