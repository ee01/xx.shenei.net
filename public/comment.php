<?php
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * 版权所有 mymps研发团队，保留所有权利。
 * 网站地址: http://www.mymps.com.cn；
 * 交流论坛：http://bbs.mymps.com.cn；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 软件作者: 彭介平（村长）
 * 联系方式：QQ:330647249 3037821 MSN:business@live.it
`*/
error_reporting(0);

$do   = isset($_GET['do']) ? trim($_GET['do']) : 'view';
$part = trim($_GET['part']);
$id   = intval($_GET['id']);

if($do == 'js'){

	echo 'document.writeln("<body onLoad=\"loading.removeNode(true);content.style.display=\'\' \"><div id=loading>&nbsp;&nbsp;评论加载中...<br /><\/div><div id=\"content\"><iframe id=\"comment_box\" name=\"info\" src=\"\../public\/comment.php?part='.$part.'&id='.$id.'\" frameborder=\"0\" scrolling=\"no\" onload=\"this.height=comment_box.document.body.scrollHeight;\"><\/iframe></div></body>")';
	
}elseif($do == 'view'){

	require_once(dirname(__FILE__)."/global.php");
	require_once(MYMPS_INC."/member.class.php");
	require_once(MYMPS_INC."/ip.class.php");

	if(!empty($part)&&$action == 'write'){
		if(if_other_site_post()){
			$msgs[]="请不要尝试从站外提交数据！";
			show_msg($msgs);
			exit();
		}
		mymps_chk_randcode();
		$content = $_POST[content];
		if(empty($content)){write_msg("请填写评论内容！");exit();}
		if(strlen($content)>255){write_msg("请不要填写超过127个汉字！");exit();}
		$result 		= verify_badwords_filter($mymps_global[cfg_if_comment_verify],'',$_POST[content]);
		$content 		= textarea_post_change($result[content]);
		$comment_level  = $result[level];
		$userid			= $_GET['userid'];
		$db->query("INSERT INTO `{$db_mymps}".$part."_comment` (".$part."id,content,pubtime,ip,comment_level,userid)VALUES('$id','$content','".time()."','".GetIP()."','$comment_level','".$_POST[userid]."')");
		if($comment_level == '1'){
			write_msg("您的评论提交成功！","?part=".$part."&id=".$id);
		}
		else{
			write_msg("您提交的留言可能含有违禁词语，审核通过后显示！","?part=".$part."&id=".$id);
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
		$arr['ip']         = $row[userid]?'':preg_replace($reg, "\\1*","IP：".$row['ip']);
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
		$smarty -> assign("s_uid","<dt></dt><dd>您好，".$s_uid."<input name=s_uid type=hidden value=".$s_uid."></dd>");
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