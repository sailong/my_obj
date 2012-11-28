<?php
class mClassExam extends mBase {
    protected $_dClassExam = null;
    
    public function __construct() {
		$this->_dClassExam = ClsFactory::Create('Data.dClassExam');    	
    }
    
    /*
     * 通过where 条件 查询记录
     *  
     */
    public function getClassExam($where, $orderby, $offset, $limit) {
        
        return $this->_dClassExam->getInfo($where, $orderby, $offset, $limit);
        
    }
    
    /**
     * 通过主键获取考试的基本信息
     * @param $exam_ids
     */
    public function getClassExamById($exam_ids) {
        if(empty($exam_ids)) {
            return false;
        }
        
        return $this->_dClassExam->getClassExamById($exam_ids);
    }
    
     /**
     * 只允许单个考试的全部成绩信息的获取
     * @param unknown_type $exam_id
     */
//    public function getClassExamById($exam_ids) {
//        if(empty($exam_ids)) {
//            return false;
//        }
//        
//        $examinfoarr = $this->getClassExamBaseById($exam_ids);
//        if(!empty($examinfoarr)) {
//            $mStudentScore = ClsFactory::Create('Model.mStudentScore');
//            $studentscorearr = $mStudentScore->getStudentScoreExamId($exam_ids);
//            if(!empty($studentscorearr)) {
//                foreach($studentscorearr as $examid=>$studentscore) {
//                    if(isset($examinfoarr[$examid]) && !empty($studentscore)) {
//                        foreach($studentscore as $studentinfo) {
//                            $examinfoarr[$examid]['student_score'][$studentinfo['client_account']] = $studentinfo;
//                        }
//                        
//                    }
//                }
//            }
//        }
//        return !empty($examinfoarr) ? $examinfoarr : false;
//    }
    
    

    /**
     * 通过科目id获取考试信息
     * @param $subject_ids
     */
    public function getClassExamBySubjectId($subject_ids , $filters = array()) {
        if(empty($subject_ids)) {
            return false;
        }
        
        $examinfolist = $this->_dClassExam->getClassExamBySubjectId($subject_ids);
        if(!empty($examinfolist) && !empty($filters)) {
            foreach($examinfolist as $subjectid=>$tilist) {
                foreach($tilist as $key=>$examinfo) {
                    foreach($filters as $field=>$values) {
                        if($field == 'exam_name') {
                            $values = is_array($values) ? array_shift($values) : $values;
                            $values = strval($values);
							//echo $examinfo[$field]."----".$values."---".strpos($examinfo[$field],$values)."<br>";
							if(strpos($examinfo[$field],$values) === false) {
                                 unset($tilist[$key]);
                                 break;
                            }
                        } else if ($field == 'exam_date') { 
							$seardate = explode(",",$values);
							if (!empty($seardate[0]) && !empty($seardate[1])) {
								if(strtotime($examinfo[$field]) < strtotime($seardate[0]) || strtotime($examinfo[$field]) > strtotime($seardate[1])) {
									 unset($tilist[$key]);
									 break;
								}
							}
						} else {
                            $values = is_array($values) ? $values : array($values);
                            if(isset($examinfo[$field]) && !in_array($examinfo[$field] , $values)) {
                                unset($tilist[$key]);
                                break;
                            }
                        }
                    }
                    $examinfolist[$subjectid] = $tilist;
                }
            }
        }

        return !empty($examinfolist) ? $examinfolist : false;
    }




    /**
     * 删除考试主表中的信息
     * @param $exam_id
     */
    public function delClassExam($exam_id) {
        if(empty($exam_id)) {
            return false;
        }
        
        return $this->_dClassExam->delClassExam($exam_id);
    }
    
    // 我的考试成绩信息
    public function getClassExamByClassCode($school_id, $classcode, $firter, $offset, $limit) {
        if(empty($classcode) || empty($school_id)) {
            return false;
        }
        
        $offset = max(0,$offset);
        $limit = max(0,$limit);
        $classcode = (array)$classcode;
        
		if(!empty($firter[0])) {
			$wheresql[] = " subject_id=".$firter[0];
		}
		if(!empty($firter[1])) {
			$wheresql[] = " exam_name like '%".$firter[1]."%'";
		}
		if(!empty($firter[2]) && !empty($firter[3])) {
			$wheresql[] = " (exam_date between '".$firter[2]."' and '".$firter[3]."')";
		}
		if(!empty($firter[4])) {
		    $wheresql[] = "add_account=$firter[4]";
		}
        return $this->_dClassExam->getInfo($wheresql, 'exam_id desc', $offset, $limit);
        
    }
    

	// 保存考试信息
	public function addClassExam($ClassExamData , $is_return_id) {
		if(empty($ClassExamData)) {
			return false;
		}
		
		return $this->_dClassExam->addClassExam($ClassExamData , $is_return_id);
    }

	//批量 保存考试信息
	public function addBatClassExam($datas) {
		if(empty($datas)) {
			return false;
		}

		return $this->_dClassExam->addBat($datas);
    }

	// 保存考试信息
	public function modifyClassExam($ClassExamData,$exam_id) {
		if(empty($ClassExamData) || empty($exam_id)) {
			return false;	
		}
		
		return $this->_dClassExam->modifyClassExam($ClassExamData,$exam_id);
    }
    
    
    /**
     * 通过班级class_code 获取考试信息
     * @param $subject_ids
     */
    public function getClassExamByClassCodeTO($class_code) {
    	if(empty($class_code)) {
    		return false;
    	}
    	
        return $this->_dClassExam->getClassExamByClassCode($class_code);
    }
}




?>