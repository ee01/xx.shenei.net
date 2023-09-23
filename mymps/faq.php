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

$do = $do ? $do : 'faq' ;
switch ($do){
	case 'faq':
		require_once(MYMPS_INC."/fckeditor/fckeditor.php");
		$part = $part ? $part : 'all' ;
		if($part == 'add'){
		
			chk_admin_purview("purview_发布帮助主题");
			$acontent = fck_editor('content','Normal');
			$here = "帮助主题发布";
			$faq_type=$db->getAll("SELECT id,typename FROM `{$db_mymps}faq_type`");
			include(mymps_tpl("faq_add"));
			
		}elseif($part == 'insert'){

			$db->query("INSERT INTO `{$db_mymps}faq` (id,typeid,title,content) Values ('','$typeid','$title','$content')");
			$inid = $db->insert_id();
			$msgs[]="恭喜您，问题帮助发布成功！<br /><br /><a href='$db_mymps_global[SiteUrl]/public/about.php?part=faq&id=$inid' target=_blank>点此查看</a> | 
			<a href='faq.php?part=edit&id=$inid'>重新编辑</a> |  
			<a href='faq.php?part=all'>返回帮助列表</a>			
			<br /><br />
			<a href='faq.php?part=add'>>>我要继续发布帮助</a>";
			$smarty->clear_cache(mymps_tpl('faq_all','smarty'),'faq');
			$smarty->clear_cache(mymps_tpl('faq','smarty'),'faq',$id);
			show_msg($msgs,"问题帮助 <b>".$title."</b> 发布成功");
			
		}elseif($part == 'edit'){
		
			if(trim($action) == 'dopost'){

				$update = $db->query("UPDATE `{$db_mymps}faq` SET title='$title',content='$content',typeid='$typeid' WHERE id = '$id'");
				if($update){
					$msgs[]="恭喜您，问题帮助修改成功！<br /><br /><a href='$db_mymps_global[SiteUrl]/public/about.php?part=faq&id=$id' target=_blank>点此查看</a> | 
					<a href='faq.php?part=edit&id=$id'>重新编辑</a> |  
					<a href='faq.php?part=all'>返回帮助列表</a>			
					<br /><br />
					<a href='faq.php?part=add'>>>我要继续发布帮助</a>";
					$smarty->clear_cache(mymps_tpl('faq_all','smarty'),'faq');
					$smarty->clear_cache(mymps_tpl('faq','smarty'),'faq',$id);
					show_msg($msgs,"问题帮助 <b>".$title."</b> 修改成功");
				}
				
			}else {
			
				$id = intval($_GET['id']);
				$here = "修改问题帮助";
				$faq_type=$db->getAll("SELECT id,typename FROM `{$db_mymps}faq_type`");
				$edit = $db -> getRow("SELECT * FROM {$db_mymps}faq WHERE id = '$id'");
				$acontent = fck_editor('content','Normal',$edit['content']);
				include(mymps_tpl("faq_edit"));
				
			}
		}elseif($part == 'delete'){

			if(empty($id)){
				write_msg("没有选择记录");
			}else{
				mymps_delete("faq","WHERE id = '$id'");
				write_msg("删除帮助 $id 成功",$url,"mymps");
			}
			
		}elseif($part == 'all'){
		
			chk_admin_purview("purview_问题帮助列表");
			$faq_type=$db->getAll("SELECT id,typename FROM `{$db_mymps}faq_type`");
			$page = empty($page) ? '1' : intval($page);
			$where="WHERE a.typeid like '%".$typeid."%'";
			$sql = "SELECT a.id,a.title,b.typename,a.typeid FROM {$db_mymps}faq AS a LEFT JOIN {$db_mymps}faq_type AS b ON a.typeid = b.id $where ORDER BY a.id DESC";
			$rows_num = $db->getOne("SELECT COUNT(*) FROM {$db_mymps}faq AS a $where");
			$param	  =setParam(array('typeid'));
			$faq 	  = array();
			foreach(page1($sql) as $k => $row){
				$arr['id']       = $row['id'];
				$arr['title']    = $row['title'];
				$arr['typeid'] 	 = $row['typeid'];
				$arr['typename'] = $row['typename'];
				$faq[]      	 = $arr;
			}
			$here="帮助主题";
			include(mymps_tpl("faq_all"));
			
		}elseif($part == 'delall'){
		
			$id = mymps_del_all("faq",$_POST['id']);
			write_msg('删除帮助 '.$id.' 成功',$url,'mymps_record');
			
		}
	break;
	case 'type':
		$part = $part ? $part : 'list' ;
		$here="<b>帮助中心类别管理</b>";
		if ($part == 'list'){
			$links = $db->getAll("SELECT * FROM {$db_mymps}faq_type ORDER BY id Asc");
			include(mymps_tpl("faq_type"));
		}elseif ($part == 'insert'){
			$sql = "Insert Into `{$db_mymps}faq_type`(id,typename)
				Values('','$typename');";
			$res = $db->query($sql);
			write_msg("添加帮助分类 $typename 成功","faq.php?do=type","mymps");
		}elseif ($part == 'update'){
			$sql = "UPDATE {$db_mymps}faq_type SET typename='$typename' WHERE id = '$id'";
			$res = $db->query($sql);
			write_msg("分类 $typename 更改成功","faq.php?do=type");
		}elseif ($part == 'delete'){
			if(empty($id)){
				write_msg("没有选择记录");
			}else{
				$url = '?do=type';
				$db -> query("DELETE FROM `{$db_mymps}faq` WHERE typeid = ".$id."");
				mymps_delete("faq_type","WHERE id='$id'");
				write_msg("删除分类 $id 成功",$url,"mymps");
			}
		}
	break;
}
?>