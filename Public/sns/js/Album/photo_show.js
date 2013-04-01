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
	this.preloadSize = 20;
	this.delegateEvent();
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
photoShowCls.prototype.delegateEvent=function() {
	var oSelf = this;
	//移动相片
	$('#photo_edit').delegate("#move_evt", 'click', function() {
		var divObj = $(this).parents("div:first");
		$("#move_photo_div").data('parentObj',divObj.data("datas"));
		oSelf.getAlbumList();
		
		var btnObj = $(this);
		art.dialog({
			id:'move_photo_dialog',
			follow:btnObj.get(0),
		    //background: '#600', // 背景色
		    opacity: 0.5,	// 透明度
			title:'移动照片',
			content:$("#move_photo_div").get(0),
			drag: false,
			fixed: false //固定定位 ie 支持不好回默认转成绝对定位
		});
		//$('.aui_close',$(".aui_titleBar")).hide();return false;
		
	});
	
	//相片详情
	$(".xpxq_a").toggle(
		  function () {
		   $("#icon_img").attr('class','icon_up');
		   var btnObj = $(this);
			art.dialog({
				id:'xpxq_dialog',
				follow:btnObj.get(0),
				//background: '#600', // 背景色
				opacity: 0.5,	// 透明度
				title:'相片详情',
				content:$("#xpxq_div").get(0),
				drag:false,
				fixed:false //固定定位 ie 支持不好回默认转成绝对定位
			});
			$('.aui_close',$(".aui_titleBar")).hide();return false;
		  },
		  function () {
			  $("#icon_img").attr('class','icon_down');
			  var dialogObj = art.dialog.list['xpxq_dialog'];
				if(!$.isEmptyObject(dialogObj)) {
					dialogObj.close();
				}
		  }
	);
	//移动相片
	$("#move_photo_div").delegate("a", 'click', function(){
		var self = $(this);
		art.dialog({
		    id: 'move',
		    content: '你确定要移动相片到〈'+self.text()+'〉',
		    button: [
		        {
		            name: '确定',
		            callback: function () {
		        		this.close();
			        	var parentObj = self.parents("div:first");
			    		var album_id = self.attr("id");
			    		oSelf.movePhoto(album_id,parentObj);
		                return false;
		            },
		            focus: true
		        },
		        {
		            name: '取消'
		        }
		    ]
		});
	});
	
	//删除相片
	$("#photo_edit").delegate('#del_evt','click',function() {
		$(".tcc_msg_center",$(".tcc_msg")).data('datas', $(this).parents("div:first").data('datas'));
		art.dialog({
			id:'del_photo_dialog',
		    //background: '#600', // 背景色
		    opacity: 0.5,	// 透明度
			title:'移动照片',
			content:$(".tcc_msg").get(0),
			drag: false,
			fixed: true //固定定位 ie 支持不好回默认转成绝对定位
		});
	});
	
	//删除相片 确定
	$(".tcc_msg").delegate('.qd_btn','click',function() {
		var obj = $(this).parents("div:first");
		oSelf.delPhoto(obj);
	});
	//删除相片 取消
	$(".tcc_msg").delegate('.qx_btn','click',function() {
		var dialogObj = art.dialog.list['del_photo_dialog'];
		if(!$.isEmptyObject(dialogObj)) {
			dialogObj.close();
		}
	});
	//设置相册封面
	$("#photo_edit").delegate('#set_img_evt','click',function() {
		var obj = $(this).parents("div:first");
		oSelf.setAlbumImg(obj);
	});
	
	//最后一张的提示
	$("body").delegate('#review_photo', 'click', function() {
		var dialogObj = art.dialog.list['last_photo_tip'];
		if(!$.isEmptyObject(dialogObj)) {
			dialogObj.close();
		}
		oSelf.lastIndex = 0;
		oSelf.init();
		
	});
	
	//添加相片描述
	$(".photo_name").delegate('#description', 'click', function(){
		var desscriptionObj = $(".description",$(".photo_name"));
		if(desscriptionObj.is(':hidden')) {
			$(".description",$(".photo_name")).show();
			return false;
		}
		$(".description",$(".photo_name")).hide();
	});
	//取消相片描述
	$(".photo_name").delegate('.gray_btn', 'click', function(){
		var desscriptionObj = $(".description",$(".photo_name"));
		if(!desscriptionObj.is(':hidden')) {
			$(".description",$(".photo_name")).hide();
			return false;
		}
	});
	//描述计算器
	$(".photo_name").delegate('.text', 'keypress', function(evt){
		var content = $.trim($(this).val()).toString();
		if(content.length >= oSelf.max_length) {
			var keyCode = evt.keyCode || evt.which;
			//字符超过限制后只有Backspace键能够按
			if(keyCode != 8) {
				$.showError('相片描述不能超过20字!');
				return false;
			}
		}
	});
	$(".photo_name").delegate('.text', 'focus', function(evt){
		oSelf.limitInterval = setInterval(function() {
			oSelf.reflushCounter();
		}, 10);
	});
	$(".photo_name").delegate('.text', 'blur', function(evt){
		clearInterval(oSelf.limitInterval);
	});
	//添加描述
	$(".photo_name").delegate('.green_btn', 'click', function() {
		if($('.text',$('.photo_name')).val() == '') {
			$.showError('描述内容不可为空！');
			return false;
		}
		oSelf.adddescription();
		$(".description",$(".photo_name")).hide();
		$("#description").html('<p><span>描述：</span><font id="description_font">'+$('.text',$('.photo_name')).val()+'</font></p>');
	});
	
	//评论
	$('.comment_reply_selector').live('click', function(){
		$.sendBox();
	});
	
	//评论删除
	$('.comment_delete_selector'). live('click', function(){
		var parentObj = $(this).parents('div:first');
		var pl_info = parentObj.data('datas');
		var comment_id = pl_info.comment_id;
		$.ajax({
			type:"post",
			dataType:"json",
			data:{"comment_id":comment_id},
			url:"/Api/Album/delPhotoCommentByClass",
			success:function(json) {
				if(json.status < 0) {
					$.showError(json.info);
				}
				parentObj.remove();
				$.showSuccess(json.info);
			}
		});
		
	});
	//评论 确定按钮
	 $("#edit_comment_div").delegate('#pl_qd', 'click', function() {
		var parentDivObj = $("#photo_edit");
		var photo_data = parentDivObj.data('datas');
		var photo_id = photo_data.photo_id;
		var content = $.trim($('.textarea', $("#edit_comment_div")).val()).toString();
		var add_uid = $("#login_account").val() || {};
		$.ajax({
			type:"post",
			dataType:"json",
			data:{"photo_id":photo_id,"content":content,"add_uid":add_uid},
			url:"/Api/Album/addCommentByClass",
			success:function(json) {
				var dialogObj = art.dialog.list['edit_comment_div_dialog'];
				if(!$.isEmptyObject(dialogObj)) {
					dialogObj.close();
				}
				if(json.status < 0) {
					$.showError(json.info);
				}
				var current_date = oSelf.getCurrentDate();
				var comment_id = json.data;
				var comment_list = {};
				comment_list[comment_id] =
									{"comment_id":comment_id,
									"photo_id":photo_id,
									"content":content,
									"client_account":add_uid,
									"add_date":current_date
									};
				oSelf.fillPhotoComments(comment_list);
				$.showSuccess(json.info);
			}
		});
		
	});
};
//获取当前时间
photoShowCls.prototype.getCurrentDate = function() {
	var myDate = new Date();
	var date_str = '';
	date_str += myDate.getFullYear();    //获取完整的年份(4位,1970-????)
	date_str += '/'+myDate.getMonth();       //获取当前月份(0-11,0代表1月)
	date_str += '/'+myDate.getDate();
	return date_str;
};
//添加相片描述
photoShowCls.prototype.adddescription = function() {
	var content = $.trim($('.text',$('.photo_name')).val()).toString();
	var photo_info = $("#photo_edit").data('datas');
	var photo_id = photo_info.photo_id;
	$.ajax({
		type:"post",
		data:{'photo_id':photo_id,'content':content},
		dataType:"json",
		async:true,
		url:"",
		success:function(json) {
			if(json.status<0) {
				$.showError(json.info);
				return false;
			}
			$.showSuccess(json.info);
		}
	});
	
};
photoShowCls.prototype.reflushCounter=function() {
	var oSelf = this;
	var context = $('.photo_name');
	
	var len = $.trim($('.text', context).val()).toString().length;
	var show_nums = this.max_length - len;
	show_nums = show_nums > 0 ? show_nums : 0;
	$("#span_count", context).html(show_nums);
};

