<?php
include_once(dirname(dirname(__FILE__)) . '/Daemon/Daemon.inc.php');

//1. 从wmw_blog读取全部数据，循环
$limit = 100;
$offset = 0;
$mBlog = ClsFactory::Create('Model.Blog.mBlog');
$mBlogContent = ClsFactory::Create('Model.Blog.mBlogContent');
echo '数据正在处理中，请稍候………………………………';
for($i=0; ;$i++) {
    $offset = $i*$limit;
    //获取日志blog_ids
    $BlogInfo = $mBlog->getBlogInfo($offset,$limit);
    if(empty($BlogInfo)) {
        break;
    }
    
    $blog_ids = array();
    foreach($BlogInfo as $key=>$val) {
        $blog_ids[$val['blog_id']] = $val['blog_id'];
    }
    //获取日志内容
    $blog_content_list = $mBlogContent->getBlogContentById($blog_ids);
    $sum_img_list = array();
    foreach($blog_content_list as $blog_id_key=>$blog_content_val) {
        $sum_img_list[$blog_id_key]['summary'] = getSummary($blog_content_val['content']);
        $sum_img_list[$blog_id_key]['first_img'] = getFirstImg($blog_content_val['content']);
    }
    //更新日志摘要和第一张图信息
    foreach($sum_img_list as $blog_id_key1=>$blog_content_val1) {
        $rs = $mBlog->modifyBlog($blog_content_val1,$blog_id_key1);
    }
}

echo '数据处理已完成!';
//2. 以blog_id 从wmw_blog_content找到 内容.
//3. 执行getSummary 得到摘要
//4. 执行getgetFirstImg获取第一张图片
//将 3,4更新到 wmw_blog的字段  更新




    /**
     * 截取日志摘要
     * @param String $content
     * @param $str_length 截取长度
     */
    function getSummary($content, $str_length = 200) {
        if(empty($content)) {
            return false;
        }
        import('@.Common_wmw.WmwString');
        // html 实体转换成一般的html 代码
        $content = WmwString::unhtmlspecialchars($content);
                
        //去除html标签 包括 img 标签
        $content = WmwString::delhtml($content);

        //截取内容
        $content = WmwString::mbstrcut(trim($content), 0, $str_length, 1, $suffix=true);

        return $content;
    }
    
    
        /**
     * 截取日志第一张图片用于列表页面展示
     * @param String $content
     */
    function getFirstImg($content) {
        import('@.Common_wmw.HtmlParser');
        $HtmlParser = new HtmlParser($content);
        $img = $HtmlParser->getElementByTagName('img');
        
        return !empty($img) ? $img : '' ;
    }    