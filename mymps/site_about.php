<?php
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * ��Ȩ���� mymps�з��Ŷӣ���������Ȩ����
 * ��վ��ַ: http://www.mymps.com.cn��
 * ������̳��http://bbs.mymps.com.cn��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�á�
 * ������Դ������޸ĺ����κ���ʽ�κ�Ŀ�ĵ��ٷ������������ǽ�׷�����ķ�������!!
 * ============================================================================
 * �������: ���ƽ���峤��
 * ��ϵ��ʽ��QQ:3037821 MSN:business@live.it
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
			chk_admin_purview("purview_��������");
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
			write_msg("�������� ".$id." �޸ĳɹ�","?id=$id","mymps.com.cn");
		}
		
	break;
	case 'type':
	
		$part = $part ? $part : 'list' ;
		$here="<b>�������� ��Ŀ����</b>";
		
		if($part == 'list'){
			chk_admin_purview("purview_��Ŀ�б�");
			$sql = "SELECT * FROM {$db_mymps}about ORDER BY typeid ASC";
			$about = $db->getAll($sql);
			include(mymps_tpl("site_about_type"));
		}elseif($part == 'insert'){
			$sql = "Insert Into `{$db_mymps}about`(typeid,typename)Values('$typeid','$typename');";
			$res = $db->query($sql);
			write_msg("�����Ŀ <b>".$typename."</b> �ɹ�","?do=type","Mymps.com.cn");
		}elseif($part == 'update'){
			$sql = "UPDATE {$db_mymps}about SET typename='$typename',typeid='$typeid' WHERE id = '$id'";
			$res = $db->query($sql);
			$smarty->clear_cache(mymps_tpl('aboutus_all','smarty'),'aboutus');
			write_msg("����������Ŀ <b>".$typename."</b> ���ĳɹ�","?do=type","MYMPS");
		}elseif($part == 'delete'){
			if(empty($id))write_msg("û��ѡ���¼");
			else{
				mymps_delete("about","WHERE id = '$id'");
				write_msg("ɾ������������Ŀ  <b>".$id."</b> �ɹ�","?do=type","MYmps.COM.CN");
			}
		}
		
	break;
}
?>