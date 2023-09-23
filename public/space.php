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
require_once(dirname(__FILE__)."/global.php");

$userid = trim($user);

$smarty -> cache_lifetime    = $mymps_cache['space']['time'];
$smarty -> caching           = $mymps_cache['space']['open'];

if (!$smarty->is_cached(mymps_tpl('space','smarty'),$userid)){
	if(!$userid){write_msg("您未指定用户名！");exit();}
	$sql ="SELECT id,sex,userid,web,cname,logo,prelogo,place,areaid FROM `{$db_mymps}member` WHERE userid = '$userid'";
	$member = $db -> getRow($sql);
	
	if(empty($member)){
		write_msg("您所指定的用户不存在，或者尚未通过审核","/");
		exit();
	}
	
	$member[prelogo] = $member[prelogo]?$mymps_global[SiteUrl].$member[prelogo]:$mymps_global[SiteUrl].'/images/nophoto.jpg';
	
	$smarty -> assign("info_list",mymps_get_info_list(10,'','',$userid,''));
	$smarty -> assign('mymps_global_header',mymps_global_header());
	$smarty -> assign("SiteName",$SiteName);
	$smarty -> assign("MPS_VERSION",MPS_VERSION);
	$smarty -> assign("mymps_global",$mymps_global);
	$smarty -> assign("member",$member);
}
$smarty -> display(mymps_tpl("space","smarty"),$userid);
?>