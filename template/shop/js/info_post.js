function CheckSubmit()
{
	if(document.postForm.catid.value=="")
	{
   		document.postForm.catid.focus();
   		alert("请选择所属类别！");
   		return false;
	}
	if(document.postForm.areaid.value=="")
	{
   		document.postForm.areaid.focus();
   		alert("请选择您要发布的地区！");
   		return false;
	}
	if(document.postForm.title.value=="")
	{
   		document.postForm.title.focus();
   		alert("请填写信息标题");
   		return false;
	}
	if(document.postForm.contact_who.value=="")
	{
   		document.postForm.contact_who.focus();
   		alert("请填写联系人");
   		return false;
	}
	if(document.postForm.tel.value=="")
	{
   		document.postForm.tel.focus();
   		alert("请填写联系电话");
   		return false;
	}
	document.postForm.mymps.disabled=true;
	document.postForm.mymps.value="提交中...";
	return true;
}// JavaScript Document