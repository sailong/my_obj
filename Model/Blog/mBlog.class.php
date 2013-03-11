<?php
class mBlog extends mBase {
    protected $_dBlog = null;
    
    public function __construct() {
        $this->_dBlog = ClsFactory::Create('Data.Blog.dBlog');
    }
    
    //根据日志ID获取信息列表          
    public function getByBlogId($blog_ids) {
        return $this->_dBlog->getByBlogId($blog_ids);
    }
    
    //根据日志分类type_id 获取日志列表
    public function getBlogByTypeId($type_ids) {
        if (empty($type_ids)) {
            return false;
        }
        
        return $this->_dBlog->getBlogByTypeId($type_ids);
    }
    
    //根据where 添加获取日志信息列表  （只是基本信息）
    public function getBlogInfo($wherearr, $orderby, $offset = null, $limit = null) {
        
        return $this->_dBlog->getInfo($wherearr, $orderby, $offset, $limit);
    }
    
    //根据添加人获取信息列表          
    public function getListByAddUid($uid, $fliter=array()) {
        //二维
        return $this->_dBlog->getListByAddUid($uid, $fliter);
    }
    
    //添加日志
    public function addBlog($data, $is_return_id) {

        return $this->_dBlog->addBlog($data, $is_return_id);
    }
    
    public function modifyBlog($blog_datas, $blog_id) {
        if(empty($blog_datas) || !is_array($blog_datas) || empty($blog_id)) {
            return false;
        }
        
        return $this->_dBlog->modifyByBlogId($blog_datas, $blog_id);
    }
    
    //根据日志ID修改日志信息
    public function modifyByBlogId($data, $blog_id) {
        return $this->_dBlog->modifyByBlogId($data, $blog_id);
    }
    
    //根据日志ID删除日志信息
    public function delByBlogId($blog_id) {
        return $this->_dBlog->delByBlogId($blog_id);   
    }
    
    //根据日志批量删除日志信息
    public function delAllByBlogIds($blog_ids) {
        if(empty($blog_ids)) {
            return false;
        }
        
        //二维
        return $this->_dBlog->delAllByBlogIds($blog_ids);
    }
    
    /**
     * 统计分类下的日志数量
     * 不包括草稿
     */
//    public function getBlogNumsByTypeIds($type_ids) {
//        if(empty($type_ids)) {
//            return false;
//        }
//                
//        $type_nums_list = false;
//        $nums_list = $this->_dBlog->getBlogNumsByTypeIds($type_ids);
//        if (!empty($nums_list)) {
//            foreach($nums_list as $key=>$val) {
//                $type_nums_list[$val['type_id']] = $val['nums'];
//            }
//        }
//        
//        return $type_nums_list;        
//    }   
}