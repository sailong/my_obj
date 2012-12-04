var bSet = {};
bSet.subject_mark = 'subject_id_{id}';
bSet.subject_name_mark = 'subject_name_{id}';
bSet.content = 'content';
bSet.end_time = 'end_time';
bSet.end_time_img = 'end_time_img';
bSet.add_accept_btn = 'add_accept_btn';
bSet.upload_file = 'file_name';
bSet.preview_btn = 'preview_btn';

//弹层相应的id设置
var pSet = {};
pSet.pop_div = 'pop_div';
pSet.class_list_select = 'class_list_select';
pSet.check_all_btn = 'check_all_btn';
pSet.sure_btn = 'sure_btn';
pSet.close_pop_div_a = 'close_pop_div_a';
//内容id标示
pSet.list_tab = 'pop_student_list_tab';
pSet.list = {};
pSet.list.img_mark = 'img_{id}';
pSet.list.user_name_mark = 'user_name_{id}';
pSet.list.chkbox_mark = 'chkbox_{id}';
//用户选择信息回显id设置
var viewSet = {};
viewSet.show_div_mark = 'show_div_{id}';
viewSet.clone_div = 'accept_list_div_for_clone';

//预览div设置
var pvSet = {};
pvSet.pop_div = 'preview_div';
pvSet.subject_name = 'subject_name';
pvSet.end_time = 'end_time';
pvSet.content = 'content';
pvSet.upload_file_name = 'upload_file_name';

pvSet.block_container = 'accepters_list';

pvSet.block = {};
pvSet.block.class_name = 'class_name';
pvSet.block.student_list = 'student_list';

pvSet.pub_btn = 'pub_btn';
pvSet.pub_with_msg_btn = 'pub_with_msg_btn';

//将事件的绑定和具体的实现函数分开
function Publish() {
	this.class_info_cache = {};
	this.class_list_cache = {};
	this.class_student_cache = {};
	
	this.editor = {};
	
	this.init();
	this.attachEventForBase();
	this.attachEventForPopdiv();
	this.attachEventForPreview();
}

Publish.prototype.init=function() {
	//加载编辑框
	var bg = $('#ContentBg').val();
	if(bg) $('#' + bSet.content).css('url', bg);
	this.editor = $('#' + bSet.content).xheditor({
		skin:'vista',
		tools:'Separator,BtnBr,Blocktag,Fontface,FontSize,Bold,Italic,Underline,Strikethrough,FontColor,BackColor,SelectAll,Removeformat,Align,List,Outdent,Indent,Link,Unlink,Emot,Img',
		upImgUrl:'/Sns/ClassHomework/Classhomework/uploadPath'
	});
};

/**
 * 绑定发布页面的基本事件
 * @return
 */
Publish.prototype.attachEventForBase=function() {
	var self = this;
	//绑定科目点击事件,todolist
	$(':input[id^="' + bSet.subject_mark.replace('{id}', '') + '"]').change(function() {
		//情况所有的数据信息
		$('#' + bSet.end_time).val('');
		$('#' + bSet.upload_file).val('');
		
		var show_div_prefix = viewSet.show_div_mark.replace('{id}', '');
		$('*[id^="' + show_div_prefix + '"]').remove();
	});
	
	//绑定交付时间
	$('#' + bSet.end_time).click(function() {
		WdatePicker({minDate:'%y-%M-%d'});
	});
	
	$('#' + bSet.end_time_img).click(function() {
		WdatePicker({el:bSet.end_time, minDate:'%y-%M-%d'});
	});
	
	//接受对象选择按钮
	$('#' + bSet.add_accept_btn).click(function() {
		self.initPopDiv();
	});
	//预览发布按钮
	$('#' + bSet.preview_btn).click(function() {
		if(self.validator()) {
			self.initPreviewDiv();
		}
	});
	//表单提交时间
	$('form:first').submit(function() {
		self.formDataCollect();
		return self.validator();
	});
};

/**
 * 绑定预览相关的事件
 * @return
 */
