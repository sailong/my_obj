<?php
class TupianAction extends SnsController {
    public function _initialize() {
        parent::_initialize();
        
        import("@.Common_wmw.Pathmanagement_sns");
    }
    
//    public function index() {
//        $uid = 11070004;
//        $mphones = ClsFactory::Create('Model.mPhotosInfo');
//        $phones_list = $mphones->getPhotoInfoByAddaccount(11070004);
//        foreach($phones_list as $key=>& $val) {
//            $phones_list['url'] = Pathmanagement_sns::getAlbum($uid) . $val['photo_url'];
//            $phones_list['min_url'] = Pathmanagement_sns::getAlbum($uid) . $val['photo_min_url'];
//        }
//       
//        dump($phones_list);
//        
//        $this->display('albumPhotos', $phones_list);
//        
//        $this->display('index');
//    }
	//删除我的相册 2012-3-21 by lyt:
	
	//相册照片列表
	public function xcmanager(){
		$account = 11070004;//$this->objInput->getInt('user_account');
		$xcid =1262759;// $this->objInput->getInt('xcid');
		$class_code =969;// $this->checkclasscode($class_code);
		$pagecount = 7;
		
		
		$mPhotosInfo = ClsFactory::Create('Model.mPhotosInfo');
		$photoinfo_result = $mPhotosInfo->getPhotoInfoByAlbumId($xcid);
		$new_photoinfo_result = &$photoinfo_result[$xcid];
		unset($photoinfo_result);
		if($new_photoinfo_result){
			$albumPhotos = array();
			foreach($new_photoinfo_result as $key=>$val){
				$val['photo_urlall'] = Pathmanagement_sns::getAlbum($account) . $val['photo_url'];
				$val['photo_min_urlall'] = Pathmanagement_sns::getAlbum($account) . $val['photo_min_url'];
				$val['photo_name'] = str_replace($account."_","",$val['photo_name']);
				$val['photo_min_url'] = trim($val['photo_min_url']);
				
				$albumPhotos[] = $val;

			}
			unset($new_photoinfo_result);
		}
		
        
		
        $albumPhotos = array_slice($albumPhotos,0,7);
        
		$this->assign('albumPhotos',$albumPhotos);
		$this->assign('client_type',$this->user['client_type']);

		$this->assign('account',$account);
		$this->assign('class_code',$class_code);
		
		$this->assign('photocount',count($albumPhotos));
		$this->assign('friendaccount',$this->getCookieAccount());
		$this->assign('xcid',$xcid);
		$this->assign('actionUrl','/Homeclass/Class/classalbum/');
		
		$this->display('index');
	}
}