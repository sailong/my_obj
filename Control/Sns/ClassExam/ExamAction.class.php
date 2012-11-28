<?php
class ExamAction extends SnsController{
    public function __construct() {
        parent::__construct();
    }
    
    /*班级成绩首页 成绩管理*/
    public function index() {
        echo '我是首页哦';
    }
    
    
    /*
     * 发布成绩
     * 
     */
    public function saveExam() {
        $this->getUserClass();
        
    }
    
    
    
    
    
    
    /*
     * 获取当前用户在当前班级的详细信息（包含当前班级的详情）
     * 
     */
    private function getUserClass() {
        $class_code = $this->getClassCode();
        $user_class_list = $this->user['client_class'];
        if(empty($user_class_list) || empty($class_code)) {
            return false;
        }
        
        //老师, 班级管理员具有修改课程表权限
        foreach($user_class_list as $key=>$client_class) {
            if (intval($class_code == $client_class['class_code'])) {
                $current_class = $client_class; 
                unset($user_class_list);
                break;
            }
        }
    
        return !empty($current_class) ? $current_class :  false;
    }
    
    /*
     * 获取并检查当前用户的当前班级
     * 如果没有通过GET或POST方式设置当前班级  默认所属班级的第一个
     * 
     */
    protected function getClassCode() {
        $user_class_list = $this->user['client_class'];
        if(empty($user_class_list)) {
            return false;
        }
        
        $class_code = $this->objInput->getInt('class_code');
        if (empty($class_code)) {
            $class_code = $this->objInput->postInt('class_code');
        }
        
        $class_code_list = array();
        foreach($user_class_list as $client_class_id=>$client_class){
            $class_code_list[] = intval($client_class['class_code']);
        }
        
        return in_array($class_code, $class_code_list) ? $class_code : array_shift($class_code_list);      
    }
    /*
     * 旧数据处理 只处理一次
     * 
     */
    public function OldData() {
        
        //旧数据处理
        import('@.Control.Sns.OldData');
        $old_data_obj = new OldData();
        //旧课程表数据处理
        //$old_data_obj->oldCourse();
        //旧课程皮肤表数据处理
        //$old_data_obj->oldcourseSkin();
        //旧个人课程皮肤配置表
        //$old_data_obj->oldcourseConfig();
        
        //旧考试信息表处理
        //$old_data_obj->oldExam();  //有意义的数据共 795 条
        //考试成绩信息表
        $old_data_obj->oldExamScore();  //有意义的数据共 30279 条
        echo('旧数据都处理完成了哦！');
    }
}