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
	chk_admin_purview("purview_��ҵ����");
	$cat = get_cat_list();
	$here = "��ҵ��Ŀ�б�";
	include(mymps_tpl("category_list"));
}elseif ($part == 'add'){
	chk_admin_purview("purview_������ҵ");
	require_once(MYMPS_INC."/fckeditor/fckeditor.php");
	$acontent = fck_editor('content','Basic','','80%','200');
	$cats = $db->getAll("SELECT * from {$db_mymps}category WHERE parentid=0");
	$maxorder = $db->getOne("SELECT MAX(catorder) FROM {$db_mymps}category");
	$maxorder = $maxorder + 1;
	$here = "�����ҵ��Ŀ";
	include(mymps_tpl("category_add"));
}elseif($part == 'insert'){	

	if(empty($catname)){
		write_msg("����д��������");
		exit();
	}
	
	$catname  	 = explode('|',trim($catname));

	if(empty($catname)){write_msg("����д��ҵ��Ŀ����");exit();};
	
	$len = strlen($catname);
	if($len < 2 || $len > 30){write_msg("��ҵ��Ŀ��������2����30���ַ�֮��");exit();};
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
				write_msg("������������2����30���ַ�֮��");
				exit();
			}
			$db->query("INSERT INTO {$db_mymps}category (catname,title,keywords,description,parentid,modid,catorder,notice,if_upimg) VALUES ('$value','$value','$value','$value','$parentid','$modid','$catorder','$content','$if_upimg')");
		}
		write_class_cache();
		$smarty->clear_cache(mymps_tpl('info_post','smarty'),'input');
		write_msg('��ҵ������ӳɹ���','?part=list','record');
		
	}else{
		write_msg('��ҵ�������ʧ�ܣ��밴��ʽ��д');
	}
	
}elseif($part == 'edit'){

	require_once(MYMPS_INC."/fckeditor/fckeditor.php");
	$cat = $db->getRow("SELECT * FROM {$db_mymps}category WHERE catid = '$catid'");
	$cats = $db->getAll("SELECT * from {$db_mymps}category WHERE parentid=0");
	$acontent = fck_editor('content','Basic',$cat[notice],'80%','200');
	$here = "�༭��ҵ��Ŀ";
	include(mymps_tpl("category_edit"));
	
}elseif($part == 'update'){

	if(empty($catname)){write_msg("����д��ҵ��Ŀ����");exit();};
	$len = strlen($catname);
	if($len < 2 || $len > 30){write_msg("��ҵ��Ŀ��������2����30���ַ�֮��");exit();};
	if(empty($content)&&$parentid!='0'){
		$row = $db->getRow("SELECT notice,modid FROM `{$db_mymps}category` WHERE catid = '$parentid'");
		$content = $row[notice];
	}
	$sql = "UPDATE {$db_mymps}category SET catname='$catname',title='$title',keywords='$keywords',description='$description',parentid='$parentid',modid='$modid',catorder='$catorder',notice='$content',if_upimg='$if_upimg' WHERE catid = '$catid'";
	$res = $db->query($sql);
	
	$msgs[]="�༭��ҵ��Ŀ $catname �ɹ���<br /><br /><a href='category.php?part=edit&catid=$catid'>���±༭����Ŀ</a> |  <a href='category.php?part=list#$catid'>����˴�������ҵ��Ŀ�б�</a><br /><br /><a href='category.php?part=add'>&raquo; ��Ҫ����������Ŀ</a>";
	write_class_cache();
	$smarty->clear_cache(mymps_tpl('info_post','smarty'),'input');
	show_msg($msgs,"�༭��ҵ��Ŀ <b>".$catname."</b> �ɹ���");
	
}elseif($part == 'delete'){

	if(empty($catid)){write_msg('û��ѡ���¼');exit();}
	if(mymps_count("category","WHERE parentid = '$catid'")>0){
		write_msg('����ҵ��Ŀ��������Ŀ���޷�ɾ��');
		exit();
	}
	if(mymps_count("information","WHERE catid = '$catid'")>0){
		write_msg('����ҵ��Ŀ�����з�����Ϣ���޷�ɾ������Ŀ');
		exit();
	}
	if(mymps_delete("category","WHERE catid = '$catid'")){
		write_msg("ɾ����ҵ��Ŀ $catid �ɹ�","category.php?part=list","Mymps");
	}else{
		write_msg("ɾ����ҵ��Ŀ $catid ʧ�ܣ�");
		exit();
	}
	write_class_cache();
	$smarty->clear_cache(mymps_tpl('info_post','smarty'),'input');
	
}
?>