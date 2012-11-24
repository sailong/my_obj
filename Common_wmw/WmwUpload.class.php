<?php
define('WMW_UPLOAD_DIR', dirname(__FILE__));
//引入公共接口
include_once WMW_UPLOAD_DIR . "/Vendor/Upload/UploadfileInterface.php";
include_once WMW_UPLOAD_DIR . "/Vendor/WmwAutoLoader.class.php";

class WmwUpload implements UploadfileInterface {
    private $objUpload = null;
    
    public function __construct() {
        $this->objUpload = new Uploadfile();
    }
    
     /** 
     * 上传文件
     * @param $field 上传文件表单名称
     * @param $options 上传配制文件属性数组
     * @return boolean
     */
    public function upfile($field, $options = array()) {
        return $this->objUpload->upfile($field, $options);
    }
    
    public function _set_options($options = array()) {
        $this->objUpload->_set_options($options);
    }
    
    /** 
     * 取得错误信息
     * @param void
     * @return boolean
     */
    public function get_error() {
        return $this->objUpload->get_error();
    }
    
    /** 
     * 显示错误信息
     * @param $msg 错误信息
     * @return void
     */
    public function error($msg) {
        return $this->objUpload->error($msg);
    }
    
    public function ignore_mine() {
        if(method_exists($this->objUpload, 'ignore_mine')) {
            return $this->objUpload->ignore_mine();
        }
        
        return false;
    }
}