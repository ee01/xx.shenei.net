function submitcheck(){
	if(document.submitForm.userid.value==""){
		alert("用户名不能为空!");
		document.submitForm.userid.focus();
		return false;
	}
	if(document.submitForm.safequestion.value=="0")
	{
   		document.submitForm.safequestion.focus();
   		alert("请选择密码提示问题");
   		return false;
	}
	if(document.submitForm.safeanswer.value=="")
	{
   		document.submitForm.safeanswer.focus();
   		alert("请填写密码提示答案");
   		return false;
	}
	if(document.submitForm.userpwd.value=="")
	{
   		document.submitForm.userpwd.focus();
   		alert("用户密码不能为空！");
   		return false;
	}
	if(document.submitForm.userpwd.value!=document.submitForm.reuserpwd.value)
	{
   		document.submitForm.reuserpwd.focus();
   		alert("用户密码两次输入不正确！");
   		return false;
	}
	document.submitForm.mymps.disabled=true;
	document.submitForm.mymps.value="提交中...";
	return true;
}// JavaScript Document// JavaScript Document