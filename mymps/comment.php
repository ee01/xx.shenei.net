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
require_once(dirname(__FILE__)."/global.php");
require_once(MYMPS_INC."/db.class.php");

$part 	= $part ? $part : 'info' ;
$action = $action ? $action : 'list' ;

if ($action == "del") {

	if(mymps_delete($part."_comment","WHERE id = '$id'")){
		write_msg("删除信息评论 $id 成功",$url,"Mymps");
	}else{
		write_msg("删除评论记录失败！");
	}
	
}elseif($action == 'delall'){

	if(!is_array($id)){write_msg("您没有选中任何记录！");exit();}
	write_msg("分类信息 ".mymps_del_all($part."_comment",$id)."删除成功！",$url,"mymps");
	
}elseif($action == 'level0'){

	if(!is_array($id)){write_msg("您没有选中任何记录！");exit();}
	foreach ($id as $k =>$v){
		$db->query("UPDATE `{$db_mymps}".$part."_comment` SET comment_level = 0 WHERE id = '$v'");
	}
	write_msg("更新评论状态为 待审状态 操作成功！",$url,"RECORD");
	
}elseif($action == 'level1'){

	if(!is_array($id)){write_msg("您没有选中任何记录！");exit();}
	foreach ($id as $k =>$v){
		$db->query("UPDATE `{$db_mymps}".$part."_comment` SET comment_level = 1 WHERE id = '$v'");
	}
	write_msg("更新评论状态为 正常状态 操作成功！",$url,"RECORD");
	
}elseif ($action == "list"){

	require_once(MYMPS_DATA."/info.level.inc.php");
	
	if($part == 'announce'){
		chk_admin_purview("purview_已发布的公告");
	}elseif($part == 'info'){
		chk_admin_purview("purview_信息评论");
	}
	
	$here 	= "信息评论列表";
	$page 	= empty($page) ? 1 : intval($page);
	
	$where ="WHERE a.content LIKE '%".$keywords."%' AND a.comment_level LIKE '%".$_GET[comment_level]."%'";
	
	$table = ($part == 'info')?'information':'announce';
	
	$sql = "SELECT a.id,a.userid,a.content,a.pubtime,a.ip,a.".$part."id,b.title,a.comment_level FROM `{$db_mymps}".$part."_comment` AS a LEFT JOIN `{$db_mymps}".$table."` AS b ON a.".$part."id = b.id $where ORDER BY a.pubtime DESC";
	
	$rows_num = $db->getOne("SELECT COUNT(*) FROM `{$db_mymps}".$part."_comment` AS a $where");
	
	$param=setParam(array("part","keywords","comment_level"));
	
	$comment = array();
	
	foreach(page1($sql) as $k => $row){
		$arr['id']        = $row['id'];
		$arr['content']   = $row['content'];
		$arr['title']     = ($part == 'info')?"<a href=../public/info.php?id=".$row[infoid]." target=_blank>".$row[title]."</a>":"<a href=../public/about.php?part=announce&id=".$row[announceid]." target=_blank>".$row[title]."</a>";
		$arr['userid']    = $row[userid]?"<a href=\"
javascript:setbg('Mymps会员中心',400,110,'../public/box.php?part=member&userid=$row[userid]')\">".$row[userid]."</a>":$row[ip];
		$arr['pubtime']   = GetTime($row['pubtime']);
		$arr['ip']   	  = $row['ip'];
		$arr['comment_level']   = $information_level[$row[comment_level]];
		$comment[]        = $arr;
	}
	
	include(mymps_tpl("comment"));
	
}
?>