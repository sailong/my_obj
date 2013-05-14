function header() {
	this.init();

	this.homepage_nav = ['HomePage'];
	this.class_nav = ['Class', 'class_code'];
	this.person_nav = ['Person', 'PrivateMsg', 'Friend'];
	
	this.setNavPos();	
};

header.prototype.init = function(){
	$("#small_head_pic").error(function(){
		this.src = '/Public/images/head_pic.jpg';
	});
	
	var me = this;	
	$('.user_msg_ul > li').bind('mouseenter', openSubMenu);
	$('.user_msg_ul > li').bind('mouseleave', closeSubMenu);
	
	$("#show_msg_on_load").click(function(){
		$("#show_msg_on_load").hide();
	});	

	function openSubMenu() {
		
		$(this).find('ul').css('visibility', 'visible');
		$("#show_msg_on_load").trigger('click');
	};
	
	function closeSubMenu() {
		$(this).find('ul').css('visibility', 'hidden');	
	};
	
	var select_class_code = $.cookie('select_class_code');
	
	$("#ha1").click(function(){
		var url = '/Sns/HomePage/Index/index';
		if (select_class_code) {
			url = url + '/class_code/' + select_class_code;
		}
		window.location.href = url;
	});

	$("#ha2").click(function(){
		var url = '/Sns/ClassIndex/Index/index';
		if (select_class_code) {
			url = url + '/class_code/' + select_class_code;
		}
		window.location.href = url;
	});  
	
};

header.prototype.setNavPos=function(obj) {
	
	var url = window.location.href;

	var i = 0;
	$.each(this.homepage_nav,function(key,val){
		if (url.indexOf(val) >= 0) {
			i = 1;
		}
	})
	
	if (i == 0) {
		$.each(this.class_nav,function(key,val){
			if (url.indexOf(val) >= 0) {
				i = 2;
			}
		});
	}
	
	if (i == 0) {
		$.each(this.person_nav,function(key,val){
			if (url.indexOf(val) >= 0) {
				i = 3;
			}
		});
	}

	$('#head_nav a').each(function(k) {
			if (i == (k + 1)) {
				$(this).addClass("ha" + i);
			} else {
				$(this).removeClass("ha" + i);
			}
	});	
};

$(document).ready(function(){
	new header();
});