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

$part = $part ? $part : 'list' ;
if($part == 'list'){
	chk_admin_purview("purview_��վ�����б�");
	$page = empty($page) ? '1' : $page;
	switch ($msg_level){
		case '0':
			$msg_level=' AND msg_level = 0';
		break;
		
		case '1':
			$msg_level=' AND msg_level = 1';
		break;
		
		case '':
			$msg_level='';
		break;
	}
	switch ($_GET[reply]){
		case '0':
			$reply=' AND reply = ""';
		break;
		
		case '1':
			$reply=' AND reply !=""';
		break;
		
		case '':
			$reply='';
		break;
	}
	switch ($_GET[hidden]){
		case '0':
			$hidden=' AND hidden = 0';
		break;
		
		case '1':
			$hidden=' AND hidden = 1';
		break;
		
		case '':
			$hidden='';
		break;
	}
	
	$where 	= "WHERE title LIKE '%".$keywords."%' ".$hidden.$msg_level.$reply."";
	$sql 	= "SELECT * FROM {$db_mymps}guestbook $where ORDER BY gid desc";
	$here 	= "��վ���Թ���";
	
	$rows_num = mymps_count("guestbook",$where);
	$param=setParam(array('part','msg_level','hidden','keywords','reply'));
	$links = array();
	foreach(page1($sql) as $k => $row){
		$arr['gid']       = $row['gid'];
		$arr['title']     = $row['title'];
		$arr['username']  = $row['username'];
		$arr['msg_level'] = $row['msg_level'];
		$arr['addtime']   = GetTime($row['addtime']);
		$arr['hidden']	  = $row['hidden'];
		$arr['reply'] 	  = $row['reply'];
		$arr['ismember']  = $row['ismember'];
		$links[]      	  = $arr;
	}
	include(mymps_tpl("guestbook_default"));
	
}elseif($part == 'reply'){

	$sql 	= "SELECT * FROM `{$db_mymps}guestbook` WHERE gid = '$gid'";
	$row 	= $db->getRow($sql);
	$here 	= "�ظ���������";
	$hidden .= "<label for=h0><input id=h0 name=hidden type=radio value=0 ";
	$hidden .= ($row[hidden]==0)?'checked':'';
	$hidden .= ">����</label> <label for=h1><input name=hidden type=radio value=1 id=h1";
	$hidden .= ($row[hidden]==1)?'checked':'';
	$hidden .= ">���Ļ�</label>";
	
	$msg_level .= "<label for=m0><input id=m0 name=msg_level type=radio value=0 ";
	$msg_level .= ($row[msg_level]==0)?'checked':'';
	$msg_level .= ">�����</label> <label for=m1><input name=msg_level type=radio value=1 id=m1";
	$msg_level .= ($row[msg_level]==1)?' checked':'';
	$msg_level .= ">����</label>";
	
	include(mymps_tpl("guestbook_reply"));
	
}elseif($part == 'update'){

	$res = $db->query("UPDATE {$db_mymps}guestbook SET reply='".trim($_POST[reply])."',replyer='$admin_id',title='".trim($title)."',content='".textarea_post_change($content)."',replytime='".time()."',hidden='".$hidden."',msg_level='".$msg_level."' WHERE gid = '$gid'");
	write_msg("��վ���� ".$gid." �ظ��ɹ�","?part=reply&gid=".$gid,"mymps");
	$smarty->clear_cache(mymps_tpl('guestbook','smarty'),'guestbook',1);
	
}elseif($part == 'delete'){

	if(empty($gid)){write_msg("û��ѡ���¼"); exit();};
	mymps_delete("guestbook","WHERE gid = '$gid'");
	write_msg("ɾ������ ".$gid." �ɹ�",$url,"mymps");
	$smarty->clear_cache(mymps_tpl('guestbook','smarty'),'guestbook',1);
	
}elseif($part == 'delall'){

	$gid = mymps_del_all("guestbook",$gid,'gid');
	write_msg('ɾ�����Լ�¼ '.$gid.' �ɹ�',$url,"mymps");
	$smarty->clear_cache(mymps_tpl('guestbook','smarty'),'guestbook',1);
	
}
?>