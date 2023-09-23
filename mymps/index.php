<?php 
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * 版权所有 mymps研发团队，保留所有权利。
 * 网站地址: http://www.mymps.com.cn；
 * 交流论坛：http://bbs.mymps.com.cn；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 不允许对代码作修改后以任何形式任何目的的再发布。否则我们将追究您的法律责任!!
 * ============================================================================
 * 软件作者: 彭介平（村长）
 * 联系方式：QQ:3037821 MSN:business@live.it
`*/
error_reporting(E_ALL ^ E_NOTICE);

$do   = isset($_GET['do']) ? trim($_GET['do']) : 'login';
$go   = isset($_GET['go']) ? trim($_GET['go']) : 'mymps_right';
$part = $_GET['part'] ? trim($_GET['part']) : 'default';

if($do == 'login'){

	define('IN_MYMPS', true);
	include("../include/global.inc.php");
	require_once(MYMPS_DATA."/config.php");
	require_once(MYMPS_DATA."/config.db.php");
	require_once(MYMPS_INC."/db.class.php");
	require_once(MYMPS_INC."/global.php");
	require_once(MYMPS_INC."/admin.class.php");
	require_once(MYMPS_DATA."/config.imgcode.php");
	
	$url = trim($url);
	
	if ($part == 'chk'){
		$url = $url ? $url : 'index.php?do=manage&go='.$go;
		
		if ($mymps_imgcode[admin][open] == 1){
			mymps_chk_randcode();
		}
		
		$username = trim($username);
		$password = trim($password);
		$pubdate  = time();
		$ip		  = GetIP();
		
		$sql = "SELECT id,userid,pwd,uname FROM {$db_mymps}admin WHERE userid='".$username."' AND pwd='".md5($password)."'";
		$row = $db->getRow($sql);
		if($row){
		
			$admin_id  	= $row['userid'];
			$admin_name = $row['uname'];
			$mymps_admin -> mymps_admin_login($admin_id,$admin_name);
			$db->query("UPDATE {$db_mymps}admin SET loginip='$_SERVER[REMOTE_ADDR]',logintime='". time() ."' WHERE userid='$row[userid]'");
			$db->query("INSERT INTO `{$db_mymps}admin_record_login` (id,adminid,adminpwd,pubdate,ip,result) VALUES ('','$username','".md5($password)."','$pubdate','$ip','1')");
			write_msg($admin_name."您已成功登陆Mymps系统管理中心",$url);
			
		}else{
		
			$db->query("INSERT INTO `{$db_mymps}admin_record_login` (id,adminid,adminpwd,pubdate,ip,result) VALUES ('','$username','$password','$pubdate','$ip','0')");
			write_msg("您输入的用户名或密码错误，请返回重新输入");
			
		}
	}elseif ($part == 'out'){
	
		define('IN_MYMPS', true);
		$mymps_admin -> mymps_admin_logout();
		write_msg("您已经安全退出系统管理中心","index.php");
		
	}elseif ($part == 'default'){
	
		define('IN_MYMPS', true);
		$url 	 = trim($_GET['url']);
		$iflogin = $mymps_admin -> mymps_admin_chk_getinfo();
		if($iflogin){
			write_msg("","index.php?do=manage");
		}
		elseif(!$iflogin){
			include(mymps_tpl("login"));
		}
		
	}else{
	
		define('IN_MYMPS', true);
		write_msg("","index.php?do=manage");
		
	}
}elseif($do == 'manage'){

	require_once(dirname(__FILE__)."/global.php");
	
	if($part == 'left'){
	
		require_once(dirname(__FILE__)."/include/mymps.menu.inc.php");
		$part=trim($_GET['part']);
		$part = $part ? $part : 'info';
		$constant 	= get_defined_constants();
		$mymps_admin_menu = mymps_admin_menu("left");
		print <<<EOT
		<html>
		<head>
		<meta http-equiv='Content-Type' content='text/html; charset=gb2312'>
		<link rel="stylesheet" type="text/css" href="css/menu.css" />
		<script type="text/javascript" src="js/ShowLeft.js"></script>
		<script type="text/javascript" src="js/mymps_noerr.js"></script>
		</head>
		<body>
		<div id="my_menu" class="{$constant['MPS_SOFTNAME']}">
		<span class="top">
		<a href="../" target="_blank">网站首页</a> <a href="#" onclick="parent.framRight.location='?do=manage&part=right'">后台首页</a>
		</span>
		{$mymps_admin_menu}
		</div>
		</body>
		</html>
EOT;

	}elseif($part == 'default'){
    
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html><head>
<title>Mymps Administrator's Control Panel</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
</head>
<body style="margin: 0px" scroll="no">
<div style="position: absolute;top: 0px;left: 0px; z-index: 2;height: 45px;width: 100%">
<iframe frameborder="0" id="framHeader" name="framHeader" src="?do=manage&part=top" scrolling="no" style="height: 45px; visibility: inherit; width: 100%; z-index: 1;"></iframe>
</div>
<table border="0" cellPadding="0" cellSpacing="0" height="100%" width="100%" style="table-layout: fixed;">
<tr><td width="165" height="45"></td><td></td></tr>
<tr>
<td><iframe frameborder="0" id="framLeft" name="framLeft" src="?do=manage&part=left" scrolling="yes" style="height: 100%; visibility: inherit; width: 100%; z-index: 1;overflow: auto;"></iframe></td>
<td><iframe frameborder="0" id="framRight" name="framRight" src="?do=manage&part=right&go=<?=$go?>" style="border-top:2px #000000 solid; border-left:2px #000000 solid; height: 100%; visibility: inherit; width: 100%; z-index: 1;overflow: auto;" scrolling="auto"></iframe></td>
</tr></table>
</body>
</html>
<?

	}elseif($part == 'top'){
	
    	require_once(dirname(__FILE__)."/include/mymps.menu.inc.php");
		
    	$constant 	= get_defined_constants();
    	$mymps_admin_menu = mymps_admin_menu("top");
		print <<<EOT
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="css/{$constant['MPS_SOFTNAME']}.css" rel="stylesheet" type="text/css">
<title>Mymps Administrator's Control Panel - powered by mymps</title>
<script type="text/javascript" src="js/menu.js"></script>
<script>
var menus = new Array('index','info', 'member', 'category', 'area', 'siteabout', 'sitesys','extend');
function togglemenu(id) {
	if(parent.framLeft) {
		for(k in menus) {
			if(parent.framLeft.document.getElementById(menus[k])) {
				parent.framLeft.document.getElementById(menus[k]).style.display = menus[k] == id ? '' : 'none';
			}
		}
	}
}
function sethighlight(n) {
	var lis = document.getElementsByTagName('li');
	for(var i = 0; i < lis.length; i++) {
		lis[i].id = '';
	}
	lis[n].id = 'menuon';
}
</script>
</head>
<body class="top" onLoad="sethighlight('0')">
    <div class="logo"><img src="images/logo.gif" border="0" alt="{$constant['MPS_SOFTNAME']} {$constant['MPS_VERSION']}"/></div>
<div class="nav">
    <div class="t-menu">您好<span>$admin_id</span>，欢迎使用Mymps</div>
{$mymps_admin_menu}
</div>
</body>
</html>
EOT;

	}elseif($part == 'right'){
	
    	$go = trim($_GET['go']);
		
        require_once(MYMPS_INC."/db.class.php");
        require_once(MYMPS_DATA."/config.inc.php");
        require_once(dirname(__FILE__)."/include/mymps.count.inc.php");
        require_once(dirname(__FILE__)."/include/welcome.inc.php");
		
        $res= $db->getRow("SELECT a.userid,a.typeid,b.typename,b.ifsystem FROM {$db_mymps}admin AS a LEFT JOIN {$db_mymps}admin_type AS b ON a.typeid = b.id WHERE a.userid = '$admin_id'");
        $level 	= $res[typename];
        $typeid = $res[typeid];
        $is_system = $res[ifsystem];
		
        foreach ($ele as $w =>$v){
            $mymps_count_str .= "<span style=\"background-color:#dff6ff; color:#006acd\">".$v."</span>";
            foreach ($element[$w] as $k =>$u){
                $mymps_count_str .= "<a href=\"#\" onclick=\"parent.framRight.location='$u[url]';\">".$k."<span>(".mymps_count($u[table]).")</span></a>";
            }
        }
		
        foreach($welcome as $k => $value){
            $mymps_welcome_str .="<tr bgcolor=\"#f5fbff\"><td width=\"15\" bgcolor=\"#f5fbff\" style=\"font-weight:bold\">".$k."</td><td bgcolor=\"white\" style=\"padding:15px;\" class=\"other\">
            ".$value."</td></tr>";
        }
		
        $here = "mymps系统管理首页";
		
		echo mymps_admin_tpl_global_head($go);
		
        ?>
			<script type="text/javascript" src="../mypub/messagebox.js"></script>
			<div class="ccc2">
			<ul>
			您好! <font color="#FF6600"><strong><?php echo $admin_name?></strong></font>。您的IP是：<font color="#FF6600"><strong><?php echo GetIP(); ?></strong></font>，管理员帐号是<font color="#FF6600"><strong><?php echo $admin_id?></strong></font>，管理员级别是<font color="#FF6600"><strong><?php echo $level?></strong></font>
			</ul>
			</div>
			<div id="<?=MPS_SOFTNAME?>">
			<table cellspacing="0" cellpadding="0"  width="100%" align="center" class="vbm">
			<tr bgcolor="#f5fbff">
			 <td colspan="6" style="padding:10px">
			 <b>相关信息统计</b></td>
			</tr>
			<tr bgcolor="#f5fbff">
			  <td bgcolor="white" style="padding:10px">
				<?php echo $mymps_count_str;?>
			  </td>
			</tr>
			</table>
			</div>
			<div class="clear"></div>
			<div id="<?=MPS_SOFTNAME?>">
				<table cellspacing="0" cellpadding="0"  width="100%" align="center" class="vbm">
					<?php echo $mymps_welcome_str;?>
				</table>
			</div>
		<?
		
		echo mymps_admin_tpl_global_foot();
		
    }
}elseif($do == 'power'){

	require_once(dirname(__FILE__)."/global.php");
	require_once(MYMPS_INC."/member.class.php");
	$s_uid = trim($_GET['userid']);
	$member_log -> in($s_uid,'off');
	
}else{

	define('IN_MYMPS', true);
	require_once(dirname(__FILE__)."/../include/global.fun.php");
	unknown_err_msg();
	
}
?>