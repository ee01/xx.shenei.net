//###########################
//   Name:�����Ź� 
//   Link:tttuangou.net
//   Date:2010.04.13
//   Intro:��JS
//#############################
var userAgent = navigator.userAgent.toLowerCase();
var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
var is_ie = (userAgent.indexOf('msie') != -1 && !is_opera) && userAgent.substr(userAgent.indexOf('msie') + 5, 3);
var is_safari = (userAgent.indexOf('webkit') != -1 || userAgent.indexOf('safari') != -1);

//����URL��ַ
function copyText(_sTxt){
	_sTxt=document.title+" "+_sTxt;
	if(is_ie) {
		clipboardData.setData('Text',_sTxt);
		alert ("��ַ��"+_sTxt+"��\n\n�Ѿ����Ƶ����ļ�������\n������ʹ��Ctrl+V��ݼ�ճ������Ҫ�ĵط�");
	} else {
		prompt("��ʹ��Ctrl+C��ݼ��������������:",_sTxt); 
	}
}
//TAB�л���ť
function setTab(name,cursel,n){
	for(i=1;i<=n;i++){
	var menu=document.getElementById(name+i);
	var con=document.getElementById("con_"+name+"_"+i);
	menu.className=i==cursel?"hover":"";
	con.style.display=i==cursel?"block":"none";
}
}
//TAB�л���ť2
function hideAllClips(){
	for (i=1; i<5; i++){
		var allClips="topNews_"+i;
		var clipNum="clipNum"+i;
		document.getElementById(allClips).style.display="none";
		document.getElementById(clipNum).className="ts3_mbtn2";
		}
} 
function clip_Switch(n){
	var curClip="topNews_"+n;
	var curClipNum="clipNum"+n;
	hideAllClips();
	document.getElementById(curClip).style.display="block";
	document.getElementById(curClipNum).className="ts3_mbtn1";
	scrollNewsCt=n; 
} 

//��񶩵�����
(function($){
    $.fn.jExpand = function(){
        var element = this;

        $(element).find("tr:odd").addClass("odd");
        $(element).find("tr:not(.odd)").hide();
        $(element).find("tr:first-child").show();

        $(element).find("tr.odd").click(function() {
            $(this).next("tr").toggle();
        });
    }    
})(jQuery); 


//����ģ������ 2010.06.23
var arrCSS=[
    ["<img src='templates/default/images/logo_m.gif' width='30' height='20' class='themes themes1' title='Ĭ�Ϸ��'>","templates/default/styles/t1.css"],
    ["<img src='templates/default/images/logo_m.gif' width='30' height='20' class='themes themes2' title='��ɫ���'>","templates/tpl_2/styles/t1.css"],
    ["<img src='templates/default/images/logo_m.gif' width='30' height='20' class='themes themes3' title='ϲ����'>","templates/tpl_3/styles/t1.css"],
	["<img src='templates/default/images/logo_m.gif' width='30' height='20' class='themes themes4' title='��ɫ���'>","templates/tpl_4/styles/t1.css"],
	["<img src='templates/default/images/logo_m.gif' width='30' height='20' class='themes themes5' title='��ˬ���'>","templates/tpl_5/styles/t1.css"],
    ""
];

// ��ȡ��ʽ������
function v(){
	return;
}

// ���� Cookies ��¼ 
function writeCookie(name, value){ 
	exp = new Date(); 
	exp.setTime(exp.getTime() + (86400 *365));
	document.cookie = name + "=" + escape(value) + "; expires=" + exp.toGMTString() + "; path=/"; 
} 

function readCookie(name){ 
	var search; 
	search = name + "="; 
	offset = document.cookie.indexOf(search); 
	if (offset != -1) { 
		offset += search.length; 
		end = document.cookie.indexOf(";", offset);  
		if (end == -1){
			end = document.cookie.length;
		}
		return unescape(document.cookie.substring(offset, end)); 
	}else{
		return "";
	}
}

