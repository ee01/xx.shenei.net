<?php
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * ��Ȩ���� mymps�з��Ŷӣ���������Ȩ����
 * ��վ��ַ: http://www.mymps.com.cn��
 * ������̳��http://bbs.mymps.com.cn��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�á�
 * ������Դ������޸ĺ����κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 * �������: ���ƽ���峤��
 * ��ϵ��ʽ��QQ:3037821 MSN:business@live.it
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
		
			chk_admin_purview("purview_������������");
			$acontent = fck_editor('content','Normal');
			$here = "�������ⷢ��";
			$faq_type=$db->getAll("SELECT id,typename FROM `{$db_mymps}faq_type`");
			include(mymps_tpl("faq_add"));
			
		}elseif($part == 'insert'){

			$db->query("INSERT INTO `{$db_mymps}faq` (id,typeid,title,content) Values ('','$typeid','$title','$content')");
			$inid = $db->insert_id();
			$msgs[]="��ϲ����������������ɹ���<br /><br /><a href='$db_mymps_global[SiteUrl]/public/about.php?part=faq&id=$inid' target=_blank>��˲鿴</a> | 
			<a href='faq.php?part=edit&id=$inid'>���±༭</a> |  
			<a href='faq.php?part=all'>���ذ����б�</a>			
			<br /><br />
			<a href='faq.php?part=add'>>>��Ҫ������������</a>";
			$smarty->clear_cache(mymps_tpl('faq_all','smarty'),'faq');
			$smarty->clear_cache(mymps_tpl('faq','smarty'),'faq',$id);
			show_msg($msgs,"������� <b>".$title."</b> �����ɹ�");
			
		}elseif($part == 'edit'){
		
			if(trim($action) == 'dopost'){

				$update = $db->query("UPDATE `{$db_mymps}faq` SET title='$title',content='$content',typeid='$typeid' WHERE id = '$id'");
				if($update){
					$msgs[]="��ϲ������������޸ĳɹ���<br /><br /><a href='$db_mymps_global[SiteUrl]/public/about.php?part=faq&id=$id' target=_blank>��˲鿴</a> | 
					<a href='faq.php?part=edit&id=$id'>���±༭</a> |  
					<a href='faq.php?part=all'>���ذ����б�</a>			
					<br /><br />
					<a href='faq.php?part=add'>>>��Ҫ������������</a>";
					$smarty->clear_cache(mymps_tpl('faq_all','smarty'),'faq');
					$smarty->clear_cache(mymps_tpl('faq','smarty'),'faq',$id);
					show_msg($msgs,"������� <b>".$title."</b> �޸ĳɹ�");
				}
				
			}else {
			
				$id = intval($_GET['id']);
				$here = "�޸��������";
				$faq_type=$db->getAll("SELECT id,typename FROM `{$db_mymps}faq_type`");
				$edit = $db -> getRow("SELECT * FROM {$db_mymps}faq WHERE id = '$id'");
				$acontent = fck_editor('content','Normal',$edit['content']);
				include(mymps_tpl("faq_edit"));
				
			}
		}elseif($part == 'delete'){

			if(empty($id)){
				write_msg("û��ѡ���¼");
			}else{
				mymps_delete("faq","WHERE id = '$id'");
				write_msg("ɾ������ $id �ɹ�",$url,"mymps");
			}
			
		}elseif($part == 'all'){
		
			chk_admin_purview("purview_��������б�");
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
			$here="��������";
			include(mymps_tpl("faq_all"));
			
		}elseif($part == 'delall'){
		
			$id = mymps_del_all("faq",$_POST['id']);
			write_msg('ɾ������ '.$id.' �ɹ�',$url,'mymps_record');
			
		}
	break;
	case 'type':
		$part = $part ? $part : 'list' ;
		$here="<b>��������������</b>";
		if ($part == 'list'){
			$links = $db->getAll("SELECT * FROM {$db_mymps}faq_type ORDER BY id Asc");
			include(mymps_tpl("faq_type"));
		}elseif ($part == 'insert'){
			$sql = "Insert Into `{$db_mymps}faq_type`(id,typename)
				Values('','$typename');";
			$res = $db->query($sql);
			write_msg("��Ӱ������� $typename �ɹ�","faq.php?do=type","mymps");
		}elseif ($part == 'update'){
			$sql = "UPDATE {$db_mymps}faq_type SET typename='$typename' WHERE id = '$id'";
			$res = $db->query($sql);
			write_msg("���� $typename ���ĳɹ�","faq.php?do=type");
		}elseif ($part == 'delete'){
			if(empty($id)){
				write_msg("û��ѡ���¼");
			}else{
				$url = '?do=type';
				$db -> query("DELETE FROM `{$db_mymps}faq` WHERE typeid = ".$id."");
				mymps_delete("faq_type","WHERE id='$id'");
				write_msg("ɾ������ $id �ɹ�",$url,"mymps");
			}
		}
	break;
}
?>