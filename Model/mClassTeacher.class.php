<?php

class mClassTeacher extends mBase {
	
	protected $_dClassTeacher = null;
	
	public function __construct() {
		$this->_dClassTeacher = ClsFactory::Create('Data.dClassTeacher');
	}
	
    public function getClassTeacherByClassCode($class_codes) {
        if(empty($class_codes)) {
            return false;
        }
        
        return $this->_dClassTeacher->getClassTeacherByClassCode($class_codes);
    }
	
    public function getClassTeacherByUid($uids, $filters = array()) {
        if (empty($uids)) {
            return false;
        }
        
        $class_teacher_list =  $this->_dClassTeacher->getClassTeacherByUid($uids);
        
        if (!empty($class_teacher_list) && !empty($filters)) {
        	
            foreach ($filters as $field=>$val) {
                $val = (array)$val;
                
                foreach ($class_teacher_list as $client_account=>$list) {
                	
                    foreach ($list as $key=>$classteacher) {
                    	
                        if (isset($classteacher[$field]) && !in_array($classteacher[$field] , $val)) {
                            unset($list[$key]);
                        }
                    }
                    
                    if (!empty($list)) {
                        $class_teacher_list[$client_account] = $list;
                    } else {
                        unset($class_teacher_list[$client_account]);
                    }
                }
            }
        }
        
        return !empty($class_teacher_list) ? $class_teacher_list : false;
    }
    
    public function addClassTeacher($dataarr) {
        if (empty($dataarr) || !is_array($dataarr)) {
            return false;
        }
        
        return $this->_dClassTeacher->addClassTeacher($dataarr);
    }
    
    public function modifyClassTeacher($dataarr, $class_teacher_id) {
        if (empty($dataarr) || !is_array($dataarr) || empty($class_teacher_id)) {
            return false;
        }
        
        return $this->_dClassTeacher->modifyClassTeacher($dataarr, $class_teacher_id);
    }
    
    public function delClassTeacher($class_teacher_id) {
        if (empty($class_teacher_id)) {
            return false;
        }
        
        return $this->_dClassTeacher->delClassTeacher($class_teacher_id);
    }
    
    
}
