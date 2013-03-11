<?php
class mBlogClassGrants extends mBase {
    protected $_dBlogClassGrants = null;
    
    public function __construct() {
        $this->_dBlogClassGrants = ClsFactory::Create('Data.Blog.dBlogClassGrants');
    }
    
    //根据班级日志权限ID获取信息列表          
    public function getById($ids) {
        return $this->_dBlogClassGrants->getById($ids);
    }
    //根据class_code获取信息列表
    public function getListByClassCode($class_code) {
        //三维
        return $this->_dBlogClassGrants->getListByClassCode($class_code);
    }
    //根据blog_id获取信息列表
    public function getListByBlogId($blog_id, $class_code) {
        //二维
        $grantInfo = $this->_dBlogClassGrants->getListByBlogId($blog_id, $class_code);
        if(empty($grantInfo)) {
            return false;
        }
        $grant_arr = array();
        foreach($grantInfo as $key=>$val) {
            $grant_arr[$val['blog_id']] = $val;
            unset($key);
        }
        return $grant_arr;
    }
    //添加班级日志权限
    public function addGrant($data, $is_return_id) {
        return $this->_dBlogClassGrants->addGrant($data, $is_return_id);
    }
    
    //根据权限ID修改信息
    public function modifyBlogClassGrants($data, $id) {
        return $this->_dBlogClassGrants->modifyBlogClassGrants($data, $id);
    }
    
    //根据权限ID删除信息
    public function delById($id) {
        return $this->_dBlogClassGrants->delById($id);   
    }
    
    //根据class_code批量删除信息
    public function delAllByClassCode($class_code) {
        if(empty($class_code)) {
            return false;
        }
        
        return $this->_dBlogClassGrants->delAllByClassCode($class_code);
    }
    //根据blog_id删除信息
    public function delGrantByBlogId($blog_id) {
        if(empty($blog_id)) {
            return false;
        }
        
        return $this->_dBlogClassGrants->delGrantByBlogId($blog_id);
    }

    /**
     * 根据班级ID和日志ID修改信息
     */
    public function modifyBlogClassGrantByWhere($data, $where_arr) {
        if(empty($data) || empty($where_arr)) {
            return false;
        }
        
        return $this->_dBlogClassGrants->modifyBlogClassGrantByWhere($data, $where_arr);
    }
    

    /**
     * 根据where 条件获取班级日志权限列表
     * @param $where_arr
     * 注明： where 两个条件 1 班级class_code 只支持一个班级  
     * 2日志ids多个 最多200个
     * @param $orderby
     * @param $offset
     * @param $limit
     */
    public function getGrantInfo($where_arr, $orderby=null, $offset=0, $limit=10) {
        if (empty($where_arr)) {
            return false;
        }
        
        return $this->_dBlogClassGrants->getInfo($where_arr, $orderby, $offset, $limit);
    }
}