//设为封面
photoShowCls.prototype.setAlbumImg=function(obj) {
	var oSelf = this;
	var dlObj = obj || {};
	var photo_datas = dlObj.data('datas') || {};
	var album_img = photo_datas.file_small;
	album_img = album_img || {};
	$.ajax({
		type:"post",
		data:{'album_id':oSelf.album_id,'album_img':album_img},
		dataType:"json",
		async:false,
		url:'',//"/Api/Album/setAlbumImgByClass",
		success:function(json) {
			if(json.status<0) {
				$.showError(json.info);
				return false;
			}
			$.showSuccess(json.info);
		}
	});
};
//删除相片
photoShowCls.prototype.delPhoto=function(obj) {
	var oSelf = this;
	var ancestorOb = obj;
	var photo_datas = ancestorOb.data('datas') || {};
	var photo_id = photo_datas.photo_id;
	$.ajax({
		type:"get",
		dataType:"json",
		url:'',//"/Api/Album/delPhotoByClass/class_code/" + oSelf.class_code + "/photo_id/" + photo_id,
		async:true,
		success:function(json) {
			if(json.status < 0) {
				$.showError(json.info);
				return false;
			}
			var liObj = $("#"+photo_id,$("#samples_list")).parents("li:first");//samples_list没有了
			liObj.remove();
			var dialogObj = art.dialog.list['del_photo_dialog'];
			if(!$.isEmptyObject(dialogObj)) {
				dialogObj.close();
			}
			$.showSuccess(json.info);
		}
	});
};

