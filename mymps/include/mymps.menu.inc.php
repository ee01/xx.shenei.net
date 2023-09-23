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
 * 软件作者: 彭介平（村长）Author:steven
 * 联系方式：chinawebmaster@yeah.net MSN:business@live.it
`*/
if (!defined('IN_MYMPS'))
{
    die('FORBIDDEN');
}
/*站务siteabout*/
$admin_menu[siteabout][name]="站 务";
$admin_menu[siteabout][style]="home";
$admin_menu[siteabout][group][element]['首页焦点图']=
array('焦点图列表'=>'focus.php','上传焦点图'=>'focus.php?part=input');

$admin_menu[siteabout][group][element]['关于我们']=
array('栏目列表'=>'site_about.php?do=type','发布内容'=>'site_about.php?part=edit');

$admin_menu[siteabout][group][element]['网站公告']=
array('已发布的公告'=>'announce.php','发布公告'=>'announce.php?part=add','公告评论'=>'comment.php?part=announce');

$admin_menu[siteabout][group][element]['帮助中心']=
array('问题帮助列表'=>'faq.php','发布帮助主题'=>'faq.php?part=add');

$admin_menu[siteabout][group][element]['友情链接']=
array('友情链接列表'=>'friendlink.php','增加链接'=>'friendlink.php?part=add');

$admin_menu[siteabout][group][element]['网站留言']=
array('网站留言列表'=>'guestbook.php');

/*信息info*/
$admin_menu[info][name]="信 息";
$admin_menu[info][style]="";
$admin_menu[info][group][element]['信息相关']=
array('分类信息'=>'information.php','信息评论'=>'comment.php','信息举报'=>'information.php?part=report');

$admin_menu[info][group][element]['信息模型']=
array('模型管理'=>'info_type.php?part=mod','选项管理'=>'info_type.php');

/*会员member*/
$admin_menu[member][name]="会 员";
$admin_menu[member][style]="";
$admin_menu[member][group][element]['会员管理']=
array('会员列表'=>'member.php','增加会员'=>'member.php?part=add');

$admin_menu[member][group][element]['会员组管理']=
array('会员组列表'=>'member.php?do=group','增加会员组'=>'member.php?do=group&part=add');

$admin_menu[member][group][element]['会员操作日志']=
array('会员登录记录'=>'record.php?do=member&part=login',
/*'会员充值记录'=>'record.php?do=member&part=money&type=pay',*/
'会员消费记录'=>'record.php?do=member&part=money&type=use');

/*行业 category*/
$admin_menu[category][name]="行 业";
$admin_menu[category][style]="";
$admin_menu[category][group][element]['行业管理']=
array('行业分类'=>'category.php','增加行业'=>'category.php?part=add');

/*地区 area*/
$admin_menu[area][name]="地 区";
$admin_menu[area][style]="";
$admin_menu[area][group][element]['地区管理']=
array('地区分类'=>'area.php','增加地区'=>'area.php?part=add');

/*系统 area*/
$admin_menu[sitesys][name]="系 统";
$admin_menu[sitesys][style]="";
$admin_menu[sitesys][group][element]['管理员']=
array('用户列表'=>'admin.php?do=user','增加用户'=>'admin.php?do=user&part=add','用户组'=>'admin.php?do=group','增加用户组'=>'admin.php?do=group&part=add');

$admin_menu[sitesys][group][element]['管理员日志']=
array('管理登录记录'=>'record.php?do=admin&part=login','管理操作记录'=>'record.php?do=admin&part=action');

$admin_menu[sitesys][group][element]['数据库操作']=
array('数据库备份'=>'database.php?part=backup','数据库还原'=>'database.php?part=restore','数据库维护'=>'database.php?part=optimize');

$admin_menu[sitesys][group][element]['其它设置']=
array('系统配置'=>'mymps_config.php','缓存设置'=>'mymps_config.php?part=cache','验证码控制'=>'mymps_config.php?part=imgcode','模板管理'=>'file_manage.php?part=template','附件管理'=>'file_manage.php?part=upload');

$admin_menu[sitesys][group][element]['系统帮助']=
array('系统环境'=>'mymps_config.php?part=phpinfo','官方首页'=>'http://www.mymps.com.cn','交流论坛'=>'http://bbs.mymps.com.cn');

/*
//扩 展extend 
//往后继续增加采集、广告和静态页面生成版块
$admin_menu[extend][name]="扩 展";
$admin_menu[extend][style]="";
$admin_menu[extend][group][element]['系统整合']=
array('ucenter整合'=>'ucenter.php');
*/


/*注销 logout*/
$admin_menu[logout][name]="注 销";
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
				if ($a != '系统帮助'){
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