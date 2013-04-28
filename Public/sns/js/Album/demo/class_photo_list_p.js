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
	$(".twc-item").delegate('.comments', 'click', function(){
		
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
				var oProduct, $row, iHeight, iTempHeight;
				var divClone = $('#clone_selector');
				for(var i in photo_list)
				{
					oProduct = photo_list[i];
					// 找出当前高度最小的列, 新内容添加到该列
					iHeight = -1;
					//$liobj = $('<li><div><img src="'+oProduct.file_middle_url+'" border="0" ><br />'+oProduct.name+'</div></li><li><div><img src="'+oProduct.file_middle_url+'" border="0" ><br />'+oProduct.name+'</div></li><li><div><img src="'+oProduct.file_middle_url+'" border="0" ><br />'+oProduct.name+'</div></li><li><div><img src="'+oProduct.file_middle_url+'" border="0" ><br />'+oProduct.name+'</div></li><li><div><img src="'+oProduct.file_middle_url+'" border="0" ><br />'+oProduct.name+'</div></li>');
					///$liobj.appendTo($("#stage"));
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
					/*var li_str = '<a class="twci-link" href="/Sns/Album/Classphoto/photo/album_id/'+oProduct.album_id+'/photo_id/'+oProduct.photo_id+'/class_code/'+oProduct.class_code+'">';
						li_str += '<img class="twcil-img" alt="'+oProduct.name+'" src="'+oProduct.file_middle_url+'"/>';
						li_str += '</a>';
						li_str += '<p class="pinlun comments" style="display:none;">';
						li_str += '<a href="javascript:;">评论（<span class="pl_count">'+oProduct.comments+'</span>）</a>';
						li_str += '</p>';
						li_str += '<h4 class="twci-header ">';
						li_str += '<a class="twcih-txt" href="#">'+oProduct.name+'</a>';
						li_str += '</h4>';*/
					var $item = divObj.hide();
					$row.append($item);
					$item.fadeIn();
				}
			}
		}
	});
	/*$.ajax({
		type:"get",
		url:"/Sns/Album/Classphoto/getPhotosByAlbumId/class_code/" + me.class_code + '/album_id/'+ me.album_id + '/client_account/' + me.client_account + serilize_params,
		dataType:"json",
		async:false,
		success:function(json) {
			if(json.status < 0) {
				is_success = false;
				return false;
			}
			me.fillPhotoList(json.data || {});
		}
	});*/
	return is_success;
};

/*photo_list.prototype.fillPhotoList=function(photo_list) {
	var me = this;
	photo_list = photo_list || {};
	var img_server = me.img_server || {};
	var parentObj = $('#container');
	var divClone = $('#clone_selector');
	for(var i in photo_list) {
		var photo_datas = photo_list[i] || {};
		photo_datas = $.extend(photo_datas,{'class_code':me.class_code});
		if(!photo_datas.small_img) {
			photo_datas.small_img = img_server + "sns/images/Album/class_list_photo_n/pic01.jpg";
		}
		var dlObj = divClone.clone().attr('id','').appendTo(parentObj).fadeIn(200);
		dlObj.data('datas', photo_datas).renderHtml(photo_datas);
	}
};*/

/*photo_list.prototype._renderItem=function(data) {
	var me = this;
	var img_server = me.img_server || {};
	var parentObj = $('#container');
	var divClone = $('#clone_selector');
	var photo_datas = data || {};
	photo_datas = $.extend(photo_datas,{'class_code':me.class_code});
	if(!photo_datas.small_img) {
		photo_datas.small_img = img_server + "sns/images/Album/class_list_photo_n/pic01.jpg";
	}
	var dlObj = divClone.clone().attr('id','').appendTo(parentObj).fadeIn(200);
	dlObj.data('datas', photo_datas).renderHtml(photo_datas);
	
	return dlObj;
};*/



$(document).ready(function() {
	var object = new photo_list();
	/*
	@版本日期: 版本日期: 2012年4月11日
	@著作权所有: 1024 intelligence ( http://www.1024i.com )
	//Download by http://www.codefans.net
	获得使用本类库的许可, 您必须保留著作权声明信息.
	报告漏洞，意见或建议, 请联系 Lou Barnes(iua1024@gmail.com)
	*/
	/*$(document).ready(function(){
		loadMore();
	});*/	
	
	/*$(window).scroll(function(){
		var page = $('#more').data('page');
		// 当滚动到最底部以上100像素时， 加载新内容
		if ($(document).height() - $(this).scrollTop() - $(this).height()<100) object.loadMorePhoto({
			page:page
		});
	});*/



/*    var $container = $('#container');
    
    $container.imagesLoaded(function(){
      $container.masonry({
        itemSelector: 'li',
    	isAnimated: true
      });
    });
    
    //翻页插件加载
	$container.infinitescroll({
	
		// callback		: function () { console.log('using opts.callback'); },
		navSelector  : '#more',    // selector for the paged navigation 
		nextSelector : '#more a',  // selector for the NEXT link (to page 2)
		itemSelector 	: "li",
		animate : true,
		debug		 	: false,
		dataType	 	: 'json',
		appendCallback	: false

    }, function( response ) {
    	var jsonData = $.parseJSON(response) || {};
    	var datas = jsonData.data || {};
    	var status = jsonData.status || '-1';
    	
    	if (status == '-1') {
    	// 如果最后一页，则取消绑定事件，不在加载
    		$(window).unbind('.infscr');
    	}
    	setTimeout(function() {
    		for(var i in datas) {
            	var item = object._renderItem(datas[i]);
            	$container.masonry( 'appended', item, true ); 
    		}
    	},1000);
		
	 });*/

    //返回顶部相关的代码
	$(window).scroll(function(){
		
		// 当滚动到最底部以上100像素时， 加载新内容
		if ($(document).height() - $(this).scrollTop() - $(this).height()<600){
			var page = $('#more').data('page');
			object.loadMorePhoto({
				page:page
			});
		} 
		if($(window).scrollTop() > 600) {
			$("#gotopbtn").css('display','').click(function(){
				$(window).scrollTop(0);
			});
		} else {
			$("#gotopbtn").css('display','none');
		}
		
	});
});