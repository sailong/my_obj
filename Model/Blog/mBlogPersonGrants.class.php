<?php
class mBlogPersonGrants extends mBase {
    protected $_dBlogPersonGrants = null;
    
    public function __construct() {
        $this->_dBlogPersonGrants = ClsFactory::Create('Data.Blog.dBlogPersonGrants');
    }          
              
    //根据个人日志权限ID获取信息列表          
    public function getById($ids) {
        return $this->_dBlogPersonGrants->getById($ids);
    }
    //根据client_account获取信息列表
    public function getListByClientAccount($client_account) {
        //三维
        return $this->_dBlogPersonGrants->getListByClientAccount($client_account, 'client_account');
    }
    //添加日志权限
    public function addGrant($data, $is_return_id) {
        return $this->_dBlogPersonGrants->addGrant($data, $is_return_id);
    }
    
    //根据个人权限ID修改信息
    public function modifyById($data, $id) {
        return $this->_dBlogPersonGrants->modifyById($data, $id);
    }
    
    //根据权限ID删除信息
    public function delById($id) {
        return $this->_dBlogPersonGrants->delById($id);   
    }
    
    //根据client_account批量删除信息
    public function delAllByClientAccount($client_account) {
        if(empty($client_account)) {
            return false;
        }
        //二维
        return $this->_dBlogPersonGrants->delAllByClientAccount($client_account);
    }      
    //根据blog_id批量删除信息
    public function delAllByBlogId($blog_id) {
        if(empty($blog_id)) {
            return false;
        }
        
        //二维
        return $this->_dBlogPersonGrants->delAllByBlogId($blog_id);
    }                                                         
}