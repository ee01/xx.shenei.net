<?php
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * 版权所有 mymps研发团队，保留所有权利。
 * 网站地址: http://www.mymps.com.cn；
 * 交流论坛：http://bbs.mymps.com.cn；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 不允许对代码作修改后以任何形式任何目的的再发布。
 * ============================================================================
 * 软件作者: 彭介平（村长）
 * 联系方式：QQ:3037821 MSN:business@live.it
`*/
require_once(dirname(__FILE__)."/../public/global.php");
require_once(MYMPS_INC."/member.class.php");
require_once(MYMPS_DATA."/config.imgcode.php");
require_once(MYMPS_MEMBER.'/include/log.func.php');
$part = $part ? $part : 'default' ;

if ($part == 'register') {

	if ($mymps_global[cfg_if_member_register] != 1) {
		write_msg("操作失败！系统管理员关闭了新会员注册！");
		exit;
	}
	
	if ($member_log->chk_in()) {
		write_msg('','index.php');
		exit;
	}

	require(MYMPS_DATA.'/safequestions.php');
	$nav_bar = '<a href=../>网站首页</a> &raquo; 会员注册';
	mymps_global_assign();
	$smarty -> assign("safequestion",GetSafequestion(0,'safequestion'));
	$smarty -> assign("mymps_imgcode",$mymps_imgcode[register]);
	$smarty -> assign('submit','立即注册');
	$smarty -> assign("nav_bar",$nav_bar);
	$smarty -> display(mymps_tpl("register","smarty"));
	
} elseif($part== 'reg_new') {

	include(MYMPS_MEMBER.'/include/reg.inc.php');
	
} elseif ($part == 'out') {

	if($mymps_global['cfg_join_othersys'] == 'login'){
		setcookie('myauth', '', -86400);
	}
	
	$member_log -> out(trim($url));

} elseif ($part == 'chk_remember') {
	
	if(strlen($userid) < 5){
		$msgs[]="你的用户名过短，不允许注册！";
		show_msg($msgs);
		exit;
	}
	
	if($db->getOne("SELECT * FROM `{$db_mymps}member` WHERE userid='$userid'")){
		$msgs[]="你指定的用户名 {$userid} 已存在，请使用别的用户名！";
		show_msg($msgs);
		exit;
	} else {
		$msgs[]="恭喜你，该用户名尚未注册！";
		show_msg($msgs);
		exit;
	}
	
} elseif ($part == 'forgetpass') {

	include(MYMPS_MEMBER.'/include/'.$part.'.inc.php');
	
} elseif ($part == 'islogin') {

	if(!$member_log->chk_in()){
		echo "document.writeln(\"<li><a href=\\\"\\".$mymps_global[SiteUrl]."/member\\/login.php?url=".$url."\\\">登录<\\/a><\\/li><li><a href=\\\"\\".$mymps_global[SiteUrl]."/member\\/login.php?part=register\\\">注册<\\/a><\\/li>\")";
	}else{
		//$count_message = mymps_count("member_pm","WHERE userid ='$s_uid' AND if_read = 0");
		echo "document.writeln(\"<li><a href=\\\"\\".$mymps_global[SiteUrl]."/member\\/index.php\\\" target=_blank>".$s_uid."<\\/a> [<a href=\\\"\\".$mymps_global[SiteUrl]."/member\\/login.php?part=out&url=".$url."\\\">退出<\\/a>]<\\/li>\");
		document.writeln(\"<li><a href=\\\"\\".$mymps_global[SiteUrl]."/member\\/index.php\\\" target=_blank>管理面板<\\/a><\\/li>\");";
	}
	
} elseif ($part == 'default'){

	if($mymps_global[cfg_if_member_log_in] != 1){
		write_msg("操作失败！系统管理员关闭了会员登录功能！");
		exit;
	}

	if ($action=="dopost"){
	
		include(MYMPS_MEMBER.'/include/login.inc.php');
		
	} else {
	
		$smarty -> cache_lifetime    = $mymps_cache['login']['time'];
		$smarty -> caching           = $mymps_cache['login']['open'];

		if(!$member_log->chk_in()){
			$url=trim($url);
			if (!$smarty->is_cached(mymps_tpl('login','smarty'),$url)){
				mymps_global_assign();
				$nav_bar = '<a href=../>网站首页</a> &raquo; 会员登录';
				$smarty -> assign("mymps_imgcode",$mymps_imgcode[login]);
				$smarty -> assign("url",$url);
				$smarty -> assign("nav_bar",$nav_bar);
			}
			$smarty -> display(mymps_tpl("login","smarty"),$url);
		} else {
			write_msg("","index.php");
		}			
	}
	
} else {
	unknown_err_msg();
}
?>