//照片移动
photoShowCls.prototype.getAlbumList=function() {
	var oSelf = this;
	var album_list_tmp = this.album_list || {};
	if($.isEmptyObject(album_list_tmp)) {
		$.ajax({
			type:"get",
			dataType:"json",
			url:"/Sns/Album/Classphoto/getAlbumList/class_code/"+oSelf.class_code,
			async:false,
			success:function(json) {
				if(json.status < 0) {
					$.showError(json.info);
				}
				album_list_tmp = json.data;
				delete album_list_tmp[oSelf.album_id];
			}
		});
	}
	oSelf.fillAlbumList(album_list_tmp);
	
};

photoShowCls.prototype.fillAlbumList = function (album_list) {
	var oSelf = this;
	var album_list = album_list || {};
	var move_obj = $("#move_photo_div");
	var a_str = "";
	for(var i in album_list) {
		var album_info = album_list[i];
		a_str += '<a id="'+album_info.album_id+'" href="javascript:;"><span>'+album_info.album_name+'</span></a>';
	}
	$("p",move_obj).html('');
	$(a_str).appendTo($("p",move_obj));
};

//移动照片
photoShowCls.prototype.movePhoto=function(album_id, photoObj) {
	var oSelf = this;
	album_id = album_id || {};
	var photo_datas = photoObj.data('parentObj') || {};
	var photo_id = photo_datas.photo_id || {};
	var from_album_id = photo_datas.album_id || {};
	var img_name = photo_datas.file_small || {};
	$.ajax({
		type:"post",
		data:{'to_album_id':album_id,'photo_id':photo_id,'from_album_id':from_album_id,'img_name':img_name},
		dataType:"json",
		url:"",///Api/Album/movePhotoByClass
		success:function(json) {
			if(json.status < 0) {
				$.showError(json.info);
				return false;
			}
			var liObj = $("#"+photo_id,$("#samples_list")).parents("li:first");
			liObj.remove();
			$.showSuccess(json.info);
			var dialogObj = art.dialog.list['move_photo_dialog'];
			if(!$.isEmptyObject(dialogObj)) {
				dialogObj.close();
			}
		}
	});
};

$(document).ready(function() {
	new photoShowCls();
});