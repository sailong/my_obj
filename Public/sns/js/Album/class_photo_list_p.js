(function($) {
	$.sendCommentBox = function(sendOptions){
		var photo_id = sendOptions.photo_id || {};
		var login_account = sendOptions.login_account || {};
		var class_code = sendOptions.class_code || {};
		var up_id = photo_id || {};
		var album_id = sendOptions.album_id || {};
		var paramData = {"photo_id":photo_id,"add_uid":login_account,"up_id":up_id,"class_code":class_code,"album_id":album_id};
		var commnetTextareaObj = sendOptions.textareaObj || {};
		var sendBoxObj = commnetTextareaObj.sendBox({
			//加载工具条，多个选项之间使用逗号隔开，目前支持：表情：emoto，文件上传：upload(form表单提交的文件的名字为:pic)
			panels:'emote',
			//设置编辑框中的字符数限制
			chars:120,
			//限制文件上传大小,(单位是：m 兆)
			file_size:2,
			//设置编辑框对应的样式,对应查看sendbox相应的目录对应的css文件目录下的css文件中的样式名的后缀,
			skin:'default',
			//表单的提交类型，建议使用post的方式，支持(get, post)
			type:'post',
			//表单提交到的位置
			url:'/Sns/Album/Photocomment/addPhotoComment',
			//数据返回格式，支持：json,html等数据格式，于success回调函数的数据格式保持一致
			data:paramData,
			dataType:'json',
			//表单提交前验证信息，返回false表示验证失败，表单不提交；返回true表示通过验证；
			beforeSubmit:function() {
				if(sendBoxObj.getSource() == ""){
					$.showTip("请您输入评论内容");
					return false;
				}
				return true;
			},
			//服务器返回数据后的回调函数
			success:function(json) {
				if(typeof sendOptions.callback == "function") {
					sendOptions.callback(json);
				}
			}
		},true);
		return sendBoxObj;
	};
	
})(jQuery);

function photo_list() {
	this.client_account = $("#client_account").val();
	this.login_account = $("#login_account").val();
	this.class_code = $("#class_code").val();
	this.album_id = $("#album_id").val();
	this.albumObj = $("#album_list_json").val();
	this.is_edit = $("#is_edit").val();
	this.img_server = $("#img_server").val();
	
	this.delegateEvent();
	this.init();
};


photo_list.prototype.init=function() {
	this.loadMorePhoto({
		page:1
	});
	$('#more').data('page', 1);
};


photo_list.prototype.delegateEvent=function() {
	var me = this;
	//显示评论操作
	$('li').delegate('.twc-item', 'mouseover', function() {
		var photo_info = $(this).data('datas');
		$('.comments', $(this)).show();
	});
	
	//隐藏设为封面，删除，移动操作
	$('li').delegate('.twc-item', 'mouseleave', function() {
		$('.comments', $(this)).hide();
	});
	
	//评论
	$(".comments",$(".twc-item")).live('click', function(){
		var div_obj = $(this).parents('.twc-item');
		var photo_data = div_obj.data("datas") || {};
		var up_id = 0;
		var sendOptions = {
				textareaObj:$("#comment_area"),
				photo_id:photo_data.photo_id || {},
				login_account:me.login_account || {},
				class_code:me.class_code || {},
				album_id:me.album_id || {},
				up_id:up_id,
				callback:function(jsonData){
					var dialogObj = art.dialog.list['edit_comment_div_dialog'];
					if(!$.isEmptyObject(dialogObj)) {
						dialogObj.close();
					}
					if(jsonData.status < 0) {
						$.showError(jsonData.info);
						return false;
					}
					$.showSuccess(jsonData.info);
					$pl_count = parseInt($(".pl_count", div_obj).text());
					$pl_count = $pl_count+1;
					$(".pl_count", div_obj).text($pl_count);
				}
		};
		$.sendCommentBox(sendOptions);
		art.dialog({
		    id: 'edit_comment_div_dialog',
		    opacity: 0.5,	// 透明度
		    content: $("#edit_comment_div").get(0),
		    drag: false,
			fixed: true, //固定定位 ie 支持不好回默认转成绝对定位
			close: function(event, ui) {
				$('.iwbQQFace:first').fadeOut(200);
			} //这是关闭事件的回调函数,在这写你的逻辑
		}).lock();
	});
};

photo_list.prototype.loadMorePhoto=function(options) {
	var me = this;
	options = options || {};
	//serilize
	var serilize_params = "";
	for(var name in options) {
		if(!options[name]) {
			continue;
		}
		serilize_params += "/" + name + "/" + options[name];
	}
	var is_success = true;
	$.ajax({
		url : '/Sns/Album/Classphoto/getPhotosByAlbumId/class_code/' + me.class_code + '/album_id/'+ me.album_id + '/client_account/' + me.client_account + serilize_params,
		dataType : 'json',
		success : function(json){
			if(json.status < 0) {
				is_success = false;
				return false;
			}
			if(typeof json == 'object')
			{
				var page = $('#more').data('page');
				page = page+1;
				$('#more').data('page',page);
				var photo_list = json.data || {};
				var oProduct, $row, $item, iHeight, iTempHeight;
				var divClone = $('#clone_selector');
				
				for(var i in photo_list)
				{
					oProduct = photo_list[i];
					// 找出当前高度最小的列, 新内容添加到该列
					iHeight = -1;
					
					$('#container li').each(function(){
						iTempHeight = Number( $(this).height() );
						if(iHeight==-1 || iHeight>iTempHeight)
						{
							iHeight = iTempHeight;
							$row = $(this);
						}
						
					});
					oProduct = $.extend(oProduct,{'class_code':me.class_code});
					if(!oProduct.small_img) {
						oProduct.small_img = img_server + "sns/images/Album/class_list_photo_n/pic01.jpg";
					}
					var divObj = divClone.clone().attr('id','');
					divObj.data('datas', oProduct).renderHtml(oProduct);
					$row.append(divObj);
					divObj.fadeIn();
				}
			}
		}
	});

	return is_success;
};




$(document).ready(function() {
	var object = new photo_list();
	//返回顶部相关的代码
	$(window).scroll(function(){
		if($(this).scrollTop() > 600) {
			$("#gotopbtn").css('display','').click(function(){
				$(window).scrollTop(0);
			});
		} else {
			$("#gotopbtn").css('display','none');
		}
		// 当滚动到最底部以上100像素时， 加载新内容
		if ($(document).height() - $(this).scrollTop() - $(this).height()<1){
			var page = $('#more').data('page');
			object.loadMorePhoto({
				page:page
			});
		} 
	});
});