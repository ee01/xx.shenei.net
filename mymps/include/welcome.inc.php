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
 * �������: ���ƽ���峤��Author:steven
 * ��ϵ��ʽ��chinawebmaster@yeah.net MSN:business@live.it
`*/
if (!defined('IN_MYMPS')){
    die('FORBIDDEN');
}

$gd_info 		= gd_info();
$gd_version 	= is_array($gd_info) ? $gd_info['GD Version'] : '<font color=red>��֧��GD��</font>';
$cfg_if_tpledit = ($mymps_mymps[cfg_if_tpledit]==0)?"<font color=green>�ر�</font>":"<font color=red>����</font>";
$if_del_install = (!is_file(MYMPS_ROOT."/install/index.php")) ? "<font color=green>��ɾ��</font>":"<font color=red>δɾ��</font>";

$welcome = array(
	'��ݲ���'=>'
		<div class="mainnav">
		<ul>
		<li><a href="'.$mymps_global[SiteUrl].'" target="_blank"><img border="0" src="images/default/home.gif" />��վ��ҳ</a></li>
		<li><a href="#" onclick="parent.framRight.location=\'member.php\'"><img border="0" src="images/default/user.png" alt="���ע��" />���ע��</a></li>
		<li><a href="#" onclick="parent.framRight.location=\'announce.php?part=add\'"><img border="0" src="images/default/tpc.png" alt="�������" />��������</a></li>
		<li><a href="#" onclick="parent.framRight.location=\'information.php\'"><img border="0" src="images/default/post.png"/>������Ϣ</a></li>
		<li><a href="#" onclick="parent.framRight.location=\'friendlink.php\'"><img border="0" src="images/default/share.png" />�������</a></li>
		<li><a href="#" onclick="parent.framRight.location=\'guestbook.php\'"><img border="0" src="images/default/report.png"/>��վ����</a></li>
		</ul>
		</div>
			',
	'��ȫ����'=>'
		<span>���߱༭ģ�幦��</span> ��ǰ��'.$cfg_if_tpledit.'��������ֻ����ʮ�ֱ�Ҫ��ʱ��ſ����������޸� /dat/config.inc.php �رմ˹���<br />
<span>Mymps installĿ¼</span> ��ǰ��'.$if_del_install.'��Ϊ��ֹվ����Ա���ã���������װ��ɺ�ɾ����Ŀ¼
			',
	'ϵͳ˵��'=>'
		<span>ϵͳ���Ϲ���</span> mymps��ǰ֧������UCENTER�����Ͻӿ��Լ����Ͻ̳���ǰ���ٷ���̳����<br />
<span>��ȷ�����棩IP��</span> mympsĬ��ʹ�þ���IP�⣬���ҳ�����в����д������ݿ⣬�������ʹ�ø��Ӿ�ȷIP���ݣ���ǰ���ٷ���̳����<br /><span>α��̬����</span> 1.5��ʽ��֮�󽫲���������һͬ���أ�����α��̬�����Լ�ʹ�ý̳���ǰ���ٷ���̳<br />
<span>��������(mymps)</span>�������ñ�������û���Ϊ�����Ŷ��з���һ������<a href=http://bbs.mymps.com.cn/thread-536-1-1.html target=_blank>�������ϵͳ&raquo;</a>
			',
	'���������'=>'
		<span>����������:</span>'.$_SERVER['SERVER_SOFTWARE'].'<br />
		<span>������ϵͳ:</span>'.PHP_OS.'<br />
		<span>��ǰʱ��:</span>'.GetTime(time())." ".date("����N",time()).'<br />
		<span>PHP��ʽ�汾:</span>'.PHP_VERSION.'<br />
		<span>MYSQL�汾:</span>'.$db->version().'<br />
		<span>mymps����Ŀ¼: </span>'.MYMPS_ROOT.'<br />
		<span>ʹ������: </span>'.$_SERVER["SERVER_NAME"].'<br />
		<span>�ű���ʱʱ�䣺</span>'.ini_get('max_execution_time').'<br />
		<span>�����ϴ�����</span>'.ini_get('upload_max_filesize').'<br />
		<span>GD��汾</span>'.$gd_version.'<br />
		<span>����ļ���дȨ��</span><a href=\'javascript:setbg("����ļ���дȨ��",330,380,"../public/box.php?part=sp_testdirs")\' class="icon_open" id="spanmymsg" >��˼��</a>
			',
	'�з��Ŷ�'=>'
		<span>��Ȩ���У�</span> mymps�з��Ŷ� Mymps DevTeam Corporation<br />
		<span>�ܲ߻�����Ŀ����</span> ���ƽ (�峤) <br />
		<span>mymps�汾��</span> '.MPS_VERSION.' [<a href="http://bbs.mymps.com.cn/forum-6-1.html" target=_blank>�鿴���°汾</a>]<br />
		<span>�����£�</span> '.MPS_RELEASE.' <br />
		<span>�ٷ���վ��</span> <a href="http://www.mymps.com.cn" target="_blank">http://www.mymps.com.cn</a> <br />
		<span>�ٷ���̳��</span> <a href="http://bbs.mymps.com.cn" target="_blank">http://bbs.mymps.com.cn</a>
					'
		);
?>