Publish.prototype.attachEventForPreview=function() {
	var self = this;
	var context = $('#' + pvSet.pop_div);
	//发布作业按钮
	$('#' + pvSet.pub_btn, context).click(function() {
		$('#is_sms').remove();
		$('form:first').submit();
	});
	//发布作业+短信按钮
	$('#' + pvSet.pub_with_msg_btn, context).click(function() {
		$('<input type="hidden" id="is_sms" name="is_sms" value="1"/>').appendTo($('form:first'));
		$('form:first').submit();
	});
};

/**
 * 绑定弹出相关的事件
 * @return
 */
Publish.prototype.attachEventForPopdiv=function() {
	var self = this;
	//班级下拉框改变事件
	$('#' + pSet.class_list_select).change(function() {
		//获取已经选择的班级code
		var class_code = $(this).val();
		//加载班级的成员选择列表
		var student_json = self.loadClassStudents(class_code);
		self.fillClassStudents(student_json);
	});
	
	//全选按钮点击事件
	$('#' + pSet.check_all_btn).click(function() {
		var chkbox_prefix = pSet.list.chkbox_mark.replace('{id}', '');
		$(':checkbox[id^="' + chkbox_prefix + '"]').attr('checked', $(this).attr('checked'));
	});
	
	//确定按钮点击事件
	$('#' + pSet.sure_btn).click(function() {
		//获取当前选中的班级
		var class_code = $('#' + pSet.class_list_select).val();
		//回显数据到页面
		var selected_student = {};
		var chkbox_prefix = pSet.list.chkbox_mark.replace('{id}', '');
		$(':checkbox[id^="' + chkbox_prefix + '"]').filter(':checked').each(function() {
			var uid = $(this).attr('id').toString().match(/(\d+)/)[1];
			var user_name = $('#' + pSet.list.user_name_mark.replace('{id}', uid)).text();
			selected_student[uid] = user_name;
		});
		
		var div_id = viewSet.show_div_mark.replace('{id}', class_code);
		if(!$.isEmptyObject(selected_student)) {
			//判断相应班级的div是否存在
			if($('#' + div_id).length == 0) {
				//创建一个新的div对象
				var cloneDiv = $('#' + viewSet.clone_div).clone().attr('id', div_id).show();
			    $('.accept_list').append(cloneDiv);
			}
			//绑定数据,将选中的数据绑定到对应的div中的data属性上,
			$('#' + div_id).data('data', selected_student || {});
			//绑定事件
			self.attachEventForAcceptList(div_id);
			//此时的数据直接和班级关联，与科目无关
			$('#' + div_id).children('span').remove();
			for(var i in selected_student) {
				$('<span style="padding-left:10px;">' + selected_student[i] + '</span>').appendTo($('#' + div_id));
			}
		} else {
			$('#' + div_id).remove();
		}
		//关闭弹出层
		$('#' + pSet.pop_div).dialog('close');
	});
	
	//弹出右侧图片关闭按钮
	$('#' + pSet.close_pop_div_a).click(function() {
		$('#' + pSet.pop_div).dialog('close');
	});
};

/**
 * 绑定选中对象回显事件
 * @return
 */
Publish.prototype.attachEventForAcceptList=function(div_id) {
	var context = $('#' + div_id);
	if(context.length == 0) {
		return false;
	}
	var self = this;
	var class_code = context.attr('id').toString().match(/(\d+)/)[1];
	//回显信息的编辑按钮,以班级组织数据
	$('.edit_a', context).click(function() {
		//弹出弹层，并选中相应的数据(选中年级信息和该年级下选择的成员列表)
		$('#' + pSet.class_list_select).children('*[value="' + class_code + '"]').attr('selected', true);
		//从对应的div的data属性上获取选中的数据信息
		self.initPopDiv();
		//选中班级成员的状态信息
		self.checkClassStudent(class_code);
		//取消全选按钮的选中状态
		$('#' + pSet.check_all_btn).attr('checked', false);
	});
	
	//回显信息的删除按钮
	$('.delete_a', context).click(function() {
		//删除改班级选择的用户信息
		$('#' + div_id).remove();
	});
};
//获取班级的学生信息
Publish.prototype.loadClassStudents=function(class_code) {
	var self = this;
	var cache_key = "class_code:" + class_code;
	var json = self.class_student_cache[cache_key];
	if($.isEmptyObject(json)) {
		$.ajax({
			type:"post",
			data:{'class_code' :class_code},
			dataType:"json",
			url:"/Sns/ClassHomework/Publish/student_info_json",
			async:false,
			success:function(_json) {
				json = self.class_student_cache[cache_key] = _json;
			}
		});
	}
	
	return json || {};
};

