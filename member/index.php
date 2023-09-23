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
$part = isset($part)? trim($part) : 'index' ;

require_once(dirname(__FILE__)."/global.php");
require_once(MYMPS_MEMBER."/include/member_count.inc.php");

$sql = "SELECT a.id,a.userid,a.jointime,a.money_own,a.levelid,a.cname,a.qq,a.tel,a.email,a.address,a.prelogo,b.levelname FROM `{$db_mymps}member` AS a LEFT JOIN `{$db_mymps}member_level` AS b ON a.levelid = b.id WHERE userid = '$s_uid'";

$info = $db -> getRow($sql);
if(!$info){
	write_msg("您当前登录的会员已不存在，请和系统管理员联系！","login.php?part=out");
	exit();
}

$here 		= "管理首页";
$money_own  = $info[money_own];

if(empty($info['cname'])||empty($info['tel'])||empty($info['qq'])){
	$info_input_url = "javascript:setbg('完善您的联系方式',460,80,'../public/box.php?part=checkmemberinfo&url=info.php?part=input')";
} else {
	$info_input_url = "info.php?part=input";
}

$tpl = mymps_tpl("mymps_right");
include(mymps_tpl("index"));
?>