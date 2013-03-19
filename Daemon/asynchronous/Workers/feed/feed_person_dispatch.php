<?php
include_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/Daemon.inc.php');
class feed_person_dispatch extends BackGroundController {

    //注意大小写
    
    protected $_common_tasks = array(
        'feed_user_all',
        'feed_user_my',
        'feed_user_friends',
        'feed_user_children',
    );
    protected $_tasks = array(
        FEED_MOOD => array(
            FEED_ACTION_PUBLISH => array(),
            FEED_ACTION_COMMENT => array(),
        ),
        FEED_BLOG => array(
            FEED_ACTION_PUBLISH => array(),
            FEED_ACTION_COMMENT => array(),
        ),
        FEED_ALBUM => array(
            FEED_ACTION_PUBLISH => array('feed_user_album'),
            FEED_ACTION_COMMENT => array(),
        ),
    );
    
    /**
     * 
     * @param Serializable(array()) $job
     * array(
     * 	'id'=>1
     *  'feed_id' => 1
     *  'feed_type' => 日志/说说/相册
     *  'action' => 发表/评论
     * )
     * 
     * @param $log
     */
    public function run($job, &$log) {

        $workload = $job->workload();
        
        $workload = unserialize($workload);
        $id = $workload["id"];
        $feed_id = $workload["feed_id"];
        $feed_type = $workload["feed_type"];
        $action = $workload["action"];
        if(empty($action) || empty($id) || empty($feed_id) || empty($feed_type)){
            $log[] = "Work Failure: id or feed_id or feed_type or action is null";
            return false;  
        }
        
        $this->_tasks = array_merge((array)$this->_common_task, (array)$this->_tasks[$feed_type][$action]);
        
        foreach($this->_tasks as $_taskName) {
            $task = $this->getTaskClass($_taskName);
            if (!empty($task)) {
                $result = $task->run($id, $feed_id);
            }
        }
        
        $log[] = "Success";
    
        return $result;

    }

}


?>