<?php
class dBlogPersonRelation extends dBase {
    protected $_pk = 'id';
    protected $_tablename = 'wmw_blog_person_relation';
    protected $_fields = array(
                    'id',
                    'client_account',
                    'blog_id',
              );
    protected $_index_list = array(
                    'id',
                    'client_account',
                    'blog_id',
              );
              
    //根据个人关系ID获取信息列表          
    public function getById($ids) {
        return $this->getInfoByPk($ids);
    }
    //根据client_account获取信息列表
    public function getListByClientAccount($client_account) {
        //三维
        return $this->getInfoByFk($client_account, 'client_account');
    }
    //添加个人关系
    public function addRelation($data, $is_return_id) {
        return $this->add($data, $is_return_id);
    }
    
    //根据关系ID修改关系信息
    public function modifyById($data, $id) {
        return $this->modify($data, $id);
    }
    
    //根据关系ID删除关系信息
    public function delById($id) {
        return $this->delete($id);   
    }
    
    //根据client_account批量删除关系信息
    public function delAllByClientAccount($client_account) {
        if(empty($client_account)) {
            return false;
        }
        
        $client_account_str = implode(',', (array)$client_account);
        $sql = "delete from {$this->_tablename} where client_account in({$client_account_str})";
        //二维
        return $this->execute($sql);
    }              
}