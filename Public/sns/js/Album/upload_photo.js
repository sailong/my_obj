function class_photo_upload() {
	this.img_server = $('#img_server').val() || '/Public';
	this.initUpload();
};

class_photo_upload.prototype.initUpload=function() {
	var me = this;
	var settings = {
		flash_url : me.img_server + "/tool_flash/swfupload/swfupload.swf",
		upload_url: "/Sns/Album/Uppersonphoto/upload",
		post_params:{
			
		},
		file_size_limit : "8 MB",
		file_types : "*.jpg;*.gif;*.png",
		file_types_description : "全部文件",
		file_upload_limit : 10,
		file_queue_limit : 10,
		custom_settings : {
			progressTarget : "fsUploadProgress",
			cancelButtonId : "btnCancel"
		},
		debug: false,

		// Button settings
		button_image_url: self.img_server + "/tool_flash/swfupload/images/SmallSpyGlassWithTransperancy_17x18.png",
		button_width: "200",
		button_height: "18",
		button_placeholder_id: "spanButtonPlaceHolder",
		button_text: '<span class="button">请点击这里选择要上传的文件 <span class="buttonSmall"></span></span>',
		button_text_style: '.button{font-family: Helvetica, Arial, sans-serif; font-size: 12pt;} .buttonSmall{font-size: 10pt;}',
		button_text_left_padding: 18,
		button_text_top_padding: 0,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,

		file_queued_handler : fileQueued,
		file_queue_error_handler : fileQueueError,
		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,
		queue_complete_handler : queueComplete	// Queue plugin event
	};
	me.swfu = new SWFUpload(settings);
	
	//绑定开始上传的事件
	$("#start_upload").click(function() {
		var secret_key = $("#secret_key").val();
		var client_account = $("#uid").val();
		var xcid = $("#xcid").val();
		var postobj = { "secret_key" : secret_key, "client_account" : client_account, "xcid" : xcid };
		me.swfu.setPostParams(postobj);
		me.swfu.startUpload();
	});
};



$(document).ready(function(){
	new class_photo_upload();
});