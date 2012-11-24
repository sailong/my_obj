<?php
class MsgApi extends ApiController{
	public function __construct() {
        parent::__construct();
    }    
    
    public function _initialize(){
		parent::_initialize();    
    }
    
    public function addMsgToRedis($uid, $type, $id) {
		$redis = new Redis();    
		$redis->pconnect('192.168.61.100',6379);
		$json_str = json_encode(array($type => $id));
  		$redis->publish('msg:' . $uid . ":" . $type, $json_str); // send message to channel 2.
  		$redis->close();
	}
}