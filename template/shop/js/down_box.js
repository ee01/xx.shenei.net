$(function(){
	$("#bbg").click(function(){
	if($("#note").hasClass('close')){
		$("#note").animate({height:'402px'},500,function(){$(this).css({height:"402px" }).removeClass('close');});
		
    }else{
		$("#note").animate({height:'114px'},500,function(){$(this).css({height:"114px" }).addClass('close');});
	
	}
	});
});

$(function(){
	$('.login_show').click(function(){
		var xmlhttp=createxmlhttp();
		if(!xmlhttp)
		{
			alert("你的浏览器不支持XMLHTTP！！");
			return;
		}
		var  Digital=new  Date();
		Digital=Digital+40000;
		if(window.location.href.toLowerCase().indexOf("learn/")==-1)
		{
			url="../request.aspx@action=checklogin&k="+Digital;
		}
		else{
			url=nowdomain+"request.aspx@action=checklogin&k="+Digital;
		}
		xmlhttp.onreadystatechange=requestdatalogincheck;
		//window.open(url);
		xmlhttp.open("GET",url,true);
		
		xmlhttp.send(null);
		function requestdatalogincheck()
		{
			if(xmlhttp.readyState==4)
			{
				if(xmlhttp.status==200)
				{
					if(xmlhttp.responseText=="1"){
						$('#login_node').addClass('display');
					}
					else{
						alert("您已经是登陆状态,如果需要更换账号登陆请先退出！");	
					}
				}
			}
		}
	});
	$('input#l_cancel').click(function(){
		$('#login_node').removeClass('display');
	});
});