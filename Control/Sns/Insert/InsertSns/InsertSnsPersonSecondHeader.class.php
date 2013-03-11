<?php
include_once WEB_ROOT_DIR . "/Control/Sns/Insert/InsertInterface.php";
class InsertSnsPersonSecondHeader implements InsertInterface {
    public function run($params, & $smarty) {
        
        $RmUser = ClsFactory::Create("RModel.mUserVm");
        $userinfo = $RmUser->getCurrentUser();
        
        $head_pic_url = $userinfo['client_headimg_url'];
        $smarty->assign('client_name', $userinfo['client_name']);
        $smarty->assign('head_pic', $head_pic_url);
        
    	return $smarty->fetch("./Public/sns_personal_second_header.html");
    }
}