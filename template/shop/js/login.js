function LoginForm(){
	if(document.formLogin.userid.value==""){
		alert("�û�������Ϊ��!");
		document.formLogin.userid.focus();
		return false;
	}		
	if(document.formLogin.userpwd.value==""){
		alert("��¼���벻��Ϊ��!");
		document.formLogin.userpwd.focus();
		return false;
	}
	return true;
}// JavaScript Document