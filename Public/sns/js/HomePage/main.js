function main() {
	this.attachEvent();
	this.init();
}

main.prototype = {
	init:function() {
		//完成对我的全部动态的初始化
		$('#user_all_feed_a').trigger('click');
	}		
	
	//动态相关的事件处理
	,attachEvent:function() {
		var me = this;
		
		//发布个人说说的相关事件的绑定
		$('.say_textarea', $('#send_mood_div')).sendBox({
			panels:'emote,upload',
			chars:140,
			type:'post',
			url:'/Sns/Mood/PersonMood/publishAjax',
			dataType:'json',
			success:function(json) {
				if(json.status < 0) {
					$.showError(json.info);
					return false;
				}
				$.showSuccess(json.info);
				
				var data = json.data;
				var feed_info = data.feed_info;

				$('#user_all_feed_div').prependChild(feed_info);

				if ($('#user_my_feed_a').data('inited')) {
					$('#user_my_feed_div').prependChild(feed_info);
				}
			}
		});		

		//获取用户的全部动态
		$('#user_all_feed_a').click(function() {
			var aObj = $(this);
			me.selectFeed({
				a_id:'user_all_feed_a', 
				div_id:'user_all_feed_div',
				feed_url:'/Sns/Feed/List/getUserAllFeedAjax'
			});
			
			return false;
		});
		
		//获取班级的全部动态
		$('#class_feed_a').click(function() {
			var class_code = $('#class_code').val();
			me.selectFeed({
				a_id:'class_feed_a', 
				div_id:'class_feed_div',
				feed_url:'/Sns/Feed/List/getClassAllFeedAjax/class_code/' + class_code
			});
			
			return false;
		});
		
		//获取好友的动态
		$('#user_friend_feed_a').click(function() {
			me.selectFeed({
				a_id:'user_friend_feed_a', 
				div_id:'user_friend_feed_div',
				feed_url:'/Sns/Feed/List/getUserFriendFeedAjax'
			});
			return false;
		});
		
		//获取与我相关
		$('#user_my_feed_a').click(function() {
			me.selectFeed({
				a_id:'user_my_feed_a', 
				div_id:'user_my_feed_div',
				feed_url:'/Sns/Feed/List/getUserMyFeedAjax'
			});
			
			return false;
		});
	}

	//选择当前的动态类型
    ,selectFeed:function(options) {
    	var aObj = $('#' + options.a_id);
    	
    	aObj.siblings('.sec_nav_selected').removeClass('sec_nav_selected').addClass('sec_nav_noselected');
    	aObj.removeClass('sec_nav_noselected').addClass('sec_nav_selected');

		if(!aObj.data('inited')) {
			$('#' + options.div_id).loadFeed({
				url:options.feed_url,
				skin:'mini'
			});

			aObj.data('inited', true);
		}
		
		$('#user_all_feed_div,#class_feed_div,#user_friend_feed_div,#user_my_feed_div').hide();
		$('#' + options.div_id).show();
	}	
};

$(document).ready(function() {
	new main();
});
