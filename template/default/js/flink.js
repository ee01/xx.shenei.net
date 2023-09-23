function CheckSubmit()
{
	if(document.form1.webname.value=="")
	{
   		document.form1.webname.focus();
   		alert("网站名称不能为空！");
   		return false;
	}
	if(document.form1.url.value=="")
	{
   		document.form1.url.focus();
   		alert("网址地址不能为空！");
   		return false;
	}
	if(document.form1.checkcode.value=="0")
	{
   		document.form1.checkcode.focus();
   		alert("请输入验证码");
   		return false;
	}
	document.form1.mymps.disabled=true;
	document.form1.mymps.value="提交中...";
	return true;
}// JavaScript Document