<?php
$userid	 	= trim($userid);
$userpwd 	= trim($userpwd);
$loginip 	= GetIP();
$logintime  = time();
$memory 	= trim($memory);
$url 		= trim($url);

if ($mymps_imgcode[login][open] == 1){
	mymps_chk_randcode();
}

if ($userid =="" || $userpwd ==""){
	write_msg("�û��ʺŻ����벻��Ϊ��");
	exit();
}

if($mymps_global['cfg_join_othersys'] == 'ucenter'){
	
	include MYMPS_ROOT.'/uc_client/client.php';

	//ͨ���ӿ��жϵ�¼�ʺŵ���ȷ�ԣ�����ֵΪ����
	list($uid, $username, $password, $email) = uc_user_login($userid, $userpwd);

	setcookie('myauth', '', -86400);
	
	if($uid > 0) {
	
		if(!$db->getOne("SELECT count(*) FROM {$db_mymps}member WHERE userid='$userid'")) {
			//�ж��û��Ƿ�������û�����������ע��
			member_reg($userid,random());
		}
		//�û���½�ɹ������� Cookie������ֱ���� uc_authcode �������û�ʹ���Լ��ĺ���
		setcookie('myauth', uc_authcode($uid."\t".$userid, 'ENCODE'));
		//����ͬ����¼�Ĵ���
		$ucsynlogin = uc_user_synlogin($uid);
		
		$db->query("UPDATE `{$db_mymps}member` SET loginip = '$loginip',logintime = '$logintime' WHERE userid = '$userid'");
		
		$db->query("INSERT INTO `{$db_mymps}member_record_login` (id,userid,userpwd,pubdate,ip,result) VALUES ('','$userid','$userpwd','$logintime','$loginip','1')");
		
		$member_log -> in($userid,$memory,$url);
		
		write_msg('��¼�ɹ�'.$ucsynlogin.'');
		
		exit;
		
	} else {
	
		$db->query("INSERT INTO `{$db_mymps}member_record_login` (id,userid,userpwd,pubdate,ip,result) VALUES ('','$userid','$userpwd','$logintime','$loginip','0')");
		
		if($uid == -1) {
			write_msg( '�û�������,���߱�ɾ��');
			exit;
		} elseif ($uid == -2) {
			write_msg( '�����������');
			exit;
		} else {
			write_msg( 'δ�������');
			exit;
		}
		
	}
	
} else {

	$row = $db -> getRow("SELECT userid,userpwd FROM `{$db_mymps}member` WHERE userid='$userid' AND userpwd='".md5($userpwd)."'");
	
	if($row){
	
		$db->query("UPDATE `{$db_mymps}member` SET loginip = '$loginip',logintime = '$logintime' WHERE userid = '$userid'");
		$db->query("INSERT INTO `{$db_mymps}member_record_login` (id,userid,userpwd,pubdate,ip,result) VALUES ('','$userid','$userpwd','$logintime','$loginip','1')");

		$s_uid = $row['userid'];
		$member_log -> in($s_uid,$memory,$url);
		
	}else{
	
		$db->query("INSERT INTO `{$db_mymps}member_record_login` (id,userid,userpwd,pubdate,ip,result) VALUES ('','$userid','$userpwd','$logintime','$loginip','0')");
		write_msg("��¼ʧ�ܣ��������������˴�����û���������");
	}

}
?>