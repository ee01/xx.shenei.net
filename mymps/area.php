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
	chk_admin_purview("purview_��������");
	$area = get_area_list();
	$here = "�����б�";
	include(mymps_tpl("area_list"));
}

if($part == 'add'){
	chk_admin_purview("purview_���ӵ���");
	$area = $db->getAll("SELECT * from {$db_mymps}area WHERE parentid=0");
	$maxorder = $db->getOne("SELECT MAX(areaorder) FROM {$db_mymps}area");
	$maxorder = $maxorder + 1;
	$here = "��ӵ���";
	include(mymps_tpl("area_add"));
}



if($part == 'insert'){
	if(empty($areaname))write_msg("����д��������");
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
				write_msg("������������2����30���ַ�֮��");
				exit();
			}
			$db->query("INSERT INTO {$db_mymps}area (areaname,parentid,areaorder) VALUES ('$value','$parentid','$areaorder')");
		}
		write_class_cache("area","area");
		write_msg('����������ӳɹ���','?part=list','record');
	}else{
		write_msg('�����������ʧ�ܣ��밴��ʽ��д');
	}
	
}



if($part=='edit'){
	$area = $db->getRow("SELECT * FROM {$db_mymps}area WHERE areaid = '$areaid'");
	$areas = $db->getAll("SELECT * FROM {$db_mymps}area WHERE parentid = '0'");	
	$here = "�༭����";
	include(mymps_tpl("area_edit"));
}



if($part=='update'){
	if(empty($areaname))write_msg("����д��������");
	$len = strlen($areaname);
	if($len < 2 || $len > 30)write_msg("������������2����30���ַ�֮��");
	
	$sql = "UPDATE {$db_mymps}area SET areaname='$areaname',
	parentid='$parentid',
	areaorder='$areaorder'
	WHERE areaid = '$areaid'";
	$res = $db->query($sql);
	$msgs[]="�༭���� $areaname �ɹ���<br /><br /><a href='?part=edit&areaid=$areaid'>���±༭�õ���</a> |  <a href='?part=list'>���ص����б�</a><br /><br /><a href='?part=add'>&raquo;��Ҫ������ӵ���</a>";
	write_class_cache("area","area");
	show_msg($msgs,"�༭���� <b>".$areaname."</b> �ɹ�!");
	
}




if($part=='delete'){
	if(empty($areaid)){write_msg('û��ѡ���¼');exit();}
	mymps_delete("area","WHERE areaid = '$areaid'");
	write_msg("ɾ������ $areaid �ɹ�","?part=list","Mymps_record");
	write_class_cache("area","area");
}
?>