/*
@版本日期: 版本日期: 2012年4月11日
@著作权所有: 1024 intelligence ( http://www.1024i.com )
//Download by http://www.codefans.net
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
		url : '/Sns/Album/Classphoto/getPhotosByAlbumId/class_code/23527/album_id/172/client_account/11070004',
		dataType : 'json',
		success : function(json)
		{
			if(typeof json == 'object')
			{
				var photo_list = json.data || {};
				var oProduct, $row, iHeight, iTempHeight;
				for(var i in photo_list)
				{
					oProduct = photo_list[i];
					// 找出当前高度最小的列, 新内容添加到该列
					iHeight = -1;
					//$liobj = $('<li><div><img src="'+oProduct.file_middle_url+'" border="0" ><br />'+oProduct.name+'</div></li><li><div><img src="'+oProduct.file_middle_url+'" border="0" ><br />'+oProduct.name+'</div></li><li><div><img src="'+oProduct.file_middle_url+'" border="0" ><br />'+oProduct.name+'</div></li><li><div><img src="'+oProduct.file_middle_url+'" border="0" ><br />'+oProduct.name+'</div></li><li><div><img src="'+oProduct.file_middle_url+'" border="0" ><br />'+oProduct.name+'</div></li>');
					///$liobj.appendTo($("#stage"));
					$('ul li').each(function(){
						iTempHeight = Number( $(this).height() );
						if(iHeight==-1 || iHeight>iTempHeight)
						{
							iHeight = iTempHeight;
							$row = $(this);
						}
					});
					var li_str = '<a class="twci-link" href="/Sns/Album/Classphoto/photo/album_id/'+oProduct.album_id+'/photo_id/'+oProduct.photo_id+'/class_code/'+oProduct.class_code+'">';
						li_str += '<img class="twcil-img" alt="'+oProduct.name+'" src="'+oProduct.file_middle_url+'"/>';
						li_str += '</a>';
						li_str += '<p class="pinlun comments" style="display:none;">';
						li_str += '<a href="javascript:;">评论（<span class="pl_count">'+oProduct.comments+'</span>）</a>';
						li_str += '</p>';
						li_str += '<h4 class="twci-header ">';
						li_str += '<a class="twcih-txt" href="#">'+oProduct.name+'</a>';
						li_str += '</h4>';
					$item = $(li_str).hide();
					
					$row.append($item);
					$item.fadeIn();
				}
			}
		}
	});
}