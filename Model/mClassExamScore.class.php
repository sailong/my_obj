<?php

class mClassExamScore extends mBase {
    protected $_dClassExamScore = null;
    
    public function __construct() {
        $this->_dClassExamScore = ClsFactory::Create('Data.dClassExamScore');       
    }
    
    
    //通过主键 score_id获取学生成绩信息
    public function getClassExamScoreById($score_ids) {
        if(empty($score_ids)) {
            return false;
        }
        
        return $this->_dClassExamScore->getClassExamScoreById($score_ids);
    } 
       
    //通过考试id获取学生的成绩信息
    public function getClassExamScoreByExamId($exam_ids) {
        if(empty($exam_ids)) {
            return false;
        }
        
        return $this->_dClassExamScore->getClassExamScoreByExamId($exam_ids);
    }
    
    // 通过考试id和账号获取成绩信息
    public function getClassExamScoreByExamIdAndAccount($exam_ids, $Account) {
        if(empty($exam_ids) || empty($Account)) {
            return false;
        }
        $exam_ids = implode("," , (array)$exam_ids);
        $Account =  is_array($Account) ? array_shift($Account) : $Account;

        $wheresql = array(
        	"client_account='$Account'",
            "exam_id in($exam_ids)",
            "exam_score!=-1"
        );
             
        return $this->_dClassExamScore->getInfo($wheresql);        
    }
    
    // 成绩保存
	public function addClassExamScore($ScoreData, $return_insertid = false) {
	    return $this->_dClassExamScore->addClassExamScore($ScoreData, $return_insertid );
    }

    // 批量添加成绩
	public function addBatClassExamScore($datas) {
	    if (empty($datas)){
	        return false;
	    }
	    
	    return $this->_dClassExamScore->addBat($datas);
    }
    
    public function modifyClassExamScore($score_data,$exam_id,$client_account) {
        if(empty($score_data) || empty($exam_id) || empty($client_account)) {
            return false;
        }
        $score_list = $this->getClassExamScoreByUid($client_account); //获取该学生参加过的所有的考试信息
        foreach($score_list as $id=>$score_info ) {//获取本次考试中该学生的score_id（主键）
            if($score_info['exam_id'] == $exam_id) {
                $score_id = $id;
            }
        }
        if(!empty($score_id)) {
            $mdf_rs = $this->_dClassExamScore->modifyClassExamScore($score_data, $score_id);
        } else {
            return false;
        }
		
		return $mdf_rs;
    }
    
    //删除学生成绩信息
    public function delClassExamScore($score_id) {
        if(empty($score_id)) {
            return false;
        }
        
        return $this->_dClassExamScore->delClassExamScore($score_id);
    }
    
    //批量删除学生成绩信息
    public function delBatClassExamScore($score_ids) {
        if(empty($score_ids) || !is_array($score_ids)) {
            return false;
        }
        
        $res = true;
        foreach($score_ids as $score_id) {
           $success =  $this->_dClassExamScore->delClassExamScore($score_id);
           if (!$success) {
               $res = false;
           }
        }
        return $res;
    }
    
}


