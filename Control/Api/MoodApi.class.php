<?php
/**
 * Author: lnc<lnczx0915@gmail.com>
 * 功能:UCenter Client
 * 说明:	作为与UCenter通信的接口类，并提供通用的与用户信息有关的方法
 * 
*/

class MoodApi extends ApiController {

    /**
     * 
     * 固定函数
     */
    public function __construct() {
        parent::__construct();
    }    
   
    /**
     * 
     * 固定函数
     */    
    public function _initialize(){
		parent::_initialize();        
    }
    
    /**
     * 发表一个说说
     * method: post
     */
    public function create_by_user() {
        $content = $this->objInput->postStr('content');
        $img_url = $this->objInput->postStr('img_url');
        
        if(empty($content)) {
            $this->ajaxReturn(null, '说说内容不能为空!', -1, 'json');
        }
        
        $add_account = $this->user['client_account'];
        $mood_datas = array(
            'content' => $content,
            'img_url' => $img_url,
            'add_account' => $add_account,
            'add_time' => time(),
        );
        $mMood = ClsFactory::Create('Model.Mood.mMood');
        $mood_id = $mMood->addMood($mood_datas, true);
        
        if(empty($mood_id)) {
            $this->ajaxReturn(null, '个人说说发表失败!', -1, 'json');
        }
        
        $mood_person_relation_datas = array(
            'client_account' => $add_account,
            'mood_id' => $mood_id,
        );
        $mMoodPersonRelation = ClsFactory::Create('Model.Mood.mMoodPersonRelation');
        $relation_id = $mMoodPersonRelation->addMoodPersonRelation($mood_person_relation_datas, true);
        
        if(empty($relation_id)) {
            //删除刚发表成功的说说基本信息
            $mMood->delMood($mood_id);
            
            $this->ajaxReturn(null, '个人说说发表失败!', -1, 'json');
        }
        
        $this->ajaxReturn(null, '个人说说发表成功!', 1, 'json');
    }
    
    /**
     * 发表班级说说
     */
    public function create_by_class() {
        $class_code  = $this->objInput->postInt('class_code');
        $content     = $this->objInput->postStr('content');
        $img_url     = $this->objInput->postStr('img_url');
        
        $class_code_list = array_keys($this->user['class_info']);
        if(empty($class_code) || !in_array($class_code, (array)$class_code_list)) {
            $this->ajaxReturn(null, '您暂时不能在该班级发表说说!', -1, 'json');
        }
        
        if(empty($content)) {
            $this->ajaxReturn(null, '说说内容不能为空!', -1, 'json');
        }
        
        $mood_datas = array(
            'content' => $content,
            'img_url' => $img_url,
            'add_account' => $this->user['client_account'],
            'add_time' => time(),
        );
        $mMood = ClsFactory::Create('Model.Mood.mMood');
        $mood_id = $mMood->addMood($mood_datas, true);
        
        if(empty($mood_id)) {
            $this->ajaxReturn(null, '班级说说发表失败!', -1, 'json');
        }
        
        $mood_class_relation_datas = array(
            'class_code' => $class_code,
            'mood_id' => $mood_id,
        );
        $mMoodPersonRelation = ClsFactory::Create('Model.Mood.mMoodClassRelation');
        $relation_id = $mMoodPersonRelation->addMoodPersonRelation($mood_class_relation_datas, true);
        
        if(empty($relation_id)) {
            //删除刚发表成功的说说基本信息
            $mMood->delMood($mood_id);
            
            $this->ajaxReturn(null, '班级说说发表失败!', -1, 'json');
        }
        
        $this->ajaxReturn(null, '班级说说发表成功!', 1, 'json');
    }
    

	/**
	 * 获取相片列表接口
	 *
	 * 
	 *
	 * @param int $albumId  相册ID  
	 * @param int $offset   分页偏移量
	 * @param int $limit    分页个数
	 * @param string $display 授权页面类型 可选范围: 
	 *  - default		默认授权页面		
	 *  - mobile		支持html5的手机		
	 *  - popup			弹窗授权页		
	 *  - wap1.2		wap1.2页面		
	 *  - wap2.0		wap2.0页面		
	 *  - js			js-sdk 专用 授权页面是弹窗，返回结果为js-sdk回掉函数		
	 *  - apponweibo	站内应用专用,站内应用不传display参数,并且response_type为token时,默认使用改display.授权后不会返回access_token，只是输出js刷新站内应用父框架
	 * @return json
	 */
    
//    public function show_class($albumId = 0, $offset = 0, $limit = 10) {
//        
//    }
//    
//    public function show_person() {
//        
//    }
//    
//    public function create() {
//        
//    }    
//    
//    public function update() {
//        
//    }
//    
//    public function destroy() {
//        
//    }
    
    
}