var timer = null;
var offset = 5000;
var index = 1;

//大图交替轮换
////Jquery图片自适应高度
////id,图片的ID
//////MaxW，图片允许的最大宽度
//////MaxH,图片允许的最大高度  
function FomartSize1(MaxW, MaxH) {
    $('#bigpicarea img').each(function() {
	    var maxWidth = MaxW; // 图片最大宽度
	    var maxHeight = MaxH;    // 图片最大高度
	    var ratio = 0;  // 缩放比例
	    var width = $(this).width();    // 图片实际宽度
	    var height = $(this).height();  // 图片实际高度
	 
	    // 检查图片是否超宽
	    if(width > maxWidth){
	        ratio = maxWidth / width;   // 计算缩放比例
	        $(this).css("width", maxWidth); // 设定实际显示宽度
	        height = height * ratio;    // 计算等比例缩放后的高度 
	        $(this).css("height", height);  // 设定等比例缩放后的高度
	    }
	 
//	    // 检查图片是否超高
//	    if(height > maxHeight){
//	        ratio = maxHeight / height; // 计算缩放比例
//	        $(this).css("height", maxHeight);   // 设定实际显示高度
//	        width = width * ratio;    // 计算等比例缩放后的高度
//	        $(this).css("width", width * ratio);    // 设定等比例缩放后的高度
//	    }
    });
}

function FomartSize(MaxW, MaxH) {
	$('#bigpicarea img').each(function() {
	   var width = $(this).width();    // 图片实际宽度
	   var height = $(this).height();  // 图片实际高度
       var Maxwidth = MaxW;//获得最大宽度
       var Maxheight = MaxH;///获得最大高度
       //var margnheight;///设置图片距顶部的距离
       var newheight;///用来存放图片改变大小后的高度，后面设置据顶高度时会用到

       if (width > Maxwidth || height > Maxheight) {
           if (width > height) {
               $(this).width(Maxwidth);
           }else {
               $(this).height(Maxheight);
               newheight = Maxheight;
           }
       }else {
           $(this).width(width);
           $(this).height(height);
       }
	});
//////调整图片距顶部的距离，在显示时可以让图片上下居中,
///因为我原先设置的img的父级容器已经设置了 text-algin：center；所以左右居中就不必了。
/////当图片的高度height ）大于允许的最大高度时,则图片高度在上面的过程中被设置成为了Maxwidth 。
///因此，高度刚好填充满，则margin-top为0

///若要图片居中，margnheight需等于允许的最大高度和图片新高度的差的1/2
       
////给图片添加CSS样式
      
   }
function slideImage(i){
    var id = 'image_'+ target[i];
    FomartSize1(774,483);
//    var imgobj = $('#'+ id+' img');
//    var img_height = imgobj.height();
//    var img_width = imgobj.width();

//    if(img_width > 774) {
//    	imgobj.attr("width",774);
//    	img_height = (774/img_width)*img_height;
//    	imgobj.attr("height",img_height);
//    }
//  if(img_width > 774) {
//	imgobj.attr("width",774);
//	img_height = (774/img_width)*img_height;
//	imgobj.attr("height",img_height);
//  }
    $('#'+ id)
        .animate({opacity: 1}, 800, function(){
            $(this).find('.word').animate({height: 'show'}, 'slow');
        }).show()
        .siblings(':visible')
        .find('.word').animate({height:'hide'},'fast',function(){
            $(this).parent().animate({opacity: 0}, 800).hide();
        });
}
//bind thumb a
function hookThumb(){    
    $('#thumbs li a')
        .bind('click', function(){
            if (timer) {
                clearTimeout(timer);
            }                
            var id = this.id;            
            index = getIndex(id.substr(6));
            rechange(index);
            slideImage(index); 
            timer = window.setTimeout(auto, offset);  
            this.blur();            
            return false;
        });
}
//bind next/prev img
function hookBtn(){
    $('#thumbs li img').filter('#play_prev,#play_next')
        .bind('click', function(){
            if (timer){
                clearTimeout(timer);
            }
            var id = this.id;
            if (id == 'play_prev') {
                index--;
                if (index < 0) index = 6;
            }else{
                index++;
                if (index > 6) index = 0;
            }
            rechange(index);
            slideImage(index);
            timer = window.setTimeout(auto, offset);
        });
}

function bighookBtn(){
    $('#bigpicarea p span').filter('#big_play_prev,#big_play_next')
        .bind('click', function(){
            if (timer){
                clearTimeout(timer);
            }
            var id = this.id;
            if (id == 'big_play_prev') {
                index--;
                if (index < 0) index = 6;
            }else{
                index++;
                if (index > 6) index = 0;
            }
            rechange(index);
            slideImage(index);
            timer = window.setTimeout(auto, offset);
        });
}

//get index
function getIndex(v){
    for(var i=0; i < target.length; i++){
        if (target[i] == v) return i;
    }
}
function rechange(loop){
    var id = 'thumb_'+ target[loop];
    $('#thumbs li a.current').removeClass('current');
    $('#'+ id).addClass('current');
}
function auto(){
    index++;
    if (index > 6){
        index = 0;
    }
    rechange(index);
    slideImage(index);
    timer = window.setTimeout(auto, offset);
}
$(function(){    
    //change opacity
    $('div.word').css({opacity: 0.85});
    auto();  
    hookThumb(); 
    hookBtn();
	bighookBtn();
    
});/*  |xGv00|d7b30c0224cec55b59311c4f2af116f7 */