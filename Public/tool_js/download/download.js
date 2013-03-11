// Creating a jQuery plugin:  
function download(url, options) {
	if(!url) {
		return false;
	}
    //create iframe, if not exists
    if($('#down_file_iframe').length == 0) {
    	$('<iframe id="down_file_iframe" name="down_file_iframe"></iframe>').attr({
		   width:1,  
		   height:1,  
		   frameborder:0
		}).append('<form method="post" target="down_file_iframe"></form>').appendTo('body').hide();
    }
    // Giving IE a chance to build the DOM in  
    // the iframe with a short timeout:  
    setTimeout(function() {
    	var form = $('form:first', $('#down_file_iframe'));
    	$('*', form).remove();
    	form.attr('action', url);
    	options = options || {};
    	for(var name in options) {
    		$('<input type="hidden" name="' + name + '" value="' + options[name] + '"/>').appendTo(form);
    	}
        form.submit();
    }, 50);
};
