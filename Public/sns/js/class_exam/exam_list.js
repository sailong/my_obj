function examCls() {

	this.attachEvent();	
}

examCls.prototype.attachEvent=function(){
	var self = this;
//	$('#searchsubmit').submit(function() {
//		
//		var exam_name  = $.trim($("#exam_name").val());
//		var start_time = $.trim($("#start_time").val());
//		var end_time   = $.trim($("#end_time").val());
//		if(exam_name=="" && start_time =="" && end_time==""){
//			alert('请输入查询条件');
//			return false;
//		}
//		
//		return true;
//		
//	});
	
	$('#last_page').click(function(){
		var page = parseInt($('#page').val()) - 1;
		
		self.searchSubmit(page);
	});
	
	$('#next_page').click(function(){
		var page = parseInt($('#page').val()) + 1;
		
		self.searchSubmit(page);
		
	});


};

examCls.prototype.searchSubmit=function(page) {
	var class_code = $('#class_code').val();
	$('#searchsubmit').attr('action','/Sns/ClassExam/Exam/index/class_code/' + class_code + '/page/' + page);
	$('#searchsubmit').submit();
};
$(document).ready(function(){
	
	var obj = new examCls();
});
