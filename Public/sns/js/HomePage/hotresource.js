function hotresource() {
	this.getResource();
};

hotresource.prototype.getResource = function() {
	var me = this;
	var context = $("#hot_resource");
	$.ajax({
		type:'post',
		url:'/Sns/Resource/Resource/getresource',
		dataType:'json',
		async:true,
		success:function(json) {
			if(json.status>0) {
				var j = 1;
				for(var i in json.data.resource_info) {
					var cloneObj = $('.clone',context).clone().removeClass('clone').show();
					var data = json.data.resource_info[i];
					if(j>3) {
						$('td span',cloneObj).addClass('num_bj');
					}else{
						$('td span',cloneObj).addClass('num_bj_hot');
						$('td a',cloneObj).addClass('rmzytj_hot');
					}
					data = $.extend(data,{num:j});
					cloneObj.renderHtml({
						data:data || {}
					});
					$('table',context).append(cloneObj);
					j++;
				}
			}
		}
	});
};
$(function(){
	new hotresource();
});