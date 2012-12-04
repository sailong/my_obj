function writehomework() {
	this.init();
	this.add_class();
	this.submitForm();
	this.publish();
	
	this.cache = {};
	
	this.attchEvent();
	this.attchEventForForm();
}

writehomework.prototype.setCache=function(key, json) {
	if(!key) {
		return false;
	}
	
	this.cache[key] = json || {};
};

writehomework.prototype.getCache=function(key) {
	if(!key) {
		return {};
	}
	
	if(!$.isEmptyObject(this.cache[key])) {
		return this.cache[key];
	}
	
	return {};
};

writehomework.prototype.getSubjectCacheKey=function(subject_id) {
	return 'subject_' + subject_id;
};

writehomework.prototype.getClassCacheKey=function(class_code) {
	return 'class_' + class_code;
};

writehomework.prototype.init = function(){
	 $("input[type=radio][name=subject_id]:radio:first").attr("checked",true);  
};

//获取当前选中科目的下的班级列表
writehomework.prototype.add_class = function(){
	var self=this;
	$("#add_obj").click(function () {
		var subject_id = $("input[name='subject_id']:checked").val();
		
		var cache_key = self.getSubjectCacheKey(subject_id);
		
		var json = self.getCache(cache_key);
		if($.isEmptyObject(json)) {
			$.ajax({
				type:"post",
				data:{'subject_id' :subject_id},
				dataType:"json",
				url:"/Sns/ClassHomework/Classhomework/class_info_json",
				async:false,
				success:function(_json) {
					json = _json;
					self.setCache(cache_key, _json);
				}
			});
		}
		if(json.status > 0) {
			$('#choose').html('');
			
			var class_info = json.data;
			$("#choose").append("<select name='choice' id='choice'>");
			$("#choice").append("<option value='-1'>请选择</option>");
			$.each(class_info,function(class_code,class_info){
				$("#choice").append("<option value='"+ class_code +"'>" +class_info.class_name+ "</option>");
			});
		}else{
			alert('没有值！');
		}
	});
};


//动态提交选中的成员的帐号
writehomework.prototype.attchEventForForm=function() {
	$('form:first').submit(function() {
		var student_uids = [];
		$(':checkbox[name="choose_stu[]"]').each(function() {
			var student_id = $(this).val().toString();
			if(student_id) {
				student_uids.push(student_id);
			}
		});
		var student_uid_str = student_uids.join(',');
		
		$(this).append("<textarea style='display:none;' name='student_ids'>" + student_uid_str + "</textarea>");
	});
};

//绑定全选清空事件
writehomework.prototype.attchEvent=function() {
	var self = this;
	
	$('#tijiao').live('click', function() {
		//清空已选的值
		$("#show_students").html('');
		$("input[name='choose_stu[]']:checkbox:checked").each(function() {
			$("#show_students").append($("#client_name_" + $(this).val()).html());
		});
	});
	
	$('#cball').live('click', function() {
		 $(":checkbox[name='choose_stu[]']").attr("checked", $("#cball").attr("checked"));
	});
	
	$('#choice').live('change', function() {
		var class_code = $("#choice").val();
		
		var cache_key = self.getClassCacheKey(class_code);
		var json = self.getCache(cache_key);
		
		if($.isEmptyObject(json)) {
			$.ajax({
				type:"post",
				data:{'class_code' :class_code},
				dataType:"json",
				url:"/Sns/ClassHomework/Classhomework/student_info_json",
				async:false,
				success:function(_json) {
					json = _json;
					self.setCache(cache_key, _json);
				}
			});
		}
		
		if(json.status > 0) {
			$('#choose_students').html('');
			
			var student_info = json.data;
			$.each(student_info, function(client_account,student_info){
				$("#choose_students").append("<input type='checkbox' name='choose_stu[]' id='stu_choose" +client_account+ "' value='"+client_account+"'/>"+"<img src='"+student_info.client_headimg+"' height=30 width=30/> <span id='client_name_"+client_account+"'>"+student_info.client_name+"</span>");
			});
		}else{
			alert('没有学生信息！');
		}
	});
};

//全选过点击确定回显
function show_check() {
	var checked_students = $("input[name='choose_stu[]']:checkbox:checked");
	$("#show_students").html('');
	
	$.each(checked_students, function(i,checked_students){
		var student_name = $("#client_name_" + checked_students.value).html();
			$("#show_students").append(student_name);
		
	});
}

writehomework.prototype.publish = function() {
	var self=this;
	var bj = document.getElementById("ContentBg").value;
	document.getElementById('content').style.background = "url("+bj+")";
	$('#content').xheditor({skin:'vista',tools:'Separator,BtnBr,Blocktag,Fontface,FontSize,Bold,Italic,Underline,Strikethrough,FontColor,BackColor,SelectAll,Removeformat,Align,List,Outdent,Indent,Link,Unlink,Emot,Img',upImgUrl:'/Sns/ClassHomework/Classhomework/uploadPath'});
};

writehomework.prototype.submitForm = function() {
	$("#submitted").click(function(){
		$("#form").submit();
	});
	
	$("#dateimg").click(function() {
		WdatePicker({el:'date',minDate:'%y-%M-%d'});
	});
};

$(document).ready(function() {
	new writehomework();
});