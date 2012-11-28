var redis = require('redis'),
	io = require('socket.io').listen(8080);

var channl = [];
	channl[0] = "homework";
	channl[1] = "comments";
	channl[2] = "req";
	channl[3] = "notice";
	channl[4] = "exam";

io.sockets.on('connection', function(socket){
    redis_obj = redis.createClient(),
    msg_sender = redis.createClient();
    var uid = 0;

    //定时获取redis中的数据提交至页面，再通过js处理
    redis_obj.on("message", function(uid, message){
        console.log('send one message to ' + uid );
        socket.emit('message', message);
    });

    socket.on('sub_msg', function(uid){
    	
    	for(i=0;i<channl.length;i++){
    		redis_obj.subscribe('msg:' + uid + ":" + channl[i]);
    	}
    });

    socket.on('on_load',function(uid){
        this.uid = uid;
        
        msg_sender.mget(["msg:11070004:homework", "msg:11070004:comments", "msg:11070004:req", "msg:11070004:notice", "msg:11070004:exam"], function (err, res) {
        		var news_arr = {};
	        		news_arr.homework = res[0] == null ? 0 : res[0];
	        		news_arr.comments = res[1] == null ? 0 : res[1];
	        		news_arr.req = res[2] == null ? 0 : res[2];
	        		news_arr.notice = res[3] == null ? 0 : res[3];
	        		news_arr.exam = res[4] == null ? 0 : res[4];
        		socket.emit('get_msg', news_arr);
        });
    });

    //断开连接callback,当关闭或者刷新网页的时候触发的事件
    socket.on('disconnect',function(){
    	
        var get_uid = this.uid;
        for(i=0;i<channl.length;i++){
        	
    		redis_obj.unsubscribe('msg:' + this.uid + ":" + channl[i]);
    		console.log('When close,unsubscribe to ' + get_uid);
    	}
    });
});