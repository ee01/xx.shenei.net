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
if (!defined('IN_MYMPS')){
    die('FORBIDDEN');
}

$admin_global_class=array(
	"网站前台设置",
	"SEO优化设置",
	"系统参数配置",
	"会员登录和注册",
	"会员消费设置",
	"图片上传设置",
	"信息审核功能设置",
	"分类信息设置"
);

$admin_global = array(

	"SiteName"=>array("des"=>'网站名称',"type"=>'字符型',"class"=>'网站前台设置'),
	
	"SiteUrl"=>array("des"=>'使用域名,范例：http://www.yourdomain.com<br /><i style=color:red>若为二级目录安装，则需填写二级目录,范例:http://www.yourdamain.com/upload</i>',"type"=>'字符型',"class"=>'网站前台设置'),
	
	"SiteQQ"=>array("des"=>'客服QQ，请只填写一个',"type"=>'字符型',"class"=>'网站前台设置'),
	
	"SiteEmail"=>array("des"=>'客服邮箱',"type"=>'字符型',"class"=>'网站前台设置'),
	
	"SiteTel"=>array("des"=>'客服热线',"type"=>'字符型',"class"=>'网站前台设置'),
	
	"SiteBeian"=>array("des"=>'网站备案号',"type"=>'字符型',"class"=>'网站前台设置'),
	
	"SiteLogo"=>array("des"=>'网站Logo路径',"type"=>'字符型',"class"=>'网站前台设置'),

	"SiteCity"=>array("des"=>'您的网站面向的城市<br /><i style=color:red>比如：郑州 （提示，填写此项有利于搜索引擎优化）</i>',"type"=>'字符型',"class"=>'网站前台设置'),
	
	"SiteStat"=>array("des"=>'第三方网站统计代码<br />如有单引号，建议你手动将其改为<b>双引号</b>，否则保存后可能造成系统显示空白，以至无法运行，如出现这种情况，请在ftp中打开/dat/config.php将 SiteStat 对应代码删除即可',"type"=>'字符型',"class"=>'网站前台设置'),
	
  "SiteSeoName"=>array("des"=>'网站标题，用于搜索引擎优化，请适当出现关键词',"type"=>'字符型',"class"=>'SEO优化设置'),
	
	"SiteKeywords"=>array("des"=>'网站关键词，多个关键词以“,”分隔开',"type"=>'字符型',"class"=>'SEO优化设置'),
	
	"SiteDescription"=>array("des"=>'网站描述，不超过255个字符',"type"=>'字符型',"class"=>'SEO优化设置'),
	
	"cfg_if_rewrite"=>array("des"=>'是否启用URL伪静态规则，<br /><i style=color:red>注意：该功能需要空间支持，详细请查看程序包中url_rewrite.txt文件</i>',"type"=>'布尔型',"class"=>'SEO优化设置'),
	
	"cfg_tpl_dir"=>array("des"=>'mymps模板目录，<i style=color:red>注意：相对template/目录，默认为default</i>',"type"=>'字符型',"class"=>'系统参数配置'),

	"cfg_if_site_open"=>array("des"=>'开启关闭网站，<i style=color:red>请注意：非特殊情况，请勿关闭网站，对搜索引擎排名影响甚大！</i>',"type"=>'布尔型',"class"=>'系统参数配置'),
	
	"cfg_site_open_reason"=>array("des"=>'若关闭网站，前台页面显示提示（关闭原因）',"type"=>'字符型',"class"=>'系统参数配置'),
	
	"cfg_page_line"=>array("des"=>'分页每页显示记录数',"type"=>'字符型',"class"=>'系统参数配置'),
	
	"cfg_backup_dir"=>array("des"=>'数据库备份文件夹,<font color=red>注意：相对<b>/dat</b>目录</font><br />为方便管理，所有数据库备份文件都默认保存在<b>/dat</b>目录下<br />具体保存目录，你可以在此设置，不须填写<b>/dat</b><br />保存成功后，系统会自动创建该目录',"type"=>'字符型',"class"=>'系统参数配置'),
	
	"cfg_join_othersys"=>array("des"=>'整合其他CMS系统<br /><i style=color:red><img src=../images/warn.gif align=absmiddle> 注意：不整合其他系统请留空! <br />【当前只支持ucenter，输入框内填写ucenter】</i>',"type"=>'字符型',"class"=>'系统参数配置'),
	
	"cfg_if_member_register"=>array("des"=>'是否开启新会员注册',"type"=>'布尔型',"class"=>'会员登录和注册'),
	
	"cfg_if_member_log_in"=>array("des"=>'是否开启会员登录',"type"=>'布尔型',"class"=>'会员登录和注册'),
	
	"cfg_member_perpost_consume"=>array("des"=>'注册会员每发布一条分类信息扣除金币<br /><i style=color:red>注意：该功能只对会员有效<br />若不扣除金币请留空</i>',"type"=>'字符型',"class"=>'会员消费设置'),
	
	"cfg_member_upgrade_index_top"=>array("des"=>'会员分类信息首页置顶扣除金币',"type"=>'字符型',"class"=>'会员消费设置'),
	
	"cfg_member_upgrade_list_top"=>array("des"=>'会员分类信息列表页置顶扣除金币',"type"=>'字符型',"class"=>'会员消费设置'),
	
	"cfg_upimg_type"=>array("des"=>'允许上传的图片格式',"type"=>'字符型',"class"=>'图片上传设置'),
	
	"cfg_upimg_size"=>array("des"=>'允许上传的图片大小，单位为KB',"type"=>'字符型',"class"=>'图片上传设置'),
	
	"cfg_upimg_watermark"=>array("des"=>'上传图片是否开启水印，<i style=color:red>注意：该功能需要您的服务器支持GD库</i>',"type"=>'布尔型',"class"=>'图片上传设置'),
	
	"cfg_upimg_watermark_value"=>array("des"=>'水印显示的内容<br /><i style=color:red>注意：仅支持英文，不支持中文，一般填写网站的域名</i>',"type"=>'字符型',"class"=>'图片上传设置'),
	
	"cfg_upimg_watermark_position"=>array("des"=>'上传图片水印显示的位置。<br /><i style=color:red>注意：1为左下角,2为右下角,3为左上角,4为右上角,5为居中</i>',"type"=>'字符型',"class"=>'图片上传设置'),
	
	"cfg_upimg_watermark_color"=>array("des"=>'水印文字的颜色，可选：白色，红色，黑色<br /><i style=color:red>白色为1，红色为2，黑色为3</i>',"type"=>'字符型',"class"=>'图片上传设置'),
	
	"cfg_arealoop_style"=>array("des"=>'信息列表页地区筛选是否启用下拉框显示<br /><i style=color:red>（默认为平板显示）</font><br /><font color=red>建议：</font>当你填写的地区超过30个以上时，建议开启下拉框显示，以免造成地区过多，页面显示杂乱</i>',"type"=>'布尔型',"class"=>'分类信息设置'),
	
	"cfg_info_if_img"=>array("des"=>'信息联系方式是否以图片形式显示<br /><i style=color:red>（默认为图片显示）</font>',"type"=>'布尔型',"class"=>'分类信息设置'),
	
	"cfg_allow_post_area"=>array("des"=>'是否限制信息发布者所在的省市，若无限制则留空<br /><font color=red>（即您指定地区以外的省市将不能发布信息）</font><br /><font color=red>范例：</font>河南省（建议填写省份，填写城市有时不准确）<br /><i style=color:red>注意：只能填写一个省市，建议填写您所在的省份</i>',"type"=>'字符型',"class"=>'分类信息设置'),
	
	"cfg_forbidden_post_ip"=>array("des"=>'禁止信息发布的IP，请您将禁止发布信息的IP填入框内，多个IP用“,”分开，不禁止IP请留空<br /><font color=red>范例：</font>202.102.205.222,102.152.125.25',"type"=>'字符型',"class"=>'分类信息设置'),

	"cfg_upimg_number"=>array("des"=>'发布信息至多能上传多少张图片，默认是4张',"type"=>'数字',"class"=>'分类信息设置'),
	
	"cfg_if_nonmember_info"=>array("des"=>'非注册会员能否发布分类信息',"type"=>'布尔型',"class"=>'分类信息设置'),
	
	"cfg_if_nonmember_info_box"=>array("des"=>'非注册会员发布信息时是否启用注册提示信息窗口<br /><i style=color:red>注意：若您设置了非注册会员不能发布信息，该设置可不填写</i>',"type"=>'布尔型',"class"=>'分类信息设置'),
	
	"cfg_nonmember_perday_post"=>array("des"=>'非注册会员每天至多发布多少条分类信息<br /><i style=color:red>注意：该功能在您设置了非会员可以发布信息时生效<br />若不限制非注册会员发布信息的条数请留空</i>',"type"=>'字符型',"class"=>'分类信息设置'),
	
	"cfg_if_info_verify"=>array("des"=>'是否开启信息发布审核功能',"type"=>'布尔型',"class"=>'信息审核功能设置'),
	
	"cfg_if_comment_verify"=>array("des"=>'是否开启信息评论审核功能',"type"=>'布尔型',"class"=>'信息审核功能设置'),

	"cfg_if_comment_open"=>array("des"=>'是否开启网友评论',"type"=>'布尔型',"class"=>'信息审核功能设置'),
	
	"cfg_if_guestbook_verify"=>array("des"=>'是否开启网站留言审核功能',"type"=>'布尔型',"class"=>'信息审核功能设置')

);

