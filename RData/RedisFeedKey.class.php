<?php

/**
 * 管理动态信息相关的key
 * @author Administrator
 */
class RedisFeedKey {
    public static function getFeedAsyncTaskQueueKey() {
        return 'feed:queue';
    }
    
    /**
     * 获取班级动态信息的key
     * @param $class_code
     */
    public static function getClassFeedAllZsetKey($class_code) {
        return str_replace('[class_code]', $class_code, 'feed:cls:[class_code]');   
    }
    
    /**
     * 获取用户的好友的相册动态的key
     * @param $uid
     */
    public static function getUserAblumFeedZsetKey($uid) {
        return str_replace('[client_account]', $uid, 'feed:usr:[client_account]:album');
    }
    
    /**
     * 获取用户的孩子动态信息的key
     * @param $uid
     */
    public static function getUserChildFeedZsetKey($uid) {
        return str_replace('[client_account]', $uid, 'feed:usr:[client_account]:children');
    }
    
    /**
     * 获取用户的全部动态的key
     * @param $uid
     */
    public static function getUserFeedAllZsetKey($uid) {
        return str_replace('[client_account]', $uid, 'feed:usr:[client_account]:all');
    }
    
    /**
     * 获取与我相关的动态信息的key
     * @param $uid
     */
    public static function getUserMyFeedZsetKey($uid) {
        return str_replace('[client_account]', $uid, 'feed:usr:[client_account]:my');
    }
    
}