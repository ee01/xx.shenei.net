function chk_reg()
{
	if(document.form1.userid.value=="")
	{
   		document.form1.userid.focus();
   		alert("�û�������Ϊ�գ�");
   		return false;
	}
	if(document.form1.userpwd.value=="")
	{
   		document.form1.userpwd.focus();
   		alert("�û����벻��Ϊ�գ�");
   		return false;
	}
	if(document.form1.userpwd.value!=document.form1.reuserpwd.value)
	{
   		document.form1.reuserpwd.focus();
   		alert("�û������������벻��ȷ��");
   		return false;
	}
	if(!document.form1.email.value.match(/(\w+[-+.]\w+)*@\w+([-.]\w+)*.\w+([-.]\w+)*$/)){
		alert("���������ʽ����!");
		document.form1.email.focus();
		return false;
		//msg=msg+"��������,";
		//sum++;
	}
	if(document.form1.safequestion.value=="0")
	{
   		document.form1.safequestion.focus();
   		alert("��ѡ��������ʾ����");
   		return false;
	}
	if(document.form1.safeanswer.value=="")
	{
   		document.form1.safeanswer.focus();
   		alert("����д������ʾ��");
   		return false;
	}

	document.form1.btnreg.disabled=true;
	document.form1.btnreg.value="�ύ��...";
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