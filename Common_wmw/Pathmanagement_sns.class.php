<?php
import("@.Common_wmw.Pathmanagement");
class Pathmanagement_sns extends Pathmanagement {
                    
    private static $photo_pic = 'photo_pic/';
    private static $talk = 'talk/';                     //得到用户说说图片路径
    private static $work = 'work/';                     //作业上传路径
    private static $jsupload = 'jsupload/';             //js上传路径
    private static $tmp = 'tmp/';                       //说说临时目录
    private static $resouce = 'uploads/';               //用户上传资源的目录
    
    /** 获取用户头像显示路径
	 * $photoname 数据库存储的头像名称
	 * $account 用户账号
	 * @return 头像显示路径
	*/
     public static function getHeadImg($account) {
         return '/' . self::getAttachment() . self::$head_pic . $account.'/';
     }
     
	/** 获取用户头像显上传路径
	 * $photoname 数据库存储的头像名称
	 * $account 用户账号
	 * @return 头像显示路径
	*/
     public static function uploadHeadImg() {
         return self::getWebroot() . self::getAttachment() . self::$head_pic;
     }

	/** 获取用户相册图片路径
	 * $account 用户账号
	 * @return 相册封面显示路径
	*/
	public static function getAlbum($account){
		return '/' . self::getAttachment() . self::$photo_pic . $account.'/';
	}
	
	/** 获取用户相册图片上传路径
	 * @return 用户图片路径
	*/
	public static function uploadAlbum($account){
		return self::getWebroot() . self::getAttachment() . self::$photo_pic . $account;
	}
	
	/**
	 * 得到用户说说图片路径
	 * @return String
	 */
	public static function getTalkIco() {
	    return '/' . self::getAttachment() . self::$talk;
	}
	/**
	 * 得到用户说说图片上传路径
	 * @return String
	 */
	public static function uploadTalkIco() {
	    return self::getWebroot() . self::getAttachment() . self::$talk;
	}
	/**
	 * 得到作业的上传路径
	 * @return String
	 */
	public static function uploadHomeWork() {
	    return self::getWebroot() . self::getAttachment() . self::$work;
	}
	
	/**
	 * 得到用户说说图片临时路径
	 * @return String
	 */
	public static function getTalktmp() {
	    return '/' . self::getAttachment() . self::$talk . self::$tmp;
	}
	
	/**
	 * 得到用户说说图片上传临时路径
	 * @return String
	 */
	public static function uploadTalktmp() {
	    return self::getWebroot() . self::getAttachment() . self::$talk . self::$tmp;
	}
	
	/**
	 * 获取显示相册图片路径
	 */
	public static function getjsupload() {
	    return '/' . self::getAttachment() . self::$jsupload;
	}
	
	/**
	 * 获取上传相册图片路径
	 */
	public static function uploadjsupload() {
	    return self::getWebroot() . self::getAttachment() . self::$jsupload;
	}
	
	 /**
     * 获取用户上传文件路径
     */
    public static function uploadResource() {
        return self::getWebroot() . self::getAttachment() . self::$resouce;
    }
    
    public function getResource() {
        return "/" . self::getAttachment() . self::$resouce;
    }
}