<?php
include_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/Daemon.inc.php');

//* 注意类名与文件名保持一致，必须以小写开头
class del_photo_entity extends BackGroundController {
    
    
    public function run($job, &$log) {

        $workload = $job->workload();
        $photo_path = $workload;  // as client_account
        //1. 做路径验证的校验
        //2. 删除
        echo $photo_path;
        
        $log[] = "Success";
        
        return "del_photo_entity Success";

    }

}


?>