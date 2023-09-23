/****************************通过ID号取得对象***********************************/
function $$(id){
  return document.getElementById(id);
}
function showeather(){
	var xmlhttp=createxmlhttp();
		if(!xmlhttp)
		{
			alert("你的浏览器不支持XMLHTTP！！");
			return;
		}
		var  Digital=new  Date();
		Digital=Digital+40000;
		var url="../pfpip.sina.com/ip.js&k="+Digital;
		xmlhttp.onreadystatechange=requestdatamsyqresult;
		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
		function requestdatamsyqresult()
		{
			if(xmlhttp.readyState==4)
			{
				if(xmlhttp.status==200)
				{
					alert(xmlhttp.responseText);
				}
			}
		}
}




