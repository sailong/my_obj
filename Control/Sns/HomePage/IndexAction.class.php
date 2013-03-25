<?php
class IndexAction extends SnsController{
    public function _initialize(){
        parent::_initialize();
    }
    
    public function index() {
        
        $this->display("main");    
    }
}