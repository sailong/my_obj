/**
 * 面临的问题：
 * 1. 不同的动态拥有不同的模板，但是js的实现只有一套代码；
 * 2. 动态相关的事件都是走委托，而事件的内部必然会涉及到元素的操作，而元素相关的对象与feed_unit创建的时候
 *    的一些设置相关；
 * 3. 严格意义上的事件委托，相应的事件只能和其当时所在的运行环境有关；
 * 
 * =》因此在feed_comment初始化的时候，对当前的对象进行绑定；
 * 
 * 阅读此段代码应该注意以下几点:
 * 1. 具备js面向对象的编程思想；
 * 2. 严格的事件委托机制；
 * 3. 基于配置可扩展的编程思路；
 */

function dump(obj) {
	
	for(var i in obj) {
		alert(i + "=>" + obj[i]);
	}
	
}

//扩展图片的处理函数
(function($) {
	//加载容器内部的图片信息
	$.fn.loadImg=function() {
		$('img', $(this)).each(function() {
			var data_original = $(this).attr('data-original');
			var data_from = $(this).attr('data-from');
			if(data_original) {
				$(this).attr('src', data_original);
			} else if(data_from) {
				$(this).attr('src', $(data_from).val());
			}
		});
		return this;
	};
})(jQuery);


(function($) {
	
	$(document).click(function(evt) {
		var pageX = evt.pageX;
		var pageY = evt.pageY;
		
		//$.sendboxHandler.close(aObj[0].handler_id);
	});
	
})(jQuery);



//针对sendbox句柄管理的扩展
(function($) {
	function handler() {
		
	}
	
	handler.prototype = {
		current_handler_id : 0
		
		,handler_pointer: 1
		
		,handler_list : []
		
		,register:function(openFunc, closeFunc) {
			var me = this;
			
			openFunc = openFunc || $noop;
			closeFunc = closeFunc || $.noop;
			
			me.handler_list[me.handler_pointer] = [openFunc, closeFunc];
			
			return me.handler_pointer++;
		}
	
		,open:function(handler_id) {
			this.close(this.current_handler_id);
			
			var handler_funcs = this.handler_list[handler_id];
			if(!$.isEmptyObject(handler_funcs)) {
				var fn = handler_funcs[0];
				typeof fn == 'function' && fn.call();
			}
			this.current_handler_id = handler_id;
		}
		
		,close:function(handler_id) {
			var handler_funcs = this.handler_list[handler_id];
			if(!$.isEmptyObject(handler_funcs)) {
				var fn = handler_funcs[1];
				typeof fn == 'function' && fn.call();
			}
		}
		
	};
	
	$.sendboxHandler = new handler();
})(jQuery);

(function($) {
	$.createFeedUnit=function(feed_datas, feed_type_name) {
		
		//获取系统自动注册的模块信息
		var modules = [];
		$(':input[name="register_model"]').each(function() {
			modules.push(this.value);
		});
		feed_type_name = $.inArray(feed_type_name, modules) >= 0 ? feed_type_name : modules[0];
		
		var templates = {
			feed_unit_tpl:'feed_unit_%s',
			comment_tpl:'comment_main_div',
			unit_1st_tpl:'comment_1st_unit_div',
			unit_2nd_tpl:'comment_2nd_unit_div',
			
			comment_params:'%s_comment_params',
			unit_1st_params:'%s_comments_1st_params',
			unit_2nd_params:'%s_comment_2nd_params'
		};
		
		//获取对应的参数设置
		function getOptions(feed_type_name) {
			var options = {};
			for(var name in templates) {
				options[name] = templates[name].replace('%s', feed_type_name);
			}
			return options;
		}
		
		var options = getOptions(feed_type_name);
		var FeedUnit = new feed_unit(options);
		
		return FeedUnit.createFeedUnit(feed_datas);
	};
})(jQuery);

function feed_unit(options) {
	feed_unit.globalInit();
	this.options = options || {};
}

feed_unit.initList = [];
       	
