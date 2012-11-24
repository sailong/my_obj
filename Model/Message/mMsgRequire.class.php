<?php

class mMsgRequire extends mBase{
	protected $_dMsgRequire = null;
	public function __construct() {
		$this->_dMsgRequire = ClsFactory::Create("Data.Message.dMsgRequire");
	}
	
	public function getMsgRequireByToAccount($to_account) {
		if(empty($to_account)){
			return false;
		}
		
		return $this->_dMsgRequire->getMsgRequireByToAccount($to_account);
	}
	
	public function addMsgRequire($dataarr, $is_return_id) {
		if(empty($dataarr) || !is_array($dataarr)) {
			return false;
		}
		
		return $this->_dMsgRequire->addMsgRequire($dataarr, $is_return_id);
	}
	
	public function delMsgRequire($msg_id) {
		if(empty($msg_id) || is_array($msg_id)) {
			return false;
		}
		
		return $this->_dMsgRequire->delMsgRequire($msg_id);
	}
	
	public function delMsgRequireForMe($to_account) {
		if(empty($to_account)) {
			return false;
		}
		
		$msg_list = $this->getMsgRequireByToAccount($to_account);
		if(empty($msg_list)) {
			$msg_arr = $msg_list[$to_account];
			foreach($msg_arr as $msg_id => $msg_info) {
				$this->delMsgRequrie($msg_id);
			}
		}
	}
}