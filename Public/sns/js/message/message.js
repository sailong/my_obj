function Message() {
	this.show_new();
	this.on_load();
	this.pushmsg();
	this.pullmsg();
};

/**
 * 加载socket.io
 * @return
 */
Message.prototype.on_load = function(){
	socket = io.connect('http://192.168.61.100',{port:8080});
	this.init();
};

/**
 * 通知服务器用户要订阅
 * @return
 */
Message.prototype.init = function(){
	var uid = $("#uid").val();
	
	socket.emit('on_load', uid);
	socket.emit('sub_msg', uid);
};

Message.prototype.show_new = function() {
	$("#new_show").click(function(){
		if(this.innerHTML == "》"){
			$("#news").show();
			this.innerHTML = "《"
		}else{
			$("#news").hide();
			this.innerHTML = "》"
		}
		
	});
};

/**
 * 消息推送的方法
 */
Message.prototype.pushmsg = function() {
	socket.on('message',function(data){
		alert(data);
//		var bjzy = $("#bjzy_num").innerHTML = data.homework;
//		var bjcj = $("#bjcj_num").innerHTML = data.exam;
//		var req = $("#req_num").innerHTML = data.req;
//		var comments = $("#comments_num").innerHTML = data.comments;
//		var bjgg = $("#bjgg_num").innerHTML = data.notice;
//		$("#bjzy_num").innerHTML = bjzy;
//		$("#bjcj_num").innerHTML = bjcj;
//		$("#req_num").innerHTML = req;
//		$("#comments_num").innerHTML = comments;
//		$("#bjgg_num").innerHTML = bjgg;
	});
};

/**
 * 消息拉去的方法
 */
Message.prototype.pullmsg = function() {
	socket.on('get_msg', function (data){
		var bjzy = $("#bjzy_num").innerHTML = data.homework;
		var bjcj = $("#bjcj_num").innerHTML = data.exam;
		var req = $("#req_num").innerHTML = data.req;
		var comments = $("#comments_num").innerHTML = data.comments;
		var bjgg = $("#bjgg_num").innerHTML = data.notice;
		$("#bjzy_num").innerHTML = bjzy;
		$("#bjcj_num").innerHTML = bjcj;
		$("#req_num").innerHTML = req;
		$("#comments_num").innerHTML = comments;
		$("#bjgg_num").innerHTML = bjgg;
	});
};

$(document).ready(function(){
	new Message();
});