<?php
return array(
    'action' => array(
        1    => '发布公告(+5)',
        2    => '发布班级作业(+3)',
        3    => '发布班级成绩(+10)',
        4    => '上传班级照片(+2)',
        5    => '发表班级日志(+2)',
        6    => '发表班级说说(+2)',
        7    => '发表评论（说说、相册、日志）(+1)',
//      8    => '分享（说说、相册、日志）(+1)',
        9    => '发表个人说说(+2)',
        10   =>	'发表个人日志(+2)',
        11   =>	'上传个人照片(+2)',
//      12   => '发布圈圈话题(+5)',
        13   => '查看学习资源(+1)',
        14   => '上传头像(+1)',
        15   => '完善性别(+1) 完善血型(+1) 填写生日(+1)',
//      16   => '完善血型(+1)',
//      17   => '填写生日(+1)',
        18   => '设置邮箱(+1)',
        19   => '每日登录(+5)',
        20   => '每日签到(+5)',
        21   => '激活账号(+5)',
        22   => '查看班级公告(+5)',
        23   => '查看班级作业(+5)',
        24   => '查看班级成绩(+5)',
        25   => '学生激活账号(+5)',
        26   => '父/母激活账号(+5)',
    ),
    
    //一次性加分
    'active_once'=>array(101 => 21, 307 => 14, 308 => 15, 309 => 18),
    
    'active_list'=>array(
        //学生
        0 => array(
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
        ),
        
        //老师
        1 => array(
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
        ),
        
        //家长
        2 => array(
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
        ),
        
        //学生与家长操作关联班主任操作
        // 格式为  module => ( client_type => array(关联操作module => 关联操作action);
        'header_teacher' => array(
            101 => array(0=> array(101 => 25), 2=> array(101 => 26)),
            307 => array(0=> array(310 => 14), 2=> array(310 => 14)),
            308 => array(0=> array(311 => 15), 2=> array(311 => 15)),
            309 => array(0=> array(312 => 18), 2=> array(312 => 18))
        ),
    ),
    
    'module' => array(
        101 => array(
        	'msg' => '激活账号',
            21=>array('value' => 5, 'day_limit' => 5, 'is_once' => true),
            25=>array('value' => 5, 'day_limit' => 5, 'is_once' => true),
            26=>array('value' => 5, 'day_limit' => 5, 'is_once' => true),
        ),
        
        102 => array(
        	'msg' => '登录',
        	19=>array('value' => 5, 'day_limit' => 5, 'is_once' => false),

        ),
        
        103 => array(
        	'msg' => '签到',
        	20=>array('value' => 5, 'day_limit' => 5, 'is_once' => false),
        ),
        
//        301 => array(
//        	'msg' => '个人资料',
//        	14=>array('value' => 1, 'day_limit' => 1, 'is_once' => true),
//            15=>array('value' => 1, 'day_limit' => 1, 'is_once' => true),
//            16=>array('value' => 1, 'day_limit' => 1, 'is_once' => true),
//            17=>array('value' => 1, 'day_limit' => 1, 'is_once' => true),
//            18=>array('value' => 1, 'day_limit' => 1, 'is_once' => true),
//        ),

        201 => array(
        	'msg' => '班级公告',
        	1 =>array('value' => 5, 'day_limit' => 15, 'is_once' => false),
            22=>array('value' => 5, 'day_limit' => 15, 'is_once' => false),
        ),
        
        202 => array(
        	'msg' => '班级作业',
        	2 =>array('value' => 3, 'day_limit' => 15, 'is_once' => false),
            23=>array('value' => 3, 'day_limit' => 15, 'is_once' => false),
        ),
        
        203 => array(
        	'msg' => '班级成绩',
        	3 =>array('value' => 10, 'day_limit' => 10, 'is_once' => false),
            24=>array('value' => 5, 'day_limit'=> 5, 'is_once' => false),  
        ),
        
        204 => array(
        	'msg' => '班级相册',
        	4 =>array('value' => 2, 'day_limit' => 10, 'is_once' => false),
        ),
        
        205 => array(
        	'msg' => '班级日志',
        	5=>array('value' => 2, 'day_limit' => 10, 'is_once' => false),
        ),
        
        206 => array(
        	'msg' => '班级说说',
        	6=>array('value' => 2, 'day_limit' => 10, 'is_once' => false),
        ),
        
        207 => array(
        	'msg' => '班级评论',
        	7=>array('value' => 1, 'day_limit' => 5, 'is_once' => false),
        ),
        
//        208 => array(
//        	'msg'      => '班级分享',
//        	8=>array('value' => 1, 'day_limit' => 5, 'is_once' => false),
//        ),

        302 => array(
        	'msg' => '个人说说',
        	9 =>array('value' => 2, 'day_limit' => 10, 'is_once' => false),
        ),
        
        303 => array(
        	'msg' => '个人日志',
        	10=>array('value' => 2, 'day_limit' => 10, 'is_once' => false),
        ),
        
        304 => array(
        	'msg' => '个人相册',
        	11=>array('value' => 2, 'day_limit' => 10, 'is_once' => false),
        ),
        
        305 => array(
        	'msg' => '个人空间评论',
        	7=>array('value' => 1, 'day_limit' => 5, 'is_once' => false),
        ),
        
//        306 => array(
//        	'msg' => '个人空间分享',
//        	8=>array('value' => 1, 'day_limit' => 5, 'is_once' => false),
//        ),

        307 => array(
        	'msg' => '上传头像',
        	14=>array('value' => 1, 'day_limit' => 1, 'is_once' => true), 
        ),

        308 => array(
        	'msg' => '完善个人资料',
        	15=>array('value' => 3, 'day_limit' => 3, 'is_once' => true), 
        ), 

        309 => array(
        	'msg' => '设置邮箱',
        	18=>array('value' => 1, 'day_limit' => 1, 'is_once' => true), 
        ),        

        310 => array(
        	'msg' => '班级成员上传头像',
        	14=>array('value' => 1, 'day_limit' => 1, 'is_once' => true), 
        ),

        311 => array(
        	'msg' => '班级成员完善个人资料',
        	15=>array('value' => 3, 'day_limit' => 3, 'is_once' => true), 
        ), 

        312 => array(
        	'msg' => '班级成员设置邮箱',
        	18=>array('value' => 1, 'day_limit' => 1, 'is_once' => true), 
        ),
//        401 => array(
//            'msg' => '圈圈话题',
//        	12=>array('value' => 5, 'day_limit' => 10, 'is_once' => false),
//        ),

        402 => array(
            'msg' => '学习资源',
        	13=>array('value' => 1, 'day_limit' => 5, 'is_once' => false),
        ),
    ),
);