// Ĭ����ʽ��
function writeCSS(){
  for(var i=0;i<arrCSS.length-1;i++){
    document.write('<link title="styles'+i+'" href="'+arrCSS[i][1]+'" rel="stylesheet" disabled="true" type="text/css" />');
  }
    setStyleSheet(readCookie("stylesheet"));
}

function writeCSSLinks(){
  for(var i=0;i<arrCSS.length-1;i++){
    if(i>0) document.write('  '); 
    document.write('<a href="javascript:v()" onclick="setStyleSheet(\'styles'+i+'\')">'+arrCSS[i][0]+'</a>');
  } 
} 
function setStyleSheet(strCSS){
  var objs=document.getElementsByTagName("link");
  var intFound=0;
  for(var i=0;i<objs.length;i++){
    if(objs[i].type.indexOf("css")>-1&&objs[i].title){
      objs[i].disabled = true;
      if(objs[i].title==strCSS) intFound=i;
    }
  }
  
  objs[intFound].disabled = false; 
  writeCookie("stylesheet",objs[intFound].title);
}

writeCSS();
document.getElementsByTagName("link")[4].disabled = false;	//��ʾĬ��ģ��
//setStyleSheet(readCookie("stylesheet"));
if(readCookie("stylesheet")){
	setStyleSheet(readCookie("stylesheet"));
}

