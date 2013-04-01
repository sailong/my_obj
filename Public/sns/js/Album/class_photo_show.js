function photoShowCls() {
	this.limitInterval = null;
	this.max_length = 140;
	this.client_account = $("#client_account").val();
	this.login_account = $("#login_account").val();
	this.class_code = $("#class_code").val();
	this.album_id = $("#album_id").val();
	this.albumObj = $("#album_list_json").val();
	this.is_edit = $("#is_edit").val();
	this.img_server = $("#img_server").val();
	this.page = 1;
	this.url = "/Api/Album/getClassPhotoListByAlbumId/";
	this.showSize= $("#photo_num").val();
	this.preloadSize = 10;
	this.init();

};

photoShowCls.prototype.init=function() {
	var self = this;

	var galleriaObj = $('#galleria').galleriaWmw(
		 //首先是配置参数
		{
			autoplay:false,
			transition : 'fade',		
			showSize : self.showSize,
			url : self.url,
			theme : 'wmw/galleria.wmw.js' 	// 自定义样式
		},
		// 再次是查询数据的参数
		{
			client_account: self.client_account,
			class_code : self.class_code,
			album_id : self.album_id	
		}
	);
	

};

$(document).ready(function() {
	new photoShowCls();
});