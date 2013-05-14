<?php
include_once(dirname(dirname(dirname(__FILE__))) . '/Daemon.inc.php');

//老师
$modules = array(
                 101 => 21, //激活账号
                 102 => 19, //登录
                 103 => 20, //签到
                 201 => 1,  //班级公告
                 202 => 2,  //班级作业
                 203 => 3,  //班级成绩
                 204 => 4,  //班级照片
                 205 => 5,  //班级日志
                 206 => 6,  //班级说说
                 207 => 7,  //评论
                 302 => 9,  //个人说说
                 303 => 10, //个人日志
                 304 => 11, //个人相册
                 305 => 7,  //个人空间评论
                 307 => 14, //个人资料 上传头像(+1)
                 308 => 15, //个人资料 完善
                 309 => 18, //个人资料 设置邮箱(+1)                 
                 402 => 13  //资源
                 );

$uid = 98302504;
foreach ($modules as $module => $action) {
    $i = 1;
    while ($i <=10 ) {
        $param_list = array(
            "module" => $module,
            "action" => $action,
            "uid" => $uid,
        );
        
        
        $param_list = serialize($param_list);
        
        $result = Gearman::send('sns_active', $param_list, PRIORITY_NORMAL, false);
        
        $i++;
    }
}


//学生
$modules = array(
                101 => 21, //激活账号
                102 => 19, //登录
                103 => 20, //签到
                201 => 22, //班级公告
                202 => 23, //班级作业
                203 => 24, //班级成绩
                204 => 4,  //班级照片
                205 => 5,  //班级日志
                206 => 6,  //班级说说
                207 => 7,  //班级评论
                302 => 9,  //个人说说
                303 => 10, //个人日志
                304 => 11, //个人相册
                305 => 7,  //个人空间评论
                307 => 14, //个人资料 上传头像(+1)
                308 => 15, //个人资料 完善
                309 => 18, //个人资料 设置邮箱(+1)                 
                402 => 13  //资源
        );

$student_uid = 12060013;
foreach ($modules as $module => $action) {
    $i = 1;
    while ($i <=10 ) {
        $param_list = array(
            "module" => $module,
            "action" => $action,
            "uid" => $student_uid,
        );

        $param_list = serialize($param_list);

        $result = Gearman::send('sns_active', $param_list, PRIORITY_NORMAL, false);
        
        $i++;
    }
}


//家长

$modules_1 = array(
                    101 => 21, //激活账号
                    102 => 19, //登录
                    103 => 20, //签到
                    201 => 22, //班级公告
                    202 => 23, //班级作业
                    203 => 24, //班级成绩
                    302 => 9,  //个人说说
                    303 => 10, //个人日志
                    304 => 11, //个人相册
                    305 => 7,  //个人空间评论
                    307 => 14, //个人资料 上传头像(+1)
                    308 => 15, //个人资料 完善
                    309 => 18, //个人资料 设置邮箱(+1)
                    402 => 13  //资源
        );
$family_uid = 83347920;
foreach ($modules_1 as $module => $action) {
//    print_r("module = $module  ====== action = $action \n");
    $i = 1;
    while ($i <=2 ) {
        $param_list = array(
            "module" => $module,
            "action" => $action,
            "uid" => $family_uid,
        );
        
        
        $param_list = serialize($param_list);
        
        $result = Gearman::send('sns_active', $param_list, PRIORITY_NORMAL, false);
        
        $i++;
    }
}
