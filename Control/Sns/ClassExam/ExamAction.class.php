<?php
class ExamAction extends SnsController{
    public    $_checkClsssCode = true;
    protected $_class_code     = '';
    protected $_client_class   = '';
    
    public function __construct() {
        parent::__construct();
        $this->_class_code = $this->getClassCode();
        $this->_client_class = $this->getClientClass($this->_class_code);
        
    }
    
    /*
     * 班级成绩 根据不同身份分发到不同的方法
     */
    public function index() {

        if ($this->_client_class['client_type'] == 1) {
             $this->examList();    //班主任、老师
        } else {
            $this->examListStu();  //学生家长
        }
    }
    
    /*
     *  成绩列表页面(老师)
     */
    public function examList() {
        $page = $this->objInput->getInt('page');
        $subject_id  = $this->objInput->postInt('subject_id');
        $exam_name  = $this->objInput->postStr('exam_name');
        $start_time = $this->objInput->postStr('start_time');
        $end_time   = $this->objInput->postStr('end_time');
        
        //接收到的数据处理
        $page = max(1, $page);
        $perpage = 20;
        $offset = ($page - 1) * $perpage;
        $class_code = $this->_class_code;
        $client_account = $this->user['client_account'];

        $subject_list = array();   //教师所教科目（班主任查询所有科目）
        $exam_list = array();      //考试列表 默认按时间倒叙排列 不包含 草稿
        $is_class_teacher = $this->isClassTeacher(); //判断是否在班主任

        if(!empty($is_class_teacher)) {
            $subject_list = $this->getSubjectAll($class_code);
        } else {
            $subject_list = $this->getSubjectByTeacher($class_code, $client_account);
        }

        //根据科目id 获取该科目的考试列表
        $where = array("class_code=$class_code");
        $subject_ids = array_keys($subject_list); 
        if(!empty($subject_id) ) {
            $where[] = "subject_id=$subject_id";
        } else {
            $in_str = implode(',', $subject_ids);
            $where[] = "subject_id in ($in_str)";
        } 
        if(!empty($exam_name)) {
            $where[] = "exam_name like('$exam_name%')";
        }
        if(!empty($start_time)) {
            $start_time = strtotime($start_time);
            $where[] = "exam_time>=$start_time";
            $this->assign('start_time', date('Y-m-d', $start_time));
        }
        if(!empty($end_time)) {
            $end_time = strtotime($end_time) + 24*3600-1;  //当天内所有的考试
            $where[] = "exam_time<$end_time";
            $this->assign('end_time', date('Y-m-d', $end_time));
        }
        
        $where[] = 'is_published=1';  //枚举查询条件放到最优提高查询效率
        if(!empty($subject_ids)) {
            //$perpage+1用于判断是否有下一页
            $mClassExam = ClsFactory::Create('Model.mClassExam');
            $exam_list = $mClassExam->getClassExam($where, 'exam_time desc', $offset, $perpage+1);  
        } 
        
        if(count($exam_list) > $perpage) {
            $is_next_page = 1;
            array_pop($exam_list);
            $this->assign('is_next_page',    $is_next_page);
        }
        //格式化数据用于前台展示
        if(!empty($exam_list)) {
            foreach ($exam_list as $key=>$exam) {
                $exam['subject_name'] = $subject_list[$exam['subject_id']]['subject_name'];
                $exam['exam_time'] = date('Y-m-d', $exam['exam_time']);
                $exam['is_del'] = ($exam['add_account'] == $this->user['client_account']) ? true : $is_class_teacher;
                $exam_list[$key] = $exam;
            }
        }
        
        $this->assign('subject_list', $subject_list ? $subject_list : array());
        $this->assign('exam_list',    $exam_list);
        $this->assign('class_code', $this->_class_code); 
        $this->assign('class_name', $this->_client_class['class_name']);
        $this->assign('subject_id', $subject_id);
        $this->assign('exam_name',  $exam_name);
        $this->assign('page',       $page);
        
        $this->display('exam_list');
    }
    
    /*
     *  成绩列表页面(学生家长)
     * 
     */
    public function examListStu() {
        
    
    }
    
