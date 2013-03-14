<?php
include_once(dirname(dirname(dirname(__FILE__))) . '/Daemon.inc.php');

/**
 * 用户说说动态测试代码
 * @author Administrator
 */
class testPersonMood {
    
    public static function createPersonFeed($uid) {
         $mood_datas = array(
            'content' => '我们经常发现需要很多功能，这些功能需要经常被分散在代码中的多个点上，但是这些点事实上跟实际业务没有任何关联。比如，在执行一些特殊任务之前需要确保用户是在登陆状态中，我们把这些特殊人物就叫做"cross-cutting concerns"，让我们通过Wikipedia来了解一下"cross-cutting concerns"（横向关系）的定义。',
            'img_url'     => '',
            'add_account' => $uid,
            'add_time'  => time(),
            'comments'  => 0,
        );
        
        import('@.Control.Api.MoodApi');
        $MoodApi = new MoodApi();
        $mood_id = $MoodApi->addPersonMood($uid, $mood_datas);
        // 创建动态
        import('@.Control.Api.FeedApi');
        $FeedApi = new FeedApi();
        $feed_id = $FeedApi->user_create($uid, $mood_id, FEED_MOOD, FEED_ACTION_PUBLISH);
        print_r("mood_id = $mood_id \n");
        print_r("feed_id = $feed_id \n");
        
        return $feed_id;
    }
    
    public static function debugPersonFeed($uid) {
        if(empty($uid)) {
            return false;
        }
        
        import('@.Control.Api.FeedApi');
        $FeedApi = new FeedApi();
        //1.全部动态
        $datas = $FeedApi->getUserAllFeed($uid);
        print_r($datas);
        
        //2.与我相关
        //3.好友动态
//        $friends_account = '56067742';
//        $friend_feeds = $FeedApi->getUserAllFeed($friends_account);
//        print_r($friend_feeds);
    }
    
}

$uid = '11070004';
// 创建一个说说实体
$feed_id = testPersonMood::createPersonFeed($uid);
//读取动态
testPersonMood::debugPersonFeed($uid);






