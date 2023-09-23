jQuery(document).ready(function(){
jQuery('input[_40name="rbt1"]').rbtCss();
jQuery('.searchText').focus(function(){
		jQuery('.select_search').slideDown(150);
	});
	jQuery('#close_s').click(function(){
		jQuery('.select_search').slideUp(150);
	});
});
jQuery.fn.rbtCss=function(){
	var thisRbt=this;
	jQuery(this).css("display","none");
	jQuery(this).next().addClass("lblCss");
	var iptL=jQuery(this).size();
	for(i=0;i<iptL;i++){
		if(jQuery(this)[i].checked==true){	
			jQuery(this).eq(i).next().addClass("checked");
		}
		jQuery(this).eq(i).next().click(function(){
			jQuery(thisRbt).next().removeClass("checked");
			jQuery(this).addClass("checked");
			jQuery(this).prev()[0].checked=true;
		});
	}
};