    /*
     * 删除考试信息（包括删除对应成绩）
     */
    public function delExam() {
        $exam_id = $this->objInput->getInt('exam_id');
        
        //获取考试信息并验证
        $mClassExam = ClsFactory::Create('Model.mClassExam');
        $exam_list = $mClassExam->getClassExamById($exam_id);
        $exam_info = $exam_list[$exam_id];
        if(empty($exam_info)) {
            $this->showError('您要删除的数据不存在或已被删除！','/Sns/ClassExam/Exam/index/class_code/'.$this->_class_code);
        } 
        
        //验证是否具有删除权限
        $is_del = ($exam_info['add_account'] == $this->user['client_account']) ? true : $this->isClassTeacher();
        if(empty($is_del)) {
            $this->showError('您没有删除权限！','/Sns/ClassExam/Exam/index/class_code/'.$this->_class_code);
        }
        
        //先删除成绩
        $mClassExamScore = ClsFactory::Create('Model.mClassExamScore');
        $exam_score_list = $mClassExamScore->getClassExamScoreByExamId($exam_id);
        if(!empty($exam_score_list)) {
            $exam_score_list = $exam_score_list[$exam_id];
            $score_ids = array_keys($exam_score_list);
            
            $del_score_success = $mClassExamScore->delBatClassExamScore($score_ids);
        }
        
        //再删除考试信息
        if(empty($del_score_success) && !empty($exam_score_list)) {
            $this->showError('系统繁忙，请稍后重试','/Sns/ClassExam/Exam/index/class_code/'.$this->_class_code);
        }
        $del_exam_success = $mClassExam->delClassExam($exam_id);  
        if (empty($del_exam_success)) {
            $this->showError('系统繁忙，请稍后重试','/Sns/ClassExam/Exam/index/class_code/'.$this->_class_code);
        }
        
        $this->showSuccess('删除成功','/Sns/ClassExam/Exam/index/class_code/'.$this->_class_code);
    }
    
    /*
     * 判断老师是否是班主任
     */
    protected function isClassTeacher() {
        $client_class = $this->_client_class;
        $teacher_class_role = $client_class['teacher_class_role'];
        
        //$teacher_class_role 1 班主任 3班级主任兼老师
        if($teacher_class_role == 1 || $teacher_class_role == 3){
            return true;
        } else {
            return false;
        }
    }
    
    /*
     * 获取本班所有科目 
     * 并格式好数据添加上教师名称
     */
    protected function getSubjectAll($class_code) {
        if(empty($class_code)) {
            return false;
        }

        $class_teacher_list = array();
        $mClassTeacher = ClsFactory::Create('Model.mClassTeacher');
        $tmp_class_teacher_list = $mClassTeacher->getClassTeacherByClassCode($class_code);
        $class_teacher_list = $tmp_class_teacher_list[$class_code];
        unset($tmp_class_teacher_list);
        
        $school_id = $this->user['class_info'][$class_code]['school_id'];
        $mSubjectInfo = ClsFactory::Create('Model.mSubjectInfo');
        $school_subject_list = $mSubjectInfo->getSubjectInfoBySchoolid($school_id);
        $tmp_subject_list = $school_subject_list[$school_id];
        unset($school_subject_list);
        $subject_list = $this->getClassTeacherName($tmp_subject_list, $class_teacher_list);
        
        return !empty($subject_list) ? $subject_list : false;
    }
    
    /*
     *	获取当前老师在当前班级所教的所有科目 
     */
    protected function getSubjectByTeacher($class_code, $client_account) {
        if(empty($class_code) || empty($client_account)) {
            return false;
        }
        $mClassTeacher = ClsFactory::Create('Model.mClassTeacher');
        $tmp_class_teacher_list = $mClassTeacher->getClassTeacherByUid($client_account);
        
        if (empty($tmp_class_teacher_list)) {
            return false;
        }
        
        $class_teacher_list = $tmp_class_teacher_list[$client_account];
       
        //过滤出当前班级 老师所教科目
        foreach ($class_teacher_list as $key=>$class_teacher) {
            if ($class_code == $class_teacher['class_code']) {
                $new_teacher_list[$class_teacher['subject_id']] = $class_teacher;
            }
        }
        unset($class_teacher_list);

        $subject_ids = array_keys($new_teacher_list);
        $mSubjectInfo = ClsFactory::Create('Model.mSubjectInfo');
        $tmp_subject_list = $mSubjectInfo->getSubjectInfoById($subject_ids);
        
        $subject_list = $this->getClassTeacherName($tmp_subject_list, $new_teacher_list);

        return !empty($subject_list) ? $subject_list : false;
    }
    
    /*
     *	格式化科目信息数据（添加上老师名称） 
     */
    protected function getClassTeacherName($subject_list, $class_teacher_list) {
        if (empty($class_teacher_list) || !is_array($class_teacher_list)) {
            return false;
        }
        
        foreach($class_teacher_list as $key=>$class_teacher) {
            $teacher_ids[$class_teacher['subject_id']] = $class_teacher['client_account'];
        }

        $mUser = ClsFactory::Create('Model.mUser');
        $user_list = $mUser->getUserBaseByUid(array_unique($teacher_ids));
        
        //数据拼装 老师名称
        foreach ($subject_list as $subject_id=>$subject) {
            if(isset($teacher_ids[$subject_id])) {
                $subject['teacher_name'] = $user_list[$teacher_ids[$subject_id]]['client_name'];
            }
            $subject_list[$subject_id] = $subject;
        }
        unset($class_teacher_list);unset($teacher_ids);
        
        return $subject_list;
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