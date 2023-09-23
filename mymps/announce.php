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
require_once(MYMPS_INC."/fckeditor/fckeditor.php");
require_once(MYMPS_SMARTY."/include.php");

$mymps_title_color = array(
	"#ff0000",
	"#006ffd",
	"#444444",
	"#000000",
	"#46a200",
	"#ff9900",
	"#ffffff"
);

$part = $part ? $part : 'all' ;

if($part == 'add'){

	chk_admin_purview("purview_发布公告");
	$acontent = fck_editor('content','Normal');
	$here = "发布网站公告";
	include(mymps_tpl("announce_add"));
	
}elseif($part == 'insert'){

	$pubdate=time();
	
	if($db->query("Insert Into `{$db_mymps}announce` (id,title,titlecolor,ifcomment,content,pubdate,author,redirecturl) Values ('','$title','$titlecolor','$ifcomment','$content','$pubdate','$author','$redirecturl')")){
		$inid = $db->insert_id();
		$msgs[]="恭喜您，公告".$title."发布成功！<br /><br /><a href='../public/about.php?part=announce&id=$inid' target=_blank>点此查看</a> | 
		<a href='?part=edit&id=$inid'>重新编辑</a> |  
		<a href='?part=all'>返回公告列表</a>			
		<br /><br />
		<a href='?part=add'>>>我要继续发布公告</a>";
		$smarty->clear_cache(MYMPS_TPL.'/about/announce_all.html','announce',1);
		$smarty->clear_cache(MYMPS_TPL.'/about/announce.html','announce',$id);
		show_msg($msgs,"公告 <b>".$title."</b> 发布成功!");
		write_announce_cache();
		$smarty->clear_cache(mymps_tpl('index','smarty'));
		
	}else{
	
		$msgs[]=$unkown_err_msg;
		show_msg($msgs);
		
	}
	
}elseif($part == 'edit'){

	if(trim($_POST[action])=='dopost'){

		$pubdate 	 = time();
		$sql = "UPDATE `{$db_mymps}announce` SET title='$title',titlecolor ='$titlecolor',ifcomment ='$ifcomment',content='$content',author='$author',pubdate='$pubdate',redirecturl='$redirecturl' WHERE id = '$id'";
		$res = $db->query($sql);
		$msgs[]="恭喜您，公告修改成功！<br /><br /><a href='../public/about.php?part=announce&id=$id' target=_blank>点此查看</a> | 
		<a href='?part=edit&id=$id'>重新编辑</a> |  
		<a href='?part=all'>返回公告列表</a>			
		<br /><br />
		<a href='?part=add'>&raquo; 我要继续发布公告</a>";
		$smarty -> clear_cache(MYMPS_TPL.'/about/announce_all.html','announce',1);
		$smarty -> clear_cache(MYMPS_TPL.'/about/announce.html','announce',$id);
		write_announce_cache();
		show_msg($msgs,"公告 <b>".$title."</b> 修改成功!");
		
	}else{
	
		$id = intval($id);
		
		$here = "修改网站公告";
		$edit = $db -> getRow("SELECT * FROM {$db_mymps}announce WHERE id = '$id'");
		$acontent = fck_editor('content','Normal',$edit['content']);
		include(mymps_tpl("announce_edit"));
		
	}
}elseif($part == 'delete'){

	$id = intval($id);
	if(empty($id))write_msg("没有选择记录");
	else{
		mymps_delete("announce","WHERE id = '$id'");
		write_msg("删除公告 $id 成功",$url,"Mymps_record");
		write_announce_cache();
		$smarty->clear_cache(mymps_tpl('index','smarty'));
	}
	
}elseif($part == 'all'){

	chk_admin_purview("purview_已发布的公告");
	$page = empty($page) ? 1 : intval($page);

	$where="WHERE title like '%".$title."%' and author like '%".$author."%'";
	$sql = "SELECT id,title,author,hits,pubdate FROM {$db_mymps}announce $where ORDER BY id DESC";
	$rows_num = mymps_count('announce',$where);
	$param=setParam(array('id','title','author','hits'));
	$announce = array();
	foreach(page1($sql) as $k => $row){
		$arr['id']       = $row['id'];
		$arr['title']    = $row['title'];
		$arr['pubdate']  = GetTime($row['pubdate']);
		$arr['author'] 	 = $row['author'];
		$arr['hits'] 	 = $row['hits'];
		$announce[]      = $arr;
	}
	$here="公告列表";
	include(mymps_tpl("announce_all"));
	
}elseif($part == 'delall'){

	write_msg('删除公告 '.mymps_del_all("announce",$_POST[id]).' 成功',$url,"Mymps_record");
	write_announce_cache();
	$smarty->clear_cache(mymps_tpl('index','smarty'));
	
}
?>