feed_unit.register = function(fn) {
	feed_unit.initList.push(fn || $.noop);
};

feed_unit.globalInit = function() {
	while(!$.isEmptyObject(feed_unit.initList)) {
		var fn = feed_unit.initList.shift() || $.noop;
		fn.call();
	}
};

//收集配置参数
feed_unit.collectParams = function(divObj) {
	var params = {
		post_params:{}
	};
	$(':input', divObj).each(function() {
		var inpObj = $(this);
		var name = inpObj.attr('name');
		var val = inpObj.val();
		params[name] = val;
		if(inpObj.parent().is('.post_selector')) {
			params.post_params[name] = val;
		}
	});
	return params;
};

feed_unit.prototype = {
	options:{
		feed_unit_tpl:'feed_unit_%s',
		comment_tpl:'comment_%s_main_div',
		unit_1st_tpl:'comment_1st_unit_div',
		unit_2nd_tpl:'comment_2nd_unit_div',
		
		comment_params:'%s_comment_params',
		unit_1st_params:'%s_comments_1st_params',
		unit_2nd_params:'%s_comment_2nd_params'
	}
	
	//动态对应的实体对象
	,feedUnitDivObj:{}

	//刷新动态对应实体的评论信息
	,reflushComments:function() {
		var me = this;
		
		var params = feed_unit.collectParams($('.feed_unit_params_selector', me.feedUnitDivObj));
		$.ajax({
			type:'get',
			url:"/Sns/Feed/List/getEntityAjax/feed_type/" + params.feed_type + "/from_id/" + params.from_id,
			dataType:'json',
			success:function(json) {
				if(json.status < 0) {
					return false;
				}
				var comments = json.data.comments || 0;
				var aObj = $('.feed_comment_selector', me.feedUnitDivObj);
				var html = aObj.html().toString() || "";
				var parttern = /([^\d]+)(\d+)([^\d*?])/;
				if(html.match(parttern)) {
					html = html.replace(parttern, function(a, b, c, d) {
						return b + comments + d;
					});
				} else {
					html = html + "(" + comments + ")";
				}
				aObj.html(html);
			}
		});
	}

	//创建动态对象
	,createFeedUnit:function(feed_datas) {
		var me = this;
		
		feed_datas = feed_datas || {};
		
		//创建feed_unit对象
		var FeedUnitdivObj = $('#' + me.options.feed_unit_tpl).clone().removeAttr('id').show();
		//根据feed类型创建不同的对象
		FeedUnitdivObj.renderHtml({
			feed:feed_datas || {}
		});
		
		//加载内容内的图片信息
		FeedUnitdivObj.loadImg();
		
		this.feedUnitDivObj = FeedUnitdivObj;
		
		//feed_unit元素对象保存了对应的当前类对象的引用
		FeedUnitdivObj[0].referObject = me;
		
		return FeedUnitdivObj;
	}
	
	//创建评论信息
	,createComment:function(from_id) {
		var me = this;
		
		var divObj = $("#" + me.options.comment_tpl).clone().removeAttr('id');
		//追加对应的参数设置
		var paramsDiv = $('#' + me.options.comment_params).clone().removeAttr('id');
		divObj.append(paramsDiv);
		
		//渲染页面的元素
		divObj.renderHtml({
			from_id:from_id
		});
		
		divObj = $(divObj);
		//加载内容内的图片信息
		divObj.loadImg();
		
		var params = feed_unit.collectParams($('.comment_params_selector', divObj));
		//加载实体的评论信息
		var comment_list = {};
		$.ajax({
			type:'get',
			url:params.get_comments_url || "",
			dataType:'json',
			async:false,
			success:function(json) {
				comment_list = json.data || {};
			}
		});
		
		var containObj = $('#first_comment_list', divObj);
		for(var i in comment_list) {
			var comment_datas = comment_list[i];
			var childDivObj = me.create1stUnit(comment_datas);
			
			childDivObj.data('datas', comment_datas);
			containObj.append(childDivObj);
		}
		
		return divObj;
	}
	
	//创建一级评论对象
	,create1stUnit:function(comment_datas) {
		var me = this;
		
		comment_datas = comment_datas || {};
		
		var divObj = $('#' + me.options.unit_1st_tpl).clone().removeAttr('id').show();
		var paramsDiv = $('#' + me.options.unit_1st_params).clone().removeAttr('id');
		divObj.append(paramsDiv);
		
		//对象的渲染操作
		divObj.renderHtml({
			comment:comment_datas || {}
		});
		divObj = $(divObj);
		//获取模板的参数设置
		var params = feed_unit.collectParams($('.comment_1st_unit_params_selector', divObj));
		var child_item_name = params.child_items_name || 'child_list';
		
		var parentObj = $('#second_comment_list', divObj);
		var child_list = comment_datas[child_item_name] || {};
		for(var i in child_list) {
			var child_comment = child_list[i];
			var childDivObj = me.create2ndUnit(child_comment);
			childDivObj.data('datas', child_comment);
			parentObj.append(childDivObj);
		}
		
		//加载内容内的图片信息
		divObj.loadImg();
		
		return divObj;
	}
	
	//创建2级评论对象
	,create2ndUnit:function(child_comment) {
		var me = this;
		
		var divObj = $('#' + me.options.unit_2nd_tpl).clone().removeAttr('id').show();
		var paramsDiv = $('#' + me.options.unit_2nd_params).clone().removeAttr('id');
		divObj.append(paramsDiv);
		
		divObj.renderHtml({
			child_comment:child_comment || {}
		});
		//加载内容内的图片信息
		divObj.loadImg();
		
		return divObj;
	}
	
};

