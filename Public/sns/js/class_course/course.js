function courseCls() {
	this.course_obj = null;
	this.course_keys = null;
	this.attachEvent();	
	
}

courseCls.prototype.attachEvent=function(){
	var self    = this;
	$("#ulam,#ulpm").children('li').each(function() {
		$(this).bind("click", function() {
			var course_obj = $(this).children(':first');
	
			self.course_obj  = course_obj;
			self.course_keys = course_obj.next().val().toString();
			self.editCourse(course_obj.html());
		});
	});
	
	$("#course_table tr").children('td').each(function() {
		$("span", $(this)).bind("click", function() {
			var tmp_course_name = $(this).html();
			var course_name = self.course_obj.html();
			
			//课程名称改变通过ajax 提交更新数据
			if ($.trim(tmp_course_name) != $.trim(course_name)) {
				self.course_obj.html(tmp_course_name);
				self.saveCourseAjax(tmp_course_name, self.course_keys);
			}
			
			$('#edit_course').dialog('close');
		});
	});
	
	//自定义课程名称添加
	$('#course_name_but').bind("click", function(){
		
		var course_name = $.trim($('#course_name').val().toString());
		var course_name_old = $.trim($('#course_name_old').val().toString()); 
		$('#span_course_name').html('');
		if (course_name == '') {
			$('#span_course_name').html('自定义课程名称不能为空');

		} else if(course_name == course_name_old) {
			$('#span_course_name').html('课程名称没有改变');

		} else {

			self.course_obj.html(course_name);
			self.saveCourseAjax(course_name, self.course_keys);
			
			$('#edit_course').dialog('close');
		}
		return ;
	});
		
	
};

//弹层
courseCls.prototype.editCourse=function(course_name) {
	var self = this;
	$('#edit_course').dialog({
		autoOpen:false,
		bgiframe:true,
		
		/*buttons:{
			'确定':function() {
				$(this).dialog('close');
			},
			'取消':function() {
				$(this).dialog('close');
			}
		},
		*/
		draggable:true,
		resizable:false,
		width:550,
		minHeight:300,
		modal:true,
		zIndex:9999,
		stack:true,
		position:'center',
		dialogClass: 'alert',
		beforeclose:function(event, ui) {
			
			return true;
		}
	});

	$('#course_name').val(course_name);
	$('#course_name_old').val(course_name);
	$('#span_course_name').html('');
	
	$('#edit_course').dialog('option', 'title', '选择您要设置的课程');
	$('#edit_course').dialog('open');
};

//ajax 更改课程
courseCls.prototype.saveCourseAjax=function(course_name, course_keys){
	var self    = this;
	var arr 	= course_keys.split(':');
	var weekday = arr[0];
	var num_th 	= arr[1];
	var class_code = $('#class_code').val();

	$.ajax({
		type:'post',
		url:'/Sns/ClassCourse/Course/saveCourseAjax',
		data:{
			'course_name': course_name,
			'weekday'	 : weekday,
			'num_th' 	 : num_th,
			'class_code' : class_code
		},
		dataType:'json',
		async:true,  //异步请求
		success:function(json) {
			//alert(json.status);
			
			self.course_obj = null;
			self.course_keys = null;
		}
	});
	
};

$(document).ready(function(){
	
	var obj = new courseCls();
});
