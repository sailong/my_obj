<?php
class mBlogPersonRelation extends mBase {
    protected $_dBlogPersonRelation = null;
    
    public function __construct() {
        $this->_dBlogPersonRelation = ClsFactory::Create('Data.Blog.dBlogPersonRelation');
    }
              
    //根据个人关系ID获取信息列表          
    public function getById($ids) {
        return $this->_dBlogPersonRelation->getById($ids);
    }
    //根据client_account获取信息列表
    public function getListByClientAccount($client_account) {
        //三维
        return $this->_dBlogPersonRelation->getListByClientAccount($client_account, 'client_account');
    }
    //添加个人关系
    public function addRelation($data, $is_return_id) {
        return $this->_dBlogPersonRelation->addRelation($data, $is_return_id);
    }
    
    //根据关系ID修改关系信息
    public function modifyById($data, $id) {
        return $this->_dBlogPersonRelation->modifyById($data, $id);
    }
    
    //根据关系ID删除关系信息
    public function delById($id) {
        return $this->_dBlogPersonRelation->delById($id);   
    }
    
    //根据client_account批量删除关系信息
    public function delAllByClientAccount($client_account) {
        //二维
        return $this->_dBlogPersonRelation->delAllByClientAccount($client_account);
    }              
}