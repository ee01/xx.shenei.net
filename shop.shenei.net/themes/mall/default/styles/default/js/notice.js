<!-- 

/**//* 
**    ================================================================================================== 
**    类名：CLASS_MSN_MESSAGE 
**    功能：提供类似MSN消息框 
**    示例： 
    --------------------------------------------------------------------------------------------------- 

            var MSG = new CLASS_MSN_MESSAGE("aa",200,120,"短消息提示：","您有1封消息","今天请我吃饭哈"); 
                MSG.show(); 

*    消息构造 
*/ 
function CLASS_MSN_MESSAGE(id,width,height,caption,title,message,target,action){ 
    this.id     = id; 
    this.title = title; 
    this.caption= caption; 
    this.message= message; 
    this.target = target; 
    this.action = action; 
    this.width    = width?width:200; 
    this.height = height?height:120; 
    this.timeout= 300; 
    this.speed    = 20; 
    this.step    = 1; 
    this.right    = screen.width -18; 
    this.bottom = screen.height; 
    this.left    = this.right - this.width; 
    this.top    = this.bottom - this.height; 
    this.timer    = 0; 
    this.pause    = false;
    this.close    = false;
    this.autoHide    = true;
} 

/**//* 
*    隐藏消息方法 
*/ 
CLASS_MSN_MESSAGE.prototype.hide = function(){ 
    if(this.onunload()){ 
        var offset = this.height>this.bottom-this.top?this.height:this.bottom-this.top; 
        var me = this; 
        if(this.timer>0){   
            window.clearInterval(me.timer); 
        } 
        var fun = function(){ 
            if(me.pause==false||me.close){
                var x = me.left; 
                var y = 0; 
                var width = me.width; 
                var height = 0; 
                if(me.offset>0){ 
                    height = me.offset; 
                } 
     
                y = me.bottom - height; 
     
                if(y>=me.bottom){ 
                    window.clearInterval(me.timer); 
                    me.Pop.hide(); 
                } else { 
                    me.offset = me.offset - me.step; 
                } 
                me.Pop.show(x,y,width,height);    
            }             
        } 
        this.timer = window.setInterval(fun,this.speed)      
    } 
} 

