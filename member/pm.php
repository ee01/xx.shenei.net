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
include("global.php");

$part = $part ? $part : 'all' ;

if($part == 'all'){

	chk_member_purview("purview_�ҵĶ���Ϣ");
	$page = empty($page) ? '1' : intval($page);
	$db->query("UPDATE `{$db_mymps}member_pm` SET if_read = 1 WHERE userid = '$s_uid'");
	$where    = " WHERE userid = '$s_uid'";
	$sql 	  = "SELECT * FROM `{$db_mymps}member_pm` $where ORDER BY pubtime DESC";
	$rows_num = mymps_count("member_pm",$where);
	$param	  = setParam(array("part"));
	$get 	  = array();
	
	foreach(page1($sql) as $k => $row){
		$arr['id']       = $row['id'];
		$arr['msg']      = $row['msg'];
		$arr['pubtime']  = GetTime($row['pubtime']);
		$arr['if_read']  = ($row[if_read]==1)?'<font color=green>���Ķ�</font>':'<font color=red>δ�Ķ�</font>';
		$get[]     		 = $arr;
	}
	
	$here="�ҵ�������";
	$tpl=mymps_tpl("pm");
	include(mymps_tpl("index"));
	
}elseif($part == 'del'){

	mymps_delete("member_pm","WHERE id = '$id'");
	write_msg("�����ɹ������Ϊ <b>".$id."</b> �������ѱ��ɹ�ɾ��!","pm.php");
	
}
?>