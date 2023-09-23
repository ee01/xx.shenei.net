<?php 
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * 版权所有 mymps研发团队，保留所有权利。
 * 网站地址: http://www.mymps.com.cn；
 * 交流论坛：http://bbs.mymps.com.cn；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 软件作者: 彭介平（村长）
 * 联系方式：QQ:3037821 MSN:business@live.it
`*/
require_once(dirname(__FILE__)."/global.php");
require_once(MYMPS_DATA."/report.type.inc.php");

if($part == 'upgrade'){
	if(empty($action)){
		require_once(MYMPS_DATA."/info.level.inc.php");
		require_once(MYMPS_INC."/member.class.php");
		require_once(MYMPS_DATA."/config.imgcode.php");
		$log = $member_log -> chk_in();
		$row = $db -> getRow("SELECT title,ismember,userid FROM `{$db_mymps}information` WHERE id = '$id' AND info_level != 0");
		if($row && $row[ismember] == 1 && $log == 'true' && $s_uid == $row[userid]){
			$money = $member_log -> get_info("money_own");
			include dirname(__FILE__)."../".(mymps_tpl("box/".$part,"1"));
		}
		elseif($row && $row[ismember] == 1 && ($log == 'false' || $s_uid != $row[userid])){
			
			include dirname(__FILE__)."../".(mymps_tpl("box/login","1"));
		}
		elseif($row && $row[ismember] != 1){
			echo "<center><h2 style=color:red>操作失败，游客发布的信息不能进行置顶操作！</h2></center>";
		}
		elseif(!$row){
			echo "<center><h2 style=color:red>操作失败，该信息不存在或者未通过审核！</h2></center>";
		}
	}
	exit();
}

if($part == 'do_report'){
	$report_type = trim($_POST['report_type']);
	$ip 		 = GetIP();
	//check if is reported
	if(mymps_count("info_report","WHERE infoid = '$infoid' AND ip = '$ip' AND pubtime > '".mktime(0,0,0)."'") > 0){echo "<center style=\"color:red; font-size:12px; font-weight:bold\">操作失败，该信息您已经举报过了！</font>";exit();}
	$db->query("INSERT INTO `{$db_mymps}info_report` (report_type,content,infoid,infotitle,ip,pubtime)VALUES('$report_type','$content','$infoid','$infotitle','$ip','".time()."')");
	echo "<div style=\"margin:0 15px\"><font style=\"color:red; font-size:12px\"><h1>感谢您的举报 :)</h1><br />● 在".$mymps_global[SiteName]."，每天有数千条违规信息通过用户举报被删除。<br /><br />● 如果你是不小心点错了举报按钮，别担心。只有当一个信息收到一定数量的举报时才会被删除。<br /><br />若有其他意见，请<a href=\"../public/about.php?part=guestbook\" target=\"_blank\">点击这里填写</a></font></div>";
	exit();
}

if($part == 'information'){
	//information inputed show message
	//related file /template/infobox.html
	$mymps_box[id] = intval($id);
	$mymps_box[infotitle] = trim($title); 
	$mymps_box[seotitle] = "发布成功 - 发布分类信息";
	$mymps_box[notice] = ($level==0)?"<h2 class=\"h\">信息发布成功</h2>":"<h1 class=\"h\">信息发布成功</h1>";
	$mymps_box[content] = ($level==0)?"<p>您发布的ID号为 <strong>".$mymps_box[id]."</strong> &nbsp;的信息，内容中可能包含违禁词语，已转为<b style=color:red>待审状态</b>，管理员审核通过后，就会在本网站上显示！<a href='../member/info.php?part=input'>再发布一条信息&raquo;</a></p>":"<p>您的信息  <a target=\"_blank\"  href=\"".Rewrite('info',array('id'=>$mymps_box[id]))."\"><b>".$mymps_box[infotitle]."</b></a> &nbsp;已经成功发布</p><p><b><font color=\"#FF0000\">温馨提示：</font></b> 该信息将在3分钟后显示在频道列表页上！</p><p><a href='../member/info.php?part=input'>我要再发布一条信息&raquo;</a></p><p style=\"padding:15px 0 0\"><a class=\"button a xxl\" href=\"javascript:setbg('置顶该信息',710,430,'../public/box.php?part=upgrade&id=".$mymps_box[id]."&do=setbg');\" style=\"width:190px; margin:auto\"><span><i></i>立即置顶该信息</span></a><a class=\"button c xxl\" href=\"".Rewrite('about',array('part'=>'faq','id'=>'9'))."\" target=\"_blank\"><span><i></i>什么是置顶信息</span></a></p>";
	//
	$nav_bar = '信息发布状态提示';
	mymps_global_assign();
	$smarty -> assign("nav_bar",$nav_bar);
	$smarty -> assign("mymps_box",$mymps_box);
	$smarty -> display(mymps_tpl("info_post_write_ok","smarty"));
	exit();
}

//verify the testdirs of mymps
require_once(MYMPS_DATA."/sp_testdirs.php");

//member_redirect redirect to power.php or space

if(empty($part)&&empty($url)&&empty($userid)){
	unknown_err_msg();
}

//check remember
if($part == 'chk_remember'){
	$userid = trim($userid);
	if(empty($userid)){
		echo "<font style='font-size:12px; color:red; margin-left:10px'>很抱歉！请填写用户名后再提交检测！</font>";
	} else {
	
		if($mymps_global['cfg_join_othersys'] == 'ucenter'){
		
			include MYMPS_ROOT.'/uc_client/client.php';
			
			if(uc_get_user($userid)){
				echo "<font style='font-size:12px; color:red; margin-left:10px'>很遗憾！该用户名已被注册！</font>";
			}else{
				echo "<font style='font-size:12px; color:green; margin-left:10px'>恭喜你！该用户名尚未被注册！</font>";
			}
			
		} else {
			$check = CheckUserID($uid,"用户名");
			if(strlen($userid) < 3 || strlen($userid) > 20){
				echo "<font style='font-size:12px; color:red; margin-left:10px'>很抱歉，用户名必须控制在 3 - 20 个字符以内！</font>";
				exit();
			}
			if($check == 'ok'){
				$re=$db->getOne("SELECT * FROM {$db_mymps}member WHERE userid like '$userid'");
				if(!$re){
					echo "<font style='font-size:12px; color:green; margin-left:10px'>恭喜你！该用户名尚未被注册！</font>";
				}else{
					echo "<font style='font-size:12px; color:red; margin-left:10px'>很抱歉！该用户名已经被注册！</font>";
				}
			}else{
				echo "<font style='font-size:12px; color:red; margin-left:10px'>".$check."</font>";
			}
		}
		
	}
	exit();
}

//member before input info,check the update
if($part == 'checkmemberinfo')
{
	echo "<div style=\"font-size:12px; font-weight:100; margin:-10px 10px;\"><img src=../images/warn.gif align=absmiddle>&nbsp;系统检测到您的联系方式还没有填写完整！<br /><br />完善您的联系方式，这样每次发布信息的时候都不需要重新填写，这就<a href=\"../member/update.php?part=contact&url=info.php?part=input\" target=_top>试试 &raquo;</a></div>";
	exit();
}

//memberinfopost
//related file:/template/info/post.html
if($part == 'memberinfopost'){
	echo "<div style=\"font-size:12px; font-weight:100; margin:-10px 10px;\"><img src=../images/warn.gif align=absmiddle>&nbsp;您还没有登录会员管理,本站并不强制要求你必须登录会员后才能发布信息<br /><br />但是注册会员后，您可以更方便地管理自己发布的信息，这就<a href=\"../member/login.php?url=".$url."\" target=_top>试试 &raquo;</a></div>";
	exit();
}
//
include dirname(__FILE__)."../".(mymps_tpl("box/".$part,"1"));
?>