<?php
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * ��Ȩ���� mymps�з��Ŷӣ���������Ȩ����
 * ��վ��ַ: http://www.mymps.com.cn��
 * ������̳��http://bbs.mymps.com.cn��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�á�
 * ������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 * �������: ���ƽ���峤��
 * ��ϵ��ʽ��QQ:330647249 3037821 MSN:business@live.it
`*/
error_reporting(0);

$do   = isset($_GET['do']) ? trim($_GET['do']) : 'view';
$part = trim($_GET['part']);
$id   = intval($_GET['id']);

if($do == 'js'){

	echo 'document.writeln("<body onLoad=\"loading.removeNode(true);content.style.display=\'\' \"><div id=loading>&nbsp;&nbsp;���ۼ�����...<br /><\/div><div id=\"content\"><iframe id=\"comment_box\" name=\"info\" src=\"\../public\/comment.php?part='.$part.'&id='.$id.'\" frameborder=\"0\" scrolling=\"no\" onload=\"this.height=comment_box.document.body.scrollHeight;\"><\/iframe></div></body>")';
	
}elseif($do == 'view'){

	require_once(dirname(__FILE__)."/global.php");
	require_once(MYMPS_INC."/member.class.php");
	require_once(MYMPS_INC."/ip.class.php");

	if(!empty($part)&&$action == 'write'){
		if(if_other_site_post()){
			$msgs[]="�벻Ҫ���Դ�վ���ύ���ݣ�";
			show_msg($msgs);
			exit();
		}
		mymps_chk_randcode();
		$content = $_POST[content];
		if(empty($content)){write_msg("����д�������ݣ�");exit();}
		if(strlen($content)>255){write_msg("�벻Ҫ��д����127�����֣�");exit();}
		$result 		= verify_badwords_filter($mymps_global[cfg_if_comment_verify],'',$_POST[content]);
		$content 		= textarea_post_change($result[content]);
		$comment_level  = $result[level];
		$userid			= $_GET['userid'];
		$db->query("INSERT INTO `{$db_mymps}".$part."_comment` (".$part."id,content,pubtime,ip,comment_level,userid)VALUES('$id','$content','".time()."','".GetIP()."','$comment_level','".$_POST[userid]."')");
		if($comment_level == '1'){
			write_msg("���������ύ�ɹ���","?part=".$part."&id=".$id);
		}
		else{
			write_msg("���ύ�����Կ��ܺ���Υ��������ͨ������ʾ��","?part=".$part."&id=".$id);
		}
		exit();
	}
	
	$page		 = empty($page) ? '1' : intval($page);
	$where		 = "WHERE ".$part."id = '$id' AND comment_level = 1";
	$sql		 = "SELECT * FROM `{$db_mymps}".$part."_comment` $where ORDER BY pubtime ASC";
	$rows_num 	 = mymps_count($part."_comment" , $where);
	$param		 = setParam(array('part','id'));
	$reg 		 = '/((?:\d+\.){3})\d+/';
	$comment_all = array();
	
	foreach(page1($sql,10) as $k => $row){
		$arr['content']    = $row['content'];
		$arr['pubtime']    = get_format_time($row['pubtime']);
		$face = $row[userid]?$db->getRow("SELECT prelogo FROM `{$db_mymps}member` WHERE userid = '$row[userid]'"):'';
		$arr['prelogo']    = $row[userid]?"<a href=../public/space.php?user=".$row[userid]." target=_blank><img src=..".$face[prelogo]."></a>":'<img src=../images/nophoto.jpg>';
		$arr['ip']         = $row[userid]?'':preg_replace($reg, "\\1*","IP��".$row['ip']);
		$nowip 			   = $row['ip'];
		
		if($mymps_mymps['ipdat_choose'] != 1){
			$now_area = ip2area($nowip);
		}else{
			$ipchoose = new ipLocation();
			$address  =	$ipchoose -> getaddress($nowip);
			$ipchoose =	NULL;
			$now_area = $address["area1"];
		}

		$arr['area']       = $row[userid]?'':$now_area;
		
		$arr['userid']     = $row[userid]?"<a href=../public/space.php?user=".$row[userid]." target=_blank>".$row[userid]."</a>":'';
		$comment_all[]     = $arr;
	}
	
	if($member_log->chk_in()){
		$smarty -> assign("s_uid","<dt></dt><dd>���ã�".$s_uid."<input name=s_uid type=hidden value=".$s_uid."></dd>");
	}
	
	$comment[charset]	= $charset;
	$comment[id]		= $id;
	$comment[part]		= $part;
	$comment[pageview]	= page2();
	$comment[disabled]	= ($mymps_global['cfg_if_comment_open'] != 1)?"disabled":'';
	
	$smarty -> assign("comment",$comment);
	$smarty -> assign("page",$page);
	$smarty -> assign("comment_all",$comment_all);
	$smarty -> display(mymps_tpl("comment","smarty"));
}
?>