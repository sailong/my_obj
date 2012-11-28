<?php
import('RData.RedisCommonKey');

class dUserObjectHash extends rBase {
    /**
     * 获取班级的学生集合
     * @param $class_code
     */
    public function getUserObjectHash($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $this->loader($uid);
        
        $redis_key = RedisCommonKey::getUserObjectHashKey($uid);
        $user_datas = $this->hGetAll($redis_key);
        
        return $this->parseDatas($user_datas);
    }
    
    /**
     * 添加班级的学生集合信息
     * @param $class_code
     * @param $student_accounts
     */
    public function addUserObjectHash($uid, $user_datas) {
        if(empty($uid)) {
            return false;
        }
        
        $redis_key = RedisCommonKey::getUserObjectHashKey($uid);
        $user_datas = $this->formatDatas($user_datas);
        
        return $this->hMset($redis_key, (array)$user_datas);
    }
    
    /**
     * 更新用户的数据
     * @param $uid
     * @param $datas
     */
    public function modifyUserObjectHash($uid, $datas) {
        if(empty($uid) || empty($datas) || !is_array($datas)) {
            return false;
        }
        
        $redis_key = RedisCommonKey::getUserObjectHashKey($uid);
        $datas = $this->formatDatas($datas);
        
        return $this->hMset($redis_key, $datas);
    }
    
    /**
     * 格式化hash数据
     * @param $datas
     */
    private function formatDatas($datas) {
        if(empty($datas) || !is_array($datas)) {
            return array();
        }
        
        foreach($datas as $key=>$val) {
            if(is_array($val)) {
                $val = json_encode($val);
            }
            $datas[$key] = $val;
        }
        
        return $datas;
    }
    
    /**
     * 解析获取的数据
     * @param $data
     */
    private function parseDatas($user_datas) {
        if(empty($user_datas)) {
            return false;
        }
        
        foreach($user_datas as $key => $data) {
            $json_decode_data = json_decode($data, true);
            if(!empty($json_decode_data)) {
                $user_datas[$key] = $json_decode_data; 
            } else {
                $user_datas[$key] = $data;
            }
        }
        
        return $user_datas;
    }
    
    /**
     * 加载用户相关的数据
     * @param $uid
     */
    private function loader($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $redis_key = RedisCommonKey::getUserObjectHashKey($uid);
        
        $GlobalKeys = ClsFactory::Create('RData.GlobalKeys');
        if(!$GlobalKeys->isExists($redis_key)) {
            
            $GlobalKeys->addKey($redis_key);
            
            import('RData.Common.Loader.LoaderUserObject');
            $loaderObject = new LoaderUserObject();
        
            return $loaderObject->load($uid);
        }
        
        return true;
    }
    
}
