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

$part = $part ? $part : 'pay' ;

if($part == 'pay'){

	chk_member_purview("purview_�˻���ֵ");
	$here = "�˻���ֵ";
	$tpl=mymps_tpl("pay");
	include (mymps_tpl("index"));
	
}elseif($part == 'record'){	

	$action = ($_GET['action'] != 'pay' && $_GET['action'] != 'use')?'pay':trim($_GET['action']);
	$here = ($action == 'pay')?"��ֵ��¼":"���Ѽ�¼";
	if($action == 'pay'){chk_member_purview("purview_��ֵ��¼");}
	elseif($action == 'use'){chk_member_purview("purview_���Ѽ�¼");}
	$where    = "WHERE type = '$record_part[$action]' AND userid = '$s_uid'";
	$sql 	  = "SELECT * FROM `{$db_mymps}member_record_pay` $where";
	$rows_num = mymps_count("member_record_pay",$where);
	$param	  = setParam(array('part','action'));
	$get 	  = array();
	foreach(page1($sql) as $k => $row){
		$arr['subject']    = $row['subject'];
		$arr['paycost']    = $row['paycost'];
		$arr['pubtime']    = GetTime($row['pubtime']);
		$get[]			   = $arr;
	}
	$tpl=mymps_tpl("record");
	include (mymps_tpl("index"));
	
}else{
	unknown_err_msg();
}
?>