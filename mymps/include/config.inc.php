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

$admin_global_class=array(
	"��վǰ̨����",
	"SEO�Ż�����",
	"ϵͳ��������",
	"��Ա��¼��ע��",
	"��Ա��������",
	"ͼƬ�ϴ�����",
	"��Ϣ��˹�������",
	"������Ϣ����"
);

$admin_global = array(

	"SiteName"=>array("des"=>'��վ����',"type"=>'�ַ���',"class"=>'��վǰ̨����'),
	
	"SiteUrl"=>array("des"=>'ʹ������,������http://www.yourdomain.com<br /><i style=color:red>��Ϊ����Ŀ¼��װ��������д����Ŀ¼,����:http://www.yourdamain.com/upload</i>',"type"=>'�ַ���',"class"=>'��վǰ̨����'),
	
	"SiteQQ"=>array("des"=>'�ͷ�QQ����ֻ��дһ��',"type"=>'�ַ���',"class"=>'��վǰ̨����'),
	
	"SiteEmail"=>array("des"=>'�ͷ�����',"type"=>'�ַ���',"class"=>'��վǰ̨����'),
	
	"SiteTel"=>array("des"=>'�ͷ�����',"type"=>'�ַ���',"class"=>'��վǰ̨����'),
	
	"SiteBeian"=>array("des"=>'��վ������',"type"=>'�ַ���',"class"=>'��վǰ̨����'),
	
	"SiteLogo"=>array("des"=>'��վLogo·��',"type"=>'�ַ���',"class"=>'��վǰ̨����'),

	"SiteCity"=>array("des"=>'������վ����ĳ���<br /><i style=color:red>���磺֣�� ����ʾ����д�������������������Ż���</i>',"type"=>'�ַ���',"class"=>'��վǰ̨����'),
	
	"SiteStat"=>array("des"=>'��������վͳ�ƴ���<br />���е����ţ��������ֶ������Ϊ<b>˫����</b>�����򱣴��������ϵͳ��ʾ�հף������޷����У�������������������ftp�д�/dat/config.php�� SiteStat ��Ӧ����ɾ������',"type"=>'�ַ���',"class"=>'��վǰ̨����'),
	
  "SiteSeoName"=>array("des"=>'��վ���⣬�������������Ż������ʵ����ֹؼ���',"type"=>'�ַ���',"class"=>'SEO�Ż�����'),
	
	"SiteKeywords"=>array("des"=>'��վ�ؼ��ʣ�����ؼ����ԡ�,���ָ���',"type"=>'�ַ���',"class"=>'SEO�Ż�����'),
	
	"SiteDescription"=>array("des"=>'��վ������������255���ַ�',"type"=>'�ַ���',"class"=>'SEO�Ż�����'),
	
	"cfg_if_rewrite"=>array("des"=>'�Ƿ�����URLα��̬����<br /><i style=color:red>ע�⣺�ù�����Ҫ�ռ�֧�֣���ϸ��鿴�������url_rewrite.txt�ļ�</i>',"type"=>'������',"class"=>'SEO�Ż�����'),
	
	"cfg_tpl_dir"=>array("des"=>'mympsģ��Ŀ¼��<i style=color:red>ע�⣺���template/Ŀ¼��Ĭ��Ϊdefault</i>',"type"=>'�ַ���',"class"=>'ϵͳ��������'),

	"cfg_if_site_open"=>array("des"=>'�����ر���վ��<i style=color:red>��ע�⣺���������������ر���վ����������������Ӱ������</i>',"type"=>'������',"class"=>'ϵͳ��������'),
	
	"cfg_site_open_reason"=>array("des"=>'���ر���վ��ǰ̨ҳ����ʾ��ʾ���ر�ԭ��',"type"=>'�ַ���',"class"=>'ϵͳ��������'),
	
	"cfg_page_line"=>array("des"=>'��ҳÿҳ��ʾ��¼��',"type"=>'�ַ���',"class"=>'ϵͳ��������'),
	
	"cfg_backup_dir"=>array("des"=>'���ݿⱸ���ļ���,<font color=red>ע�⣺���<b>/dat</b>Ŀ¼</font><br />Ϊ��������������ݿⱸ���ļ���Ĭ�ϱ�����<b>/dat</b>Ŀ¼��<br />���屣��Ŀ¼��������ڴ����ã�������д<b>/dat</b><br />����ɹ���ϵͳ���Զ�������Ŀ¼',"type"=>'�ַ���',"class"=>'ϵͳ��������'),
	
	"cfg_join_othersys"=>array("des"=>'��������CMSϵͳ<br /><i style=color:red><img src=../images/warn.gif align=absmiddle> ע�⣺����������ϵͳ������! <br />����ǰֻ֧��ucenter�����������дucenter��</i>',"type"=>'�ַ���',"class"=>'ϵͳ��������'),
	
	"cfg_if_member_register"=>array("des"=>'�Ƿ����»�Աע��',"type"=>'������',"class"=>'��Ա��¼��ע��'),
	
	"cfg_if_member_log_in"=>array("des"=>'�Ƿ�����Ա��¼',"type"=>'������',"class"=>'��Ա��¼��ע��'),
	
	"cfg_member_perpost_consume"=>array("des"=>'ע���Աÿ����һ��������Ϣ�۳����<br /><i style=color:red>ע�⣺�ù���ֻ�Ի�Ա��Ч<br />�����۳����������</i>',"type"=>'�ַ���',"class"=>'��Ա��������'),
	
	"cfg_member_upgrade_index_top"=>array("des"=>'��Ա������Ϣ��ҳ�ö��۳����',"type"=>'�ַ���',"class"=>'��Ա��������'),
	
	"cfg_member_upgrade_list_top"=>array("des"=>'��Ա������Ϣ�б�ҳ�ö��۳����',"type"=>'�ַ���',"class"=>'��Ա��������'),
	
	"cfg_upimg_type"=>array("des"=>'�����ϴ���ͼƬ��ʽ',"type"=>'�ַ���',"class"=>'ͼƬ�ϴ�����'),
	
	"cfg_upimg_size"=>array("des"=>'�����ϴ���ͼƬ��С����λΪKB',"type"=>'�ַ���',"class"=>'ͼƬ�ϴ�����'),
	
	"cfg_upimg_watermark"=>array("des"=>'�ϴ�ͼƬ�Ƿ���ˮӡ��<i style=color:red>ע�⣺�ù�����Ҫ���ķ�����֧��GD��</i>',"type"=>'������',"class"=>'ͼƬ�ϴ�����'),
	
	"cfg_upimg_watermark_value"=>array("des"=>'ˮӡ��ʾ������<br /><i style=color:red>ע�⣺��֧��Ӣ�ģ���֧�����ģ�һ����д��վ������</i>',"type"=>'�ַ���',"class"=>'ͼƬ�ϴ�����'),
	
	"cfg_upimg_watermark_position"=>array("des"=>'�ϴ�ͼƬˮӡ��ʾ��λ�á�<br /><i style=color:red>ע�⣺1Ϊ���½�,2Ϊ���½�,3Ϊ���Ͻ�,4Ϊ���Ͻ�,5Ϊ����</i>',"type"=>'�ַ���',"class"=>'ͼƬ�ϴ�����'),
	
	"cfg_upimg_watermark_color"=>array("des"=>'ˮӡ���ֵ���ɫ����ѡ����ɫ����ɫ����ɫ<br /><i style=color:red>��ɫΪ1����ɫΪ2����ɫΪ3</i>',"type"=>'�ַ���',"class"=>'ͼƬ�ϴ�����'),
	
	"cfg_arealoop_style"=>array("des"=>'��Ϣ�б�ҳ����ɸѡ�Ƿ�������������ʾ<br /><i style=color:red>��Ĭ��Ϊƽ����ʾ��</font><br /><font color=red>���飺</font>������д�ĵ�������30������ʱ�����鿪����������ʾ��������ɵ������࣬ҳ����ʾ����</i>',"type"=>'������',"class"=>'������Ϣ����'),
	
	"cfg_info_if_img"=>array("des"=>'��Ϣ��ϵ��ʽ�Ƿ���ͼƬ��ʽ��ʾ<br /><i style=color:red>��Ĭ��ΪͼƬ��ʾ��</font>',"type"=>'������',"class"=>'������Ϣ����'),
	
	"cfg_allow_post_area"=>array("des"=>'�Ƿ�������Ϣ���������ڵ�ʡ�У���������������<br /><font color=red>������ָ�����������ʡ�н����ܷ�����Ϣ��</font><br /><font color=red>������</font>����ʡ��������дʡ�ݣ���д������ʱ��׼ȷ��<br /><i style=color:red>ע�⣺ֻ����дһ��ʡ�У�������д�����ڵ�ʡ��</i>',"type"=>'�ַ���',"class"=>'������Ϣ����'),
	
	"cfg_forbidden_post_ip"=>array("des"=>'��ֹ��Ϣ������IP����������ֹ������Ϣ��IP������ڣ����IP�á�,���ֿ�������ֹIP������<br /><font color=red>������</font>202.102.205.222,102.152.125.25',"type"=>'�ַ���',"class"=>'������Ϣ����'),

	"cfg_upimg_number"=>array("des"=>'������Ϣ�������ϴ�������ͼƬ��Ĭ����4��',"type"=>'����',"class"=>'������Ϣ����'),
	
	"cfg_if_nonmember_info"=>array("des"=>'��ע���Ա�ܷ񷢲�������Ϣ',"type"=>'������',"class"=>'������Ϣ����'),
	
	"cfg_if_nonmember_info_box"=>array("des"=>'��ע���Ա������Ϣʱ�Ƿ�����ע����ʾ��Ϣ����<br /><i style=color:red>ע�⣺���������˷�ע���Ա���ܷ�����Ϣ�������ÿɲ���д</i>',"type"=>'������',"class"=>'������Ϣ����'),
	
	"cfg_nonmember_perday_post"=>array("des"=>'��ע���Աÿ�����෢��������������Ϣ<br /><i style=color:red>ע�⣺�ù������������˷ǻ�Ա���Է�����Ϣʱ��Ч<br />�������Ʒ�ע���Ա������Ϣ������������</i>',"type"=>'�ַ���',"class"=>'������Ϣ����'),
	
	"cfg_if_info_verify"=>array("des"=>'�Ƿ�����Ϣ������˹���',"type"=>'������',"class"=>'��Ϣ��˹�������'),
	
	"cfg_if_comment_verify"=>array("des"=>'�Ƿ�����Ϣ������˹���',"type"=>'������',"class"=>'��Ϣ��˹�������'),

	"cfg_if_comment_open"=>array("des"=>'�Ƿ�����������',"type"=>'������',"class"=>'��Ϣ��˹�������'),
	
	"cfg_if_guestbook_verify"=>array("des"=>'�Ƿ�����վ������˹���',"type"=>'������',"class"=>'��Ϣ��˹�������')

);

