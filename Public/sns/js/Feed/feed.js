(function($) {
	$.showError=function(msg) {
		art.dialog({
			id:'show_error_dialog',
			title:'错误提示',
			content:msg || '操作失败!',
			icon:'error'
		}).lock().time(3);
	};
	$.showSuccess=function(msg) {
		art.dialog({
			id:'show_error_dialog',
			title:'成功提示',
			content:msg || '操作成功!',
			icon:'succeed'
		}).lock().time(3);
	};
})(jQuery);


function dump(obj) {
	for(var i in obj)
		alert(i + "=>" + obj[i]);

}


function feed(options, elem) {
	this.init(options, elem);
	this.loadFeed(1);
};

//标示是否进行了全局初始化
feed.globalInited = false;

//动态相关的默认设置
feed.defaults = {
	template:'/Sns/Feed/List/loadFeedTemplateAjax'
};

//整个页面的模板信息
feed.templates = [];

//全局待初始化函数列表
feed.initFunctions = {};
feed.register=function(name, fn) {
	feed.initFunctions[name] = (typeof fn == 'function') ? fn : $.noop;
};

/**
 * 动态相关的全局初始化函数
 * 1. 事件的委托
 * 2. 加载子模板信息
 * @return
 */
feed.globalInit=function() {
	if(feed.globalInited) {
		return true;
	}
	for(var name in feed.initFunctions) {
		feed.initFunctions[name].call();
	}
	feed.globalInited = true;
};

feed.prototype = {
	//父级容器对象
	$elem : {},
	//表示是否已经初始化
	inited:false,
	
	//是否有下一页
	hasNextPage : true,
	
	//相关的设置
	settings: {},
	
	//动态列表容器
	feedListDivObj : {},
	
	//加载更多$对象
	loadMoreDivObj:{},
	
	/**
	 * 初始化
	 * 1. 主要在于模板加载;如何避免模板的重复加载
	 * 2. 以及相关的设置的处理,
	 * 3. div对象的创建
	 * @return
	 */
	init:function(options, elem) {
		var me = this;
		me.$elem = $(elem);
		//合并相关配置
		me.settings = $.extend({}, options || {});
		for(var i in feed.defaults) {
			if(me.settings[i]) {
				continue;
			}
			me.settings[i] = feed.defaults[i];
		}
		
		//加载模板信息
		if($.inArray(me.settings['template'], feed.templates) < 0) {
			$.ajax({
				url:me.settings['template'],
				dataType:'html',
				async:false,
				success:function(html) {
					$('body').append($(html));
				}
			});
			feed.templates.push(me.settings['template']);
		}
		
		//初始化全局配置信息
		feed.globalInit();
		
		//创建div容器对象
		me.feedListDivObj = $('#feed_list_div').clone().removeAttr('id').show();
		me.loadMoreDivObj = $('#load_more_div').clone().removeAttr('id').show();
		//将对象追加到父级容器
		me.$elem.append(me.feedListDivObj);
		me.$elem.append(me.loadMoreDivObj);
		
		//绑定事件
		me.attachEvent();
	},
	
	//绑定对应的加载更多的按钮信息
	attachEvent:function() {
		var me = this;
		//加载更多
		$('#load_more_feed_a', me.loadMoreDivObj).click(function() {
			if(!me.hasNextPage) {
				return false;
			}
			
			var page = $(this).data('page') || 1;
			me.loadFeed(page + 1);
			$(this).data('page', page + 1);
			
			return false;
		});
	},
	
	//加载动态信息
	loadFeed:function(page) {
		var me = this;
		page = page >= 1 ? page : 1;
		$.ajax({
			type:'get',
			url:me.settings['url'] + "/page/" + page,
			dataType:'json',
			success:function(json) {
				var feed_list = json.data || {};
				if(!$.isEmptyObject(feed_list)) {
					for(var i in feed_list) {
						var feed_datas = feed_list[i] || {};
						var divObj = feed_unit.create(feed_datas);
						divObj.data('datas', feed_datas);
						me.feedListDivObj.append(divObj);
					}
				} else {
					me.hasNextPage = false;
				}
			}
		});
	}
};