/**//* 
*    消息卸载事件，可以重写 
*/ 
CLASS_MSN_MESSAGE.prototype.onunload = function() { 
    return true; 
} 
/**//* 
*    消息命令事件，要实现自己的连接，请重写它 
* 
*/ 
CLASS_MSN_MESSAGE.prototype.oncommand = function(){ 
    //this.close = true;
    this.hide(); 
//window.open("../www.shenei.net");
   
} 
/**//* 
*    消息显示方法 
*/ 
CLASS_MSN_MESSAGE.prototype.show = function(){ 
    var oPopup = window.createPopup(); //IE5.5+ 
    
    this.Pop = oPopup; 

    var w = this.width; 
    var h = this.height; 

    var str = "<DIV style='BORDER-RIGHT: #FF6600 1px solid; BORDER-TOP: #FFE3BB 1px solid; Z-INDEX: 99999; LEFT: 0px; BORDER-LEFT: #FFE3BB 1px solid; WIDTH: " + w + "px; BORDER-BOTTOM: #FF6600 1px solid; POSITION: absolute; TOP: 0px; HEIGHT: " + h + "px; BACKGROUND-COLOR: #FF9377'>" 
        str += "<TABLE style='BORDER-TOP: #ffffff 1px solid; BORDER-LEFT: #ffffff 1px solid' cellSpacing=0 cellPadding=0 width='100%' bgColor=#FFD1B3 border=0>" 
        str += "<TR>" 
        str += "<TD style='FONT-SIZE: 12px;COLOR: #FF6600' width=30 height=24></TD>" 
        str += "<TD style='PADDING-LEFT: 4px; FONT-WEIGHT: normal; FONT-SIZE: 12px; COLOR: #FF3300; PADDING-TOP: 4px' vAlign=center width='100%'>" + this.caption + "</TD>" 
        str += "<TD style='PADDING-RIGHT: 2px; PADDING-TOP: 2px' vAlign=center align=right width=19>" 
        str += "<SPAN title=关闭 style='FONT-WEIGHT: bold; FONT-SIZE: 12px; CURSOR: hand; COLOR: red; MARGIN-RIGHT: 4px' id='btSysClose' >×</SPAN></TD>" 
        str += "</TR>" 
        str += "<TR>" 
        str += "<TD style='PADDING-RIGHT: 1px;PADDING-BOTTOM: 1px' colSpan=3 height=" + (h-28) + ">" 
        str += "<DIV style='BORDER-RIGHT: #FFB380 1px solid; PADDING-RIGHT: 8px; BORDER-TOP: #FF9900 1px solid; PADDING-LEFT: 8px; FONT-SIZE: 12px; PADDING-BOTTOM: 8px; BORDER-LEFT: #FF9900 1px solid; WIDTH: 100%; COLOR: #FF3300; PADDING-TOP: 8px; BORDER-BOTTOM: #FFB380 1px solid; HEIGHT: 100%'><b>" + this.title + "</b><BR>" 
        str += "<DIV style='WORD-BREAK: break-all' align=left>" + this.message + "<A href='javascript:void(0)' hidefocus=false id='btCommand'></A><A href='javascript:void(0)' hidefocus=false id='ommand'></A></DIV>" 
//		str += "<DIV style='WORD-BREAK: break-all' align=left><A href='javascript:void(0)' hidefocus=false id='btCommand'><FONT color=#ff0000>" + this.message + "</FONT></A></DIV>" 
        str += "</DIV>" 
        str += "</TD>" 
        str += "</TR>" 
        str += "</TABLE>" 
        str += "</DIV>" 

    oPopup.document.body.innerHTML = str; 
    

    this.offset = 0; 
    var me = this; 
    oPopup.document.body.onmouseover = function(){me.pause=true;}
    oPopup.document.body.onmouseout = function(){me.pause=false;}
    var fun = function(){ 
        var x = me.left; 
        var y = 0; 
        var width    = me.width; 
        var height    = me.height; 
            if(me.offset>me.height){ 
                height = me.height; 
            } else { 
                height = me.offset; 
            } 
        y = me.bottom - me.offset; 
        if(y<=me.top){ 
            me.timeout--; 
            if(me.timeout==0){ 
                window.clearInterval(me.timer); 
                if(me.autoHide){
                    me.hide(); 
                }
            } 
        } else { 
            me.offset = me.offset + me.step; 
        } 
        me.Pop.show(x,y,width,height);    
    } 

    this.timer = window.setInterval(fun,this.speed)      

     

    var btClose = oPopup.document.getElementById("btSysClose"); 

    btClose.onclick = function(){ 
        me.close = true;
        me.hide(); 
    } 

    var btCommand = oPopup.document.getElementById("btCommand"); 
    btCommand.onclick = function(){ 
        me.oncommand(); 
    }    
var ommand = oPopup.document.getElementById("ommand"); 
      ommand.onclick = function(){ 
       //this.close = true;
    me.hide(); 
window.open(ommand.href);
    }   
} 
/**//* 
** 设置速度方法 
**/ 
CLASS_MSN_MESSAGE.prototype.speed = function(s){ 
    var t = 20; 
    try { 
        t = praseInt(s); 
    } catch(e){} 
    this.speed = t; 
} 
/**//* 
** 设置步长方法 
**/ 
CLASS_MSN_MESSAGE.prototype.step = function(s){ 
    var t = 1; 
    try { 
        t = praseInt(s); 
    } catch(e){} 
    this.step = t; 
} 

