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

$part 	= $part ? $part : 'info' ;
$action = $action ? $action : 'list' ;

if ($action == "del") {

	if(mymps_delete($part."_comment","WHERE id = '$id'")){
		write_msg("ɾ����Ϣ���� $id �ɹ�",$url,"Mymps");
	}else{
		write_msg("ɾ�����ۼ�¼ʧ�ܣ�");
	}
	
}elseif($action == 'delall'){

	if(!is_array($id)){write_msg("��û��ѡ���κμ�¼��");exit();}
	write_msg("������Ϣ ".mymps_del_all($part."_comment",$id)."ɾ���ɹ���",$url,"mymps");
	
}elseif($action == 'level0'){

	if(!is_array($id)){write_msg("��û��ѡ���κμ�¼��");exit();}
	foreach ($id as $k =>$v){
		$db->query("UPDATE `{$db_mymps}".$part."_comment` SET comment_level = 0 WHERE id = '$v'");
	}
	write_msg("��������״̬Ϊ ����״̬ �����ɹ���",$url,"RECORD");
	
}elseif($action == 'level1'){

	if(!is_array($id)){write_msg("��û��ѡ���κμ�¼��");exit();}
	foreach ($id as $k =>$v){
		$db->query("UPDATE `{$db_mymps}".$part."_comment` SET comment_level = 1 WHERE id = '$v'");
	}
	write_msg("��������״̬Ϊ ����״̬ �����ɹ���",$url,"RECORD");
	
}elseif ($action == "list"){

	require_once(MYMPS_DATA."/info.level.inc.php");
	
	if($part == 'announce'){
		chk_admin_purview("purview_�ѷ����Ĺ���");
	}elseif($part == 'info'){
		chk_admin_purview("purview_��Ϣ����");
	}
	
	$here 	= "��Ϣ�����б�";
	$page 	= empty($page) ? 1 : intval($page);
	
	$where ="WHERE a.content LIKE '%".$keywords."%' AND a.comment_level LIKE '%".$_GET[comment_level]."%'";
	
	$table = ($part == 'info')?'information':'announce';
	
	$sql = "SELECT a.id,a.userid,a.content,a.pubtime,a.ip,a.".$part."id,b.title,a.comment_level FROM `{$db_mymps}".$part."_comment` AS a LEFT JOIN `{$db_mymps}".$table."` AS b ON a.".$part."id = b.id $where ORDER BY a.pubtime DESC";
	
	$rows_num = $db->getOne("SELECT COUNT(*) FROM `{$db_mymps}".$part."_comment` AS a $where");
	
	$param=setParam(array("part","keywords","comment_level"));
	
	$comment = array();
	
	foreach(page1($sql) as $k => $row){
		$arr['id']        = $row['id'];
		$arr['content']   = $row['content'];
		$arr['title']     = ($part == 'info')?"<a href=../public/info.php?id=".$row[infoid]." target=_blank>".$row[title]."</a>":"<a href=../public/about.php?part=announce&id=".$row[announceid]." target=_blank>".$row[title]."</a>";
		$arr['userid']    = $row[userid]?"<a href=\"
javascript:setbg('Mymps��Ա����',400,110,'../public/box.php?part=member&userid=$row[userid]')\">".$row[userid]."</a>":$row[ip];
		$arr['pubtime']   = GetTime($row['pubtime']);
		$arr['ip']   	  = $row['ip'];
		$arr['comment_level']   = $information_level[$row[comment_level]];
		$comment[]        = $arr;
	}
	
	include(mymps_tpl("comment"));
	
}
?>