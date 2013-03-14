<?php
class IndexAction extends SnsController {

    public function _initialize(){
        parent::_initialize();
    }
    
    
    public function index() {
        $vuid = $this->objInput->getInt('client_account');
        $client_account = $this->user['client_account'];
        if(!empty($vuid) && $vuid != $client_account) {
            $resault = $this->write_vistior($vuid);
        }
        
        import('@.Control.Api.VistiorApi');
        $Vistior = new VistiorApi();
        $total_count = $Vistior->total_count($client_account);
        $total_count_day = $Vistior->total_count_day($client_account);
        
        $this->assign('total_count_day',$total_count_day);
        $this->assign('total_count',$total_count);
        $this->display("main_first");
    }
    
    
    public function get_vistior_list_ajax() {
        $client_account = $this->user['client_account'];
        import('@.Control.Api.VistiorApi');
        $Vistior = new VistiorApi();
        $offset = 0;
        $limit = 5;
        
        $vistior_list = $Vistior->vistior_list($client_account,'timeline desc',$offset,$limit);
        
        if(empty($vistior_list)) {
            $this->ajaxReturn(null,'获取访客列表失败！',-1,'json');
        }
        
            $this->ajaxReturn($vistior_list,'获取访客列表成功！',1,'json');
    }
    
    
    /**
     * 记录最近访客列表
     */
    private function write_vistior($vuid) {
        $client_account = $this->user['client_account'];
        if(empty($vuid) || $vuid == $client_account) {
             $this->redirect("/Sns/Index/index/client_account/$client_account");
        }
        
        $mPersonVistior = ClsFactory::Create('Model.PersonVistior.mPersonVistior');
        $wheresql = array(
            'uid='.$client_account,
            'vuid='.$vuid
        );
        
         
        $resault_vistior = $mPersonVistior->getPersonVistiorInfo($wheresql);
        $vistior_id = array_shift($resault_vistior['id']);
        
        $dataarr = array(
                'uid' => $client_account,
                'vuid'=> $vuid,
                'timeline' => time()
         );
        if(empty($resault_vistior)) {
            $resault = $mPersonVistior->addPersonVistior($dataarr);
        } else {
          $resault = $mPersonVistior->modifyPersonVistior($dataarr,$vistior_id);
        }
        
        return !empty($resault) ? $resault : false;
    }
        
}