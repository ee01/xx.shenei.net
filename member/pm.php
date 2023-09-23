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

$part = $part ? $part : 'all' ;

if($part == 'all'){

	chk_member_purview("purview_我的短消息");
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
		$arr['if_read']  = ($row[if_read]==1)?'<font color=green>已阅读</font>':'<font color=red>未阅读</font>';
		$get[]     		 = $arr;
	}
	
	$here="我的留言箱";
	$tpl=mymps_tpl("pm");
	include(mymps_tpl("index"));
	
}elseif($part == 'del'){

	mymps_delete("member_pm","WHERE id = '$id'");
	write_msg("操作成功，编号为 <b>".$id."</b> 的留言已被成功删除!","pm.php");
	
}
?>