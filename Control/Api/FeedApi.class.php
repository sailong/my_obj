<?php
class FeedApi extends ApiController {
    
    /**
     * 创建用户动态信息
     * @param $entity_datas array  动态信息引用的实体，如：日志动态，则为日志在数据库中的字段信息，包括主键id在内
     * @param $feed_type    int    枚举值
     * @param $uid          bigint 添加动态的用户账号信息
     */
    public function urs_create($entity_datas, $feed_type, $uid) {
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
    public function cls_create($entity_datas, $feed_type, $uid, $class_code) {
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
    public function usr_all($uid, $offset = 0, $limit = 10) {
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
    public function usr_album($uid, $offset = 0, $limit = 10) {
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
    public function cls_all($class_code, $offset = 0, $limit = 10) {
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
    public function usr_my($uid, $offset = 0, $limit = 10) {
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
    public function usr_child($uid, $offset = 0, $limit = 10) {
        if(empty($uid)) {
            return false;
        }
        
        import('@.Control.Api.FeedImpl.UserChildFeed');
        $userChildFeed = new UserChildFeed();
        
        return $userChildFeed->getUserChildFeed($uid, $offset, $limit);
    }
}