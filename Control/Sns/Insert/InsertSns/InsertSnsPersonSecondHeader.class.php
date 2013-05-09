<?php
include_once WEB_ROOT_DIR . "/Control/Sns/Insert/InsertInterface.php";
class InsertSnsPersonSecondHeader implements InsertInterface {
    public function run($params, & $smarty) {
        $uid = $params['uid'];
        $RmUser = ClsFactory::Create("RModel.mUserVm");
        $userinfo = reset($RmUser->getUserBaseByUid($uid));
        $login_info = $RmUser->getCurrentUser();
        $login_account = $login_info['client_account'];
        $head_pic_url = $userinfo['client_headimg_url'];
        $is_vuid = true;
        if($login_account == $uid) {
            $is_vuid = false;
        }
        $smarty->assign('client_name', $userinfo['client_name']);
        $smarty->assign('space_uid', $userinfo['client_account']);
        $smarty->assign('head_pic', $head_pic_url);
        $smarty->assign('is_vuid', $is_vuid);
        
    	return $smarty->fetch("./Public/sns_person_space_header.html");
    }
}