//填充学生列表
Publish.prototype.fillClassStudents=function(json) {
	//清空相应的数据
	$('#' + pSet.list_tab + ' *').remove();
	var self = this;
	if(json.status <= 0) {
		alert(json.info);
		return false;
	}
	var data = json.data;
	for(var i in data) {
		//填充相应的数据
		var trObj = $('<tr></tr>');
		$('<td id="img_' + data[i].client_account + '">' + data[i].client_headimg + '</td>').appendTo(trObj);
		$('<td id="user_name_' + data[i].client_account + '">' + data[i].client_name + '</td>').appendTo(trObj);
		$('<input type="checkbox" id="chkbox_' + data[i].client_account + '" /><span>选择</span>').appendTo('<td></td>').appendTo(trObj);
		trObj.appendTo($('#' + pSet.list_tab));
	}
	return true;
};

//勾选班级成员
Publish.prototype.checkClassStudent=function(class_code) {
	if(!class_code) {
		return false;
	}
	var div_id = viewSet.show_div_mark.replace('{id}', class_code);
	//获取当前班级选中的学生列表
	var data = $('#' + div_id).data('data');
	//将选中的成员勾选上
	for(var uid in data) {
		//把相应的checkbox对应的复选框勾上
		var chkbox_id = pSet.list.chkbox_mark.replace('{id}', uid);
		$('#' + chkbox_id).attr('checked', true);
	}
	return true;
};

//远程加载班级列表
Publish.prototype.loadClassList=function(subject_id) {
	if(!subject_id) {
		return false;
	}
	var self = this;
	var cache_key = "subject_id:" + subject_id;
	var class_list_json = self.class_list_cache[cache_key];
	if($.isEmptyObject(class_list_json)) {
		$.ajax({
			type:"post",
			data:{'subject_id' :subject_id},
			dataType:"json",
			url:"/Sns/ClassHomework/Publish/class_info_json",
			async:false,
			success:function(_json) {
				class_list_json = self.class_list_cache[cache_key] = _json;
				$.extend(self.class_info_cache, _json.data || {});
			}
		});
	}
	return class_list_json || {};
};

//填充班级列表
Publish.prototype.fillClassList=function(json) {
	$('#' + pSet.class_list_select + ' option:gt(0)').remove();
	
	if(json.status <= 0) {
		alert(json.info);
		return false;
	}
	//数据填充
	var parentObject = $('#' + pSet.class_list_select);
	var data = json.data;
	for(var i in data) {
		$('<option value="' + data[i].class_code + '">' + data[i].class_name + '</option>').appendTo(parentObject);
	}
	//默认选择第一个班级
	$('#' + pSet.class_list_select + ' option:eq(1)').attr('selected', true);
	
	return true;
};

//表单数据收集函数
Publish.prototype.formDataCollect=function() {
	$('.accepters').remove();
	//将当前活动的学生列表上的数据收集起来整理为数组格式或者以逗号分隔的字符串格式
	var div_prefix = viewSet.show_div_mark.replace('{id}', '');
	$('*[id^="' + div_prefix + '"]').each(function() {
		var class_code = $(this).attr('id').toString().match(/(\d+)/)[1];
		var data = $(this).data('data') || {};
		var uids = [];
		for(var uid in data) {
			uids.push(uid);
		}
		$('<textarea name="accept_list[' + class_code + ']" class="accepters"></textarea>').text(uids.join(',')).appendTo($('form:first'));
	});
};

