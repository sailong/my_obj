<?php
class mMypyCollect extends mBase {
	
    protected $_dPyCollect = null;
    
    public function __construct() {
        $this->_dPyCollect = ClsFactory::Create('Data.dMyPyCollect');
    }
    
	/*按评语类型读取评语
     * @param $account
     * return $py_collect_arr
     */
	public function getMyPycollectByaccount($account) {
	    if(empty($account)) {
	        return false;
	    }
	    
		return  $this->_dPyCollect->getMyPycollectByaccount($account);
	}

	/*删除评语
	 * @param $py_id
	 * return $effect_rows
	 */
	public function delMyCollect($py_id) {
	    if(empty($py_id)) {
	        return false;
	    }
	    
	    return $this->_dPyCollect->delMyCollect($py_id);
	}

	/*收藏到我的评语库
	 * @param $arrPyCommentData
	 * @param $is_return_insert_id
	 * return $effect_rows OR $insert_id
	 */
    public function addMyPyCollect($datas, $is_return_id) {
        if(empty($datas) || !is_array($datas)) {
            return false;
        }
        
		return  $this->_dPyCollect->addMyPyCollect($datas, $is_return_id);
    }
}
