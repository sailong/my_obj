<?php
class mClassHomeworkSend extends mBase{
    protected $_dClassHomeworkSend = null;
	
	public function __construct() {
		$this->_dClassHomeworkSend = ClsFactory::Create('Data.dClassHomeworkSend');
	}
    
	//添加作业发布对象批量
	public function addHomeworkSend($dataarr) {
	    if(empty($dataarr)) {
	        return false;
	    }
	    
	    $resault = $this->_dClassHomeworkSend->addBat($dataarr);
	    return !empty($resault) ? $resault : false;
	}
	
	
	//修改作业对象
	public function modifyHomeworkSend($datas, $id) {
	    if(empty($datas) || empty($id)) {
	        return false;
	    }
	    
	    $resault = $this->_dClassHomeworkSend->modifyHomeworkSend($datas,$id);
	    return !empty($resault) ? $resault : false;
	}
	
	
	//删除班级对象
	public function delHomeworkSend($id) {
	    if(empty($id)) {
	        return false;
	    }
	    
	    $resault = $this->_dClassHomeworkSend->delHomeworkSend($id);
	    
	    return !empty($resault) ? $resault : false;
	}
	
	//根据作业id查询发送对象信息
	public function getHomeworkSendByhomeworkid($homeworkid) {
	    if(empty($homeworkid)) {
	        return false;
	    }
	    
	    $resault = $this->_dClassHomeworkSend->getHomeworkSendByhomeworkid($homeworkid);
	    
	    return !empty($resault) ? $resault : false;
	}
	
}