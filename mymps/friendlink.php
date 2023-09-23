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
define('CURSCRIPT','friendlink');

require_once(dirname(__FILE__)."/global.php");
require_once(MYMPS_INC."/db.class.php");
require_once(MYMPS_SMARTY."/include.php");
$do = $do ? $do : 'link';
switch ($do){
	case 'link':
		$part = $part ? $part : 'list';
		require_once MYMPS_INC."/flink.fun.php";
		if($part == 'list'){
			chk_admin_purview("purview_友情链接列表");
			$rows_num = mymps_count("flink",$where);
			$param	= setParam(array("do","part"));
			$links 	= page1("SELECT * FROM {$db_mymps}flink ORDER BY ordernumber asc");
			$here 	= "友情链接管理";
			include mymps_tpl(CURSCRIPT."_default");
		}elseif($part == 'add'){
			chk_admin_purview("purview_增加链接");
			$here = "添加友情链接";
			$sql = "SELECT * FROM {$db_mymps}flink_type ORDER BY id Asc";
			$links = $db->getAll($sql);
			include mymps_tpl(CURSCRIPT."_add");
		}elseif($part == 'insert'){
			$sql = "Insert Into `{$db_mymps}flink`(id,url,webname,weblogo,typeid,createtime,ischeck,ordernumber)
				Values('','$url','$webname','$weblogo','$typeid','".time()."','$ischeck','$ordernumber'); ";
			$res = $db->query($sql);
			write_msg("添加友情链接 $webname 成功","?part=list","mymps");
			$smarty->clear_cache(mymps_tpl('friendlink','smarty'),'friendlink');
			$smarty->clear_cache(mymps_tpl('index','smarty'));
		}elseif ($part == 'edit'){
			$sql = "SELECT * FROM {$db_mymps}flink WHERE id = '$id'";
			$link = $db->getRow($sql);
			$here = "编辑友情链接";
			$sql = "SELECT * FROM {$db_mymps}flink_type ORDER BY id Asc";
			$links = $db->getAll($sql);
			include mymps_tpl(CURSCRIPT."_edit");
		}elseif($part == 'update'){
			if(empty($url)||empty($webname)){write_msg("请输入完整信息");exit();};
			$sql = "UPDATE {$db_mymps}flink SET webname='$webname',weblogo='$weblogo',url='$url',ordernumber='$ordernumber',createtime='".time()."',ischeck='$ischeck',msg='".textarea_post_change($msg)."',name='$name',qq='$qq',email='$email',typeid='$typeid',dayip='$dayip',pr='$pr' WHERE id = '$id'";
			$res = $db->query($sql);
			write_msg("编辑链接 $webname 成功","?part=edit&id=".$id,"mymps");
			$smarty->clear_cache(mymps_tpl('friendlink','smarty'),'friendlink');
			$smarty->clear_cache(mymps_tpl('index','smarty'));
		}elseif($part == 'delete'){
			if(empty($id))write_msg("没有选择记录");
			mymps_delete("flink","WHERE id = '$id'");
			write_msg("删除友情链接 $id 成功","friendlink.php");
			$smarty->clear_cache(mymps_tpl('friendlink','smarty'),'friendlink');
			$smarty->clear_cache(mymps_tpl('index','smarty'));
		}elseif ($part == 'delall'){
			$id = mymps_del_all("flink",$id);
			write_msg("友情链接更新或删除成功！", "?do=link&part=list","mymps");
			$smarty->clear_cache(mymps_tpl('friendlink','smarty'),'friendlink');
			$smarty->clear_cache(mymps_tpl('index','smarty'));
		}
	break;
	case 'type':
		$part = $part ? $part : 'list' ;
		$here="<b>网站类型管理</b>";
		if ($part == 'list'){
			$sql = "SELECT * FROM {$db_mymps}flink_type ORDER BY id Asc";
			$links = $db->getAll($sql);
			include mymps_tpl(CURSCRIPT."_type");
		}elseif ($part == 'insert'){
			$typename = trim($typename);
			$sql = "Insert Into `{$db_mymps}flink_type`(id,typename)
				Values('','$typename');";
			$res = $db->query($sql);
			write_msg("添加网站分类 $typename 成功","?do=type","mymps");
			$smarty->clear_cache(mymps_tpl('friendlink','smarty'),'friendlink');
		}elseif ($part == 'update'){
			$typename = trim($_POST['typename']);
			$sql = "UPDATE {$db_mymps}flink_type SET typename='$typename' WHERE id = '$id'";
			$res = $db->query($sql);
			write_msg("分类 $typename 更改成功","?do=type","mymps");
			$smarty->clear_cache(mymps_tpl('friendlink','smarty'),'friendlink');
		}elseif ($part == 'delete'){
			if(empty($id)){
				write_msg("没有选择记录");
			}else{
				mymps_delete("flink_type","WHERE id = '$id'");
				write_msg("删除分类 $id 成功","?do=type","mymps");
				$smarty->clear_cache(mymps_tpl('friendlink','smarty'),'friendlink');
			}
		}
	break;
}
?>