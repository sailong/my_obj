<?php

class LoaderUser {
    public function load($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $this->loadUserFriendSet($uid);
        
        $user = $this->initUser($uid);
        $client_type = intval($user['client_type']);
        
        if($client_type == CLIENT_TYPE_STUDENT) {
            $this->loadUserParentSet($uid);
        } else if($client_type == CLIENT_TYPE_FAMILY) {
            $this->loadUserChildrenSet($uid);
        }
        
        return true;
    }
        
    /**
     * 从数据库获取相应信息
     * @param $uid
     */    
    private function initUser($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $mUser = ClsFactory::Create('Model.mUser');
        $user_list = $mUser->getUserBaseByUid($uid);
        $user = $user_list[$uid];
        
        return !empty($user) ? $user : false;
    }
    
    /**
     * 获取用户的好友列表
     * @param $uid
     */
    private function loadUserFriendSet($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $mAccountRelation = ClsFactory::Create('Model.mAccountrelation');
        $account_relation_arr = $mAccountRelation->getAccountRelationByClientAccout($uid);
        $account_relation_list = & $account_relation_arr[$uid];
        
        $friend_uids = array();
        if(!empty($account_relation_list)) {
            foreach($account_relation_list as $friend_relation) {
                $friend_uids[] = $friend_relation['friend_account'];
            }
        }
        
        $dUserFriendSet = ClsFactory::Create('@.RData.Common.dUserFriendSet');
        return $dUserFriendSet->addUserFriendSet($uid, $friend_uids);
    }
    
    /**
     * 加载用户孩子集合信息
     * @param $uid
     */
    private function loadUserChildrenSet($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $mFamilyRelation = ClsFactory::Create('Model.mFamilyRelation');
        $family_relation_arr = $mFamilyRelation->getFamilyRelationByFamilyUid($uid);
        $family_relation_list = & $family_relation_arr[$uid];
        
        $children_uids = array();
        if(!empty($family_relation_list)) {
            foreach($family_relation_list as $relation) {
                $children_uids[] = $relation['client_account'];
            }
        }
        
        $dUserChildrenSet = ClsFactory::Create('@.RData.Common.dUserChildrenSet');
        return $dUserChildrenSet->addUserChildrenSet($uid, $children_uids);
    }
    
    /**
     * 加载用户家长信息
     * @param $uid
     */
    private function loadUserParentSet($uid) {
        if(empty($uid)) {
            return false;
        }
        
        $mFamilyRelation = ClsFactory::Create('Model.mFamilyRelation');
        $family_relation_arr = $mFamilyRelation->getFamilyRelationByUid($uid);
        $family_relation_list = & $family_relation_arr[$uid];
        
        $parent_uids = array();
        if(!empty($family_relation_list)) {
            foreach($family_relation_list as $relation) {
                $parent_uids[] = $relation['family_account'];
            }
        }
        
        $dUserParentSet = ClsFactory::Create('@.RData.Common.dUserParentSet');
        return $dUserParentSet->addUserParentSet($uid, $parent_uids);
    }
}