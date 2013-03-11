<?php
class mBlogPersonType extends mBase {
    protected $_dBlogPersonType = null;
    
    public function __construct() {
        $this->_dBlogPersonType = ClsFactory::Create('Data.Blog.dBlogPersonType');
    }
              
    //根据个人关系ID获取信息列表          
    public function getById($ids) {
        return $this->_dBlogPersonType->getById($ids);
    }
    //根据client_account获取信息列表
    public function getListByClientAccount($client_account) {
        //三维
        return $this->_dBlogPersonType->getListByClientAccount($client_account, 'client_account');
    }
    //添加个人关系
    public function addTypeRel($data, $is_return_id) {
        return $this->_dBlogPersonType->addTypeRel($data, $is_return_id);
    }
    
    //根据关系ID修改关系信息
    public function modifyById($data, $id) {
        return $this->_dBlogPersonType->modifyById($data, $id);
    }
    
    //根据关系ID删除关系信息
    public function delById($id) {
        return $this->_dBlogPersonType->delById($id);   
    }
    
    //根据client_account批量删除关系信息
    public function delAllByClientAccount($client_account) {
        //二维
        return $this->_dBlogPersonType->delAllByClientAccount($client_account);
    }              
}