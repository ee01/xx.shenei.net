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
if (!defined('IN_MYMPS'))
{
    die('FORBIDDEN');
}
$information_level = array();
$information_level[0] = '<font color=red>待审</font>';
$information_level[1] = '<font color=#006acd>正常</font>';
$information_level[2] = '<font color=green>推荐</font>';

function GetInfoLevel($level='',$formname='info_level')
{
	global $information_level;
	$mymps .= "<select name='$formname' id='$formname'>";
	$mymps .= "<option value = \"\">请选择信息属性</option>";
	foreach($information_level as $k=>$v)
	{
	 	if($k==$level) $mymps .= "<option value='$k' selected style='background-color:#6EB00C;color:white'>$v</option>\r\n";
	 	else $mymps .= "<option value='$k'>$v</option>\r\n";
	}
	$mymps .= "</select>\r\n";
	return $mymps;
}

//upgrade
$info_upgrade_level = array();
$info_upgrade_level[1] = '不置顶';
$info_upgrade_level[2] = '<font color=red>分类置顶</font>';
$info_upgrade_level[3] = '<font color=red><b>首页置顶</b></font>';

function GetUpgradeType($level='',$formname='upgrade_type')
{
	global $info_upgrade_level;
	$mymps .= "<select name='$formname' id='$formname'>";
	foreach($info_upgrade_level as $k=>$v)
	{
	 	if($k==$level) $mymps .= "<option value='$k' selected style='background-color:#6EB00C;color:white'>$v</option>\r\n";
	 	else $mymps .= "<option value='$k'>$v</option>\r\n";
	}
	$mymps .= "</select>\r\n";
	return $mymps;
}

//upgrade time
$info_upgrade_time = array();
$info_upgrade_time[1] = '1天';
$info_upgrade_time[3] = '3天';
$info_upgrade_time[7] = '7天';
$info_upgrade_time[30] = '30天';
$info_upgrade_time[90] = '90天';

function GetUpgradeTime($time='',$formname='upgrade_time')
{
	global $info_upgrade_time;
	$mymps .= "<select name='$formname' id='$formname'>";
	foreach($info_upgrade_time as $k=>$v)
	{
	 	if($k==$time) $mymps .= "<option value='$k' selected style='background-color:#6EB00C;color:white'>$v</option>\r\n";
	 	else $mymps .= "<option value='$k'>$v</option>\r\n";
	}
	$mymps .= "</select>\r\n";
	return $mymps;
}
?>