<?php

define('FEED_DEBUG', true);

include WEB_ROOT_DIR . '/Debug.class.php';

class FeedApi extends ApiController {
    
    public function index() {
        
        dump($this->_type);
        
        dump($this->_method);
        
        Debug::start();
        
        $redis = new rBase();
        
//        $keys = $redis->keys('*');
//        foreach($keys as $key) {
//            $redis->del($key);
//        }
//        
//        dump($redis->keys('*'));
//        exit;
        
        $datas = array(
            'blog_id' => 1,
            'add_account' => 11070004,
            'feed_content' => '我的日志测试',
            'add_time' => time(),
        );
        
//        $mUser = ClsFactory::Create('Model.mUser');
//        $userlist = $mUser->getUserByUid(11070004);
//        dump($userlist);
        
        //dump($redis->hGetAll('usr:11070004:obj'));
        
        
        //$this->user_create($datas, FEED_BLOG, 11070004);
        $this->class_create($datas, FEED_ALBUM, 11070004, 6102);
        
        //$this->dispatch();
        
        dump($redis->lRange('feed:queue', 0, -1));
        
        //dump($redis->keys('*'));
        
//        dump($this->user_all(89288947, 99, 15));
//        
//        dump($this->user_all(89288947, 19, 10));
//        
        dump($this->class_all(6102, 0, 100));
        
        Debug::end();
        
//        dump($redis->keys('usr:11070004:*'));
        
        //dump($redis->keys('feed:usr:11070004:*'));
        
//        dump($redis->sMembers('usr:11070004:friend'));
//        
//        dump($redis->hGetAll('usr:11070004:obj'));

        $this->display(WEB_ROOT_DIR . '/View/Template/Sns/Album/index.html');
        
    }
    
    public function dispatch() {
        import('@.Control.Api.FeedImpl.Dispatch');
        
        $des = new Dispatch();
        $des->dispatchFeed();
    }
    
    /**
     * 创建用户动态信息
     * @param $entity_datas array  动态信息引用的实体，如：日志动态，则为日志在数据库中的字段信息，包括主键id在内
     * @param $feed_type    int    枚举值
     * @param $uid          bigint 添加动态的用户账号信息
     */
    public function user_create($entity_datas, $feed_type, $uid) {
        if(empty($entity_datas) || empty($feed_type) || empty($uid)) {
            return false;
        }
        
        import('@.Control.Api.FeedImpl.CreateFeed');
        $createFeed = new CreateFeed();
        
        return $createFeed->createPersonFeed($entity_datas, $feed_type, $uid);
    }
    
    /**
     * 添加用户在班级空间中产生的动态
     * @param $entity_datas   array  动态信息引用的实体，如：日志动态，则为日志在数据库中的字段信息，包括主键id在内
     * @param $feed_type      int    枚举值
     * @param $uid			  bigint 添加动态的用户账号信息
     * @param $class_code     int    用户当前所在的班级
     */
    public function class_create($entity_datas, $feed_type, $uid, $class_code) {
        if(empty($entity_datas) || empty($feed_type) || empty($uid)) {
            return false;
        }
        
        import('@.Control.Api.FeedImpl.CreateFeed');
        $createFeed = new CreateFeed();
        
        return $createFeed->createClassFeed($entity_datas, $feed_type, $uid, $class_code);
    }
    
    /**
     * 获取用户的全部动态信息
     * @param $uid       bigint 要获取动态的用户id
     * @param $offset	 int    动态信息的起始位置
     * @param $limit     int    要获取动态信息的长度，默认为10，可能大于10
     */
    public function user_all($uid, $offset = 0, $limit = 10) {
        if(empty($uid)) {
            return false;
        }
        
        import('@.Control.Api.FeedImpl.UserAllFeed');
        $userAllFeed = new UserAllFeed();
        
        return $userAllFeed->getUserAllFeed($uid, $offset, $limit);
    }
    
    /**
     * 获取用户的好友相册动态
     * @param $uid     bigint 要获取动态的用户id
     * @param $offset  int    动态信息的起始位置
     * @param $limit   int    要获取动态信息的长度，默认为10，可能大于10
     */
    public function user_album($uid, $offset = 0, $limit = 10) {
        if(empty($uid)) {
            return false;
        }
        
        import('@.Control.Api.FeedImpl.UserAlbumFeed');
        $userAlbumFeed = new UserAlbumFeed();
        
        return $userAlbumFeed->getUserAlbumFeed($uid, $offset, $limit);
    }
    
    /**
     * 获取班级动态
     * @param $class_code  int 要获取动态信息的班级code
     * @param $offset	   int    动态信息的起始位置
     * @param $limit	   int    要获取动态信息的长度，默认为10，可能大于10
     */
    public function class_all($class_code, $offset = 0, $limit = 10) {
        if(empty($class_code)) {
            return false;
        }
        
        import('@.Control.Api.FeedImpl.ClassAllFeed');
        $classAllFeed = new ClassAllFeed();
        
        return $classAllFeed->getClassFeedAll($class_code, $offset, $limit);
    }
    
    /**
     * 获取用户与我相关的动态
     * @param $uid		bigint 要获取动态的用户id
     * @param $offset   int    动态信息的起始位置
     * @param $limit    int    要获取动态信息的长度，默认为10，可能大于10
     */
    public function user_my($uid, $offset = 0, $limit = 10) {
        if(empty($uid)) {
            return false;
        }
        
        import('@.Control.Api.FeedImpl.UserMyFeed');
        $userMyFeed = new UserMyFeed();
        
        return $userMyFeed->getUserMyFeed($uid, $offset, $limit);
    }
    
    /**
     * 获取用户的孩子动态
     * @param $uid      bigint 要获取动态的用户id
     * @param $offset   int    动态信息的起始位置
     * @param $limit    int    要获取动态信息的长度，默认为10，可能大于10
     */
    public function user_child($uid, $offset = 0, $limit = 10) {
        if(empty($uid)) {
            return false;
        }
        
        import('@.Control.Api.FeedImpl.UserChildFeed');
        $userChildFeed = new UserChildFeed();
        
        return $userChildFeed->getUserChildFeed($uid, $offset, $limit);
    }
}