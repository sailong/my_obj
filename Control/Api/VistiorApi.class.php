<?php
class VistiorApi extends ApiController {
    
    public function __construct() {
        parent::__construct();
    }    
    
    public function _initialize() {
        parent::_initialize();
    }
    
    
    //统计所有访客
    public function total_count($client_account) {
        if(empty($client_account)) {
            return false;
        }
        
       import('@.Control.Api.VistiorImpl.Vistior');
       $Vistior = new Vistior();
        
       $total_count = $Vistior->total_count($client_account);
       
       return $total_count;
    }
    
    
    //统计今日访客
    public function total_count_day($client_account) {
         if(empty($client_account)) {
            return false;
        }
        
       import('@.Control.Api.VistiorImpl.Vistior');
       $Vistior = new Vistior();
        
       $total_count_day = $Vistior->total_count_day($client_account);
       
       return $total_count_day;
    }
    
    
    public function vistior_list($client_account,$orderby,$offset,$limit) {
        $wheresql = array(
            'uid = ' . $client_account,
            'timeline <= ' . time(), 
        );
        
        $mPersonVistior = ClsFactory::Create('Model.PersonVistior.mPersonVistior');
        $vistior_list = $mPersonVistior->getPersonVistiorInfo($wheresql,$orderby,$offset,$limit);
        
        $vistior_account_arr = array_keys($vistior_list);
        
        //最近访客的信息
        $mUser = ClsFactory::Create('Model.mUser');
        $client_account_list = $mUser->getUserBaseByUid($vistior_account_arr);

        return !empty($client_account_list) ? $client_account_list : false;
    }
    
}