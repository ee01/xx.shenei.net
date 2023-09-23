function winshow(pagename,w,h){
  window.open(pagename,null,"width="+w+",height="+h);
}
function checkbox(obj,num){
  var id;
  for (i=1;i<=num;i++){
	id=obj+i;
	if(document.getElementById(id).checked==""){
	  document.getElementById(id).checked="checked";
	}
	else{
	  document.getElementById(id).checked="";
	}
  }
}
function win(){

   window.opener.document.all.imgs.innerText=document.getElementById("imgs").value;
   //window.opener.document.all.bb.innerText=document.getElementById("sex").value;

   window.close();
}
function $(id)
{
    return document.getElementById(id);
}

ifcheck = true;
function CheckAll(form)
{
	for (var i=0;i<form.elements.length-1;i++)
	{
		var e = form.elements[i];
		e.checked = ifcheck;
	}
	ifcheck = ifcheck == true ? false : true;
}
//ÏÔÊ¾Òþ²Ø²ã
<!--
function mymps(targetid,objN){
   
      var target=document.getElementById(targetid);
   var aa=document.getElementById(objN)

            if (target.style.display=="none"){
                target.style.display="block";
		
            } else {
                target.style.display="none";
		
            }
	
   
}
-->