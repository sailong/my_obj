<?php

class LoaderUserObject {
    public function load($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $mUser = ClsFactory::Create('Model.mUser');
        $user_list = $mUser->getUserByUid($uid);
        $user_datas = isset($user_list[$uid]) ? $user_list[$uid] : array();
        
        $dUserObjectHash = ClsFactory::Create('RData.Common.dUserObjectHash');
        
        return $dUserObjectHash->addUserObjectHash($uid, $user_datas);
    }
}