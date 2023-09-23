function chk_reg()
{
	if(document.form1.userid.value=="")
	{
   		document.form1.userid.focus();
   		alert("用户名不能为空！");
   		return false;
	}
	if(document.form1.userpwd.value=="")
	{
   		document.form1.userpwd.focus();
   		alert("用户密码不能为空！");
   		return false;
	}
	if(document.form1.userpwd.value!=document.form1.reuserpwd.value)
	{
   		document.form1.reuserpwd.focus();
   		alert("用户密码两次输入不正确！");
   		return false;
	}
	if(!document.form1.email.value.match(/(\w+[-+.]\w+)*@\w+([-.]\w+)*.\w+([-.]\w+)*$/)){
		alert("电子邮箱格式错误!");
		document.form1.email.focus();
		return false;
		//msg=msg+"电子邮箱,";
		//sum++;
	}
	if(document.form1.safequestion.value=="0")
	{
   		document.form1.safequestion.focus();
   		alert("请选择密码提示问题");
   		return false;
	}
	if(document.form1.safeanswer.value=="")
	{
   		document.form1.safeanswer.focus();
   		alert("请填写密码提示答案");
   		return false;
	}

	document.form1.btnreg.disabled=true;
	document.form1.btnreg.value="提交中...";
	return true;
}

function OnChange()
{
	var accept=document.getElementById("accept");
	var btnreg=document.getElementById("btnreg");
	if(accept.checked==true)
	{
		btnreg.disabled=false;
	}
	else{
	
		btnreg.disabled=true;
	}
}