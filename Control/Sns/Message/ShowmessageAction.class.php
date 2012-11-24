<?php

class ShowmessageAction extends SnsController{
	public $_isLoginCheck = false;
	
	public function _initialize() {
        parent::_initialize();
    }
    
	public function index(){
		$this->display('home');
	}
	
	public function addMsg() {
		import('@.Control.Api.MsgApi');
		$msgApi = new MsgApi();
		$msgApi->addMsgToRedis('11070004','BJGG',array(1,2,3,4,5,6));
	}
}