//扩展评论相关的事件信息
(function(feed_unit) {
	
//评论相关的事件需要集中处理
function comment_events() {
	this.delegateEventForEffect();
	this.delegateEventForFeedUnit();
	this.delegateEventForComment();
	this.delegateEventForComment1stUnit();
	this.delegateEventForComment2ndUnit();
}

comment_events.prototype = {
	delegateEventForEffect:function() {
		//一级评论的删除效果
		$('.comment_1st_delete_slide_selector').live('mouseover', function() {
			$('.comment_1st_delete_selector', $(this)).show();
		}).live('mouseleave', function() {
			$('.comment_1st_delete_selector', $(this)).hide();
		});
	
		//二级评论的删除效果
		$('.comment_2nd_delete_slide_selector').live('mouseover', function() {
			$('.comment_2nd_delete_selector', $(this)).show();
		}).live('mouseleave', function() {
			$('.comment_2nd_delete_selector', $(this)).hide();
		});
	}

	,delegateEventForFeedUnit:function() {
		//删除按钮
		$('.feed_delete_selector').live('click', function() {
			var ancestorObj = $(this).closest('.feed_unit_selector');
			var aObj = $(this);
			
			var params = feed_unit.collectParams($('.feed_unit_params_selector', ancestorObj));
			//触发删除相关的事件
			$('#feed_delete_div').trigger('openEvent', [{
				datas : {
					feed_id:params.feed_id
				},
				follow:aObj.get(0),
				callback:function() {
					$(ancestorObj[0].followDiv).remove();
					//动画相关慢慢移除
					ancestorObj.animate({
						height:0
					}, 'slow').remove();
				}
			}]);
		});
		
		//评论按钮, 点击有切换的效果
		$('.feed_comment_selector').live('click', function() {
			var aObj = $(this);
			var ancestorObj = $(this).closest('.feed_unit_selector');
			
			var params = feed_unit.collectParams($('.feed_unit_params_selector', ancestorObj));
			var toggled_nums = aObj.data('toggled_nums') || 1;
			//初始化动态对应的评论层信息
			if(toggled_nums == 1) {
				//创建评论层
				var referObject = ancestorObj[0].referObject;
				var commentDivObj = referObject.createComment(params.from_id);
				
				commentDivObj.insertAfter(ancestorObj);
				commentDivObj[0].feedUnitObject = referObject;
				ancestorObj[0].followDiv = commentDivObj;
			}
			
			if(toggled_nums % 2 == 0) {
				$(ancestorObj[0].followDiv).hide();
			} else {
				$(ancestorObj[0].followDiv).show();
			}
			aObj.data('toggled_nums', toggled_nums + 1);
			
			return false;
		});
	}
	
	//comment相关的事件
	,delegateEventForComment:function() {
		$('.reply_1st_txt_selector').live('click', function() {
			var textObj = $(this);
			var ancestorObj = $(this).closest('.comment_main_selector');
			var tabObj = $('.reply_1st_full_tab_selector', ancestorObj);
			
			var params = feed_unit.collectParams($('.comment_params_selector', ancestorObj));
			//初始一级菜单的sendbox对象
			if(!tabObj.data('inited')) {
				$('.reply_1st_content_selector', tabObj).sendBox({
					panels:'emote',
					type:'post',
					url:params.publish_comment_url || "",
					data:params.post_params || {},
					dataType:'json',
					success:function(json) {
						if(json.status < 0) {
							$.showError(json.info);
							return false;
						}
						
						//添加对应的评论层
						var feedUnitObject = ancestorObj[0].feedUnitObject;
						var containObj = $('#first_comment_list', ancestorObj);
						var unitDivObj = feedUnitObject.create1stUnit(json.data || {});
						containObj.prepend(unitDivObj);
						$('.reply_1st_content_selector', tabObj).val('');
						
						feedUnitObject.reflushComments();
						
						$.sendboxHandler.close(textObj[0].handler_id);
					}
				});
				//注册到全局的句柄管理中去
				textObj[0].handler_id = $.sendboxHandler.register(function() {
					tabObj.show();
					textObj.hide();
				}, function() {
					tabObj.hide();
					textObj.show();
				});
				tabObj.data('inited', true);
			}
			
			$.sendboxHandler.open(textObj[0].handler_id);
		});
	}

	//一级评论相关的事件
	,delegateEventForComment1stUnit:function() {
		//一级评论的删除事件
		$('.comment_1st_delete_selector').live('click', function() {
			var ancestorObj = $(this).closest('.comment_1st_unit_selector');
			
			var commentDivObj = $(this).closest('.comment_main_selector');
			var feedUnitObject = commentDivObj[0].feedUnitObject;
			
			var params = feed_unit.collectParams($('.comment_1st_unit_params_selector', ancestorObj));
			$.ajax({
				type:'get',
				url:params.delete_comment_url,
				dataType:'json',
				success:function(json) {
					if(json.status < 0) {
						$.showError(json.info);
						return false;
					}
					ancestorObj.animate({
						height:'0px'
					}, 'slow').remove();
					
					//刷新评论数
					feedUnitObject.reflushComments();
				}
			});
		});
		
		//一级评论的回复事件,多次点击有切换功能
		$('.comment_1st_reply_selector').live('click', function() {
			var aObj = $(this);
			
			var commentDivObj = $(this).closest('.comment_main_selector');
			var feedUnitObject = commentDivObj[0].feedUnitObject;
			
			var ancestorObj = $(this).closest('.comment_1st_unit_selector');
			var reply2ndObj = $('.reply_2nd_selector', ancestorObj);
			
			var params = feed_unit.collectParams($('.comment_1st_unit_params_selector', ancestorObj));
			//第一次点击的时候初始化相应的sendbox对象
			if($.isEmptyObject(reply2ndObj[0].sendBoxObj)) {
				reply2ndObj[0].sendBoxObj = $('.reply_2nd_content_selector', ancestorObj).sendBox({
					panels:'emote',
					type:'post',
					url:params.publish_comment_url || "",
					data:params.post_params || {},
					dataType:'json',
					success:function(json) {
						if(json.status < 0) {
							$.showError(json.info);
							return false;
						}
						
						//创建一个二级评论的对象
						var unit2divObj = feedUnitObject.create2ndUnit(json.data || {});
						$('#second_comment_list', ancestorObj).prepend(unit2divObj);
						
						$.sendboxHandler.close(aObj[0].handler_id);
						
						//刷新评论数
						feedUnitObject.reflushComments();
					}
				});
				
				//注册到sendbox的管理列表
				aObj[0].handler_id = $.sendboxHandler.register(function() {
					reply2ndObj.css('display', 'block');
				}, function() {
					$('.reply_2nd_content_selector', ancestorObj).val('');
					reply2ndObj.css('display', 'none');
				});
			}
			//显示状态的切换
			if(reply2ndObj.css('display') == 'none') {
				$.sendboxHandler.open(aObj[0].handler_id);
			} else {
				$.sendboxHandler.close(aObj[0].handler_id);
			}
		});
		
		//绑定"我也来说一句"
		$('.reply_2nd_simple_selector').live('click', function() {
			var inpObj = $(this);
			var inpElem = inpObj[0];
			
			//处理相关的逻辑
			var ancestorObj = $(this).closest('.reply_2nd_simple_div_selector');
			var scopeObj = $(this).closest('.comment_1st_unit_selector');
			
			var params = feed_unit.collectParams($('.comment_1st_unit_params_selector', scopeObj));
			//获取要提交的相关数据信息
			//处理编辑框的相关事件
			if($.isEmptyObject(inpElem.sendBoxObj)) {
				inpElem.sendBoxObj = $('.reply_2nd_simple_content_selector', ancestorObj).sendBox({
					panels:'emote',
					type:'post',
					url:params.publish_comment_url || "",
					data:params.post_params || {},
					dataType:'json',
					success:function(json) {
						if(json.status < 0) {
							$.showError(json.info);
							return false;
						}
						
						//创建一个二级评论的对象
						var commentDivObj = inpObj.closest('.comment_main_selector');
						var feedUnitObject = commentDivObj[0].feedUnitObject;
						
						var unit2divObj = feedUnitObject.create2ndUnit(json.data || {});
						$('#second_comment_list', scopeObj).prepend(unit2divObj);
						
						$.sendboxHandler.close(inpElem.handler_id);
						//刷新评论数
						feedUnitObject.reflushComments();
					}
				});
				
				//注册到sendbox的管理列表
				inpElem.handler_id = $.sendboxHandler.register(function() {
					//获取输入焦点
					inpElem.sendBoxObj.focus();
					//显示sendbox所在的div
					inpObj.hide();
					$('.simple_reply_div_selector', ancestorObj).show();
				}, function() {
					inpObj.show();
					$('.simple_reply_div_selector', ancestorObj).hide();
				});
			}
			
			$.sendboxHandler.open(inpElem.handler_id);
		});
	}
	
	//委托二级评论对应的事件
	,delegateEventForComment2ndUnit:function() {
		//2级评论的删除事件
		$('.comment_2nd_delete_selector').live('click', function() {
			var ancestorObj = $(this).closest('.comment_2nd_unit_selector');
			
			var commentDivObj = $(this).closest('.comment_main_selector');
			var feedUnitObject = commentDivObj[0].feedUnitObject;
			
			var params = feed_unit.collectParams($('.comment_2nd_unit_params_selector', ancestorObj));
			$.ajax({
				type:'get',
				url:params.delete_comment_url,
				dataType:'json',
				success:function(json) {
					if(json.status < 0) {
						$.showError(json.info);
						return false;
					}
					ancestorObj.animate({
						height:'0px'
					}, 'slow').remove();
					//刷新评论数
					feedUnitObject.reflushComments();
				}
			});
			
			return false;
		});
	}
};

feed_unit.register(function() {
	new comment_events();
});

})(feed_unit);


//扩展feed_unit相关的事件
(function() {

function feed_delete() {
	this.attachEventUserDefine();
	this.delegateEvent();
}

feed_delete.prototype = {
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
	}
	
	//相应的事件委托
	,delegateEvent:function() {
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

feed_unit.register(function() {
	new feed_delete();
});

})();