<?php
class ClassphotoAction extends SnsController {
    public function _initialize() {
        parent::_initialize();
    }
    
    public function index() {
        $this->display('index');
    }
}
