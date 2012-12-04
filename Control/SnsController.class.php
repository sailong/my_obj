<?php
class SnsController extends FrontController {
    protected $_checkClsssCode = false;  //是否检查有班级
    
	/*
	 * 构造函数
	 * 用于smarty引入头文件
	 */
	public function __construct() {
		parent::__construct();
		import("@.Control.Insert.InsertSnsFunc", null,".php");
		
		if(method_exists($this,'checkAop')) {
            $this->checkAop();        
        }
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
	
	/*
	 * 判断是否需要检查用户有没有班级
	 */
	public function checkAop() {
	    if($this->_checkClsssCode) {
	        $this->checkClsssCode();
	    }
	}
	
	/*
	 * 检查当前账号是否有班级 
	 */
	protected function checkClsssCode() {
	    $class_code = $this->getClassCode();
	    if(empty($class_code)) {
	        $this->showError("您不属于任何班级!", "/Homeuser/Index/spacehome/spaceid/".$this->getCookieAccount());
	    }
	}
	
    /*
     * 获取并检查当前用户的当前班级
     * 如果没有通过GET或POST方式设置当前班级 
     * 或者该用户不属于设置的班级  默认取所属班级的第一个
     * 
     */
    protected function getClassCode() {

        $class_code = $this->objInput->getInt('class_code');
        if (empty($class_code)) {
            $class_code = $this->objInput->postInt('class_code');
        }
        
        $client_class = array();
        if(!empty($class_code)) {
            $client_class = $this->getClientClass($class_code);  //判断当前用户是否属于当前班级
        }
        //没有设置或者当前用户不属于设置的班级，取第一个班级
        if (empty($client_class)) {
            $client_class = reset($this->user['client_class']); 
        }
        
        $class_code = intval($client_class['class_code']);

        return !empty($class_code) ? $class_code : false;      
    }
    
    /*
     * 获取当前账号的当前班级关系 
     */
    protected function getClientClass($class_code) {
        $user_class_list = $this->user['client_class'];
        if(empty($class_code) || empty($user_class_list)) {
            return false;
        }

        $current_client_class = array();
        foreach($user_class_list as $key=>$client_class) {
            if($class_code == $client_class['class_code']) {
                $current_client_class = $client_class;
                $current_client_class['class_name'] = $this->user['class_info'][$class_code]['class_name'];
                unset($user_class_list);
                break;
            } 
        }

        return !empty($current_client_class) ? $current_client_class : false;
    }
    
	/*
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
