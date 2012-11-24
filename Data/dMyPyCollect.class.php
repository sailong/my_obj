<?php
class dMyPyCollect extends dBase {

    protected $_tablename='wmw_py_collect';
    protected $_fields = array(
        'collect_id',
        'py_content',
        'client_account',
        'add_time',
    );
    protected $_pk = 'collect_id';
    protected $_index_list = array(
        'py_content',
        'collect_id',
        'client_account'
    );
    
    /*按评语类型读取评语
     * @param $account
     * return $py_collect_arr
     */
	public function getMyPycollectByaccount($clientAccount) {
		return $this->getInfoByFk($clientAccount, 'client_account', 'collect_id desc');
	}
	
	/*删除评语
	 * @param $py_id
	 * return $effect_rows
	 */
	public function delMyCollect($py_id) {
        return $this->delete($py_id);
	}
	
	/*收藏到我的评语库
	 * @param $arrPyCommentData
	 * @param $is_return_insert_id
	 * return $effect_rows OR $insert_id
	 */
    public function addMyPyCollect($datas, $is_return_id = false) {
        return $this->add($datas, $is_return_id);
    }
}