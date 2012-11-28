function skinCls() {
	this.attachEvent();	
}

skinCls.prototype.attachEvent=function(){
	var self = this;
	$("#skin_show li").each(function(){
		$("img", this).bind("click", function(){
			$("#skin_div").attr('style','background:url(' + this.src + ')');
			//调用ajax 保存用户设置的课程表皮肤
			self.saveSkinAjax(this.name);
		});
	});
		
};

skinCls.prototype.saveSkinAjax=function(skin_id){
	var self = this;

	$.ajax({
		type:'post',
		url:'/Sns/ClassCourse/Course/saveSkinAjax',
		data:{
			'skin': skin_id
		},
		dataType:'json',
		async:true,  //异步请求
		success:function(json) {
			//alert(json.status);
		}
	});
};

$(document).ready(function(){
	
	var obj = new skinCls();
});
