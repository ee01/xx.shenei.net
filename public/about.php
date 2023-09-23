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
 * ��ϵ��ʽ��QQ:3037821 MSN:business@live.it
`*/
require_once(dirname(__FILE__)."/global.php");

if ($part == 'aboutus'){

	$smarty -> cache_lifetime    = $mymps_cache['aboutus']['time'];
	$smarty -> caching           = $mymps_cache['aboutus']['open'];
	$id = intval($id);
	
	if(empty($id)){
		if (!$smarty->is_cached(mymps_tpl('aboutus','smarty'),$part)){
			$do_mymps = $db->query("SELECT * FROM `{$db_mymps}about` ORDER BY typeid,id ASC");
			while($row = $db-> fetchRow($do_mymps)){
				$arr['id'] 			= $row['id'];
				$arr['typeid']	 	= $row['typeid'];
				$arr['typename'] 	= $row['typename'];
				$arr['pubdate'] 	= GetTime($row['pubdate']);
				$arr['uri'] 		= Rewrite('about',array('part'=>'aboutus','id'=>$row['id']));
				$aboutus_all[] 		= $arr;
			}
			$id 		= $db -> getOne("SELECT MIN(id) FROM `{$db_mymps}about`");
			$aboutus 	= $db -> getRow("SELECT * FROM {$db_mymps}about WHERE id =$id");
			$nav_bar	= '��վ��ҳ &raquo; �������� &raquo; '.$aboutus[typename];
			mymps_global_assign();
			$smarty -> assign("aboutus_all",$aboutus_all);
			$smarty -> assign("aboutus",$aboutus);
			$smarty -> assign("nav_bar",$nav_bar);
		}
		$smarty -> display(mymps_tpl("aboutus","smarty"),$part);
	} else {
		if (!$smarty->is_cached(mymps_tpl('aboutus','smarty'),$part,$id)){
			$do_mymps = $db->query("SELECT * FROM `{$db_mymps}about` ORDER BY typeid,id ASC");
			while($row = $db-> fetchRow($do_mymps)){
				$arr['id'] 			= $row['id'];
				$arr['typeid']	 	= $row['typeid'];
				$arr['typename'] 	= $row['typename'];
				$arr['pubdate'] 	= GetTime($row['pubdate']);
				$arr['uri'] 		= Rewrite('about',array('part'=>'aboutus','id'=>$row['id']));
				$aboutus_all[] 		= $arr;
			}
			$aboutus = $db->getRow("SELECT * FROM {$db_mymps}about WHERE id =$id");
			$nav_bar	= '��վ��ҳ &raquo; �������� &raquo; '.$aboutus[typename];
			mymps_global_assign();
			$smarty -> assign("aboutus_all",$aboutus_all);
			$smarty -> assign("aboutus",$aboutus);
			$smarty -> assign("nav_bar",$nav_bar);
		}
		$smarty -> display(mymps_tpl("aboutus","smarty"),$part,$id);
	}
	
} elseif ($part == 'friendlink'){

	$smarty->cache_lifetime    = $mymps_cache['friendlink']['time'];
	$smarty->caching           = $mymps_cache['friendlink']['open'];
	
	require_once(MYMPS_INC."/flink.fun.php");
	
	if(trim($action)=='insert'){
	
		if(if_other_site_post()){
			$msgs[]="�벻Ҫ���Դ�վ���ύ���ݣ�";
			show_msg($msgs);
			exit();
		}
		
		if($email && !is_email($email)){write_msg("����email���벻��ȷ��");exit();}
		mymps_chk_randcode();
		$sql = "INSERT INTO `{$db_mymps}flink`(id,url,webname,weblogo,msg,pr,dayip,name,qq,email,typeid,createtime,ischeck)
			VALUES('','".trim($_POST[url])."','".trim($_POST[webname])."','".trim($_POST[weblogo])."','".textarea_post_change($_POST[msg])."','".trim($_POST[pr])."','".trim($_POST[dayip])."','".trim($_POST[name])."','".trim($_POST[qq])."','$email','".intval($_POST[typeid])."','".time()."','1'); ";
		$res = $db->query($sql);
		
		write_msg("���������ύ�ɹ�������Ա���ͨ������ʾ",Rewrite('about',array('part'=>'friendlink','action'=>'apply')));
		
	} else {
	
		if (!$smarty->is_cached(MYMPS_TPL.'/friendlink.html',$part)){
			$ltype 	= $db-> getAll("SELECT id,typename FROM {$db_mymps}flink_type ORDER BY id ASC");
			$link 	= $db->getAll("SELECT * FROM {$db_mymps}flink_type ORDER BY id ASC");
			for($i=0; $i<count($link); $i++){
				$link[$i]['flink'] = $db->getAll("select * from {$db_mymps}flink where typeid = ".$link[$i]['id']." AND ischeck = '2' order by weblogo,id Asc");
			}
			$about[friendlink_apply_uri]	= Rewrite('about',array('part'=>'friendlink','action'=>'apply'));
			$nav_bar	= '<a href=../>��վ��ҳ</a> &raquo; ��������';
			mymps_global_assign();
			$smarty -> assign("nav_bar",$nav_bar);
			$smarty -> assign("link",$link);
			$smarty -> assign("ltype",$ltype);
			$smarty -> assign("apply_flink_pr",apply_flink_pr());
			$smarty -> assign("apply_flink_dayip",apply_flink_dayip());
		}
		$smarty -> display(mymps_tpl("friendlink","smarty"),$part);
		
	}
	
} elseif ($part == 'guestbook'){

	if ($action=='insert'){
		if(if_other_site_post()){
			$msgs[]="�벻Ҫ���Դ�վ���ύ���ݣ�";
			show_msg($msgs);
			exit();
		}
		mymps_chk_randcode();

		$content 	= textarea_post_change($content);
		$result 	= verify_badwords_filter($mymps_global[cfg_if_guestbook_verify],$title,$content);
		$title 		= $result[title];
		$content 	= $result[content];
		$msg_level 	= $result[level];
		
		$sql 		= "INSERT INTO `{$db_mymps}guestbook` (gid,username,title,content,hidden,qq,email,homepage,addtime,ip,ismember,msg_level)
				VALUES('','".trim($_POST[username])."','$title','$content','".intval($_POST[hidden])."','".intval($_POST[qq])."','".trim($_POST[email])."','".trim($_POST[homepage])."','".time()."','".GetIP()."','".intval($_POST[ismember])."','$msg_level')";
				
		$res = $db->query($sql);
		if($res&&$msg_level == '0'){
			write_msg("�����������ݿ��ܰ���Υ���������Ա���ͨ������ʾ",Rewrite('about',array('part'=>'guestbook','page'=>'1')));
		} elseif ($res&&$msg_level == '1'){
			$smarty->clear_cache(mymps_tpl('guestbook','smarty'),$part,1);
			write_msg("������������ύ�ɹ���",Rewrite('about',array('part'=>'guestbook','page'=>'1')));
		} else {
			unknown_err_msg();
		}
		
	} else {
		$smarty -> cache_lifetime    = $mymps_cache['guestbook']['time'];
		$smarty -> caching           = $mymps_cache['guestbook']['open'];
		
		$page = intval($page);

		require_once(MYMPS_INC."/member.class.php");
		$ismember			="0";
		$write[disabled] 	= "";
		
		if($member_log -> chk_in()){
			$ismember = "1";
			$write[homepage] = $mymps_global[SiteUrl]."/public/space.php?user=".$s_uid;
			$write[s_uid] .= $member_log -> get_info("userid");
			$write[s_uid] .= "<input name=\"username\" type=\"hidden\"  value=\"$write[s_uid]\">";
			$write[qq] 	   = $member_log -> get_info("qq");
			$write[email]  = $member_log -> get_info("email");
			$write[logo]   = $member_log -> get_info("prelogo");
		} else {
			$write[s_uid] = "<input name=\"username\" type=\"text\" size=\"25\" value=\"$write[s_uid]\">";
		}
		
		$sql = "SELECT gid,title,content,qq,email,homepage,reply,addtime,replyer,username,replytime,hidden,ismember FROM `{$db_mymps}guestbook` WHERE msg_level = 1 ORDER BY gid DESC";
		$rows_num = mymps_count("guestbook","WHERE msg_level = 1");
		$param=setParam(array('part'),'public','guestbook-','about');
		$message = array();
		
		foreach(page1($sql,10) as $k => $row){
			$arr['gid']       = $row['gid'];
			$arr['title']     = $row['title'];
			$arr['content']   = $row['content'];
			$arr['qq']        = $row['qq'];
			if($row[ismember]==1){
				$re = $db->getRow("SELECT prelogo FROM `{$db_mymps}member` WHERE userid = '$row[username]'");
				$arr['prelogo'] = $re['prelogo'];
			} else {
				$arr['prelogo'] = '../images/nophoto.jpg';
			}
			$arr['ismember']    = $row['ismember'];
			$arr['email']     	= $row['email'];
			$arr['homepage']    = $row['homepage'];
			$arr['reply']     	= $row['reply'];
			$arr['addtime'] 	= $row['addtime'];
			$arr['replytime'] 	= $row['replytime'];
			$arr['replyer'] 	= $row['replyer'];
			$arr['hidden'] 		= $row['hidden'];
			$arr['userid']      = $row['username'];
			$message[]      	= $arr;
		}
		
		mymps_global_assign();
		$about[guestbook_write_uri]	 = Rewrite('about',array('part'=>'guestbook','action'=>'write'));
		$nav_bar	= '<a href=../>��վ��ҳ</a> &raquo; ���Խ���';
		$smarty -> assign("nav_bar",$nav_bar);
		$smarty -> assign("message",$message);
		$smarty -> assign('pageview',page2('public'));
		$smarty -> assign("write",$write);
		$smarty -> assign("ismember",$ismember);
		$smarty -> display(mymps_tpl("guestbook","smarty"),$part,$page);
		
	}
} elseif ($part == 'faq'){

	$smarty->cache_lifetime    = $mymps_cache['faq']['time'];
	$smarty->caching           = $mymps_cache['faq']['open'];
	
	$id = intval($id);
	
	if(empty($id)){
		if (!$smarty->is_cached(mymps_tpl('faq_all','smarty'),$part)){
			$faq_type = $db->getAll("SELECT id,typename FROM `{$db_mymps}faq_type` ORDER BY id ASC");
			for($i=0; $i<count($faq_type); $i++){
				$do_sql = $db->query("SELECT * FROM `{$db_mymps}faq` WHERE typeid = ".$faq_type[$i]['id']." ORDER BY id ASC");
				while($row = $db->fetchRow($do_sql)){
					$arr['id'] 		= $row['id'];
					$arr['title'] 	= $row['title'];
					$arr['uri'] 	= Rewrite('about',array('part'=>'faq','id'=>$row['id']));
					$faq_type[$i]['faq'][] 	= $arr;
				}
			}
			
			$nav_bar	=	'<a href=../>��վ��ҳ</a> &raquo; �������';
			mymps_global_assign();
			$smarty -> assign("nav_bar"	,$nav_bar);
			$smarty -> assign("faq_type",$faq_type);
		}
		$smarty -> display(mymps_tpl("faq_all","smarty"),$part);
		
	} else {
	
		if (!$smarty->is_cached(mymps_tpl('faq','smarty'),$part,$id)){
			if($faq = $db->getRow("SELECT id,title,content FROM `{$db_mymps}faq` WHERE id = '$id'")){
				$nav_bar	=	'<a href=../>��վ��ҳ</a> &raquo; <a href='.Rewrite(about,array(part=>faq)).'>�������</a> &raquo; '.$faq[title];
				mymps_global_assign();
				$smarty -> assign("nav_bar"	,$nav_bar);
				$smarty -> assign("faq",$faq);
			} else {
				write_msg('��ָ���İ������ⲻ���ڣ�',$mymps_global[SiteUrl]);
				exit();
			}
		}
		
		$smarty -> display(mymps_tpl("faq","smarty"),$part,$id);
		
	}
} elseif ($part == 'announce'){

	$smarty->cache_lifetime    = $mymps_cache['announce']['time'];
	$smarty->caching           = $mymps_cache['announce']['open'];
	
	$id = intval($id);
	
	if(empty($id)){
		if (!$smarty->is_cached(mymps_tpl('anounnce','smarty'),$part,$page)){
			$id 		= $db->getOne("SELECT MAX(id) FROM `{$db_mymps}announce`");
			$announce 	= $db->getRow("SELECT * FROM `{$db_mymps}announce` WHERE id = '$id'");
			$nav_bar	= '��վ��ҳ &raquo; ��վ���� &raquo; '.$announce[title];
			mymps_global_assign();
			$smarty -> assign("announce"		, $announce);
			$smarty -> assign('announce_list' 	, mymps_get_announce(12));//����
		}
		$smarty -> display(mymps_tpl("announce","smarty"),$part,$page);
		
	} elseif (!empty($id)&&empty($action)){
	
		$announce = $db->getRow("SELECT * FROM `{$db_mymps}announce` WHERE id = '$id'");
		if($announce && empty($announce[redirecturl])){
			if (!$smarty->is_cached(mymps_tpl('announce','smarty'),$part,$id)){
				$db->query("UPDATE `{$db_mymps}announce` SET hits = hits+1 WHERE id = '$id'");
				mymps_global_assign();
				$smarty -> assign("announce"		,$announce);
				$smarty -> assign('announce_list' 	, mymps_get_announce(12));//����
			}
			$smarty -> display(mymps_tpl("announce","smarty"),$part,$id);
		} elseif ($announce && !empty($announce[redirecturl])){
			$db->query("UPDATE `{$db_mymps}announce` SET hits = hits+1 WHERE id = '$id'");
			write_msg("���Ժ���������ת��<br /><br />".$announce[redirecturl],$announce[redirecturl]);
		} else {
			unknown_err_msg();
		}
		
	}
} else {

	unknown_err_msg();
	
}
?>