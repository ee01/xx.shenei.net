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

$userid = trim($user);

$smarty -> cache_lifetime    = $mymps_cache['space']['time'];
$smarty -> caching           = $mymps_cache['space']['open'];

if (!$smarty->is_cached(mymps_tpl('space','smarty'),$userid)){
	if(!$userid){write_msg("��δָ���û�����");exit();}
	$sql ="SELECT id,sex,userid,web,cname,logo,prelogo,place,areaid FROM `{$db_mymps}member` WHERE userid = '$userid'";
	$member = $db -> getRow($sql);
	
	if(empty($member)){
		write_msg("����ָ�����û������ڣ�������δͨ�����","/");
		exit();
	}
	
	$member[prelogo] = $member[prelogo]?$mymps_global[SiteUrl].$member[prelogo]:$mymps_global[SiteUrl].'/images/nophoto.jpg';
	
	$smarty -> assign("info_list",mymps_get_info_list(10,'','',$userid,''));
	$smarty -> assign('mymps_global_header',mymps_global_header());
	$smarty -> assign("SiteName",$SiteName);
	$smarty -> assign("MPS_VERSION",MPS_VERSION);
	$smarty -> assign("mymps_global",$mymps_global);
	$smarty -> assign("member",$member);
}
$smarty -> display(mymps_tpl("space","smarty"),$userid);
?>