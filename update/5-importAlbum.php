<?php
ini_set('error_reporting', E_ALL);
include_once(dirname(dirname(__FILE__)) . '/Daemon/Daemon.inc.php');
import('@.Common_wmw.Pathmanagement_sns');
$Pathmanagement_sns = new Pathmanagement_sns();
$mAlbumPhotos = ClsFactory::Create('Model.Album.mAlbumPhotos');

$limit = 100;
$offset = 0;
$i = 0;
$start = date('Y-m-d H:i:s');

while(true) {

    $photo_list = $mAlbumPhotos->getPhotos($offset, $limit);
    $lastSql = $mAlbumPhotos->getLastSql();
    print_r($lastSql."\n");    
    print_r("offset = $offset  count =" . count($photo_list) ."\n");
    if(empty($photo_list)) {
        //print_r("$photo_list \n");
        print_r("没有数据了 \n");
        break;
    }    
    
    foreach($photo_list as $photo_id=>$photo_info) {
        $i++;
        $file_path = $Pathmanagement_sns->uploadAlbum($photo_info['upd_account']);
        $file_path = $file_path . '/' .$photo_info['file_big'];

        $rs = scalePhoto($file_path);
        if(empty($rs)) {
            print_r("错误：第{$i}张图片 \n");
            continue;
        } else {
            print_r("成功：第{$i}张图片 \n");
        }
        
    }
    sleep(1);
    $offset = $offset + 100;
    unset($photo_list);
}
print_r("开始时间: $start \n");
$end = date('Y-m-d H:i:s');
print_r("结束时间: $end \n");
print_r("切割图片结束   \n");

//相片缩略图（中：瀑布流，小：照片普通列表）
/**
 * @param  
 * @return array
 * $img_path_arr = array(
 * 		'_'
 * );
 */
 function scalePhoto($src_img) {
    if(empty($src_img) || !file_exists($src_img)) {
        print_r("源文件不存在|源文件路径：$src_img \n");
        return false;
    }
    //相册,照片列表width:198 height:162------small
    //瀑布流列表width:178--------------------middle
    $img_name = basename($src_img);

    import('@.Common_wmw.WmwScaleImage');
    $wmwScaleImage = new WmwScaleImage();
    $_s_path = $wmwScaleImage->scaleSmall($src_img);
    $_m_path = $wmwScaleImage->scaleMiddle($src_img);
    if(empty($_s_path) || empty($_m_path)) {
        
        print_r("|切割失败|源文件路径：$src_img \n");
        return false;
    }
    $img_name_arr = array(
        'img_name'=> $img_name,
        '_s'	  => basename($_s_path),
        '_m'	  => basename($_m_path)
    ); 
    return $img_name_arr;
}