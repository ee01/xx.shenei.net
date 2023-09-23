function LoginForm(){
	if(document.formLogin.userid.value==""){
		alert("用户名不能为空!");
		document.formLogin.userid.focus();
		return false;
	}		
	if(document.formLogin.userpwd.value==""){
		alert("登录密码不能为空!");
		document.formLogin.userpwd.focus();
		return false;
	}
	return true;
}// JavaScript Document