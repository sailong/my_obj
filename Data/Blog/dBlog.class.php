<?php
class dBlog extends dBase {
    protected $_pk = 'blog_id';
    protected $_tablename = 'wmw_blog';
    protected $_fields = array(
                    'blog_id',
                    'title',
                    'type_id',
                    'views',
                    'is_published',
                    'add_account',
                    'add_time',
                    'upd_account',
                    'upd_time',
                    'contentbg',
                    'summary',
                    'comments',
              );
    protected $_index_list = array(
    				'blog_id',
                    'add_account',
    				'type_id'
              );
    //根据日志ID获取信息列表          
    public function getByBlogId($blog_ids) {
        return $this->getInfoByPk($blog_ids);
    }
    
    //根据日志分类type_id 获取日志列表
    public function getBlogByTypeId($type_ids) {
        if (empty($type_ids)) {
            return false;
        }
        
        return $this->getInfoByFk($type_ids, 'type_id');
    }
    
    //根据添加人获取信息列表          
//    public function getListByAddUid($uid, $filter) {
//        $wherearr['add_account'] =  $uid;
//        if(!empty($filter)) {
//            $wherearr['is_published']=$filter['is_published'];
//        }
//        $orderby = ' blog_id desc';
//        return $this->getInfo($wherearr, $orderby, $offset = null, $limit = null);
//    }
    
    //添加日志
    public function addBlog($data, $is_return_id) {
        return $this->add($data, $is_return_id);
    }
    
    //根据日志ID修改日志信息
    public function modifyByBlogId($data, $blog_id) {
        return $this->modify($data, $blog_id);
    }
    
    //根据日志ID删除日志信息
    public function delByBlogId($blog_id) {
        return $this->delete($blog_id);   
    }
    
    //根据添加人删除日志信息
    public function delByAddAccount($add_accounts) {
        if(empty($add_accounts)) {
            return false;
        }
        
        $account_str = implode(',', (array)$add_accounts);
        $sql = "delete from {$this->_tablename} where add_account in({$account_str})";
        //二维
        return $this->execute($sql);
    }
    
    /**
     * 统计分类下的日志数量
     * 不包括草稿
     */
//    public function getBlogNumsByTypeIds($type_ids) {
//        if(empty($type_ids)) {
//            return false;
//        }
//        $type_str = implode(',', (array)$type_ids);
//        
//        $sql = "select count(*) nums, type_id from wmw_blog ";
//        $sql .= " where type_id in({$type_str}) and is_published=1 group by type_id";
//        
//        return $this->query($sql);
//    }   
    
    
}