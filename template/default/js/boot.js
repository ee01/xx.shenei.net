function $(objname){return document.getElementById(objname);}

var BrowserUtil={navi:navigator.userAgent.toLowerCase(),isIE:function(){var B=this;return(B.navi.indexOf("msie")!=-1)&&(B.navi.indexOf("opera")==-1)&&(B.navi.indexOf("omniweb")==-1)},isIE7:function(){var B=this;return(B.navi.indexOf("msie")!=-1)&&(B.navi.indexOf("msie 7")!=-1)&&(B.navi.indexOf("opera")==-1)&&(B.navi.indexOf("omniweb")==-1)},isMaxthon:function(){var B=this;return(B.navi.indexOf("msie")!=-1)&&(B.navi.indexOf("maxthon")!=-1)&&(B.navi.indexOf("opera")==-1)&&(B.navi.indexOf("omniweb")==-1)},isMaxthon2:function(){var B=this;return(B.navi.indexOf("msie")!=-1)&&(B.navi.indexOf("maxthon 2")!=-1)&&(B.navi.indexOf("opera")==-1)&&(B.navi.indexOf("omniweb")==-1)},getBody:function(){return(document.compatMode&&document.compatMode!="BackCompat")?document.documentElement:document.body},getScrollTop:function(){return this.isIE()?this.getBody().scrollTop:window.pageYOffset},getScrollLeft:function(){return this.isIE()?this.getBody().scrollLeft:window.pageXOffset},getxy:function(E){var C=E.offsetTop;var B=E.offsetLeft;var A=E.offsetWidth;var D=E.offsetHeight;while(E=E.offsetParent){C+=E.offsetTop;B+=E.offsetLeft}return{x:B,y:C,w:A,h:D}}};


function nav_over(D)
{
	var A=BrowserUtil.getxy(D);
	var L=(D.clientHeight);
	var G=BrowserUtil.getBody().clientHeight;
	var I=G+BrowserUtil.getScrollTop();
	var F=document.getElementById("YMenu-side");
	/*
	if(null!=F)
	{
		var E=(F.clientHeight);
		var B=BrowserUtil.getxy(F);
		if((E+B.y)<I)
		{
			I=E+B.y
		}
	}
	
	*/
	if((A.y+L)>I)
	{
		D.style.top=(I-(A.y+L))-10+"px"
	}
	
	
	//alert(A.y+L+"|"+I);

}


var obj = $("YMenu-side").getElementsByTagName("li");
for(var i=0;i<obj.length;i++)
{
	obj[i].onmouseover=function()
	{
		//alert("a");
		if(this.getElementsByTagName("ul").length>0)
		{
			//alert("cc");
			this.className="current";
			this.getElementsByTagName("ul")[0].style.display="block";
			
			nav_over(this.getElementsByTagName("ul")[0]);
			
		}
	}
	obj[i].onmouseout=function()
	{
		
		if(this.getElementsByTagName("ul").length>0)
		{
			this.className="";
			this.getElementsByTagName("ul")[0].style.display="none";
		}
		
	}

}