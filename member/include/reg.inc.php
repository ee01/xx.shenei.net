<?php
$log = $member_log->chk_in();

$userid 	= trim($userid);
$userpwd 	= trim($userpwd);
$reuserpwd 	= trim($reuserpwd);
$email	 	= trim($email);

if($mymps_imgcode[register][open] == 1 && empty($activation)){
	mymps_chk_randcode();
}

if($reuserpwd!=$userpwd && empty($activation)){
	write_msg("��������������벻һ�£�");
	exit();
}

if($mymps_global['cfg_join_othersys'] == 'ucenter'){

	include MYMPS_ROOT.'/uc_client/client.php';
	
	setcookie('myauth', '', -86400);
	
	//��UCenterע���û���Ϣ
	if(!empty($activation) && ($activeuser = uc_get_user($activation))) {
	
		list($uid, $userid) = $activeuser;
		
	} else {
	
		if(uc_get_user($userid) && !$db->getOne("SELECT userid FROM {$db_mymps}member WHERE userid='$userid'")) {
			//�ж���Ҫע����û��������Ҫ������û���������ת����¼ҳ��
			write_msg("���û�����ע�ᣬ�����µ�¼",$mymps_global[SiteUrl]."/member/login.php");
			exit;
		}

		$uid = uc_user_register($userid,$userpwd, $email);
		
		if($uid <= 0) {
		
			if($uid == -1) {
				write_msg('�û������Ϸ�');
			} elseif($uid == -2) {
				write_msg( '����Ҫ����ע��Ĵ���');
			} elseif($uid == -3) {
				write_msg( '�û����Ѿ�����');
			} elseif($uid == -4) {
				write_msg( 'Email ��ʽ����');
			} elseif($uid == -5) {
				write_msg( 'Email ������ע��');
			} elseif($uid == -6) {
				write_msg( '�� Email �Ѿ���ע��');
			} else {
				write_msg( 'δ����');
			}
			
		} else {
		
			$userid  = trim($userid);
			$userpwd = $userpwd ? trim($userpwd) : MD5(random());
			$email 	 = trim($email);
			
		}
		
	}
	
} else {
	
	$rs	= CheckUserID($userid,'�û���');
	
	if($rs != 'ok'){
		write_msg($rs);
		exit();
	}
	
	if(strlen($userid) > 20){
		write_msg("����û������ǳ����ƹ�����������ע�ᣡ");
		exit();
	}
	
	if(strlen($userid) < 3 || strlen($userpwd) < 5){
		write_msg("����û������������(��������3���ַ�)��������ע�ᣡ");
		exit();
	}
	
	if(!is_email($email)){
		write_msg("Email��ʽ����ȷ��");
		exit();
	}
	
	if($db->getOne("Select id From `{$db_mymps}member` where userid like '$userid' ")){
		write_msg("��ָ�����û��� {$userid} �Ѵ��ڣ���ʹ�ñ���û�����");
		exit();
	}
}

if($userid) {

	member_reg($userid,md5($userpwd));
	if($mymps_global['cfg_join_othersys'] == 'ucenter'){
		setcookie('auth', uc_authcode($uid."\t".$userid, 'ENCODE'));
	}
	$member_log -> in($userid,$memory,$url,"reg_new");
	write_msg("��ϲ�㣡ע��ɹ�,������ת���û���������",$mymps_global[SiteUrl]."/member/index.php");
	
}

?>