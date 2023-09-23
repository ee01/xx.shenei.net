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
define('CURSCRIPT','area');
require_once(dirname(__FILE__)."/global.php");
require_once(MYMPS_INC."/db.class.php");

$part = $part ? $part : 'list' ;

function get_area_list(){
	global $db,$db_mymps;
	$sql = "SELECT a.areaid, a.areaname, a.areaorder as areaorder,b.areaid AS childid, b.areaname AS childname, b.areaorder AS childorder FROM {$db_mymps}area AS a LEFT JOIN {$db_mymps}area AS b ON b.parentid = a.areaid WHERE a.parentid = '0' ORDER BY areaorder,a.areaid,childorder ASC"; 
    $res = $db->getAll($sql);
	$newres = array();
    $areas  = array();
    foreach ($res as $row){
		$areas[$row['areaid']]['areaid']   = $row['areaid'];
		$areas[$row['areaid']]['areaname'] = $row['areaname'];
		$areas[$row['areaid']]['areaorder'] = $row['areaorder'];
		if($row['childid'] != NULL){
			$areas[$row['areaid']]['children'][$row['childid']]['id']   = $row['childid'];
			$areas[$row['areaid']]['children'][$row['childid']]['name'] = $row['childname'];
			$areas[$row['areaid']]['children'][$row['childid']]['order'] = $row['childorder'];
			$areas[$row['areaid']]['children'][$row['childid']]['num']  = $newres[$row['childid']]?$newres[$row['childid']]:'0';
		}
    }
	return $areas;
}

if($part == 'list'){
	chk_admin_purview("purview_地区分类");
	$area = get_area_list();
	$here = "地区列表";
	include(mymps_tpl("area_list"));
}

if($part == 'add'){
	chk_admin_purview("purview_增加地区");
	$area = $db->getAll("SELECT * from {$db_mymps}area WHERE parentid=0");
	$maxorder = $db->getOne("SELECT MAX(areaorder) FROM {$db_mymps}area");
	$maxorder = $maxorder + 1;
	$here = "添加地区";
	include(mymps_tpl("area_add"));
}



if($part == 'insert'){
	if(empty($areaname))write_msg("请填写地区名称");
	$areaname  = explode('|',trim($areaname));
	
	if(empty($areaorder)){
		$maxorder = $db->getOne("SELECT MAX(areaorder) FROM {$db_mymps}area");
		$areaorder = $maxorder + 1;
	}
	
	if(is_array($areaname)){
		foreach ($areaname as $key => $value){
			$areaorder ++;
			$len = strlen($value);
			if($len < 2 || $len > 30){
				write_msg("地区名必须在2个至30个字符之间");
				exit();
			}
			$db->query("INSERT INTO {$db_mymps}area (areaname,parentid,areaorder) VALUES ('$value','$parentid','$areaorder')");
		}
		write_class_cache("area","area");
		write_msg('地区分类添加成功！','?part=list','record');
	}else{
		write_msg('地区分类添加失败，请按格式填写');
	}
	
}



if($part=='edit'){
	$area = $db->getRow("SELECT * FROM {$db_mymps}area WHERE areaid = '$areaid'");
	$areas = $db->getAll("SELECT * FROM {$db_mymps}area WHERE parentid = '0'");	
	$here = "编辑地区";
	include(mymps_tpl("area_edit"));
}



if($part=='update'){
	if(empty($areaname))write_msg("请填写地区名称");
	$len = strlen($areaname);
	if($len < 2 || $len > 30)write_msg("地区名必须在2个至30个字符之间");
	
	$sql = "UPDATE {$db_mymps}area SET areaname='$areaname',
	parentid='$parentid',
	areaorder='$areaorder'
	WHERE areaid = '$areaid'";
	$res = $db->query($sql);
	$msgs[]="编辑地区 $areaname 成功！<br /><br /><a href='?part=edit&areaid=$areaid'>重新编辑该地区</a> |  <a href='?part=list'>返回地区列表</a><br /><br /><a href='?part=add'>&raquo;我要继续添加地区</a>";
	write_class_cache("area","area");
	show_msg($msgs,"编辑地区 <b>".$areaname."</b> 成功!");
	
}




if($part=='delete'){
	if(empty($areaid)){write_msg('没有选择记录');exit();}
	mymps_delete("area","WHERE areaid = '$areaid'");
	write_msg("删除地区 $areaid 成功","?part=list","Mymps_record");
	write_class_cache("area","area");
}
?>