function submitcheck(){
	if(document.submitForm.userid.value==""){
		alert("�û�������Ϊ��!");
		document.submitForm.userid.focus();
		return false;
	}
	if(document.submitForm.safequestion.value=="0")
	{
   		document.submitForm.safequestion.focus();
   		alert("��ѡ��������ʾ����");
   		return false;
	}
	if(document.submitForm.safeanswer.value=="")
	{
   		document.submitForm.safeanswer.focus();
   		alert("����д������ʾ��");
   		return false;
	}
	if(document.submitForm.userpwd.value=="")
	{
   		document.submitForm.userpwd.focus();
   		alert("�û����벻��Ϊ�գ�");
   		return false;
	}
	if(document.submitForm.userpwd.value!=document.submitForm.reuserpwd.value)
	{
   		document.submitForm.reuserpwd.focus();
   		alert("�û������������벻��ȷ��");
   		return false;
	}
	document.submitForm.mymps.disabled=true;
	document.submitForm.mymps.value="�ύ��...";
	return true;
}// JavaScript Document// JavaScript Document