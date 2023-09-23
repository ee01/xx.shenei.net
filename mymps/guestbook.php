<?php 
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * 版权所有 mymps研发团队，保留所有权利。
 * 网站地址: http://www.mymps.com.cn；
 * 交流论坛：http://bbs.mymps.com.cn；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 不允许对代码作修改后以任何形式任何目的的再发布。否则我们将追究您的法律责任!!
 * ============================================================================
 * 软件作者: 彭介平（村长）
 * 联系方式：QQ:3037821 MSN:business@live.it
`*/
require_once(dirname(__FILE__)."/global.php");
require_once(MYMPS_INC."/db.class.php");
require_once(MYMPS_SMARTY."/include.php");

$part = $part ? $part : 'list' ;
if($part == 'list'){
	chk_admin_purview("purview_网站留言列表");
	$page = empty($page) ? '1' : $page;
	switch ($msg_level){
		case '0':
			$msg_level=' AND msg_level = 0';
		break;
		
		case '1':
			$msg_level=' AND msg_level = 1';
		break;
		
		case '':
			$msg_level='';
		break;
	}
	switch ($_GET[reply]){
		case '0':
			$reply=' AND reply = ""';
		break;
		
		case '1':
			$reply=' AND reply !=""';
		break;
		
		case '':
			$reply='';
		break;
	}
	switch ($_GET[hidden]){
		case '0':
			$hidden=' AND hidden = 0';
		break;
		
		case '1':
			$hidden=' AND hidden = 1';
		break;
		
		case '':
			$hidden='';
		break;
	}
	
	$where 	= "WHERE title LIKE '%".$keywords."%' ".$hidden.$msg_level.$reply."";
	$sql 	= "SELECT * FROM {$db_mymps}guestbook $where ORDER BY gid desc";
	$here 	= "网站留言管理";
	
	$rows_num = mymps_count("guestbook",$where);
	$param=setParam(array('part','msg_level','hidden','keywords','reply'));
	$links = array();
	foreach(page1($sql) as $k => $row){
		$arr['gid']       = $row['gid'];
		$arr['title']     = $row['title'];
		$arr['username']  = $row['username'];
		$arr['msg_level'] = $row['msg_level'];
		$arr['addtime']   = GetTime($row['addtime']);
		$arr['hidden']	  = $row['hidden'];
		$arr['reply'] 	  = $row['reply'];
		$arr['ismember']  = $row['ismember'];
		$links[]      	  = $arr;
	}
	include(mymps_tpl("guestbook_default"));
	
}elseif($part == 'reply'){

	$sql 	= "SELECT * FROM `{$db_mymps}guestbook` WHERE gid = '$gid'";
	$row 	= $db->getRow($sql);
	$here 	= "回复网友留言";
	$hidden .= "<label for=h0><input id=h0 name=hidden type=radio value=0 ";
	$hidden .= ($row[hidden]==0)?'checked':'';
	$hidden .= ">正常</label> <label for=h1><input name=hidden type=radio value=1 id=h1";
	$hidden .= ($row[hidden]==1)?'checked':'';
	$hidden .= ">悄悄话</label>";
	
	$msg_level .= "<label for=m0><input id=m0 name=msg_level type=radio value=0 ";
	$msg_level .= ($row[msg_level]==0)?'checked':'';
	$msg_level .= ">待审核</label> <label for=m1><input name=msg_level type=radio value=1 id=m1";
	$msg_level .= ($row[msg_level]==1)?' checked':'';
	$msg_level .= ">正常</label>";
	
	include(mymps_tpl("guestbook_reply"));
	
}elseif($part == 'update'){

	$res = $db->query("UPDATE {$db_mymps}guestbook SET reply='".trim($_POST[reply])."',replyer='$admin_id',title='".trim($title)."',content='".textarea_post_change($content)."',replytime='".time()."',hidden='".$hidden."',msg_level='".$msg_level."' WHERE gid = '$gid'");
	write_msg("网站留言 ".$gid." 回复成功","?part=reply&gid=".$gid,"mymps");
	$smarty->clear_cache(mymps_tpl('guestbook','smarty'),'guestbook',1);
	
}elseif($part == 'delete'){

	if(empty($gid)){write_msg("没有选择记录"); exit();};
	mymps_delete("guestbook","WHERE gid = '$gid'");
	write_msg("删除留言 ".$gid." 成功",$url,"mymps");
	$smarty->clear_cache(mymps_tpl('guestbook','smarty'),'guestbook',1);
	
}elseif($part == 'delall'){

	$gid = mymps_del_all("guestbook",$gid,'gid');
	write_msg('删除留言记录 '.$gid.' 成功',$url,"mymps");
	$smarty->clear_cache(mymps_tpl('guestbook','smarty'),'guestbook',1);
	
}
?>