//将动态的加载扩展到$全局变量
$.fn.loadFeed=function(settings) {
	this.each(function() {
		elem = this;
		if($.isEmptyObject(elem.feed)) {
			elem.feed = new feed(settings, elem);
		}
	});
	return this;
};

var feed_unit = {
	//创建feed_unit对象
	create:function(feed_datas) {
		var divObj,
			feed_type = feed_datas.feed_type || 1;
		
		//根据feed类型创建不同的对象
		switch(feed_type) {
			case 1:
				divObj = $('#feed_unit_mood').clone().removeAttr('id').show();
				break;
			case 2:
				divObj = $('#feed_unit_blog').clone().removeAttr('id').show();
				break;
			case 3:
				divObj = $('#feed_unit_album').clone().removeAttr('id').show();
				break;
			default:
				divObj = $('#feed_unit_mood').clone().removeAttr('id').show();
				break;
		}
		
		divObj.renderHtml({
			feed:feed_datas || {}
		});
		return divObj;
	},
	
	//事件委托
	delegateEvent:function() {
		//删除按钮
		$('.feed_delete_selector').live('click', function() {
			var ancestorObj = $(this).parents('.feed_unit_selector:first');
			var datas = ancestorObj.data('datas') || {};
			var aObj = $(this);
			//触发删除相关的事件
			$('#feed_delete_div').trigger('openEvent', [{
				datas : datas,
				follow:aObj.get(0),
				callback:function() {
					$(ancestorObj[0].followDiv).remove();
					ancestorObj.remove();
				}
			}]);
		});
		
		//评论按钮, 点击有切换的效果
		$('.feed_comment_selector').live('click', function() {
			var ancestorObj = $(this).parents('.feed_unit_selector:first');
			var datas = ancestorObj.data('datas') || {};
			
			var aObj = $(this);
			var toggled_nums = aObj.data('toggled_nums') || 1;
			if(toggled_nums == 1) {
				$('#comment_main_div').trigger('cloneEvent', [{
					datas:datas,
					callback:function(divObj) {
						divObj.insertAfter(ancestorObj);
						ancestorObj[0].followDiv = divObj;
					}
				}]);
			}
			
			if(toggled_nums % 2 == 0) {
				$(ancestorObj[0].followDiv).hide();
			} else {
				$(ancestorObj[0].followDiv).show();
			}
			aObj.data('toggled_nums', toggled_nums + 1);
		});
	}
};

feed.register('initFeedUnit', function() {
	feed_unit.delegateEvent();
});

var feed_comment = {
	//创建一个comment对象
	create:function(feed_id) {
		var divObj = $('#comment_main_div').clone().attr('id', '');
		var parentObj = $('#first_comment_list', divObj);
		//初始化以及评论列表
		var comment_list = feed_comment.loadFirstLevelComment(feed_id);
		for(var i in comment_list) {
			var comment_datas = comment_list[i];
			var childDivObj = comment_1st_unit.create(comment_datas);
			childDivObj.data('datas', comment_datas);
			parentObj.append(childDivObj);
		}
		//渲染页面数据信息
		divObj.renderHtml({
			feed:{
				feed_id:feed_id
			}
		});
		
		//增加一级评论的初始化处理
		$('.reply_1st_content_selector', divObj).sendBox({
			panels:'emote,upload',
			type:'post',
			url:'/Sns/Feed/List/publishCommentAjax',
			data:{
				feed_id:feed_id,
				up_id:0
			},
			dataType:'json',
			success:function(json) {
				if(json.status < 0) {
					alert(json.info);
					return false;
				}
				//添加对应的评论层
				var unitDivObj = comment_1st_unit.create(json.data || {});
				var parentObj = $('#first_comment_list', divObj);
				var childObj = parentObj.children('.comment_1st_unit_selector:first');
				if(childObj.length > 0) {
					unitDivObj.insertBefore(childObj);
				} else {
					unitDivObj.appendTo(parentObj);
				}
				$('.reply_1st_content_selector', divObj).val('');
			}
		});
		
		return divObj;
	},
	
	//绑定动态评论的相关事件
	attachEventUserDefine:function() {
		$('#comment_main_div').bind({
			//评论层的克隆事件
			cloneEvent:function(evt, options) {
				options = options || {};
				var datas = options.datas || {};
				var divObj = feed_comment.create(datas.feed_id);
				divObj.data('datas', datas);
				//回调处理
				if(typeof options.callback == 'function') {
					options.callback(divObj);
				}
			}
		});
	},
	
	//加载一级评论信息
	loadFirstLevelComment:function(feed_id) {
		var comment_list = {};
		$.ajax({
			type:'get',
			url:'/Sns/Feed/List/getFeedCommentsAjax/feed_id/' + feed_id + "/level/1",
			dataType:'json',
			async:false,
			success:function(json) {
				comment_list = json.data || {};
			}
		});
		return comment_list;
	}
};

