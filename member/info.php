<?php 
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
 * 软件作者: 彭介平（村长）
 * 联系方式：QQ:3037821 MSN:business@live.it
`*/
require_once(dirname(__FILE__)."/../public/global.php");

function write_pwd_smarty($action='修改'){
	global $smarty,$post,$title,$cat,$part,$id;
	$title = "输入管理密码 - ".$action."信息 - ".$post[title];
	$nav_bar = '<a href="../public/info.php?id='.$id.'">'.$post[title].'</a> &raquo; 输入管理密码 &raquo; '.$action.'信息</li>';
	$post[part] = $part;
	mymps_global_assign();
	$smarty -> assign("title"	,$title);
	$smarty -> assign("post"	,$post);
	$smarty -> assign("nav_bar" ,$nav_bar);
	$smarty -> assign("cat"		,$cat);
	$smarty -> display(mymps_tpl("info_write_pwd","smarty"));
}

function check_info_contact(){
	global $qq,$email,$tel,$contact_who;
	if(empty($tel)||empty($contact_who)){write_msg("联系电话或联系人不能为空！");exit();}
	//if(eregi("[^\x80-\xff]",$contact_who)){write_msg("联系人只能输入汉字！");exit();}
	if(!is_tel($tel)){write_msg("请正确输入电话号码！");exit();}
}

function submit_check($var, $allowget = 0) {
    if(empty($GLOBALS[$var])) return false;
    if($allowget || ($_SERVER['REQUEST_METHOD'] == 'POST' && (empty($_SERVER['HTTP_REFERER']) ||
            preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])))) {
        return true;
    } else {
        exit('Access Denied');
    }
}

if($part == 'edit'){
	require_once(MYMPS_INC."/upfile.fun.php");
	require_once(MYMPS_INC."/member.class.php");
}elseif($part == 'input'){
	require_once(MYMPS_INC."/upfile.fun.php");
	require_once(MYMPS_DATA."/config.imgcode.php");
	require_once(MYMPS_INC."/ip.class.php");
}

if(!submit_check('mymps_submit')){
	//未提交内容
	
	if($part == 'input'){
		//信息发布页
		if(empty($catid)){
			//选择类别页
			$smarty -> cache_lifetime    = $mymps_cache['select']['time'];
			$smarty -> caching           = $mymps_cache['select']['open'];
			
			if (!$smarty->is_cached(mymps_tpl('info_post','smarty'),$part)){
				
				$nav_bar = '<a href=../>网站首页</a> &raquo; 填写信息内容';
				
				$title		 = "第一步 - 选择信息分类";
				
				mymps_global_assign();
				$smarty -> assign("nav_bar"		,$nav_bar);
				$smarty -> assign("scategory"	,$category_cache_s);
				$smarty -> assign("FirstSecond" ,$FirstSecond);
				$smarty -> assign("title"		,$title);
				$smarty -> assign("first_cat"	,$first_cat);
			}
			
			$smarty -> display(mymps_tpl("info_post","smarty"),$part);
		} else {
			//填写信息页
			
			$FirstSecond = FirstSecond($catid);
			require_once(MYMPS_INC."/member.class.php");
			require_once(MYMPS_DATA."/info_lasttime.php");
			require_once(MYMPS_DATA."/info.type.inc.php");
			
			$log = $member_log->chk_in();
			if($log)chk_member_purview("purview_发布分类信息");
			
			$acontent = "<textarea name=\"content\" cols=\"100\" rows=\"10\"></textarea><br />
不得少于10个汉字！描述中请勿出现联系方式以及网址，否则可能被管理员删除";

			$cat = $db ->getRow("SELECT a.catid as scatid,a.modid,a.catname as scatname,a.if_upimg,b.catid,b.catname FROM `{$db_mymps}category` AS a LEFT JOIN `{$db_mymps}category` AS b ON a.parentid = b.catid WHERE a.catid = '$catid'");
			
			if(!empty($mymps_global['cfg_allow_post_area'])){
			
				/*IP库使用判断*/
				$nowip = GetIP();
				if($mymps_mymps['ipdat_choose'] != 1){
					$now_area = ip2area($nowip);
				}else{
					$ipchoose = new ipLocation();
					$address  =	$ipchoose -> getaddress($nowip);
					$ipchoose =	NULL;
					$now_area = $address["area1"];
				}
				
				if(!strstr($now_area,$mymps_global['cfg_allow_post_area']))
				{
					write_msg("操作失败，<b style='color:red'>".$mymps_global['cfg_allow_post_area']."</b> 以外地区不允许发布信息！");
					exit();
				}
			}
			
			if($log){
				
			
				//判断金额是否足够
				$his_money = $member_log -> get_info("money_own");
				if(($his_money - $mymps_global[cfg_member_perpost_consume]) < 0){
					write_msg('您的用户余额 <font color=red><b>'.$his_money.'</b></font>过低 不能再发布信息，请联系管理员充值');
					exit();
				}
				
				//判断是否还能发布信息
				$per = $db->getRow("SELECT b.perday_maxpost FROM `{$db_mymps}member` AS a LEFT JOIN `{$db_mymps}member_level` AS b ON a.levelid = b.id WHERE a.userid = '$s_uid'");
				$perday_maxpost = $per[perday_maxpost];
				
				if(!empty($perday_maxpost)){
					$count = mymps_count("information","WHERE userid LIKE '$s_uid' AND begintime > '".mktime(0,0,0)."'");
					if($count >= $perday_maxpost){
						write_msg("很抱歉！您当前的会员级别每天只能发布".$perday_maxpost."条分类信息");
						exit();
					}
				}
				
				$onload 			= '';
				$areaid 			= $member_log -> get_info("areaid");
				$post[tel] 			= $member_log -> get_info("tel");
				$post[qq] 			= $member_log -> get_info("qq");
				$post[email] 		= $member_log -> get_info("email");
				$post[userid] 		= $member_log -> get_info("userid");
				$post[contact_who]  = $member_log -> get_info("cname");
				$post[ismember] 	= 1;
				
			}else{
			
				if(!empty($mymps_global[cfg_nonmember_perday_post])){
					$count = mymps_count("information","WHERE ip LIKE '$ip' AND begintime > '".mktime(0,0,0)."'");
					if($count >= $mymps_global[cfg_nonmember_perday_post]){
						write_msg("很抱歉！非会员每天只能发布".$mymps_global[cfg_nonmember_perday_post]."条分类信息");
						exit();
					}
				}
				
				if($mymps_global['cfg_if_nonmember_info']=='0'){
					write_msg("对不起，您还没有登录！请您登录后再发布信息！","/member/login.php?url=".urlencode(getUrl()));
					exit();
				}
				
				$onload = ($mymps_global[cfg_if_nonmember_info_box] == 1)?"javascript:setbg('建议您登陆后再进行此次操作',450,100,'../public/box.php?part=memberinfopost&url=".urlencode(urlencode(getUrl()))."')":"";
				
				$post[manage_pwd] =  "<li><span class=\"span_1\"><font color=red>*</font>管理密码：</span><span class=\"span_2\"><input type=\"password\" class=\"textInt\" name=\"manage_pwd\" size=\"40\"/>  必填！用于您今后修改或删除该信息</span></li>";
				
				$post[imgcode]= ($mymps_imgcode[post][open] == 1) ? "<li><span class=\"span_1\"><font color=red>*</font>验证码：</span><span class=\"span_2\"><input type=\"text\" name=\"checkcode\" class=\"textInt\" style=\"width:70px\"/> <img src=\"../include/chkcode.php\" alt=\"看不清，请点击刷新\" width=\"70\" height=\"25\" align=\"absmiddle\" style=\"cursor:pointer;\" onClick=\"this.src=this.src+'?'\"/></span></li>":"";	
					
				$post[ismember] = '0';
			}
			
			$nav_bar 	= '<a href=../>网站首页</a> &raquo; <a href=?part=input>选择信息分类</a> &raquo; 填写信息内容';
			$title 		= "填写信息内容 - ".$cat[scatname]." - 发布分类信息";
			$cat 		= get_cat_info($catid,'srow');

			if($FirstSecond == 'first'){
				write_msg('请在二级栏目下发布信息！');
				exit();
			}elseif($FirstSecond == 'second'){
				$post[location]	=	$cat[catname].' &raquo '.$cat[scatname];
			}
			//get the mod
			$post[mymps_extra_value] = get_category_info_options($cat[modid]);
			$post[upload_img] 		 = get_upload_image_view($cat[if_upimg],$id);
			
			//Get information Last Time
			$post[GetInfoLastTime]	 = GetInfoLastTime();
			$post[part]		 	  	 ="input";
			$post[submit]		  	 = "发布";
			$post[ip]				 = GetIP();
			$post[catid]			 = $catid;
			
			mymps_global_assign();
			$smarty -> assign("cat"		, $cat);
			$smarty -> assign("post"	, $post);
			$smarty -> assign("title"	, $title);
			$smarty -> assign("nav_bar"	, $nav_bar);
			$smarty -> assign("onload"	, $onload);
			$smarty -> assign("acontent", $acontent);
			$smarty -> display(mymps_tpl("info_post_write","smarty"));
			
		}
		
	} elseif ($part == 'all') {
	
		require_once("global.php");
		require_once(MYMPS_DATA."/info.level.inc.php");
		
		chk_member_purview("purview_管理我的信息");
		
		$here = "我发布的分类信息";
		$page = empty($page) ? '1' : intval($page);
		$where = "WHERE userid = '$s_uid'";
		$sql = "SELECT id,info_level,title,content,begintime,endtime,hit,upgrade_type,upgrade_time FROM {$db_mymps}information $where ORDER BY begintime DESC";
		$rows_num = mymps_count("information",$where);
		$param=setParam(array("part"));
		$art = array();
		foreach(page1($sql) as $k => $row){
			$arr['id']          = $row['id'];
			$arr['title']  	    = substring(SpHtml2Text($row['title']),0,20);
			$arr['content']     = substring(SpHtml2Text($row['content']),0,45);
			$arr['begintime']   = get_format_time($row['begintime']);
			$arr['endtime']  	= $row['endtime'];
			$arr['hit']  		= $row['hit'];
			$now 				= time();
			
			if($row[upgrage_type] != 1 && $row[upgrade_time] < $now){
				$db->query("UPDATE `{$Db_mymps}information` SET upgrade_type = 1, upgrade_time = '' WHERE id = '$row[id]'");
				
			}
			$arr['info_level']   = $information_level[$row['info_level']];
			
			$arr['upgrade_type'] = ($row[upgrade_type]>1&$row['upgrade_time']<time())?$info_upgrade_level[$row[upgrade_type]]."<br /><font color=#cccccc>已过期</font>":$info_upgrade_level[$row[upgrade_type]];
			$art[]= $arr;
		}
		
		$tpl=mymps_tpl("info_all");
		include(mymps_tpl("index"));
		
	} elseif($part == 'edit') {
		
		require_once(MYMPS_DATA."/info_lasttime.php");
		$id 	= intval($id);
		$post 	= is_member_info($id);
		$catid 	= $post['catid'];
		$areaid = $post['areaid'];
		$cat = $db->getRow("SELECT a.if_upimg,a.modid,b.catid FROM `{$db_mymps}category` AS a LEFT JOIN `{$db_mymps}category` AS b ON a.parentid = b.catid WHERE a.catid = '$catid'");
		
		if($post[ismember] == 1){
		
			$log = $member_log -> chk_in();
			
			if(!$log){
				write_msg("操作失败！请您登录后再执行此操作！");
				exit();
			}elseif($log && $s_uid != $post[userid]){
				write_msg("操作失败！您还未登录或者该信息不是您发布的！","/member/index.php");
				exit();
			}
			
			$post[ismember] = 1;
			
			$nav_bar 		= '<a href="../public/info.php?id='.$id.'">'.$post[title].'</a> &raquo; 修改信息';
			
		}elseif($post[ismember] == 0 &&!empty($manage_pwd)){
		
			if(if_other_site_post()){
				$msgs[]="请不要尝试从站外提交数据！";
				show_msg($msgs);
				exit();
			}
			
			if(mymps_count("information","WHERE id = '$id' AND manage_pwd = '".md5($manage_pwd)."' AND ismember = 0") == 0){
				write_msg("操作失败！您输入的管理密码不正确！");
				exit();
			}
			
			$post[manage_pwd]= "<li><span class=\"span_1\">管理密码：</span><span class=\"textInt\" ><input type=\"password\" class=\"textInt\"  name=\"manage_pwd\" size=\"40\" />
如不修改，请留空</span></li>";
			$post[ismember] = 0; 
			$nav_bar 		= '<a href="../public/info.php?id='.$id.'">'.$post[title].'</a> &raquo; <a href="../member/info.php?part=edit&id='.$id.'">输入管理密码</a> &raquo; 修改信息';
			
		}elseif($post[ismember] == 0 &&empty($manage_pwd)){
		
			write_pwd_smarty("修改");
			exit();
			
		}
		
		$acontent = "<textarea name=\"content\" cols=\"100\" rows=\"10\">".de_textarea_post_change($post[content])."</textarea><br />
不得少于10个汉字！描述中请勿出现联系方式以及网址，否则可能被管理员删除";
		$title 	  = "修改信息内容 - ".$post[title];
		$post[GetInfoLastTime]	 =	GetInfoLastTime();
		
		require_once(MYMPS_DATA."/info.type.inc.php");
		
		$post[mymps_extra_value] = get_category_info_options($cat[modid],$id);
		$post[upload_img] 		 = get_upload_image_edit($cat[if_upimg],$id);
		$post[part]				 = "edit";
		$post[submit] 			 = "修改";
		$post[location]			 =	"<select name=catid disabled>".get_cat_options($cat[catid])."</select> 不可修改";
		
		mymps_global_assign();
		
		$smarty -> assign("post"	,$post);
		$smarty -> assign("title"	,$title);
		$smarty -> assign("nav_bar"	,$nav_bar);
		$smarty -> assign("cat"		,$cat);
		$smarty -> assign("acontent",$acontent);
		$smarty -> display(mymps_tpl("info_post_write","smarty"));
	
	} elseif($part == 'del') {
	
		require_once(MYMPS_INC."/member.class.php");
		$id 	= intval($id);
		$post 	= is_member_info($id);
		
		if(!empty($id)&&empty($manage_pwd)&&$post['ismember']==0){
			$cat = $db->getRow("SELECT b.catid FROM `{$db_mymps}category` AS a LEFT JOIN `{$db_mymps}category` AS b ON a.parentid = b.catid WHERE a.catid = '$catid'");
			write_pwd_smarty("删除");
			exit();
		}elseif(!empty($id)&&(!empty($manage_pwd)||$post['ismember']==1)){
		
			if($post['ismember'] == 0){
				if(mymps_count("information","WHERE id = '$id' AND manage_pwd = '".md5($manage_pwd)."'")<=0){
					write_msg("操作失败！您输入了错误的管理密码！");
					exit();
				}
			}
			if($post['ismember'] == 1){
				$log = $member_log -> chk_in();
				if(!$log || $s_uid != $post[userid])
				{
					write_msg("操作失败！您还未登录或者该信息不是您发布的！");
					exit();
				}
			}
			
			$image = $db->getAll("SELECT id,path,prepath FROM `{$db_mymps}info_img` WHERE infoid = '$id'");
			
			if(is_array($image)){
			
				foreach ($image as $k => $v){
					if(file_exists(MYMPS_ROOT.$v[prepath])){
						@unlink(MYMPS_ROOT.$v[prepath]);
					}
					if(file_exists(MYMPS_ROOT.$v[path])){
						@unlink(MYMPS_ROOT.$v[path]);
					}
					mymps_delete("info_img","WHERE id = $v[id]");
				}
				
			}
			
			mymps_delete("information","WHERE id = '$id'");
			mymps_delete("info_extra","WHERE infoid = '$id'");
			
			$smarty->clear_cache(mymps_tpl('info_list','smarty'),$catid,0);
			$smarty->clear_cache(mymps_tpl('info_list','smarty'),$catid,$areaid);
			$smarty->clear_cache(mymps_tpl('info_list','smarty'),$cat[catid],0);
			$smarty->clear_cache(mymps_tpl('info_list','smarty'),$cat[catid],$areaid);
			
			$url = ($post[ismember] == 1) ? 'info.php?part=all' : $mymps_global['SiteUrl'];
			write_msg("成功删除编号为 $id 的信息！",$url);
			
		}
	
	} elseif($part == 'clear') {
		
		unset($manage_pwd);
		write_msg("","?part=edit&id=".$id);
		
	} elseif($part == 'refresh') {
	
		require_once(MYMPS_INC."/member.class.php");
		
		$id		 = intval($id);
		$post	 = is_member_info($id);
		
		if(!empty($id)&&empty($manage_pwd)&&$post[ismember]==0){
		
			$cat = $db->getRow("SELECT b.catid FROM `{$db_mymps}category` AS a LEFT JOIN `{$db_mymps}category` AS b ON a.parentid = b.catid WHERE a.catid = '$catid'");
			write_pwd_smarty("刷新");
			exit();
			
		}elseif(!empty($id)&&(!empty($manage_pwd)||$post['ismember']==1)){
		
			if($post['ismember'] == 0){
				if(mymps_count("information","WHERE id = '$id' AND manage_pwd = '".md5($manage_pwd)."'")<=0){
					write_msg("操作失败！您输入了错误的管理密码！");
					exit();
				}
			}
			
			if($post['ismember'] == 1){
				if(!$member_log -> chk_in() || $s_uid != $post[userid]){
					write_msg("操作失败！您还未登录或者该信息不是您发布的！");
					exit();
				}
			}
			
			$now = time();
			$row = $db -> getRow("SELECT begintime,endtime FROM `{$db_mymps}information` WHERE id = ".$id);
			$endtime = $row[endtime]==0?0:$row[endtime]-$row[begintime]+$now;
			$db->query("UPDATE `{$db_mymps}information` SET begintime = '$now' , endtime = '$endtime' WHERE id =".$id);
			$smarty->clear_cache(mymps_tpl('info_list','smarty'),$catid,0);
			$smarty->clear_cache(mymps_tpl('info_list','smarty'),$catid,$areaid);
			$url = $url ? $url : Rewrite('info',array('id'=>$id));
			write_msg("编号为 $id 的信息已成功刷新！",$url);
			
		}
	
	} elseif($part == 'upgrade') {
	
		require_once(dirname(__FILE__)."/global.php");
		$id 	= intval($id);
		$action = trim($action);
		if(empty($action)){
			require_once(MYMPS_DATA."/info.level.inc.php");
			require_once(MYMPS_INC."/member.class.php");
			$log = $member_log -> chk_in();
			$row = $db -> getRow("SELECT title,ismember,userid FROM `{$db_mymps}information` WHERE id = '$id' AND info_level != 0");
			
			if($row && $row[ismember] == 1 && $log && $s_uid == $row[userid]){
				$here 	= "<a href=\"?part=all\">我发布的分类信息</a> &raquo; 信息置顶操作";
				$money	= $member_log -> get_info("money_own");
				$tpl 	= dirname(__FILE__)."../".(mymps_tpl("box/".$part,"1"));
				include (mymps_tpl("index"));	
			}elseif($row && $row[ismember] == 1 && (!$log || $s_uid != $row[userid])){
				echo "<center><h2 style=\"color:red;\">操作失败，您没有该信息的操作权限或者您还没有登录！</h2></center>";
			}elseif($row && $row[ismember] != 1){
				echo "<center><h2 style=color:red>操作失败，游客发布的信息不能进行置顶操作！</h2></center>";
			}elseif(!$row){
				echo "<center><h2 style=color:red>操作失败，该信息不存在或者未通过审核！</h2></center>";
			}
		}elseif($action == 'do_post'){
		
			require_once(MYMPS_SMARTY."/include.php");
			$id				 = intval($id);
			$catid			 = intval($catid);
			$money_cost 	 = $upgrade_time * $upgrade_type;
			$index_up 	 	 = $mymps_global[cfg_member_upgrade_index_top];
			$list_up 		 = $mymps_global[cfg_member_upgrade_list_top];
			$upgrade_time 	 = ($upgrade_time*3600*24)+time();
			$money = $member_log -> get_info("money_own");
			
			if($money < $money_cost){
				if($do == 'setbg')write_msg("您的帐户余额不足，请先充值");
				else write_msg("您的帐户余额不足，请先充值","bank.php");
				exit();
			}
			//重构数组
			$upgrade_arr 	= array($list_up=>'2',$index_up=>'3');
			$up_type 		= $upgrade_arr[$upgrade_type];//取得置顶类型
			$do_upgrade 	= $db->query("UPDATE `{$db_mymps}information` SET upgrade_type = '$up_type' , upgrade_time = '$upgrade_time' WHERE id = '$id' AND userid = '$userid'");
			
			if(!$do_upgrade){
				write_msg("操作失败！请返回重新操作！");
				exit();
			}
			
			$do_money = $db->query("UPDATE `{$db_mymps}member` SET money_own = money_own - '$money_cost' WHERE userid = '$userid'");
			if($do_money){
			
				if($up_type == 2){
					$do_action = '分类置顶';
					$smarty->clear_cache(mymps_tpl('info_list','smarty'),$catid,0);
				}elseif($up_type == 3){
					$do_action = '上首页';
					$smarty->clear_cache(mymps_tpl('index','smarty'));
				}
				
				$url 	 = '?part=all';
				$do_type = $record_part['use'];
				write_money_use("编号为 $id 的信息被执行 <b>".$do_action."</b> 操作","<font color=red>扣除金币 ".$money_cost." </font>",$do_type);
				write_msg("操作成功！您的帐户已扣除金币 <b style=color:red>".$money_cost."</b>",$url);
			}else{
				write_msg("操作失败！请返回重新操作！");
			}
		}
	
	}
	
} else {
	//已提交内容
	
	if($part == 'input'){
		
		!$catid && write_msg('请选择你要发布的栏目类别！');
		
		if(!empty($mymps_global['cfg_allow_post_area'])){
		
			/*IP库使用判断*/
			$nowip = GetIP();
			if($mymps_mymps['ipdat_choose'] != 1){
				$now_area = ip2area($nowip);
			}else{
				$ipchoose = new ipLocation();
				$address  =	$ipchoose -> getaddress($nowip);
				$ipchoose =	NULL;
				$now_area = $address["area1"];
			}
			
			if(!strstr($now_area,$mymps_global['cfg_allow_post_area']))
			{
				write_msg("操作失败，<b style='color:red'>".$mymps_global['cfg_allow_post_area']."</b> 以外地区不允许发布信息！");
				exit();
			}
		}
		
if(!empty($mymps_global['cfg_forbidden_post_ip'])){
			$nowip=GetIP();
			$forbiddenip=explode(',',$mymps_global['cfg_forbidden_post_ip']);
			if(in_array($nowip,$forbiddenip)){
				write_msg("您当前的IP <b style='color:red'>".$nowip."</b> 已被管理员加入黑名单，不允许修改信息！");
				exit();
			}
		}
		
		mymps_check_upimage("mymps_img_");
		
		$areaid = intval($areaid);
		if(empty($areaid)){
			write_msg('请选择您要发布的地区！');
			exit();
		}
		
		$title = trim($title);
		if(empty($title)){
			write_msg("请输入信息标题!");
			exit();
		}
		
		$content = textarea_post_change($content);
		if(empty($content)){
			write_msg("您还没有输入信息描述!");
			exit();
		}
		
		$begintime 	= time();
		$endtime	= intval($endtime);
		$endtime 	= ($endtime == 0)?0:(($endtime*3600*24)+$begintime);
		check_info_contact();
		$ismember	= intval($ismember);
		$ip 		= GetIP();
		$img_count 	= upload_img_num("mymps_img_");
		$result 	= verify_badwords_filter($mymps_global[cfg_if_info_verify],$title,$content);
		$title 		= $result[title];
		$content 	= $result[content];
		$info_level = $result[level];
		
		if(is_array($extra)){
			foreach ($extra as $k => $v){
				$row = $db -> getRow("SELECT title,required FROM `{$db_mymps}info_typeoptions` WHERE identifier = '$k'");
				if($row[required] == 'on' && empty($v)){write_msg ($row[title]."不能为空！");exit();}
				if(strlen($v)>254){write_msg($row[title]."不得超过255个字符！");exit();}
			}
		}
		
		/*
		if(mymps_count("information","WHERE title LIKE '$title' AND content LIKE '$content'")>0){
			write_msg("请不要发布重复内容的信息！");
			exit();
		}
		*/
		
		if ($ismember == 0 || $ismember == '' || empty($ismember)){
			
			if($mymps_global['cfg_if_nonmember_info']=='0'){
				write_msg("对不起，您还没有登录！请您登录后再发布信息！","/member/login.php?url=".urlencode(getUrl()));
				exit();
			}
		
			if(empty($manage_pwd)){write_msg("请输入您的管理密码！以便于以后对该信息的修改和删除");exit();}
			if(empty($contact_who)){write_msg("请填写联系人！");exit();}
			$manage_pwd = md5($manage_pwd);
			
			if($mymps_imgcode[post][open] == 1){
				mymps_chk_randcode();
			}
			
			$sql = "INSERT INTO `{$db_mymps}information` (title,content,catid,areaid,begintime,endtime,manage_pwd,ismember,ip,info_level,qq,email,tel,contact_who,img_count)VALUES('$title','$content','$catid','$areaid','$begintime','$endtime','$manage_pwd','$ismember','$ip','$info_level','$qq','$email','$tel','$contact_who',$img_count)";
			
		}elseif($ismember == 1){
		
			$perpost_money_cost = $mymps_global[cfg_member_perpost_consume];
			$userid = trim($userid);
			$db->query("UPDATE `{$db_mymps}member` SET money_own = money_own - ".$mymps_global[cfg_member_perpost_consume]." WHERE userid = '$userid'");
			$sql = "INSERT INTO `{$db_mymps}information` (title,content,begintime,endtime,catid,areaid,userid,ismember,ip,info_level,qq,email,tel,contact_who,img_count) Values ('$title','$content','$begintime','$endtime','$catid','$areaid','$userid','$ismember','$ip','$info_level','$qq','$email','$tel','$contact_who','$img_count')";

			if(!empty($perpost_money_cost)){
				$db->query("UPDATE `{$db_mymps}member` SET money_own = money_own - '$perpost_money_cost' WHERE userid = '$userid'");
			}
			
		}else {
			
			unknown_err_msg();
		
		}
		
		$db->query($sql);
		$id = $db -> insert_id();
		
		if(is_array($extra)){
			foreach ($extra as $k => $v){
				if(is_array($v)){
					$v = !empty($v) ? join(',', $v) : 0;
				}
				$db->query("INSERT INTO `{$db_mymps}info_extra` (infoid,name,value)VALUES('$id','$k','$v')");
			}
		}
		
		if(is_array($_FILES)){
			for($i=0;$i<count($_FILES);$i++){
				$name_file = "mymps_img_".$i;
				if($_FILES[$name_file]['name']){
					$destination="/information/".date('Ym')."/";
					$mymps_image=start_upload($name_file,$destination,$mymps_global[cfg_upimg_watermark],$mymps_mymps[cfg_information_limit][width],$mymps_mymps[cfg_information_limit][height]);
					$db->query("INSERT INTO `{$db_mymps}info_img` (image_id,path,prepath,infoid,uptime) VALUES ('$i','$mymps_image[0]','$mymps_image[1]','$id','".time()."')");
				}
			}
		}
		
		if($info_level != 0){$smarty->clear_cache(mymps_tpl('info_list','smarty'),$catid,0);}
		write_msg("","../public/box.php?part=information&id=".$id."&title=".urlencode($title)."&level=".$info_level);
	
	} elseif($part == 'edit') {
	
		$id = intval($id);
		
		mymps_check_upimage("mymps_img_");
		
		if(is_array($_FILES)){
			for($i=0;$i<count($_FILES);$i++){
				$name_file = "mymps_img_".$i;
				if($_FILES[$name_file]['name']){
					$destination="/information/".date('Ym')."/";
					$mymps_image=start_upload($name_file,$destination,$mymps_global[cfg_upimg_watermark],$mymps_mymps[cfg_information_limit][width],$mymps_mymps[cfg_information_limit][height]);
					
					if($row = $db -> getRow("SELECT path,prepath FROM `{$db_mymps}info_img` WHERE infoid = '$id' AND image_id = '$i'")){
					
						@unlink(MYMPS_ROOT.$row[path]);
						@unlink(MYMPS_ROOT.$row[prepath]);
						
						$db->query("UPDATE `{$db_mymps}info_img` SET image_id = '$i' , path = '$mymps_image[0]' , prepath = '$mymps_image[1]' , uptime = '".time()."' WHERE image_id = '$i' AND infoid = '$id'");
						
					} else {
						$db->query("INSERT INTO `{$db_mymps}info_img` (image_id,path,prepath,infoid,uptime) VALUES ('$i','$mymps_image[0]','$mymps_image[1]','$id','".time()."')");	
						
					}
				}
			}
		}
		
		$userid = trim($userid);
		$catid  = intval($catid);
		$areaid = intval($areaid);
		
		if(empty($areaid)){
			write_msg('请选择您要发布的地区！');
			exit();
		}
		
		$title = trim($title);
		
		if(empty($title)){
			write_msg("请输入信息标题!");
			exit();
		}
		
		$content = textarea_post_change($content);
		if(empty($content)){
			write_msg("您还没有输入信息描述!");
			exit();
		}
		
		$begintime 	= time();
		$endtime 	= intval($endtime);
		$endtime 	= ($endtime == 0)?0:(($endtime*3600*24)+$begintime);
		check_info_contact();
		$ismember 	= intval($ismember);
		$ip 		= GetIP();
		$result 	= verify_badwords_filter($mymps_global[cfg_if_info_verify],$title,$content);
		$title 		= $result[title];
		$content 	= $result[content];
		$info_level = $result[level];
		
		if(empty($contact_who)){
			write_msg("请填写联系人！");
			exit();
		}
		
		if(is_array($extra)){
			foreach ($extra as $k => $v){
				$row = $db -> getRow("SELECT title,required FROM `{$db_mymps}info_typeoptions` WHERE identifier = '$k'");
				if($row[required] == 'on' && empty($v)){write_msg ($row[title]."不能为空！");exit();}
				if(strlen($v)>254){write_msg($row[title]."不得超过255个字符！");exit();}
				if(is_array($v)){
					$v = !empty($v) ? join(',', $v) : 0;
				}
				//$db->query("REPLACE INTO `{$db_mymps}info_extra` (name,value,infoid)VALUES('$k','$v','$id')");
				$db->query("UPDATE `{$db_mymps}info_extra` SET value = '$v' WHERE name = '$k' AND infoid = '$id'");
				
			}
		}
		
		$manage_pwd = empty($manage_pwd) ? "" : "manage_pwd='".md5($manage_pwd)."',";
		$userid 	= empty($userid) ? "" : "userid='$userid',";
		$img_count 	= mymps_count("info_img","WHERE infoid = '$id'");
		$sql 		= "UPDATE `{$db_mymps}information` SET {$manage_pwd} {$userid} title = '$title',content = '$content',catid = '$catid', areaid = '$areaid',begintime = '$begintime', endtime = '$endtime', ismember = '$ismember' , ip = '$ip' , info_level = '$info_level' , qq = '$qq' , email = '$email' , tel = '$tel' , contact_who = '$contact_who' , img_count = '$img_count' WHERE id = '$id'";
		
		$db->query($sql);
		
		if($info_level != 0){
			$smarty->clear_cache(mymps_tpl('info_list','smarty'),$catid,0);
			$smarty->clear_cache(mymps_tpl('info_list','smarty'),$catid,$areaid);
			
		}
		write_msg("操作成功！您已经成功修改该信息！",Rewrite('info',array('id'=>$id)));
	
	} else{

		unknown_err_msg();
	
	}
	
}
?>