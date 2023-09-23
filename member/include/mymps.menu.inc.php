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

// get the menu of member mymps
$member_menu=array(
	'info'=>array(
		'name' => '分类信息',
		'group' => array(
				'发布分类信息'=>'info.php?part=input',
				'管理我的信息'=>'info.php?part=all',
				'我的短消息'=>'pm.php'
				)
		),
	'shop'=>array(
		'name' => '商城管理',
		'group' => array(
				'账户概览'=>'http://shop.shenei.net/index.php?app=member',
				'我的订单'=>'http://shop.shenei.net/index.php?app=buyer_order',
				'商品管理'=>'http://shop.shenei.net/index.php?app=my_goods'
				)
		),
	'update'=>array(
		'name'=>'资料修改',
		'group' => array(
				'修改形象照片'=>'update.php?part=logo',
				'修改联系方式'=>'update.php?part=contact',
				'修改登录密码'=>'update.php?part=password'
				)
		),
	'bank'=>array(
		'name'=>'账户管理',
		'group' => array(
				'账户充值'=>'bank.php',
				//'充值记录'=>'bank.php?part=record',
				'消费记录'=>'bank.php?part=record&action=use'
				)
		),
	'help'=>array(
		'name'=>'问题帮助',
		'group'=> '
					<li>联系电话：<br /><b style="margin-left:15px;">'.$mymps_global[SiteTel].'</b></li>
					<li>QQ 咨 询:<br /><b style="margin-left:15px;">'.$mymps_global[SiteQQ].'</b></li>
					<li><a href="'.$mymps_global[SiteUrl].'/public/about.php?part=guestbook" style="color:#006acd; text-decoration:underline">在线留言</a></li>
				  '
		)
);
//get the menu
function mymps_member_menu()
{
	global $member_menu;
	foreach($member_menu as $k => $v)
	{
		$mymps_member_menu .="<div class=\"t-info\">
			<div class=\"tt\"><a href=\"javascript:collapse_change('".$k."')\"><img id=\"menuimg_".$k."\" src=\"images/menu_reduce.gif\" align=\"absmiddle\"/> ".$v[name]."</a></div></div><div class=\"t-menu\" id=\"menu_".$k."\">";
		if(!is_array($v[group]))
		{
			$mymps_member_menu .= $v[group];
		}
		else
		{
			foreach($v[group] as $w => $y)
			{
				$mymps_member_menu .= "<li><a href=\"".$y."\">".$w."</a></li>";
			}
		}
		$mymps_member_menu .= "</div>";
	}
	return $mymps_member_menu;
}
//get the purview
function mymps_member_purview($purview='')
{
	global $member_menu;
    foreach($member_menu as $k => $v)
	{
    	if(is_array($v[group]))
		{
			$mymps_member_purview .="<tr bgcolor=\"#f5fbff\"><td>".$v[name]."</td><td>";
			foreach($v[group] as $w => $y)
			{
				$mymps_member_purview .= "<label for=\"purview_".$w."\" style=\"width:110px\"><input type=\"checkbox\" name=\"purview[]\" id=\"purview_".$w."\" value=\"purview_".$w."\"";
				$mymps_member_purview .= ((is_array($purview)&&in_array('purview_'.$w,$purview))||empty($purview))? "checked":"";
				$mymps_member_purview .= ">".$w."</label> ";
			}
			$mymps_member_purview .="</td></tr>";
		}
	}
	return $mymps_member_purview;
}
?>