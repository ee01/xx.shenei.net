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
if (!defined('IN_MYMPS'))
{
    die('FORBIDDEN');
}
/*վ��siteabout*/
$admin_menu[siteabout][name]="վ ��";
$admin_menu[siteabout][style]="home";
$admin_menu[siteabout][group][element]['��ҳ����ͼ']=
array('����ͼ�б�'=>'focus.php','�ϴ�����ͼ'=>'focus.php?part=input');

$admin_menu[siteabout][group][element]['��������']=
array('��Ŀ�б�'=>'site_about.php?do=type','��������'=>'site_about.php?part=edit');

$admin_menu[siteabout][group][element]['��վ����']=
array('�ѷ����Ĺ���'=>'announce.php','��������'=>'announce.php?part=add','��������'=>'comment.php?part=announce');

$admin_menu[siteabout][group][element]['��������']=
array('��������б�'=>'faq.php','������������'=>'faq.php?part=add');

$admin_menu[siteabout][group][element]['��������']=
array('���������б�'=>'friendlink.php','��������'=>'friendlink.php?part=add');

$admin_menu[siteabout][group][element]['��վ����']=
array('��վ�����б�'=>'guestbook.php');

/*��Ϣinfo*/
$admin_menu[info][name]="�� Ϣ";
$admin_menu[info][style]="";
$admin_menu[info][group][element]['��Ϣ���']=
array('������Ϣ'=>'information.php','��Ϣ����'=>'comment.php','��Ϣ�ٱ�'=>'information.php?part=report');

$admin_menu[info][group][element]['��Ϣģ��']=
array('ģ�͹���'=>'info_type.php?part=mod','ѡ�����'=>'info_type.php');

/*��Աmember*/
$admin_menu[member][name]="�� Ա";
$admin_menu[member][style]="";
$admin_menu[member][group][element]['��Ա����']=
array('��Ա�б�'=>'member.php','���ӻ�Ա'=>'member.php?part=add');

$admin_menu[member][group][element]['��Ա�����']=
array('��Ա���б�'=>'member.php?do=group','���ӻ�Ա��'=>'member.php?do=group&part=add');

$admin_menu[member][group][element]['��Ա������־']=
array('��Ա��¼��¼'=>'record.php?do=member&part=login',
/*'��Ա��ֵ��¼'=>'record.php?do=member&part=money&type=pay',*/
'��Ա���Ѽ�¼'=>'record.php?do=member&part=money&type=use');

/*��ҵ category*/
$admin_menu[category][name]="�� ҵ";
$admin_menu[category][style]="";
$admin_menu[category][group][element]['��ҵ����']=
array('��ҵ����'=>'category.php','������ҵ'=>'category.php?part=add');

/*���� area*/
$admin_menu[area][name]="�� ��";
$admin_menu[area][style]="";
$admin_menu[area][group][element]['��������']=
array('��������'=>'area.php','���ӵ���'=>'area.php?part=add');

/*ϵͳ area*/
$admin_menu[sitesys][name]="ϵ ͳ";
$admin_menu[sitesys][style]="";
$admin_menu[sitesys][group][element]['����Ա']=
array('�û��б�'=>'admin.php?do=user','�����û�'=>'admin.php?do=user&part=add','�û���'=>'admin.php?do=group','�����û���'=>'admin.php?do=group&part=add');

$admin_menu[sitesys][group][element]['����Ա��־']=
array('�����¼��¼'=>'record.php?do=admin&part=login','���������¼'=>'record.php?do=admin&part=action');

$admin_menu[sitesys][group][element]['���ݿ����']=
array('���ݿⱸ��'=>'database.php?part=backup','���ݿ⻹ԭ'=>'database.php?part=restore','���ݿ�ά��'=>'database.php?part=optimize');

$admin_menu[sitesys][group][element]['��������']=
array('ϵͳ����'=>'mymps_config.php','��������'=>'mymps_config.php?part=cache','��֤�����'=>'mymps_config.php?part=imgcode','ģ�����'=>'file_manage.php?part=template','��������'=>'file_manage.php?part=upload');

$admin_menu[sitesys][group][element]['ϵͳ����']=
array('ϵͳ����'=>'mymps_config.php?part=phpinfo','�ٷ���ҳ'=>'http://www.mymps.com.cn','������̳'=>'http://bbs.mymps.com.cn');

/*
//�� չextend 
//����������Ӳɼ������;�̬ҳ�����ɰ��
$admin_menu[extend][name]="�� չ";
$admin_menu[extend][style]="";
$admin_menu[extend][group][element]['ϵͳ����']=
array('ucenter����'=>'ucenter.php');
*/


/*ע�� logout*/
$admin_menu[logout][name]="ע ��";
$admin_menu[logout][style]="more";
$admin_menu[logout][target]="_top";
$admin_menu[logout][url]="index.php?part=out";

function mymps_admin_menu($place='top',$default='siteabout')
{
	global $admin_menu;
	$i="0";
	foreach($admin_menu as $q => $w){
		if($place == 'top'){
			$uri=!$w[url]?'#':$w[url];
			$onc=!$w[url]?"onclick=sethighlight('".$i."');togglemenu('".$q."');return false;":'$w[url]';
			$tar=$w[target]?$w[target]:'';
			$mymps_admin_menu .= "<li class=\"$w[style]\"><a href=\"".$uri."\"".$onc." target=".$tar.">".$w[name]."</a></li>";
		}elseif($place == 'left'){
			if(is_array($w[group])){
				foreach($w[group] as $e=>$r){
						$estyle=($q!=$default)?'style="display:none"':"";
						$mymps_admin_menu .= "<dl id=\"".$q."\" ".$estyle.">";
						foreach ($w[group][$e] as $r => $t){
							if(is_array($t)){
								$mymps_admin_menu .= "<div><span>".$r."</span>";
								foreach ($w[group][$e][$r] as $y => $u){
									$mymps_admin_menu .= "<a  
									href=# onClick=\"parent.framRight.location='".$u."';\"  >".$y."</a>";
								}
								$mymps_admin_menu .= "</div>";
							}
						}
						$mymps_admin_menu .= "</dl>";

				}
			}
		}
		$i++;
	}
	return $mymps_admin_menu;
}

function mymps_admin_purview($purview='')
{
	global $admin_menu;
    foreach($admin_menu as $k => $v){
        if ($k != 'logout'){
			$mymps_admin_purview .="<tr style=\"font-weight:bold; height:24px; background-color:#dff6ff\"><td colspan=\"2\">".$v[name]."</td></tr>";
			foreach($v[group][element] as $a => $e){
				if ($a != 'ϵͳ����'){
					$mymps_admin_purview .="<tr bgcolor=\"#f5fbff\"><td>".$a."</td><td>";
					foreach($e as $w => $y){
						$mymps_admin_purview .= "<label for=\"purview_".$w."\" style=\"width:110px\"><input type=\"checkbox\" name=\"purview[]\" id=\"purview_".$w."\" value=\"purview_".$w."\"";
						$mymps_admin_purview .= ((is_array($purview)&&in_array('purview_'.$w,$purview))||empty($purview))? "checked":"";
						$mymps_admin_purview .= ">".$w."</label> ";
					}
				}
			}
			$mymps_admin_purview .="</td></tr>";
		}
	}
	return $mymps_admin_purview;
}
?>