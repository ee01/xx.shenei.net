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
			chk_admin_purview("purview_���������б�");
			$rows_num = mymps_count("flink",$where);
			$param	= setParam(array("do","part"));
			$links 	= page1("SELECT * FROM {$db_mymps}flink ORDER BY ordernumber asc");
			$here 	= "�������ӹ���";
			include mymps_tpl(CURSCRIPT."_default");
		}elseif($part == 'add'){
			chk_admin_purview("purview_��������");
			$here = "�����������";
			$sql = "SELECT * FROM {$db_mymps}flink_type ORDER BY id Asc";
			$links = $db->getAll($sql);
			include mymps_tpl(CURSCRIPT."_add");
		}elseif($part == 'insert'){
			$sql = "Insert Into `{$db_mymps}flink`(id,url,webname,weblogo,typeid,createtime,ischeck,ordernumber)
				Values('','$url','$webname','$weblogo','$typeid','".time()."','$ischeck','$ordernumber'); ";
			$res = $db->query($sql);
			write_msg("����������� $webname �ɹ�","?part=list","mymps");
			$smarty->clear_cache(mymps_tpl('friendlink','smarty'),'friendlink');
			$smarty->clear_cache(mymps_tpl('index','smarty'));
		}elseif ($part == 'edit'){
			$sql = "SELECT * FROM {$db_mymps}flink WHERE id = '$id'";
			$link = $db->getRow($sql);
			$here = "�༭��������";
			$sql = "SELECT * FROM {$db_mymps}flink_type ORDER BY id Asc";
			$links = $db->getAll($sql);
			include mymps_tpl(CURSCRIPT."_edit");
		}elseif($part == 'update'){
			if(empty($url)||empty($webname)){write_msg("������������Ϣ");exit();};
			$sql = "UPDATE {$db_mymps}flink SET webname='$webname',weblogo='$weblogo',url='$url',ordernumber='$ordernumber',createtime='".time()."',ischeck='$ischeck',msg='".textarea_post_change($msg)."',name='$name',qq='$qq',email='$email',typeid='$typeid',dayip='$dayip',pr='$pr' WHERE id = '$id'";
			$res = $db->query($sql);
			write_msg("�༭���� $webname �ɹ�","?part=edit&id=".$id,"mymps");
			$smarty->clear_cache(mymps_tpl('friendlink','smarty'),'friendlink');
			$smarty->clear_cache(mymps_tpl('index','smarty'));
		}elseif($part == 'delete'){
			if(empty($id))write_msg("û��ѡ���¼");
			mymps_delete("flink","WHERE id = '$id'");
			write_msg("ɾ���������� $id �ɹ�","friendlink.php");
			$smarty->clear_cache(mymps_tpl('friendlink','smarty'),'friendlink');
			$smarty->clear_cache(mymps_tpl('index','smarty'));
		}elseif ($part == 'delall'){
			$id = mymps_del_all("flink",$id);
			write_msg("�������Ӹ��»�ɾ���ɹ���", "?do=link&part=list","mymps");
			$smarty->clear_cache(mymps_tpl('friendlink','smarty'),'friendlink');
			$smarty->clear_cache(mymps_tpl('index','smarty'));
		}
	break;
	case 'type':
		$part = $part ? $part : 'list' ;
		$here="<b>��վ���͹���</b>";
		if ($part == 'list'){
			$sql = "SELECT * FROM {$db_mymps}flink_type ORDER BY id Asc";
			$links = $db->getAll($sql);
			include mymps_tpl(CURSCRIPT."_type");
		}elseif ($part == 'insert'){
			$typename = trim($typename);
			$sql = "Insert Into `{$db_mymps}flink_type`(id,typename)
				Values('','$typename');";
			$res = $db->query($sql);
			write_msg("�����վ���� $typename �ɹ�","?do=type","mymps");
			$smarty->clear_cache(mymps_tpl('friendlink','smarty'),'friendlink');
		}elseif ($part == 'update'){
			$typename = trim($_POST['typename']);
			$sql = "UPDATE {$db_mymps}flink_type SET typename='$typename' WHERE id = '$id'";
			$res = $db->query($sql);
			write_msg("���� $typename ���ĳɹ�","?do=type","mymps");
			$smarty->clear_cache(mymps_tpl('friendlink','smarty'),'friendlink');
		}elseif ($part == 'delete'){
			if(empty($id)){
				write_msg("û��ѡ���¼");
			}else{
				mymps_delete("flink_type","WHERE id = '$id'");
				write_msg("ɾ������ $id �ɹ�","?do=type","mymps");
				$smarty->clear_cache(mymps_tpl('friendlink','smarty'),'friendlink');
			}
		}
	break;
}
?>