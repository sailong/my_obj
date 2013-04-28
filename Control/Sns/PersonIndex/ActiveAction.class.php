<?php
class ActiveAction extends SnsController{
    public function _initialize(){
        parent::_initialize();
    }
    
    public function showActiveInfo(){
        $current_uid = $this->user["client_account"];
        import("@.Control.Api/ActiveApi");
        $ActiveApi = new ActiveApi();
        $active_num = $ActiveApi->client_active($current_uid);
        
        $active_num = reset($active_num);
        //获取每日活跃值
        list($client_log_list, $today_value) = $ActiveApi->client_active_log($current_uid);
        //获取一次性活跃值
        
        list($client_log_list_once, $today_value_once) = $ActiveApi->client_active_log_once($current_uid);
        
        $this->assign("active_num", $active_num["value"] | 0);
        $this->assign("active_log_list", $client_log_list);
        $this->assign("active_log_list_once",$client_log_list_once);
        $this->assign("today_value", $today_value);
        $this->assign("current_uid", $current_uid);
        $this->display("active_info");
    }
    
    public function showActiveRule(){
        $client_type = $this->user['client_type'];
        switch ($client_type) {
            case 0:
                $this->display("active_rule-student");
                break;
            case 1:
                $this->display("active_rule-teacher");
                break;
            case 2:
                $this->display("active_rule-Parents");
                break;
        }
    }
}