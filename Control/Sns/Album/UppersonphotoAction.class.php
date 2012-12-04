<?php
class UppersonphotoAction extends SnsController {
//上传相册图片（js队列单张上传，多次调用）
    public function upload() {
        $xiangce = $this->objInput->postStr('xcid');
        //$account = $this->getCookieAccount(); //bug:通过flash请求时，firefox无法获取cookie信息
        $account = $this->user['client_account'];
        
        $max_width_bigpic = 500;   //没有对大图进行缩放,需要在页面加宽度限制
        $max_width_smllpic = $max_height_smllpic = 112;
		$attachements_path = Pathmanagement_sns::uploadAlbum($account); 
		
		$new_name =  $account.'_'.time().WmwString::rand_string(5,2,'1234567890'); //不包含扩展名
        
        $up_init = array (
			  'attachmentspath' => $attachements_path, 
              'renamed' => true,
              'newname' => $new_name,
			  'ifresize' => true,
              'max_size' => 8 * 1020, // 最大8M
			  'resize_width' => $max_width_smllpic,
              'resize_height' => $max_height_smllpic,
        	  'allow_type' => array('jpg', 'png', 'gif', 'bin'),
    	);
    	
		$uploadObj = ClsFactory::Create('@.Common_wmw.WmwUpload');  
        $uploadObj->_set_options($up_init);
        $up_rs = $uploadObj->upfile('Filedata'); 
        
        if(!empty($up_rs)) {
            //重命名小图：xxx_small.jpg => xxx_s.jpg
            $small_pic_name = $new_name.'_s.'.array_pop(explode('.',$up_rs['getsmallfilename']));
            $rs =  rename($up_rs['getsmallfilename'], $attachements_path.'/'.$small_pic_name);
            $photo_name = $photo_url = $new_name.'.'.array_pop(explode('.',$up_rs['getfilename']));

            $data=array(
    			'album_id'      => $xiangce,  
    			'name'    => $photo_name, // 类中返回的$up_r['filename']有误，同$up_r['getfilename']值相同
    			'file_big'     => $photo_url,  //该字段可删除
                'file_middle'  => $photo_url,
    			'file_small' => $small_pic_name,
    			'description' => "",
    			'add_time'      => time(),
    			'add_account'   => $account,
    			'upd_time'      => time(),
    			'upd_account'   => $account,
    		);
    		
    		$mAlbumPhotos = ClsFactory::Create('Model.Album.mAlbumPhotos');
    		$album_id=$mAlbumPhotos->addPhoto($data, true);
        }
        
		echo 'success:'.$up_rs['getfilename'].','.$_FILES['Filedata']['name'].','.$up_rs['size'].','.$up_rs['getsmallfilename'];
    }
    

	//保存上传照片
	function saveuploadphoto($large_image_location,$xiangce,$user_account,$PHPSESSID){
		$photoname = substr($large_image_location, strrpos($large_image_location, '/') + 1);//照片名称
		$photoid  = substr($photoname, 0,strrpos($photoname, '.'));//照片id
		$file_ext = strtolower(substr($photoname, strrpos($photoname, '.') + 1));//照片后缀
		$adddate= time();
		$data=array(
			'album_id'=>$xiangce,
			'name'=>$photoname,
			'file_big'=>$photoname,
			'file_middle'=>$photoname,
			'file_small'=>$photoid."_s.".$file_ext,
			'description'=>"",
			'add_time'=>$adddate,
			'add_account'=>$PHPSESSID,
			'upd_time'=>$adddate,
			'upd_account'=>$PHPSESSID,
			
		);
		$mPhotosInfo = ClsFactory::Create('Model.mPhotosInfo');
		$album_id=$mPhotosInfo->addphotos($data, true);
		//上传相片时添加用户动态信息表 intval
		$mFeed = ClsFactory::Create('Model.mFeed');
		$mFeed->addPersonFeed(intval($user_account),intval($album_id),PERSON_FEED_ALBUM,FEED_UPD,time());
	}
}