feed.register('initFeedComment', function() {
	feed_comment.attachEventUserDefine();
});

var comment_1st_unit = {
	create:function(comment_datas) {
		comment_datas = comment_datas || {};
		
		var divObj = $('#comment_1st_unit_div').clone().attr('id', '').show();
		var parentObj = $('#second_comment_list', divObj);
		var child_list = comment_datas.child_list || {};
		for(var i in child_list) {
			var child_comment = child_list[i];
			var childDivObj = comment_2nd_unit.create(child_comment);
			childDivObj.data('datas', child_comment);
			parentObj.append(childDivObj);
		}
		divObj.renderHtml({
			comment:comment_datas || {}
		});
		return divObj;
	},
	
	delegateEvent:function() {
		//一级评论的删除事件
		$('.comment_1st_delete_selector').live('click', function() {
			var ancestorObj = $(this).parents('.comment_1st_unit_selector:first');
			var comment_id = $('#comment_id', ancestorObj).val();
			$.ajax({
				type:'get',
				url:'/Sns/Feed/List/deleteCommentAjax/comment_id/' + comment_id,
				dataType:'json',
				success:function(json) {
					if(json.status < 0) {
						$.showError(json.info);
						return false;
					}
					ancestorObj.remove();
				}
			});
		});
		
		//一级评论的回复事件,多次点击有切换功能
		$('.comment_1st_reply_selector').live('click', function() {
			var ancestorObj = $(this).closest('.comment_1st_unit_selector');
			var reply2ndObj = $('.reply_2nd_selector', ancestorObj);
			
			var feed_id = $('*[name="feed_id"]', ancestorObj).val();
			var up_id = $('*[name="up_id"]', ancestorObj).val();
			
			//第一次点击的时候初始化相应的sendbox对象
			if($.isEmptyObject(reply2ndObj[0].sendBoxObj)) {
				reply2ndObj[0].sendBoxObj = $('.reply_2nd_content_selector', ancestorObj).sendBox({
					panels:'emote',
					type:'post',
					url:'/Sns/Feed/List/publishCommentAjax',
					data:{
						feed_id:feed_id,
						up_id:up_id
					},
					dataType:'json',
					success:function(json) {
						if(json.status < 0) {
							alert(json.info);
							return false;
						}
						//创建一个二级评论的对象
						var unit2divObj = comment_2nd_unit.create(json.data || {});
						var parentObj = $('#second_comment_list', ancestorObj);
						var childObj = parentObj.children('.comment_2nd_unit_selector:first');
						if(childObj.length > 0) {
							unit2divObj.insertBefore(childObj);
						} else {
							unit2divObj.appendTo(parentObj);
						}
						$('.reply_2nd_content_selector', ancestorObj).val('');
						reply2ndObj.hide();
					}
				});
			}
			//显示状态的切换
			if(reply2ndObj.css('display') == 'none') {
				reply2ndObj.css('display', 'block');
			} else {
				reply2ndObj.css('display', 'none');
			}
		});
	},
	
	delegateEventForReply2ndSimple:function() {
		//绑定"我也来说一句"
		$('.reply_2nd_simple_selector').live('click', function() {
			//按钮所在的P元素范围
			var pObj = $(this).closest('p');
			//处理相关的逻辑
			var ancestorObj = $(this).closest('.reply_2nd_simple_div_selector');
			var scopeObj = $(this).closest('.comment_1st_unit_selector');
			//获取要提交的相关数据信息
			var feed_id = $('*[name="feed_id"]', ancestorObj).val();
			var up_id = $('*[name="up_id"]', ancestorObj).val();
			//处理编辑框的相关事件
			if($.isEmptyObject(pObj[0].sendBoxObj)) {
				pObj[0].sendBoxObj = $('.reply_2nd_simple_content_selector', ancestorObj).sendBox({
					panels:'emote',
					type:'post',
					url:'/Sns/Feed/List/publishCommentAjax',
					data:{
						feed_id:feed_id,
						up_id:up_id
					},
					dataType:'json',
					success:function(json) {
						if(json.status < 0) {
							alert(json.info);
							return false;
						}
						//创建一个二级评论的对象
						var unit2divObj = comment_2nd_unit.create(json.data || {});
						var parentObj = $('#second_comment_list', scopeObj);
						var childObj = parentObj.children('.comment_2nd_unit_selector:first');
						if(childObj.length > 0) {
							unit2divObj.insertBefore(childObj);
						} else {
							unit2divObj.appendTo(parentObj);
						}
						pObj.show();
						$('.simple_reply_div_selector', ancestorObj).hide();
					}
				});
			}
			//获取输入焦点
			pObj[0].sendBoxObj.focus();
			//显示sendbox所在的div
			pObj.hide();
			$('.simple_reply_div_selector', ancestorObj).show();
		});
	}
};

