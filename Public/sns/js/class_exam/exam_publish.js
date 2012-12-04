function publishCls() {

	this.attachEvent();	
}

publishCls.prototype.attachEvent=function(){
	var self = this;
	
	//保存草稿
	$('#draft_but').click(function(){
		url = "/Sns/ClassExam/Publish/draft";
		alert('1111111');
		$('#publish_from').submit(url, -1);
	});
	
	//预览发布 要检查数据完整性和正确性
	$('#preview_but').click(function(){
		url = "/Sns/ClassExam/Publish/preview";
		alert('222222');
		$('#publish_from').submit(url, 1);
	});
	
	$('#publish_from').submit(function(url, is_check) {
		alert('44444444');
		url      = typeof(url)      == "undefined" ? url : "/Sns/ClassExam/Publish/preview";
		is_check = typeof(is_check) == "undefined" ? 1 : is_check;
		alert(is_check);
		if (is_check == 1) {
			//检查数据
			return false;
		}

		$('#publish_from').attr('action',url);
		alert('333333333')
		return true;
	});
	
};


/*
 * 错误提示 todo 以后可能提到公共方法里面
 */
publishCls.prototype.showSuccess=function(type, msg) {
	
	$('#success_div').arrt('display', 'block');
};

$(document).ready(function(){
	
	var obj = new publishCls();
});
