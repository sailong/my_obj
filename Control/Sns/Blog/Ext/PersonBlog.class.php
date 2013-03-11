<?php

import('@.Control.Sns.Blog.Ext.BlogBase');
class PersonBlog extends BlogBase {
    private $client_account = 0;
    
    public function __construct($user_info) {
        $this->client_account = $user_info;
    }
    
    /**
     * todo list 未完成
     * 获取班级日志详情包含权限，内容，分类等
     * @param $blog_ids
     *  注明：最多返回20 篇日志
     */
    public function getBlogInfoById($blog_ids) {
    
    }
    
    /**
     * 发表个人日志
     * @param $blog_datas
     *   title
     *   content
     *   type_id
     *   views
     *   is_published
     *   add_account
     *   add_time
     *   upd_account
     *   upd_time
     *   contentbg
     *   summary
     *   comments
     *   grant
     */
    public function publishBlog($blog_datas) {
        if(empty($blog_datas)) {
            return false;
        }
        
        //实体表的数据保存
        $mBlog = ClsFactory::Create('Model.Blog.mBlog');
        $blog_id = $mBlog->addBlog($blog_datas, true);
        if(empty($blog_id)) {
            return false;
        }
        
        $blog_datas['blog_id'] = $blog_id;
        
        //保存日志内容
        $blog_content_datas = $this->extractBlogContent($blog_datas);
        $mBlogContent = ClsFactory::Create('Model.Blog.mBlogContent');
        if(!$mBlogContent->addContent($blog_content_datas, true)) {
            return false;
        }
        
        //人和日志的关系表
        $blog_person_relation_datas = $this->extractBlogRelation($blog_datas);
        $mBlogPersonRelation = ClsFactory::Create('Model.Blog.mBlogPersonRelation');
        if(!$mBlogPersonRelation->addRelation($blog_person_relation_datas, true)) {
            return false;
        }
        //权限表的数据保存
        $blog_person_grants_datas = $this->extractBlogGrant($blog_datas);
        $mBlogPersonGrants = ClsFactory::Create('Model.Blog.mBlogPersonGrants');
        if(!$mBlogPersonGrants->addGrant($blog_person_grants_datas)) {
            return false;
        }
        
        return true;
    }
    
    public function modifyBlog($blog_datas, $blog_id) {
        if(empty($blog_datas) || empty($blog_id) || !is_array($blog_datas)) {
            return false;
        }

        //修改涉及到的表: 权限表，日志内容表，日志基本信息表
        //日志的关系表示不会涉及到修改的
        //保存日志内容
        $step_1 = $step_2 = $step_3 = true;
        if($this->needModifyBlogEntity($blog_datas)) {
            $mBlog = ClsFactory::Create('Model.Blog.mBlog');
            $step_1 = $mBlog->modifyBlog($blog_datas, $blog_id);
        }
        
        if($this->needModifyBlogContent($blog_datas)) {
            $mBlogContent = ClsFactory::Create('Model.Blog.mBlogContent');
            $step_2 = $mBlogContent->addContent($blog_datas, $blog_id);
        }
        
        if($this->needModifyBlogGrants($blog_datas)) {
            $mBlogPersonGrants = ClsFactory::Create('Model.Blog.mBlogPersonGrants');
            $step_3 = $mBlogPersonGrants->modifyById($blog_datas, $blog_id);
        }
        
        return $step_1 && $step_2 && $step_3 ? true : false;
    }
    
    /**
     * todo 未完成
     * 获取在当前班级班级的 草稿 
     * @param $offset
     * @param $limit
     * @return $draft_list
     */
    public function getDraftList($client_account, $offset = 0, $limit = 20) {
        if(empty($client_account)) {
            return false;
        }
        
        $where_arr = array(
            "add_account='$client_account'"
        );
        
        $mBlog = ClsFactory::Create('Model.Blog.mBlogPersonRelation');
       // $draft_list = $mBlog->getPersonBlogByClassCode($this->class_code, $where_arr, 0, 20);
        
       // return !empty($draft_list) ? $draft_list : false;
    }
    
    protected function extractBlogRelation($blog_datas) {
        if(empty($blog_datas)) {
            return false;
        }
        
        $blog_person_relation = array(
            'client_account' => $this->client_account,
            'blog_id' => $blog_datas['blog_id'],
        );
        
        return $blog_person_relation;
    }
    
    protected function extractBlogGrant($blog_datas) {
        if(empty($blog_datas)) {
            return false;
        }
        
        $blog_person_grants_datas = array(
            'blog_id' => $blog_datas['blog_id'],
            'client_account' => $this->client_account,
            'grant' => $blog_datas['grant'],
        );
        
        return $blog_person_grants_datas;
    }
    
}