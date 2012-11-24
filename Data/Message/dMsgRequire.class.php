<?php

class dMsgRequire extends dBase{
	protected $_tablename = 'wmw_msg_require';
	protected $_pk = 'req_id';
	protected $_fields = array(
		'req_id',
		'content',
		'to_account',
		'add_account',
		'add_time',
	);
	protected $_index_list = array(
		'req_id',
		'to_account',
	);
	
	public function getMsgRequireByToAccount($to_account) {
		return $this->getInfoByFk($to_account, 'to_account');
	}
	
	public function addMsgRequire($dataarr, $is_return_id) {
		return $this->add($dataarr, $is_return_id);
	}
	
	public function delMsgRequire($msg_id) {
		return $this->delete($msg_id);
	}
}