// ������ʾ������
function ShowHideDiv(init) {
	if(document.getElementById("Sright").style.display == "block"){
	    document.getElementById("Sright").style.display = "none";
  }
  else{
  	document.getElementById("Sright").style.display = "block";
  }
}
// ����
var showWindown = true;
var templateSrc = "./"; //����loading.gif·��
function tipsWindown(title,content,width,height,drag,time,showbg,cssName) {
	$("#windown-box").remove(); //�������
	var width = width>= 950?this.width=950:this.width=width;	    //������󴰿ڿ��
	var height = height>= 527?this.height=527:this.height=height;  //������󴰿ڸ߶�
	if(showWindown == true) {
		var simpleWindown_html = new String;
			simpleWindown_html = "<div id=\"windownbg\" style=\"height:"+$(document).height()+"px;filter:alpha(opacity=0);opacity:0;z-index: 999901\"></div>";
			simpleWindown_html += "<div id=\"windown-box\">";
			simpleWindown_html += "<div id=\"windown-title\"><h2></h2><span id=\"windown-close\">�ر�</span></div>";
			simpleWindown_html += "<div id=\"windown-content-border\"><div id=\"windown-content\"></div></div>"; 
			simpleWindown_html += "</div>";
			$("body").append(simpleWindown_html);
			show = false;
	}
	contentType = content.substring(0,content.indexOf(":"));
	content = content.substring(content.indexOf(":")+1,content.length);
	switch(contentType) {
		case "text":
		$("#windown-content").html(content);
		break;
		case "id":
		$("#windown-content").html($("#"+content+"").html());
		break;
		case "img":
		$("#windown-content").ajaxStart(function() {
			$(this).html("<img src='"+templateSrc+"/images/loading.gif' class='loading' />");
		});
		$.ajax({
			error:function(){
				$("#windown-content").html("<p class='windown-error'>�������ݳ���...</p>");
			},
			success:function(html){
				$("#windown-content").html("<img src="+content+" alt='' />");
			}
		});
		break;
		case "url":
		var content_array=content.split("?");
		$("#windown-content").ajaxStart(function(){
			$(this).html("<img src='"+templateSrc+"/templates/default/images/loading2.gif' class='loading' />");
		});
		$.ajax({
			type:content_array[0],
			url:content_array[1],
			data:content_array[2],
			error:function(){
				$("#windown-content").html("<p class='windown-error'>�������ݳ���...</p>");
			},
			success:function(html){
				$("#windown-content").html(html);
			}
		});
		break;
		case "iframe":
		$("#windown-content").ajaxStart(function(){
			$(this).html("<img src='"+templateSrc+"/images/loading.gif' class='loading' />");
		});
		$.ajax({
			error:function(){
				$("#windown-content").html("<p class='windown-error'>�������ݳ���...</p>");
			},
			success:function(html){
				$("#windown-content").html("<iframe src=\""+content+"\" width=\"100%\" height=\""+parseInt(height)+"px"+"\" scrolling=\"auto\" frameborder=\"0\" marginheight=\"0\" marginwidth=\"0\"></iframe>");
			}
		});
	}
	$("#windown-title h2").html(title);
	if(showbg == "true") {$("#windownbg").show();}else {$("#windownbg").remove();};
	$("#windownbg").animate({opacity:"0.0"},"normal");//����͸����
	$("#windown-box").show();
	if( height >= 527 ) {
		$("#windown-title").css({width:(parseInt(width)+22)+"px"});
		$("#windown-content").css({width:(parseInt(width)+17)+"px",height:height+"px"});
	}else {
		$("#windown-title").css({width:(parseInt(width)+10)+"px"});
		$("#windown-content").css({width:width+"px",height:height+"px"});
	}
	var	cw = document.documentElement.clientWidth,ch = document.documentElement.clientHeight,est = document.documentElement.scrollTop; 
	var _version = $.browser.version;
	if ( _version == 6.0 ) {
		$("#windown-box").css({left:"50%",top:(parseInt((ch)/2)+est)+"px",marginTop: -((parseInt(height)+53)/2)+"px",marginLeft:-((parseInt(width)+32)/2)+"px",zIndex: "999999"});
	}else {
		$("#windown-box").css({left:"50%",top:"50%",marginTop:-((parseInt(height)+53)/2)+"px",marginLeft:-((parseInt(width)+32)/2)+"px",zIndex: "999999"});
	};
	var Drag_ID = document.getElementById("windown-box"),DragHead = document.getElementById("windown-title");
		
	var moveX = 0,moveY = 0,moveTop,moveLeft = 0,moveable = false;
		if ( _version == 6.0 ) {
			moveTop = est;
		}else {
			moveTop = 0;
		}
	var	sw = Drag_ID.scrollWidth,sh = Drag_ID.scrollHeight;
		DragHead.onmouseover = function(e) {
			if(drag == "true"){DragHead.style.cursor = "move";}else{DragHead.style.cursor = "default";}
		};
		DragHead.onmousedown = function(e) {
		if(drag == "true"){moveable = true;}else{moveable = false;}
		e = window.event?window.event:e;
		var ol = Drag_ID.offsetLeft, ot = Drag_ID.offsetTop-moveTop;
		moveX = e.clientX-ol;
		moveY = e.clientY-ot;
		document.onmousemove = function(e) {
				if (moveable) {
				e = window.event?window.event:e;
				var x = e.clientX - moveX;
				var y = e.clientY - moveY;
					if ( x > 0 &&( x + sw < cw) && y > 0 && (y + sh < ch) ) {
						Drag_ID.style.left = x + "px";
						Drag_ID.style.top = parseInt(y+moveTop) + "px";
						Drag_ID.style.margin = "auto";
						}
					}
				}
		document.onmouseup = function () {moveable = false;};
		Drag_ID.onselectstart = function(e){return false;}
	}
	$("#windown-content").attr("class","windown-"+cssName);
	var closeWindown = function() {
		$("#windownbg").remove();
		$("#windown-box").fadeOut("slow",function(){$(this).remove();});
	}
	if( time == "" || typeof(time) == "undefined") {
		$("#windown-close").click(function() {
			$("#windownbg").remove();
			$("#windown-box").fadeOut("slow",function(){$(this).remove();});
		});
	}else { 
		setTimeout(closeWindown,time);
	}
}
