function CheckSubmit()
{
	if(document.postForm.catid.value=="")
	{
   		document.postForm.catid.focus();
   		alert("��ѡ���������");
   		return false;
	}
	if(document.postForm.areaid.value=="")
	{
   		document.postForm.areaid.focus();
   		alert("��ѡ����Ҫ�����ĵ�����");
   		return false;
	}
	if(document.postForm.title.value=="")
	{
   		document.postForm.title.focus();
   		alert("����д��Ϣ����");
   		return false;
	}
	if(document.postForm.contact_who.value=="")
	{
   		document.postForm.contact_who.focus();
   		alert("����д��ϵ��");
   		return false;
	}
	if(document.postForm.tel.value=="")
	{
   		document.postForm.tel.focus();
   		alert("����д��ϵ�绰");
   		return false;
	}
	document.postForm.mymps.disabled=true;
	document.postForm.mymps.value="�ύ��...";
	return true;
}// JavaScript Document