$admin_cache_array = array(
	"index"=>"��ҳ",
	"info" => "������Ϣ",
	"about" => "վ��",
	"log"=>"�û���¼ע��",
	"post"=>"��Ϣ����"
);

$admin_cache = array(
	
	"index"=>array(
			"site"=>array("name"=>'��վ��ҳ',"open"=>'�Ƿ���',"des"=>'��վ��ҳ�������ó�һЩ'),
			"space"=>array("name"=>'�ռ���ҳ',"open"=>'�Ƿ���',"des"=>'�ռ���ҳ�������ó�һЩ'),
			),
	
	"info"=>array(
			"info" => array("name"=>'������Ϣ����ҳ',"open"=>'�Ƿ���',"des"=>'ҳ��->������Ϣ����ҳ���������ö�һЩ'),
			"list" => array("name"=>'������Ϣ�б�ҳ',"open"=>'�Ƿ���',"des"=>'ҳ��->������Ϣ�б�ҳ���������ó�һЩ')
			),
	
	"about"=>array(
			"aboutus" => array("name"=>'վ��-��������',"open"=>'�Ƿ���',"des"=>'ҳ��->վ��->�������ǣ�һ����ٱ仯���������ó�һЩ'),
			"announce" => array("name"=>'վ��-��վ����',"open"=>'�Ƿ���',"des"=>'ҳ��->վ��->��վ���棬һ����ٱ仯���������ó�һЩ'),
			"faq" => array("name"=>'վ��-��վ����',"open"=>'�Ƿ���',"des"=>'ҳ��->վ��->��վ������һ����ٱ仯���������ó�һЩ'),
			"friendlink" => array("name"=>'վ��-��������',"open"=>'�Ƿ���',"des"=>'ҳ��->վ��->�������ӣ�һ����ٱ仯���������ó�һЩ'),
			"guestbook" => array("name"=>'վ��-��վ����',"open"=>'�Ƿ���',"des"=>'ҳ��->վ��->��վ���ԣ�һ����ٱ仯���������ó�һЩ'),
			),
	
	"log"=>array(
			"login" => array("name"=>'�û���¼ҳ',"open"=>'�Ƿ���',"des"=>'ҳ��->�û���½ҳҳ���������ó�һЩ'),
			"forgetpass" => array("name"=>'�һ�����ҳ',"open"=>'�Ƿ���',"des"=>'ҳ��->�һ�����ҳ���������ó�һЩ'),
			),
	
	"post"=>array(
			"select" => array(
				"name"=>'ѡ�񷢲����ҳ',
				"open"=>'�Ƿ���',
				"des"=>'ҳ��->ѡ�񷢲����ҳ���������ó�һЩ'
				)
			),
);

