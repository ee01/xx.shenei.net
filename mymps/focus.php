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
require_once(MYMPS_INC."/upfile.fun.php");
require_once(MYMPS_SMARTY."/include.php");

$part = $part ? $part : 'list' ;

if($part == 'list'){

	chk_admin_purview("purview_����ͼ�б�");
	$sql="SELECT * FROM `{$db_mymps}focus` ORDER BY focusorder DESC";
	$row=$db->getAll($sql);
	$here = "��ҳ����ͼ�޸�";
	include(mymps_tpl("focus_list"));
	
}elseif($part =='input'){

	chk_admin_purview("purview_�ϴ�����ͼ");
	$here = "��ӽ���ͼ";
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
	write_msg("����ͼ�ϴ��ɹ�","focus.php","mymps");
	$smarty->clear_cache(mymps_tpl('index','smarty'));
	
}elseif($part == 'edit'){

	$here = "�޸Ľ���ͼ";
	if (empty($id)){write_msg("��δָ��ID"); exit();}
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
	write_msg("����ͼ ".$id." �޸ĳɹ�!","focus.php","mymps");
	$smarty->clear_cache(mymps_tpl('index','smarty'));
	
}elseif($part == 'del'){

	if(!empty($image)){
		@unlink(MYMPS_ROOT.$image);
		@unlink(MYMPS_ROOT.$pre_image);
	}
	
	mymps_delete("focus","WHERE id = '$id'");
	write_msg("����ͼ ".$id." ɾ���ɹ�","focus.php","mymps");
	$smarty->clear_cache(mymps_tpl('index','smarty'));
	
}
?>