/**
 * 功能： 表情替换插件
 * 作者： lnczx
 * 时间：2013.4.27

 * 实现：
 *   动态内容的表情代号替换
 */
(function($){
	$.parseFace = {};
	var BASE_URL = "/Public/tool_js/sendbox/images/emotions/";
	var 
		//表情图片树的json格式
		FACE_DATA = [
					{id:14, title:'微笑'},
					{id:1 , title:'撇嘴'},
					{id:2 , title:'色'},
					{id:3 , title:'发呆'},
					{id:4 , title:'得意'},
					{id:5 , title:'流泪'},
					{id:6 , title:'害羞'},
					{id:7 , title:'闭嘴'},
					{id:8 , title:'睡'},
					{id:9 , title:'大哭'},
					{id:10 , title:'尴尬'},
					{id:11 , title:'发怒'},
					{id:12 , title:'调皮'},
					{id:13 , title:'呲牙'},
					{id:0 , title:'惊讶'},
					{id:15 , title:'难过'},
					{id:16 , title:'酷'},
					{id:96 , title:'冷汗'},
					{id:18 , title:'抓狂'},
					{id:19 , title:'吐'},
					{id:20 , title:'偷笑'},
					{id:21 , title:'可爱'},
					{id:22 , title:'白眼'},
					{id:23 , title:'傲慢'},
					{id:24 , title:'饥饿'},
					{id:25 , title:'困'},
					{id:26 , title:'惊恐'},
					{id:27 , title:'流汗'},
					{id:28 , title:'憨笑'},
					{id:29 , title:'大兵'},
					{id:30 , title:'奋斗'},
					{id:31 , title:'咒骂'},
					{id:32 , title:'疑问'},
					{id:33 , title:'嘘'},
					{id:34 , title:'晕'},
					{id:35 , title:'折磨'},
					{id:36 , title:'衰'},
					{id:37 , title:'骷髅'},
					{id:38 , title:'敲打'},
					{id:39 , title:'再见'},
					{id:97 , title:'擦汗'},
					{id:98 , title:'抠鼻'},
					{id:99 , title:'鼓掌'},
					{id:100 , title:'糗大了'},
					{id:101 , title:'坏笑'},
					{id:102 , title:'左哼哼'},
					{id:103 , title:'右哼哼'},
					{id:104 , title:'哈欠'},
					{id:105 , title:'鄙视'},
					{id:106 , title:'委屈'},
					{id:107 , title:'快哭了'},
					{id:108 , title:'阴险'},
					{id:109 , title:'亲亲'},
					{id:110 , title:'吓'},
					{id:111 , title:'可怜'},
					{id:112 , title:'菜刀'},
					{id:89 , title:'西瓜'},
					{id:113 , title:'啤酒'},
					{id:114 , title:'篮球'},
					{id:115 , title:'乒乓'},
					{id:60 , title:'咖啡'},
					{id:61 , title:'饭'},
					{id:46 , title:'猪头'},
					{id:63 , title:'玫瑰'},
					{id:64 , title:'凋谢'},
					{id:116 , title:'示爱'},
					{id:66 , title:'爱心'},
					{id:67 , title:'心碎'},
					{id:53 , title:'蛋糕'},
					{id:54 , title:'闪电'},
					{id:55 , title:'炸弹'},
					{id:56 , title:'刀'},
					{id:57 , title:'足球'},
					{id:117 , title:'瓢虫'},
					{id:59 , title:'便便'},
					{id:75 , title:'月亮'},
					{id:74 , title:'太阳'},
					{id:69 , title:'礼物'},
					{id:49 , title:'拥抱'},
					{id:76 , title:'强'},
					{id:77 , title:'弱'},
					{id:78 , title:'握手'},
					{id:79 , title:'胜利'},
					{id:118 , title:'抱拳'},
					{id:119 , title:'勾引'},
					{id:120 , title:'拳头'},
					{id:121 , title:'差劲'},
					{id:122 , title:'爱你'},
					{id:123 , title:'NO'},
					{id:124 , title:'OK'},
					{id:42 , title:'爱情'},
					{id:85 , title:'飞吻'},
					{id:43 , title:'跳跳'},
					{id:41 , title:'发抖'},
					{id:86 , title:'怄火'},
					{id:125 , title:'转圈'},
					{id:126 , title:'磕头'},
					{id:127 , title:'回头'},
					{id:128 , title:'跳绳'},
					{id:129 , title:'挥手'},
					{id:130 , title:'激动'},
					{id:131 , title:'街舞'},
					{id:132 , title:'献吻'},
					{id:133 , title:'左太极'},
					{id:134 , title:'右太极'}
				]	

	/**
	 * 将字符串中的表情代号以图片标签代替
	 */
	function textFormat(str){
		
		var reg;
		for(var i = 0; i < FACE_DATA.length; i++){
			reg = new RegExp("/" + FACE_DATA[i].title,"ig");
			str = str.replace(reg, '<img src="'+ BASE_URL + FACE_DATA[i].id+'.gif" width="24" height="24" />')
		}

		return str;	
	}
	//私有函数

	/**
	 * 图片转换，目的是将表情代号转化成图片地址
	 * 如:[微笑] == > <img src='smile.png' />
	 */
	function _switchImg(str){
		for(var i = 0; i < FACE_DATA.length; i++){
			if(IMGS_DATA[i].title == str){
				return '<img src="'+ BASE_URL + FACE_DATA[i].id+'.gif" width="24" height="24" />';
			}
		}
		return str;
	}

	//扩展到jquery
	$.parseFace = {
			textFormat : textFormat
	};
				
})(jQuery)