Publish.prototype.openPopDiv=function(div_id) {
	$('#' + div_id).dialog({
		autoOpen:false,
		bgiframe:true,
		/*
		buttons:{
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
	var title = $('#title', $('#' + div_id)).val();
	$('#' + div_id).dialog('option', 'title', title);
	$('#' + div_id).dialog('open');
};

//初始化弹出层的相关数据
Publish.prototype.initPopDiv=function() {
	var self = this;
	//加载班级对象列表
	var subject_prefix = bSet.subject_mark.replace('{id}', '');
	var subject_id = $(':input[id^="' + subject_prefix + '"]:checked').val();
	var class_list_json = self.loadClassList(subject_id);
	self.fillClassList(class_list_json);
	//获取已经选择的班级code
	var class_code = $('#' + pSet.class_list_select).val();
	//加载班级的成员选择列表
	var student_list_json = self.loadClassStudents(class_code);
	self.fillClassStudents(student_list_json);
	//选中班级成员的状态信息
	self.checkClassStudent(class_code);
	//打开弹出层
	self.openPopDiv(pSet.pop_div);
};

//初始化预览的相关数据
Publish.prototype.initPreviewDiv=function() {
	var self = this;
	
	var context = $('#' + pvSet.pop_div);
	//科目信息
	var subject_id = $('*[id^="' + bSet.subject_mark.replace('{id}', '') + '"]').filter(':checked').val();
	var subject_name = $('#' + bSet.subject_name_mark.replace('{id}', subject_id)).html();
	$('#' + pvSet.subject_name, context).html(subject_name);
	//交付日期
	var end_time = $('#' + bSet.end_time).val();
	$('#' + pvSet.end_time, context).html(end_time);
	
	//作业内容
	var content = self.editor.getSource();
	$('#' + pvSet.content, context).html(content);
	//作业附件
	var upload_file_name = $('#' + bSet.upload_file).val().toString().split('/').pop();
	$('#' + pvSet.upload_file_name, context).html(upload_file_name);
	
	//情况已经存在的成员列表信息
	$('#' + pvSet.block_container, context).children('.pv_list').remove();
	//接受对象,todolist
	var div_id = viewSet.show_div_mark.replace('{id}', '');
	$('*[id^="' + div_id + '"]').each(function() {
		var class_code = $(this).attr('id').toString().match(/(\d+)/)[1];
		var blockObj = $('.clone', context).clone().removeClass().addClass('pv_list').show();
		//班级名称
		var class_info = self.class_info_cache[class_code] || {};
		$('#' + pvSet.block.class_name, blockObj).html(class_info.class_name);
		//班级成员列表
		var data = $(this).data('data');
		for(var i in data) {
			$('<span>' + data[i] + '</span>').appendTo($('#' + pvSet.block.student_list, blockObj));
		}
		blockObj.appendTo($('#' + pvSet.block_container, context));
	});
	//弹出层
	self.openPopDiv(pvSet.pop_div);
};

Publish.prototype.validator=function() {
	var self = this;
	var subject_prefix = bSet.subject_mark.replace('{id}', '');
	if($('*[id^="' + subject_prefix + '"]').filter(':checked').length == 0) {
		alert('请选择科目!');
		return false;
	}
	if(!$.trim($('#' + bSet.end_time).val())) {
		alert('请交作业日期!');
		return false;
	}
	if(!self.editor.getSource()) {
		alert('请填写作业内容!');
		return false;
	}
	if(!self.isSelectAccepters()) {
		alert('请选择接受对象!');
		return false;
	}
	
	return true;
};

Publish.prototype.isSelectAccepters=function() {
	var div_id = viewSet.show_div_mark.replace('{id}', '');
	var selected = false;
	$('*[id^="' + div_id + '"]').each(function() {
		var data = $(this).data('data');
		if(!$.isEmptyObject(data)) {
			selected = true;
			return false;
		}
	});
	return selected;
};

$(document).ready(function() {
	new Publish();
});
