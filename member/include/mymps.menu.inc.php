<?php
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * ��Ȩ���� mymps�з��Ŷӣ���������Ȩ����
 * ��վ��ַ: http://www.mymps.com.cn��
 * ������̳��http://bbs.mymps.com.cn��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�á�
 * ������Դ������޸ĺ����κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 * �������: ���ƽ���峤��
 * ��ϵ��ʽ��QQ:3037821 MSN:business@live.it
`*/

if (!defined('IN_MYMPS'))
{
    die('FORBIDDEN');
}

// get the menu of member mymps
$member_menu=array(
	'info'=>array(
		'name' => '������Ϣ',
		'group' => array(
				'����������Ϣ'=>'info.php?part=input',
				'�����ҵ���Ϣ'=>'info.php?part=all',
				'�ҵĶ���Ϣ'=>'pm.php'
				)
		),
	'shop'=>array(
		'name' => '�̳ǹ���',
		'group' => array(
				'�˻�����'=>'http://shop.shenei.net/index.php?app=member',
				'�ҵĶ���'=>'http://shop.shenei.net/index.php?app=buyer_order',
				'��Ʒ����'=>'http://shop.shenei.net/index.php?app=my_goods'
				)
		),
	'update'=>array(
		'name'=>'�����޸�',
		'group' => array(
				'�޸�������Ƭ'=>'update.php?part=logo',
				'�޸���ϵ��ʽ'=>'update.php?part=contact',
				'�޸ĵ�¼����'=>'update.php?part=password'
				)
		),
	'bank'=>array(
		'name'=>'�˻�����',
		'group' => array(
				'�˻���ֵ'=>'bank.php',
				//'��ֵ��¼'=>'bank.php?part=record',
				'���Ѽ�¼'=>'bank.php?part=record&action=use'
				)
		),
	'help'=>array(
		'name'=>'�������',
		'group'=> '
					<li>��ϵ�绰��<br /><b style="margin-left:15px;">'.$mymps_global[SiteTel].'</b></li>
					<li>QQ �� ѯ:<br /><b style="margin-left:15px;">'.$mymps_global[SiteQQ].'</b></li>
					<li><a href="'.$mymps_global[SiteUrl].'/public/about.php?part=guestbook" style="color:#006acd; text-decoration:underline">��������</a></li>
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