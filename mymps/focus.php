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
require_once(MYMPS_INC."/upfile.fun.php");
require_once(MYMPS_SMARTY."/include.php");

$part = $part ? $part : 'list' ;

if($part == 'list'){

	chk_admin_purview("purview_焦点图列表");
	$sql="SELECT * FROM `{$db_mymps}focus` ORDER BY focusorder DESC";
	$row=$db->getAll($sql);
	$here = "首页焦点图修改";
	include(mymps_tpl("focus_list"));
	
}elseif($part =='input'){

	chk_admin_purview("purview_上传焦点图");
	$here = "添加焦点图";
	$maxorder = $db->getOne("SELECT MAX(focusorder) FROM {$db_mymps}focus");
	$maxorder = $maxorder + 1;
	include(mymps_tpl("focus_input"));
	
}elseif($part == 'insert'){

	$name_file = "mymps_focus";
	if($_FILES[$name_file]["name"]){
		check_upimage($name_file);
		$destination="/focus/";
		$mymps_image=start_upload($name_file,$destination,0,$mymps_mymps[cfg_focus_limit][width],$mymps_mymps[cfg_focus_limit]['height']);
		$pubdate = time();
		$sql = "Insert Into `{$db_mymps}focus` (id,image,pre_image,words,url,pubdate,focusorder)
				Values('','$mymps_image[0]','$mymps_image[1]','$words','$url','$pubdate','$focusorder')";
		$res = $db->query($sql);
	}
	write_msg("焦点图上传成功","focus.php","mymps");
	$smarty->clear_cache(mymps_tpl('index','smarty'));
	
}elseif($part == 'edit'){

	$here = "修改焦点图";
	if (empty($id)){write_msg("您未指定ID"); exit();}
	$sql = "SELECT * FROM {$db_mymps}focus WHERE id ='$id'";
	$row = $db->getRow($sql);
	include(mymps_tpl("focus_edit"));
	
}elseif($part == 'update'){
	
	$name_file = "mymps_focus";
	
	if($_FILES[$name_file]["name"]){
		check_upimage($name_file);
		$destination="/focus/";
		$mymps_image=start_upload($name_file,$destination,0,$mymps_mymps[cfg_focus_limit][width],$mymps_mymps[cfg_focus_limit][height],$image,$pre_image);
		$image = $mymps_image[0];
		$pre_image = $mymps_image[1];
	}
	
	$sql="UPDATE `{$db_mymps}focus` SET image='$image',pre_image='$pre_image',words='$words',url='$url',focusorder='$focusorder' WHERE id = '$id'";
	$res = $db->query($sql);
	write_msg("焦点图 ".$id." 修改成功!","focus.php","mymps");
	$smarty->clear_cache(mymps_tpl('index','smarty'));
	
}elseif($part == 'del'){

	if(!empty($image)){
		@unlink(MYMPS_ROOT.$image);
		@unlink(MYMPS_ROOT.$pre_image);
	}
	
	mymps_delete("focus","WHERE id = '$id'");
	write_msg("焦点图 ".$id." 删除成功","focus.php","mymps");
	$smarty->clear_cache(mymps_tpl('index','smarty'));
	
}
?>