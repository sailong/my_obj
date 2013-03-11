<?php
class IndexAction extends SnsController {

    public function _initialize(){
        parent::_initialize();
    }
    
    public function index() {
        $current_uid = $this->user['client_account'];
        
        $this->display("main_first");
    }
}