CLASS_MSN_MESSAGE.prototype.rect = function(left,right,top,bottom){ 
    try { 
        this.left        = left    !=null?left:this.right-this.width; 
        this.right        = right    !=null?right:this.left +this.width; 
        this.bottom        = bottom!=null?(bottom>screen.height?screen.height:bottom):screen.height; 
        this.top        = top    !=null?top:this.bottom - this.height; 
    } catch(e){} 
} 
//var MSG1 = new CLASS_MSN_MESSAGE("aa",300,240,"网站通告：","<center>本站全新改版</center>","本站做了全新修改，改进了用户界面与用户体验！<br>因所有数据重新导入，难免会有丢失，请见谅！<br><br>　　所有已完成的交易订单未录入新数据库，但站长以作备份，用于比赛最后评分！<br><br>　　新平台的线下交易(货到付款)流程细微变化，请卖家注意！<br><br>若使用中还遇到问题的<br>可以联系我们：295092301,162053702<br><div align=right>——【疯·神】工作室</div>"); 
//var MSG1 = new CLASS_MSN_MESSAGE("aa",250,180,"网站通告：","<center>服务器检修</center>","用户家园 模块因网站空间不足需等明天扩容后重新开放~<br>shop.shenei.net域名备案审核ing...使用此网址的用户可以联系我们进行网址跳转！<br><br>若使用中还遇到问题的<br>可以联系我们：295092301,162053702<br><div align=right>——【疯·神】工作室</div>"); 
//var MSG1 = new CLASS_MSN_MESSAGE("aa",300,240,"网站通告：","<center>送货协议</center>","为保证送货质量和速度，维护会员利益，且考虑店长信誉和效率。本站制定订货—送货安排时间如下：<br><br>当日0~11时订单，送货时段当日12~13：30时；<br>当日11~20时订单,送货时段当日21~23时；<br>当时20~次日11时订单送货时段次日12~13：30时。<br>特殊情况请协调处理。订单者可酌情给店长评价，特此公告！<br><br>若使用中还遇到问题的<br>可以联系我们：185813620,162053702<br><div align=right>——【疯·神】工作室</div>"); 
//var MSG1 = new CLASS_MSN_MESSAGE("aa",280,210,"网站通告：","<center>大赛结果统计ing...</center>","大赛结果【疯·神】工作室正在紧张统计中…<br>将于2010年6月20日公布并评出名次！<br><br>评分依据按信用度、交易额、订单数、商品数、商品访问量、服务态度、是否刷信用等多项数据综合评定！<br><br>若使用中还遇到问题的<br>可以Q我们：185813620,162053702<br><div align=right>——【疯·神】工作室</div>"); 
//var MSG1 = new CLASS_MSN_MESSAGE("aa",280,180,"网站通告：","<center>大赛统计结果已公布！</center>","大赛结果已公布到公告栏~<br><br>评分依据按信用度、交易额、订单数、商品数、商品访问量、服务态度、是否刷信用等多项数据综合评定！<br><br>若使用中还遇到问题的<br>可以Q我们：185813620,162053702<br><div align=right>——【疯·神】工作室</div>"); 
//var MSG1 = new CLASS_MSN_MESSAGE("aa",280,210,"网站通告：","<center>【分类信息】即将上市！</center>","注意：为了新添【信息平台】功能，本周五网店将暂停几天维护。<br><br>重新恢复后，用户中心的发布信息功能就可以使用了，大家期待~<br><br>若关闭期间需要查看订单或其他什么服务的，可以联系直接我们！<br>也可以Q我们：185813620,162053702<br><div align=right>——【疯·神】工作室</div>"); 
//var MSG1 = new CLASS_MSN_MESSAGE("aa",280,275,"网站通告：","<center>【舍内网】新增功能！</center>","“校园欢乐购”已正式更名为“舍内网店”，网址变更为shop.shenei.net<br><br>【信息平台】功能上线！大家可以通过用户中心免费发布信息~<br>新增【专题站】，为以后举办活动设立专题！<br><br>【舍内家园】新增功能：<br>飞信功能、大学城这块地（同城邂逅）、大学城的天空（许愿天空）、手机WAP版：wap.shenei.net（大家以后可以用手机写博客、看心情了~<br><br>若使用中还遇到问题的<br>可以Q我们：185813620,162053702<br><div align=right>——【疯·神】工作室</div>"); 
var MSG1 = new CLASS_MSN_MESSAGE("aa",280,240,"网站通告：","<center>大赛统计结果全部公布！</center>","上学期首届网络营销大赛大赛结果已公布到公告栏！（优胜奖也已公布~<br><br>评分依据按信用度、交易额、订单数、商品数、商品访问量、服务态度、是否刷信用等多项数据综合评定！<br><br>奖状即将颁发，颁奖典礼筹划ing...<br><br>若有异议，或使用中有问题<br>可以Q我们：185813620,162053702<br><div align=right>——【疯·神】工作室</div>"); 
    MSG1.rect(null,null,null,screen.height-65); 
    MSG1.speed    = 10; 
    MSG1.step    = 5; 
    //alert(MSG1.top); 
//	MSG1.show(); //注释这里关闭公告

//同时两个有闪烁，只能用层代替了，不过层不跨框架 
//var MSG2 = new CLASS_MSN_MESSAGE("aa",200,120,"短消息提示：","您有2封消息","好的啊"); 
//   MSG2.rect(100,null,null,screen.height); 
//    MSG2.show(); 
//-->