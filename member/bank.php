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
include("global.php");

$part = $part ? $part : 'pay' ;

if($part == 'pay'){

	chk_member_purview("purview_账户充值");
	$here = "账户充值";
	$tpl=mymps_tpl("pay");
	include (mymps_tpl("index"));
	
}elseif($part == 'record'){	

	$action = ($_GET['action'] != 'pay' && $_GET['action'] != 'use')?'pay':trim($_GET['action']);
	$here = ($action == 'pay')?"充值记录":"消费记录";
	if($action == 'pay'){chk_member_purview("purview_充值记录");}
	elseif($action == 'use'){chk_member_purview("purview_消费记录");}
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