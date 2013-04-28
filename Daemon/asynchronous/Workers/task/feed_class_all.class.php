<?php

/**
 * 班级全部动态
 * @author guoxuewen
 *
 */
class feed_class_all {
    
    /**
     * 
     * @param int $class_code
     * @param int $feed_id
     */
    public function run($uid, $class_code, $feed_id){
        if (empty($class_code) || empty($feed_id)) {
            if(C('LOG_RECORD')) Log::write('task UserLastLoginTime run error:',  "class_code OR feed_id is null", Log::ERR);
            return false;
        }

        $RM = ClsFactory::Create("RModel.Feed.mZsetClassAll");
        $RM->setFeed($class_code, $feed_id, $feed_id);
        file_put_contents('/tmp/feed-debug.txt', "class_code111 = $class_code \n", FILE_APPEND);
        
        
        //发送到班级成员的user_all 动态
        $mSetClassStudent = ClsFactory::Create('RModel.Common.mSetClassStudent');
	    $mSetClassTeacher = ClsFactory::Create('RModel.Common.mSetClassTeacher');
	    $mSetClassFamily = ClsFactory::Create('RModel.Common.mSetClassFamily');
        
	    $students = $mSetClassStudent->getClassStudentById($class_code);
        $teachers = $mSetClassTeacher->getClassTeacherById($class_code);
        $parents = $mSetClassFamily->getClassFamilyById($class_code);
        if (empty($students)) $students = array();
        if (empty($teachers)) $teachers = array();
        if (empty($parents))  $parents = array();

        $all_uids = array();
        $all_uids = array_merge($all_uids, $students);
        $all_uids = array_merge($all_uids, $teachers);
        $all_uids = array_merge($all_uids, $parents);	    
//        file_put_contents('/tmp/feed-debug.txt', "all uids \n", FILE_APPEND);
//        file_put_contents('/tmp/feed-debug.txt', print_r($all_uids, true), FILE_APPEND);
        $RM = ClsFactory::Create("RModel.Feed.mZsetUserAll");
              
        foreach ($all_uids as $uid) {
            file_put_contents('/tmp/feed-debug.txt', "uid = $uid", FILE_APPEND);
            $RM->setFeed($uid, $feed_id, $feed_id);  
        }

        return true;
    }
}