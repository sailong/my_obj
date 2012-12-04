function searchhomework() {
	this.showdate();
	this.SubmitFrom();
	this.getaccepters();
	this.delete_homework();
};

searchhomework.prototype.showdate = function() {
	var self=this;
	$("#startdate").click(function() {
		WdatePicker();
	});
	
	$("#enddate").click(function() {
		WdatePicker();
	});
};

searchhomework.prototype.getaccepters = function() {
	$(":button[id^='click_accepters']").click(function() {
		
		var homework_id = $(this).attr('id').toString().match(/(\d+)/)[1];
		
		$.ajax({
			type:"post",
			data:{'homework_id' :homework_id},
			dataType:"json",
			url:"/Sns/ClassHomework/Published/accepters_json",
			async:false,
			success:function(json) {
				//展示内容
			}
		});
	});
;}

searchhomework.prototype.delete_homework = function() {
	$(":button[id^='del']").click(function() {
		var homework_id = $(this).attr('id').toString().match(/(\d+)/)[1];
		if(confirm('确定要删除该作业吗？')) {
			window.location.href='/Sns/ClassHomework/Del/del_homework/homework_id/'+ homework_id;
		} 
	});
};

searchhomework.prototype.SubmitFrom = function() {
	$("#search").click(function(){
		$("#form_search").submit();
	});
};

$(document).ready(function() {
	new searchhomework();
});