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
require_once(MYMPS_SMARTY."/include.php");

$part = $part ? $part : 'list' ;

function get_cat_list(){
	global $db,$db_mymps;
	$sql = "SELECT a.catid, a.catname, a.catorder as catorder ,b.catid AS childid, b.catname AS childname, b.catorder AS childorder FROM {$db_mymps}category AS a LEFT JOIN {$db_mymps}category AS b ON b.parentid = a.catid WHERE a.parentid = '0' ORDER BY catorder,a.catid,childorder ASC"; 
    $res = $db->getAll($sql);
	$newres = array();
    $cats = array();
    foreach ($res as $row){
		$cats[$row['catid']]['catid']   = $row['catid'];
		$cats[$row['catid']]['catname'] = $row['catname'];
		$cats[$row['catid']]['catorder'] = $row['catorder'];
		if($row['childid'] != NULL){
			$cats[$row['catid']]['children'][$row['childid']]['id']   = $row['childid'];
			$cats[$row['catid']]['children'][$row['childid']]['name'] = $row['childname'];
			$cats[$row['catid']]['children'][$row['childid']]['order'] = $row['childorder'];
			$cats[$row['catid']]['children'][$row['childid']]['num']  = $newres[$row['childid']]?$newres[$row['childid']]:'0';
		}
    }
	return $cats;
}

function info_typemodels($modid=""){
	global $db,$db_mymps;
	$sql = "SELECT id,name,displayorder FROM `{$db_mymps}info_typemodels` ORDER BY displayorder,id DESC";
	$opt = $db->getAll($sql);
	foreach ($opt as $k => $value){
		$mymps .= "<option value=\"".$value[id]."\"";
		$mymps .= ($modid == $value[id])?"selected style=\"background-color:#6EB00C;color:white\"":"";
		$mymps .= ">".$value[name]."</option>";
	}
	return $mymps;
}

if($part == 'list'){
	chk_admin_purview("purview_行业分类");
	$cat = get_cat_list();
	$here = "行业栏目列表";
	include(mymps_tpl("category_list"));
}elseif ($part == 'add'){
	chk_admin_purview("purview_增加行业");
	require_once(MYMPS_INC."/fckeditor/fckeditor.php");
	$acontent = fck_editor('content','Basic','','80%','200');
	$cats = $db->getAll("SELECT * from {$db_mymps}category WHERE parentid=0");
	$maxorder = $db->getOne("SELECT MAX(catorder) FROM {$db_mymps}category");
	$maxorder = $maxorder + 1;
	$here = "添加行业栏目";
	include(mymps_tpl("category_add"));
}elseif($part == 'insert'){	

	if(empty($catname)){
		write_msg("请填写分类名称");
		exit();
	}
	
	$catname  	 = explode('|',trim($catname));

	if(empty($catname)){write_msg("请填写行业栏目名称");exit();};
	
	$len = strlen($catname);
	if($len < 2 || $len > 30){write_msg("行业栏目名必须在2个至30个字符之间");exit();};
	if(empty($content)&&$parentid!='0'){
		$row = $db->getRow("SELECT notice,modid FROM `{$db_mymps}category` WHERE catid = '$parentid'");
		$content = $row[notice];
	}
	
	if(empty($catorder)){
		$sql = "SELECT MAX(catorder) FROM {$db_mymps}category";
		$maxorder = $db->getOne($sql);
		$catorder = $catorder + 1;
	}
	
	if(is_array($catname)){
		foreach ($catname as $key => $value){
			$catorder ++;
			$len = strlen($value);
			if($len < 2 || $len > 30){
				write_msg("分类名必须在2个至30个字符之间");
				exit();
			}
			$db->query("INSERT INTO {$db_mymps}category (catname,title,keywords,description,parentid,modid,catorder,notice,if_upimg) VALUES ('$value','$value','$value','$value','$parentid','$modid','$catorder','$content','$if_upimg')");
		}
		write_class_cache();
		$smarty->clear_cache(mymps_tpl('info_post','smarty'),'input');
		write_msg('行业分类添加成功！','?part=list','record');
		
	}else{
		write_msg('行业分类添加失败，请按格式填写');
	}
	
}elseif($part == 'edit'){

	require_once(MYMPS_INC."/fckeditor/fckeditor.php");
	$cat = $db->getRow("SELECT * FROM {$db_mymps}category WHERE catid = '$catid'");
	$cats = $db->getAll("SELECT * from {$db_mymps}category WHERE parentid=0");
	$acontent = fck_editor('content','Basic',$cat[notice],'80%','200');
	$here = "编辑行业栏目";
	include(mymps_tpl("category_edit"));
	
}elseif($part == 'update'){

	if(empty($catname)){write_msg("请填写行业栏目名称");exit();};
	$len = strlen($catname);
	if($len < 2 || $len > 30){write_msg("行业栏目名必须在2个至30个字符之间");exit();};
	if(empty($content)&&$parentid!='0'){
		$row = $db->getRow("SELECT notice,modid FROM `{$db_mymps}category` WHERE catid = '$parentid'");
		$content = $row[notice];
	}
	$sql = "UPDATE {$db_mymps}category SET catname='$catname',title='$title',keywords='$keywords',description='$description',parentid='$parentid',modid='$modid',catorder='$catorder',notice='$content',if_upimg='$if_upimg' WHERE catid = '$catid'";
	$res = $db->query($sql);
	
	$msgs[]="编辑行业栏目 $catname 成功！<br /><br /><a href='category.php?part=edit&catid=$catid'>重新编辑该栏目</a> |  <a href='category.php?part=list#$catid'>点击此处返回行业栏目列表</a><br /><br /><a href='category.php?part=add'>&raquo; 我要继续增加栏目</a>";
	write_class_cache();
	$smarty->clear_cache(mymps_tpl('info_post','smarty'),'input');
	show_msg($msgs,"编辑行业栏目 <b>".$catname."</b> 成功！");
	
}elseif($part == 'delete'){

	if(empty($catid)){write_msg('没有选择记录');exit();}
	if(mymps_count("category","WHERE parentid = '$catid'")>0){
		write_msg('该行业栏目下有子栏目，无法删除');
		exit();
	}
	if(mymps_count("information","WHERE catid = '$catid'")>0){
		write_msg('该行业栏目下尚有分类信息，无法删除该栏目');
		exit();
	}
	if(mymps_delete("category","WHERE catid = '$catid'")){
		write_msg("删除行业栏目 $catid 成功","category.php?part=list","Mymps");
	}else{
		write_msg("删除行业栏目 $catid 失败！");
		exit();
	}
	write_class_cache();
	$smarty->clear_cache(mymps_tpl('info_post','smarty'),'input');
	
}
?>