function get_mymps_config_menu()
{
	global $admin_global_class;
	foreach($admin_global_class as $k =>$value){
		$mymps .="<input type=\"button\" onClick=\"location.href='#".$value."'\" value=\"".$value."\"\" class=\"gray mini\">&nbsp;&nbsp;";
    }
	return $mymps;
}

function get_mymps_config_input()
{
	global $admin_global,$admin_global_class,$config_global;
	$i=0;
	foreach($admin_global_class as $k =>$mymps_v){
		$mymps .="<a name=\"".$mymps_v."\"></a><div id=\"".MPS_SOFTNAME."\"><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vbm\"><tr class=\"firstr\"><td colspan=\"5\"><div class=\"left\"><a href=\"javascript:collapse_change('".$i."')\">".$mymps_v."</a></div><div class=\"right\"><a href=\"javascript:collapse_change('".$i."')\"><img id=\"menuimg_".$i."\" src=\"images/menu_reduce.gif\"/></a></div></td></tr><tbody id=\"menu_".$i."\" style=\"display:\"><tr style=\"font-weight:bold; height:24px; background-color:#dff6ff\"><td>���˵��</td><td>ֵ</td><td>ģ����ô���</td></tr>";
		foreach ($admin_global as $k =>$a){
			if ($a["class"]==$mymps_v){
				$mymps .="<tr bgcolor=\"#f5fbff\" onMouseOver=\"this.className='Ipt IptIn'\"  onMouseOut=\"this.className='Ipt'\"><td style=\"width:35%; line-height:22px\">".$a["des"]."</td><td>";
				if($k == 'SiteDescription' || $k == 'SiteStat' || $k == 'cfg_forbidden_post_ip' || $k =='cfg_site_open_reason'){
					$mymps .= "<textarea name=\"".$k."\" class=\"input\" style=\"height:100px\">".$config_global[$k]."</textarea>";
				}else{
					if($a["type"]=="������"){
						$mymps .="<select name=\"".$k."\"/>";
						$mymps .="<option value=\"1\"";
						$mymps .= ($config_global[$k] == 1)?" selected='selected' style='background-color:#6eb00c; color:white!important;'":"";
						$mymps .=">����</option>";
						$mymps .="<option value=\"0\"";
						$mymps .= ($config_global[$k] == 0)?" selected='selected' style='background-color:#6eb00c; color:white!important;'":"";
						$mymps .=">�ر�</option>";
						$mymps .="<select>";
					}else{
						$mymps .="<input name=\"".$k."\" value=\"".$config_global[$k]."\" class=\"input\"/>";
					}
				}
				$mymps .=($a["type"]=="������")?"</td><td width=30%>&nbsp;</td></tr>":"</td><td width=30%>{\$mymps_global.".$k."}</td></tr>";
		   }
	   }
	   $mymps .="</tbody></table></div>";
	   $i=$i+1;
    }
	return $mymps;
}

$start = '<?php
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
 * �������: ���ƽ���峤��Author:steven 
 * ��ϵ��ʽ��chinawebmaster@yeah.net business@live.it
`*/
';
?>