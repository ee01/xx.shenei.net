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
require_once(MYMPS_INC."/fckeditor/fckeditor.php");
require_once(MYMPS_SMARTY."/include.php");

$mymps_title_color = array(
	"#ff0000",
	"#006ffd",
	"#444444",
	"#000000",
	"#46a200",
	"#ff9900",
	"#ffffff"
);

$part = $part ? $part : 'all' ;

if($part == 'add'){

	chk_admin_purview("purview_��������");
	$acontent = fck_editor('content','Normal');
	$here = "������վ����";
	include(mymps_tpl("announce_add"));
	
}elseif($part == 'insert'){

	$pubdate=time();
	
	if($db->query("Insert Into `{$db_mymps}announce` (id,title,titlecolor,ifcomment,content,pubdate,author,redirecturl) Values ('','$title','$titlecolor','$ifcomment','$content','$pubdate','$author','$redirecturl')")){
		$inid = $db->insert_id();
		$msgs[]="��ϲ��������".$title."�����ɹ���<br /><br /><a href='../public/about.php?part=announce&id=$inid' target=_blank>��˲鿴</a> | 
		<a href='?part=edit&id=$inid'>���±༭</a> |  
		<a href='?part=all'>���ع����б�</a>			
		<br /><br />
		<a href='?part=add'>>>��Ҫ������������</a>";
		$smarty->clear_cache(MYMPS_TPL.'/about/announce_all.html','announce',1);
		$smarty->clear_cache(MYMPS_TPL.'/about/announce.html','announce',$id);
		show_msg($msgs,"���� <b>".$title."</b> �����ɹ�!");
		write_announce_cache();
		$smarty->clear_cache(mymps_tpl('index','smarty'));
		
	}else{
	
		$msgs[]=$unkown_err_msg;
		show_msg($msgs);
		
	}
	
}elseif($part == 'edit'){

	if(trim($_POST[action])=='dopost'){

		$pubdate 	 = time();
		$sql = "UPDATE `{$db_mymps}announce` SET title='$title',titlecolor ='$titlecolor',ifcomment ='$ifcomment',content='$content',author='$author',pubdate='$pubdate',redirecturl='$redirecturl' WHERE id = '$id'";
		$res = $db->query($sql);
		$msgs[]="��ϲ���������޸ĳɹ���<br /><br /><a href='../public/about.php?part=announce&id=$id' target=_blank>��˲鿴</a> | 
		<a href='?part=edit&id=$id'>���±༭</a> |  
		<a href='?part=all'>���ع����б�</a>			
		<br /><br />
		<a href='?part=add'>&raquo; ��Ҫ������������</a>";
		$smarty -> clear_cache(MYMPS_TPL.'/about/announce_all.html','announce',1);
		$smarty -> clear_cache(MYMPS_TPL.'/about/announce.html','announce',$id);
		write_announce_cache();
		show_msg($msgs,"���� <b>".$title."</b> �޸ĳɹ�!");
		
	}else{
	
		$id = intval($id);
		
		$here = "�޸���վ����";
		$edit = $db -> getRow("SELECT * FROM {$db_mymps}announce WHERE id = '$id'");
		$acontent = fck_editor('content','Normal',$edit['content']);
		include(mymps_tpl("announce_edit"));
		
	}
}elseif($part == 'delete'){

	$id = intval($id);
	if(empty($id))write_msg("û��ѡ���¼");
	else{
		mymps_delete("announce","WHERE id = '$id'");
		write_msg("ɾ������ $id �ɹ�",$url,"Mymps_record");
		write_announce_cache();
		$smarty->clear_cache(mymps_tpl('index','smarty'));
	}
	
}elseif($part == 'all'){

	chk_admin_purview("purview_�ѷ����Ĺ���");
	$page = empty($page) ? 1 : intval($page);

	$where="WHERE title like '%".$title."%' and author like '%".$author."%'";
	$sql = "SELECT id,title,author,hits,pubdate FROM {$db_mymps}announce $where ORDER BY id DESC";
	$rows_num = mymps_count('announce',$where);
	$param=setParam(array('id','title','author','hits'));
	$announce = array();
	foreach(page1($sql) as $k => $row){
		$arr['id']       = $row['id'];
		$arr['title']    = $row['title'];
		$arr['pubdate']  = GetTime($row['pubdate']);
		$arr['author'] 	 = $row['author'];
		$arr['hits'] 	 = $row['hits'];
		$announce[]      = $arr;
	}
	$here="�����б�";
	include(mymps_tpl("announce_all"));
	
}elseif($part == 'delall'){

	write_msg('ɾ������ '.mymps_del_all("announce",$_POST[id]).' �ɹ�',$url,"Mymps_record");
	write_announce_cache();
	$smarty->clear_cache(mymps_tpl('index','smarty'));
	
}
?>