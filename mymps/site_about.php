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

$do=$do?$do:'about';
switch ($do){
	case 'about':
	
		$part = $part ? $part : 'edit' ;
		$id = $id ? $id : '1' ;
		require_once(MYMPS_INC."/fckeditor/fckeditor.php");
		
		if($part == 'edit'){
			chk_admin_purview("purview_发布内容");
			$about = $db->getRow("SELECT * FROM {$db_mymps}about WHERE id = '$id'");
			$about_type = $db->getAll("SELECT id,typename FROM {$db_mymps}about");
			$here = $about[typename];	
			$acontent = fck_editor('content','Member',$about['content']);
			include(mymps_tpl("site_about_edit"));
		}elseif($part == 'update'){
			$pubdate = time();
			$res = $db->query("UPDATE {$db_mymps}about SET content='$content',pubdate='$pubdate' WHERE id = '$id'");
			$smarty->clear_cache(mymps_tpl('aboutus_all','smarty'),'aboutus');
			$smarty->clear_cache(mymps_tpl('aboutus','smarty'),'aboutus',$id);
			write_msg("关于我们 ".$id." 修改成功","?id=$id","mymps.com.cn");
		}
		
	break;
	case 'type':
	
		$part = $part ? $part : 'list' ;
		$here="<b>关于我们 栏目管理</b>";
		
		if($part == 'list'){
			chk_admin_purview("purview_栏目列表");
			$sql = "SELECT * FROM {$db_mymps}about ORDER BY typeid ASC";
			$about = $db->getAll($sql);
			include(mymps_tpl("site_about_type"));
		}elseif($part == 'insert'){
			$sql = "Insert Into `{$db_mymps}about`(typeid,typename)Values('$typeid','$typename');";
			$res = $db->query($sql);
			write_msg("添加栏目 <b>".$typename."</b> 成功","?do=type","Mymps.com.cn");
		}elseif($part == 'update'){
			$sql = "UPDATE {$db_mymps}about SET typename='$typename',typeid='$typeid' WHERE id = '$id'";
			$res = $db->query($sql);
			$smarty->clear_cache(mymps_tpl('aboutus_all','smarty'),'aboutus');
			write_msg("关于我们栏目 <b>".$typename."</b> 更改成功","?do=type","MYMPS");
		}elseif($part == 'delete'){
			if(empty($id))write_msg("没有选择记录");
			else{
				mymps_delete("about","WHERE id = '$id'");
				write_msg("删除关于我们栏目  <b>".$id."</b> 成功","?do=type","MYmps.COM.CN");
			}
		}
		
	break;
}
?>