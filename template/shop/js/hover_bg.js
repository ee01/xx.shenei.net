$(document).ready(function(){
	var temp = $('.list_module');
	temp.hover(function() {
		$(this).addClass("orange");
	}, function() {
		$(this).removeClass("orange");
	});
});