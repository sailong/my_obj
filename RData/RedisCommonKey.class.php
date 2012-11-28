<?php
/**
 * 管理通用的rediskey信息
 * @author Administrator
 * 注明：
 * 1. 因为在不同rdata文件中可能涉及到集合求交集的情况，因此需要key的共享和集中处理
 */
class RedisCommonKey {
    /**
     * 获取在线用户
     */
    public static function getLiveUserSetKey() {
        return "live:usr";
    }
    
    /**
     * 获取班级学生
     * @param $class_code
     */
    public static function getClassStudentSetKey($class_code) {
        return str_replace("[class_code]", $class_code, "cls:[class_code]:student");
    }
    
    /**
     * 获取班级老师
     * @param $class_code
     */
    public static function getClassTeacherSetKey($class_code) {
        return str_replace("[class_code]", $class_code, "cls:[class_code]:teacher");
    }
    
    /**
     * 获取班级家长
     * @param $class_code
     */
    public static function getClassFamilySetKey($class_code) {
        return str_replace("[class_code]", $class_code, "cls:[class_code]:family");
    }
    
    /**
     * 获取用户孩子
     * @param $class_code
     */
    public static function getUserChildrenSetKey($uid) {
        return str_replace("[client_account]", $uid, "usr:[client_account]:children");
    }
    
    /**
     * 获取用户好友
     * @param $class_code
     */
    public static function getUserFriendSetKey($uid) {
        return str_replace("[client_account]", $uid, "usr:[client_account]:friend");
    }
    
    /**
     * 获取用户家长
     * @param $class_code
     */
    public static function getUserParentSetKey($uid) {
        return str_replace("[client_account]", $uid, "usr:[client_account]:parent");
    }
    
   /**
     * 获取用户对象
     * @param $class_code
     */
    public static function getUserObjectHashKey($uid) {
        return str_replace("[client_account]", $uid, "usr:[client_account]:obj");
    }
    
    /**
     * 获取redis的全部key信息
     */
    public static function getGlobalKeyPre() {
        return "global_keys:";
    }
}