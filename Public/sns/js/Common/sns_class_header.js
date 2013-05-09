function sns_class_header(){
	this.init();
};

sns_class_header.prototype.init = function(){
	var me = this;	
	
	var user_client_type = $('#user_client_type').val();
	if (user_client_type == 1) {
		$('.class_change_ul').css('visibility', 'visible');	
	}	

	$('.class_change_ul > li').bind('mouseenter', openSubMenu);
	$('.class_change_ul > li').bind('mouseleave', closeSubMenu);
	
	function openSubMenu() {
		$(this).find('ul').css('visibility', 'visible');
		$(this).find('ul').css('z-index', 1000);
	};
	
	function closeSubMenu() {
		$(this).find('ul').css('visibility', 'hidden');	
	};	
	
	$('#class_list li').click(function(){
		var class_code = this.id;
		$.cookie("select_class_code", class_code, {domain:".wmw.cn", path:"/"});
		window.location.href="/Sns/ClassIndex/Index/index/class_code/" + class_code;
	});	

};

$(document).ready(function(){
	new sns_class_header();
});