<?php
include_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/Daemon.inc.php');

class sns_active extends BackGroundController {

    public function run($job, &$log) {
        $workload = $job->workload();
        $workload = unserialize($workload);
        
        $uid = $workload['uid'];
        $module = $workload['module'];
        $action = $workload['action'];
        
        if (empty($uid) || empty($module) || empty($action)) {
            return false;
        }
        
        //先判断是否超过当天上限值
        if($this->isDailyLimit($uid, $module, $action)) {
            //加入活跃度值
            $this->addActiveinfo($uid, $module, $action);
            
            // 班级成员活跃度，关联的班主任也增加
            $this->addHeaderActiveInfo($uid, $module, $action);
        }
        
        return true;
    }
    
    /**
     * 判断该用户是否已经到达当然上限，
     * @param String $module   查看Config SnsAction config.php
     * @param String $action   查看Config SnsAction config.php
     * $return boolean
     */
    private function isDailyLimit($uid, $module, $action) {
                
        $time = strtotime(date('Y-m-d'));
        $mActiveLog = ClsFactory::Create("Model.Active.mActiveLog");
        
        $active_config = $this->getActiveConfig();
        //查看是否是一次性加分配置
        $is_once = $active_config['module'][$module][$action]['is_once'];
        
        //一次性加分，则看是否已经加过，有记录则不需要在加分了
        if (!empty($is_once)) {
            $ActiveLog_list = $mActiveLog->getActive($uid, $module, $action);
            if (empty($ActiveLog_list)) {
                return true;
            }
            
            return false;
        }
        
        //不是一次性加分，则出现情况为：
        //1. 当天有无记录，无记录可以执行
        //2. 当天有记录，再判断是否超过当天上限值
        $ActiveLog_list = $mActiveLog->getActive($uid, $module, $action, $time);
        if (empty($ActiveLog_list)) {
            return true;
        }        
        
        //查找配置每天上限值
        $day_limit = $active_config['module'][$module][$action]['day_limit'];
        if (empty($day_limit)) $day_limit = 0;
        $max = 0;
        foreach ($ActiveLog_list as $key => $value) {
            if (isset($value['value'])) {
                $max += (int)$value['value'];
            }
        }

        return $max >=  (int)$day_limit ? false : true;
    }    
    
    /**
     * 添加用户的活跃度
     * @param string $uid     用户帐号
     * @param string $module  查看Config SnsAction config.php
     * @param string $action  查看Config SnsAction config.php
     */
    private function addActiveinfo($uid, $module, $action){
        $active_config = $this->getActiveConfig();
//        print_r("\n addActiveinfo \n");
//        print_r("uid = $uid \n");
//        print_r("module = $module \n");
//        print_r("action = $action \n");
        //加分值
        $value = $active_config['module'][$module][$action]['value'];
        
//        print_r("value = $value \n");
        if (empty($value)) {
            return false;
        }
        
        //加分描述
        $msg = $active_config['module'][$module]['msg'];
        
        $ative_info = array(
            'client_account' => $uid,
            'value' => $value,
            'message' => $msg,
            'add_time' => time(),
            'module' => $module,
            'action' => $action,
        );
        
//        print_r($active_info);

        $mActiveLog = ClsFactory::Create("Model.Active.mActiveLog");
        $active_log_id = $mActiveLog->addActiveLog($ative_info, true);
//        print_r("active_log_id = $active_log_id \n");
        if (empty($active_log_id)) {
            return false;
        }
        
        $mActive = ClsFactory::Create("Model.Active.mActive");
        $active_result = $mActive->getActiveByClientAccount($uid);
        if(!empty($active_result)){
            $active_result = reset($active_result);
            $active_id = key($active_result);
            $active_info = array(
                'client_account' => $uid,
                'value' => "%value+$value%"
            );
            
            $mActive->modifyActive($active_info, $active_id);
        } else {
            $active_info = array(
                'client_account' => $uid,
                'value' => $value,
            );
            
            $active_id = $mActive->addActive($active_info, true);
//            print_r("active_id = $active_id \n");
            //如果添加不成功，则删除activelog 表记录
            if(empty($active_id)) {
                $mActiveLog->delActiveLog($active_log_id);
            }
        }
        
    }
        
    
    /**
     * 学生操作活跃度加分，关联的班主任也相应加分，设计操作是  激活帐号和完善个人资料
     * @param string $uid     用户帐号
     * @param string $module  查看Config SnsAction config.php
     * @param string $action  查看Config SnsAction config.php
     */
    private function addHeaderActiveInfo($uid, $module, $action) {
//        print_r("\n addHeaderActiveInfo \n");
        $mUserVm = ClsFactory::Create("RModel.mUserVm");
        $current_user_info = $mUserVm->getUserBaseByUid($uid);
        $client_type = $current_user_info[$uid]['client_type'];
//        print_r("client_type = $client_type \n");
        //不是老师操作
        if($client_type == 1) {
            return false;
        }
        
        //可以关联加分的模块： 301 完善个人资料
        $can_modules = array(101=>'激活帐号', 301=>'完善个人资料');
        
        //可以关联加分的操作: 21 激活帐号
        $can_actions = array(21=>'激活帐号');
//        print_r(" isset action =  " .isset($can_actions[$action]) . "\n");
//        print_r(" isset module =  " .isset($can_modules[$module]). "\n");
        if ( isset($can_actions[$action]) || isset($can_modules[$module]) ) {
            $class_code = key($current_user_info[$uid]["client_class"]);
//            print_r("class_code = $class_code \n");
            //找到班主任帐号
            $mClassInfo = ClsFactory::Create("RModel.Common.mHashClass");
            $ClassInfo = $mClassInfo->getClassById($class_code);
            $headteacher_account = $ClassInfo['headteacher_account'];
//            print_r("headteacher_account = $headteacher_account \n");
            
            $module = $module == 301 ? 307 :$module;        
            $action = $action == 21 && $client_type == 0 ? 25 : $action;
            $action = $action == 21 && $client_type == 1 ? 26 : $action;
    
            $this->addActiveinfo($headteacher_account, $module, $action);
        }
    }

    /**
     * 得到用户活跃度的配置
     */
    private function getActiveConfig(){
        C(include_once WEB_ROOT_DIR . '/Config/SnsActive/config.php');
        $active_config_action = C('action');
        $active_config_module = C('module');
        return array('action' => $active_config_action, 'module' => $active_config_module);
    }

}