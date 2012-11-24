<?php
class SnsController extends FrontController {
	/*
	 * 构造函数
	 * 用于smarty引入头文件
	 */
	public function __construct() {
		parent::__construct();
		import("@.Control.Insert.InsertSnsFunc", null,".php");
	}
	
	/**
	 * 初始化Sns当前登录的用户信息
	 */
	protected function initCurrentUser() {
	    $uid = $this->getCookieAccount();
        
        $mUser = ClsFactory::Create('Model.mUser');
        $userlist = $mUser->getUserByUid($uid);
        
        $this->user = & $userlist[$uid];
	}
	
	/**
	 * 获取统一注销地址
	 */
	protected function getLogoutUrl() {
	   return $this->uc_client->get_uc_logout_url($this->appName);
	}	
	
    protected function getSuccessTplFile() {
        return WEB_ROOT_DIR . "/View/Template/Public/wmw_success_tips.html";
    }
    
    protected function getErrorTplFile() {
        return WEB_ROOT_DIR . "/View/Template/Public/wmw_error_tips.html";
    }
    
    protected function append_success_assign() {
        $this->assign('pathImg', IMG_SERVER."/Public/images/new/success.gif");
    }
    
    protected function append_error_assign() {
        $this->assign('pathImg', IMG_SERVER."/Public/images/new/error.jpg");
    }
    
}