$admin_cache_array = array(
	"index"=>"首页",
	"info" => "分类信息",
	"about" => "站务",
	"log"=>"用户登录注册",
	"post"=>"信息发布"
);

$admin_cache = array(
	
	"index"=>array(
			"site"=>array("name"=>'网站首页',"open"=>'是否开启',"des"=>'网站首页可以设置长一些'),
			"space"=>array("name"=>'空间首页',"open"=>'是否开启',"des"=>'空间首页可以设置长一些'),
			),
	
	"info"=>array(
			"info" => array("name"=>'分类信息内容页',"open"=>'是否开启',"des"=>'页面->分类信息内容页，可以设置短一些'),
			"list" => array("name"=>'分类信息列表页',"open"=>'是否开启',"des"=>'页面->分类信息列表页，可以设置长一些')
			),
	
	"about"=>array(
			"aboutus" => array("name"=>'站务-关于我们',"open"=>'是否开启',"des"=>'页面->站务->关于我们，一般很少变化，可以设置长一些'),
			"announce" => array("name"=>'站务-网站公告',"open"=>'是否开启',"des"=>'页面->站务->网站公告，一般很少变化，可以设置长一些'),
			"faq" => array("name"=>'站务-网站帮助',"open"=>'是否开启',"des"=>'页面->站务->网站帮助，一般很少变化，可以设置长一些'),
			"friendlink" => array("name"=>'站务-友情链接',"open"=>'是否开启',"des"=>'页面->站务->友情链接，一般很少变化，可以设置长一些'),
			"guestbook" => array("name"=>'站务-网站留言',"open"=>'是否开启',"des"=>'页面->站务->网站留言，一般很少变化，可以设置长一些'),
			),
	
	"log"=>array(
			"login" => array("name"=>'用户登录页',"open"=>'是否开启',"des"=>'页面->用户登陆页页，可以设置长一些'),
			"forgetpass" => array("name"=>'找回密码页',"open"=>'是否开启',"des"=>'页面->找回密码页，可以设置长一些'),
			),
	
	"post"=>array(
			"select" => array(
				"name"=>'选择发布类别页',
				"open"=>'是否开启',
				"des"=>'页面->选择发布类别页，可以设置长一些'
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
		$mymps .="<a name=\"".$mymps_v."\"></a><div id=\"".MPS_SOFTNAME."\"><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vbm\"><tr class=\"firstr\"><td colspan=\"5\"><div class=\"left\"><a href=\"javascript:collapse_change('".$i."')\">".$mymps_v."</a></div><div class=\"right\"><a href=\"javascript:collapse_change('".$i."')\"><img id=\"menuimg_".$i."\" src=\"images/menu_reduce.gif\"/></a></div></td></tr><tbody id=\"menu_".$i."\" style=\"display:\"><tr style=\"font-weight:bold; height:24px; background-color:#dff6ff\"><td>相关说明</td><td>值</td><td>模板调用代码</td></tr>";
		foreach ($admin_global as $k =>$a){
			if ($a["class"]==$mymps_v){
				$mymps .="<tr bgcolor=\"#f5fbff\" onMouseOver=\"this.className='Ipt IptIn'\"  onMouseOut=\"this.className='Ipt'\"><td style=\"width:35%; line-height:22px\">".$a["des"]."</td><td>";
				if($k == 'SiteDescription' || $k == 'SiteStat' || $k == 'cfg_forbidden_post_ip' || $k =='cfg_site_open_reason'){
					$mymps .= "<textarea name=\"".$k."\" class=\"input\" style=\"height:100px\">".$config_global[$k]."</textarea>";
				}else{
					if($a["type"]=="布尔型"){
						$mymps .="<select name=\"".$k."\"/>";
						$mymps .="<option value=\"1\"";
						$mymps .= ($config_global[$k] == 1)?" selected='selected' style='background-color:#6eb00c; color:white!important;'":"";
						$mymps .=">开启</option>";
						$mymps .="<option value=\"0\"";
						$mymps .= ($config_global[$k] == 0)?" selected='selected' style='background-color:#6eb00c; color:white!important;'":"";
						$mymps .=">关闭</option>";
						$mymps .="<select>";
					}else{
						$mymps .="<input name=\"".$k."\" value=\"".$config_global[$k]."\" class=\"input\"/>";
					}
				}
				$mymps .=($a["type"]=="布尔型")?"</td><td width=30%>&nbsp;</td></tr>":"</td><td width=30%>{\$mymps_global.".$k."}</td></tr>";
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
 * 版权所有 mymps研发团队，保留所有权利。
 * 网站地址: http://www.mymps.com.cn；
 * 交流论坛：http://bbs.mymps.com.cn；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 不允许对代码作修改后以任何形式任何目的的再发布。
 * ============================================================================
 * 软件作者: 彭介平（村长）Author:steven 
 * 联系方式：chinawebmaster@yeah.net business@live.it
`*/
';
?>