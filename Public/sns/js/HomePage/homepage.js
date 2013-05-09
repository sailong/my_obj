
function homepage() {
	this.client_account = $("#client_account").val();
	this.attachEvent();
	this.setCookieSelectClass();
}


//填充好友列表
homepage.prototype.attachEvent=function() {
	var me = this;
	$('#checkin',$('.checkin_selector')).click(function() {
		me.checkin($(this));
	});
	$("#show_class_list").toggle(
	  function () {
		  me.set_position_show();
		  $("#class_list_div").css('z-index', 999).show();
	  },
	  function () {
		  me.set_position_show();
		  $("#class_list_div").css('z-index', 999).hide();
	  }
	);
};

homepage.prototype.setCookieSelectClass=function() {
	var me = this;
	var url = window.location.href;
	var select_class_code;
	if (url.indexOf('class_code') >= 0) {
		select_class_code = me.parseUrlParams(url, 'class_code');
	} else {
		$('#class_list_div a').each(function(k) {
			if (k == 0) {
				url = $(this).attr('href');
				select_class_code = me.parseUrlParams(url, 'class_code');
			}
		});				
	}

//    console.log('select_class_code =' + select_class_code);
	//保持班级选择统一
	//1. 先查看cookies 里面是否有已选择班级
	//2. 如果没有，则默认列表第一个为选择班级，并更新到cookies中
	//3. 切换班级，更新cookies
	$.cookie("select_class_code", select_class_code, {domain:".wmw.cn", path:"/"});
}

homepage.prototype.parseUrlParams=function(url, name) {
	var resultVal;
	var urlParts = url.split('/');
//	console.log(urlParts);	
	$.each(urlParts, function(key,val) {
//		console.log("key =" + key + " val =" + val);
		if (val == name) {
			resultVal = urlParts[key+1];
		}
	});
	return resultVal;
}

homepage.prototype.checkin=function(obj) {
	var me = this;
	$.ajax({
		type:'post',
		url:'/Sns/HomePage/Index/chckinOk',
		data:{},
		dataType:'json',
		async:'false',
		success:function(json) {
			if(json.status == 1) {
				$.showSuccess(json.info);
				obj.remove();
				$('#qd_td',$('.checkin_selector')).html('<span class="qd_font">已签到</span>');
			}
			$.showError('网络异常');
		}
	});
};
homepage.prototype.set_position_show = function() {
	var show_class_list_obj = $("#show_class_list");
	var class_list_div_obj = $("#class_list_div");
	var height = show_class_list_obj.css('height');
	var width = show_class_list_obj.css('width');
    var show_x = parseInt(show_class_list_obj.outerHeight()) + parseInt(show_class_list_obj.position().top);
    var show_y = show_class_list_obj.position().left;
    class_list_div_obj.css("position","absolute"); 
    class_list_div_obj.css("left",show_y + "px"); 
    class_list_div_obj.css('top',show_x + "px");
    class_list_div_obj.css('width',width);
};
$(document).ready(function() { 	
	new homepage();
});



