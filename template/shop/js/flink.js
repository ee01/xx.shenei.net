function CheckSubmit()
{
	if(document.form1.webname.value=="")
	{
   		document.form1.webname.focus();
   		alert("��վ���Ʋ���Ϊ�գ�");
   		return false;
	}
	if(document.form1.url.value=="")
	{
   		document.form1.url.focus();
   		alert("��ַ��ַ����Ϊ�գ�");
   		return false;
	}
	if(document.form1.checkcode.value=="0")
	{
   		document.form1.checkcode.focus();
   		alert("��������֤��");
   		return false;
	}
	document.form1.mymps.disabled=true;
	document.form1.mymps.value="�ύ��...";
	return true;
}// JavaScript Document