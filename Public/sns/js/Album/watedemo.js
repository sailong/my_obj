/*
@版本日期: 版本日期: 2012年4月11日
@著作权所有: 1024 intelligence ( http://www.1024i.com )

获得使用本类库的许可, 您必须保留著作权声明信息.
报告漏洞，意见或建议, 请联系 Lou Barnes(iua1024@gmail.com)
*/
$(document).ready(function(){
	loadMore();
});	

$(window).scroll(function(){
	// 当滚动到最底部以上100像素时， 加载新内容
	if ($(document).height() - $(this).scrollTop() - $(this).height()<100) loadMore();
});


function loadMore()
{
	$.ajax({
		type:"get",
		url:"/Sns/Album/Classphoto/getPhotosByAlbumId/class_code/23527/album_id/172/client_account/11070004",
		dataType:"json",
		async:false,
		success:function(json) {
			if(typeof json == 'object')
			{
				var photo_list = json.data || {};
				var parentObj = $('#container');
				var divClone = $('#clone_selector');
				for(var i in photo_list) {
					var photo_datas = photo_list[i] || {};
					photo_datas = $.extend(photo_datas,{'class_code':'23527'});
					
					if(!photo_datas.small_img) {
						photo_datas.small_img =  "sns/images/Album/class_list_photo_n/pic01.jpg";
					}
					var dlObj = divClone.clone();
					var dlObj = divClone.clone().attr('id','').appendTo(parentObj);
					dlObj.data('datas', photo_datas).renderHtml(photo_datas);
				}

			}
		}
	});
}