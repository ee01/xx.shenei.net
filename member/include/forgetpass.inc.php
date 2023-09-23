<?php

$smarty -> cache_lifetime    = $mymps_cache['forgetpass']['time'];
$smarty -> caching           = $mymps_cache['forgetpass']['open'];

if ($action == "doreset") {
	
	if (empty($userpwd) ||empty($reuserpwd)){write_msg("�������벻��Ϊ��");exit;}
	
	if ($userpwd != $reuserpwd){write_msg("�����������벻��ͬ");exit;}
	
	if( strlen($userpwd) < 5 ){write_msg("������������(��������5���ַ�)��");exit;}
	
	if($mymps_imgcode[forgetpass][open] == 1){
		mymps_chk_randcode();
	}
	
	$rs = CheckUserID($userid,'�û���');
	
	if($rs != 'ok'){
		write_msg($rs);
		exit;
	}
	
	if(!($db->getOne("SELECT id FROM `{$db_mymps}member` WHERE userid LIKE '$userid' "))){
		write_msg("��ָ�����û��� {$userid} �����ڣ�");
		exit;
	}
	
	$get = $db -> getOne("SELECT count(id) FROM `{$db_mymps}member` WHERE userid = '$userid' AND safequestion = '$safequestion' AND safeanswer = '$safeanswer'");
	
	if($get){
	
		if($mymps_global['cfg_join_othersys'] == 'ucenter'){
					
			include MYMPS_ROOT.'/uc_client/client.php';
			
			$result =  uc_user_edit($userid, $userpwd, $userpwd, $email, 1);
							
			if($result == 1 || $result == 0) {
				$result = 1;
			} elseif ($result == -4) {
				write_msg('δ�������EMAIL��ʽ����');
				exit;
			}  elseif ($result == -5) {
				write_msg('δ������󣺸�email������ע�ᣡ');
				exit;
			} elseif ($result == -6) {
				write_msg('δ������󣺸�email�Ѿ���ע�ᣡ');
				exit;
			} elseif ($result == -8) {
				write_msg('δ��������ܱ������û�������Ȩ�޸ģ�');
				exit;
			} elseif ($result == -1) {
				write_msg('δ������󣺾����벻��ȷ��');
				exit;
			} else {
				write_msg('δ������������޸�ʧ�ܣ�');
			}
			
		}
	
		$userpwd=md5($userpwd);
		$res = $db->query("UPDATE `{$db_mymps}member` SET userpwd = '$userpwd' WHERE userid = '$userid'");
		write_msg("��������ɹ������ס������","login.php");
		
	}else{
		write_msg("����������ʾ�������������!");
	}
	
}else {

	if(!$member_log->chk_in()){
	
		$smarty->cache_lifetime    = $mymps_cache['log']['time'];
		$smarty->caching           = $mymps_cache['log']['open'];
		
		if (!$smarty->is_cached(MYMPS_TPL.'/log/forgetpass.html',$part)){
		
			require(MYMPS_DATA.'/safequestions.php');
			$safequestion = $safequestions[$safequestion];
			$nav_bar = '<a href=../>��վ��ҳ</a> &raquo; �һ�����';
			mymps_global_assign();
			$smarty -> assign("nav_bar",$nav_bar);
			$smarty -> assign("safequestion",GetSafequestion());
			$smarty -> assign("mymps_imgcode",$mymps_imgcode[forgetpass]);
			
		}
		
		$smarty -> display(mymps_tpl("forgetpass","smarty"),$part);
		
	}else{
	
		write_msg('','update.php?part=password');
		
	}
	
}

?>