feed.register('initCommnet1stUnit', function() {
	comment_1st_unit.delegateEvent();
	comment_1st_unit.delegateEventForReply2ndSimple();
});

var comment_2nd_unit = {
	create:function(child_comment) {
		var divObj = $('#comment_2nd_unit_div').clone().attr('id', '').show();
		divObj.renderHtml({
			child_comment:child_comment || {}
		});
		return divObj;
	},
	
	delegateEvent:function() {
		//2级评论的删除事件
		$('.comment_2nd_delete_selector').live('click', function() {
			var ancestorObj = $(this).parents('.comment_2nd_unit_selector:first');
			var comment_id = $('#comment_id', ancestorObj).val();
			$.ajax({
				type:'get',
				url:'/Sns/Feed/List/deleteCommentAjax/comment_id/' + comment_id,
				dataType:'json',
				success:function(json) {
					if(json.status < 0) {
						$.showError(json.info);
						return false;
					}
					ancestorObj.remove();
				}
			});
		});
	}
};

feed.register('initComment2ndUnit', function() {
	comment_2nd_unit.delegateEvent();
});

//动态的删除部分
var feed_delete = {
	//绑定用户自己定义事件
	attachEventUserDefine:function() {
		$('#feed_delete_div').bind({
			//打开删除层
			openEvent:function(evt, options) {
				options = options || {};
				var divObj = $(this);
				divObj.data('options', options);
				art.dialog({
					id:'feed_delete_dialog',
					title:'动态删除',
					content:divObj.get(0),
					follow:options.follow || {},
					init:function() {
						
					}
				}).lock();
			},
			//关闭删除层
			closeEvent:function() {
				var dialogObj = art.dialog.list['feed_delete_dialog'];
				if(!$.isEmptyObject(dialogObj)) {
					dialogObj.close();
				}
			}
		});
	},
	
	//相应的事件委托
	delegateEvent:function() {
		//确定按钮
		$('#feed_delete_sure_btn').live('click', function() {
			var ancestorObj = $(this).parents('#feed_delete_div');
			var options = ancestorObj.data('options') || {};
			var datas = options.datas || {};
			var feed_id = datas.feed_id;
			$.ajax({
				type:'get',
				url:'/Sns/Feed/List/deleteFeedAjax/feed_id/' + feed_id,
				dataType:'json',
				success:function(json) {
					if(json.status < 0) {
						$.showError(json.info);
						return false;
					}
					if(typeof options.callback == 'function') {
						options.callback();
					}
					$('#feed_delete_div').trigger('closeEvent');
				}
			});
		});
		
		//关闭按钮
		$('#feed_delete_cancel_btn').live('click', function() {
			$('#feed_delete_div').trigger('closeEvent');
		});
	}
};

feed.register('initFeedDelete', function() {
	feed_delete.attachEventUserDefine();
	feed_delete.delegateEvent();
});

$(document).ready(function() {
	$('#show_feed').loadFeed({
		url:'/Sns/Feed/List/getUserAllFeedAjax'
	});
});
