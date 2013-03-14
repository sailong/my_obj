<?php
include_once(dirname(dirname(dirname(__FILE__))) . '/Daemon.inc.php');

/**
 * 班级日志动态
 */

$client_account = 11070004;
$class_code = 23527;

/**
 * 创建日志
 * @var unknown_type
 */
$blog_datas = array (
    'title'        => "日志动态日志动态日志动态",
    'content'      => "日志动态日志动态日志动态日志动态日志动态日志动态日志动态日志动态日志动态日志动态",
    'type_id'      => 13,
    'views'        => 0,
    'is_published' => 1,  //默认发布 1 发布 0 草稿
    'add_account'  => $client_account,
    'add_time'     => time(),
    'contentbg'    => "",
    'summary'      => "日志动态日志动态日志",
    'comments'     => 0,
    'grant'        => 0
);
import('@.Control.Sns.Blog.Ext.ClassBlog');
$BlogObj = new ClassBlog($class_code);
$blog_id = $BlogObj->publishBlog($blog_datas, true);

/**
 * 创建日志动态
 */

import("@.Control.Api.FeedApi");
$feed = new FeedApi();
$feed_id = $feed->class_create($class_code,$client_account,$blog_id,3);

/**
 * 日志动态的读取
 */
echo '<br>-------------------------班级日志动态--------------------------------<br>';
$classallablumfeed = $feed->getClassAlbumFeed($class_code);
printf($classallablumfeed);

echo '<br>-------------------------班级全部动态--------------------------------<br>';
$classallfeed = $feed->getClassAllFeed($class_code);
printf($classallfeed);