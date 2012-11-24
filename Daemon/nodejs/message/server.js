var redis = require("redis"),
	client = redis.createClient(),
	io = require('socket.io').listen(8080);
    
io.set('transports', [ 'websocket', 'flashsocket', 'htmlfile', 'xhr-polling', 'jsonp-polling']);

io.sockets.on('connection', function(socket) {
	client.subscribe("msg:11070004:BJGG");
	client.subscribe("msg:11070004:BJZY");
client.on("message", function(channel, message) {
		socket.emit('news', {
			msg : message
		});
	});

//断开连接callback,当关闭或者刷新网页的时候触发的事件
socket.on('disconnect',function()
{
    client.unsubscribe('msg:11070004:BJGG');
    client.unsubscribe('msg:11070004:BJZY');
});
});