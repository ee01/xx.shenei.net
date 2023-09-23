<?php
header("content-type:text/vnd.wap.wml");
echo("<?xml version=\"1.0\" encoding=\"UTF-8\"?> \n");
echo("<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/DTD/wml_1.1.xml\">\n");

define('IN_PHPMPS', true);
require '../include/wap.php';
$area_option = area_options();//地区下拉菜单

$_REQUEST['act'] = $_REQUEST['act'] ? trim($_REQUEST['act']) : 'post' ;


if($_REQUEST['act'] == 'post')
{
	$catid = intval($_REQUEST['id']);
	if(empty($catid)){
		header("Location: ./\n");
		exit;
	}
	
	//$custom = create_cus_html($catid);
	//$content = fckeditor('content');
	$mappoint = $CFG['map'] ? explode(',', $CFG['map']) : '';

	$sql = "SELECT catid,catname FROM {$table}category WHERE catid='$catid'";
	$catinfo = $db->getRow($sql);
	//include template('post');
	include "post.htm";
}

if($_REQUEST['act'] == 'postok')
{
	echo("<wml>");
	echo("<card id='post' title='post'>");
	echo("<p>");
    $catid     = $_POST['catid'] ? intval($_POST['catid']) : '';
	$bt     = $_POST['bt'] ? trim($_POST['bt']) : '';
	$areaid    = $_POST['areaid'] ? intval($_POST['areaid']) : '';
	$postdate  = time();
	$enddate   = $_POST['enddate']>0 ? (intval($_POST['enddate']*3600*24)) + time() : '0';
	$content   = $_POST['content'] ? trim($_POST['content']) : '';
	$linkman   = $_POST['linkman'] ? trim($_POST['linkman']) : '';
	$phone     = $_POST['phone'] ? trim($_POST['phone']) : '';
	$qq        = $_POST['qq'] ? intval($_POST['qq']) : '';
	$email     = $_POST['email'] ? trim($_POST['email']) : '';
	$password  = $_POST['password'] ? trim($_POST['password']) : '';
	$address   = $_POST['address'] ? trim($_POST['address']) : '';
	$mappoint  = $_POST['mappoint'] ? trim($_POST['mappoint']) : '';
    if(empty($bt)) echo("标题不能为空<br>");
    if(empty($content))echo("内容不能为空<br>");
    if(empty($linkman))echo("联系人不能为空<br>");
    if(empty($phone) && empty($_REQUEST[qq]) && empty($email))echo("联系方式必须填写一项<br>");


	//是否有违禁词语
	if(!empty($CFG['banwords']))
	{
		$ban = explode('|',$CFG['banwords']);
		$count = count($ban);
		for($i=0;$i<$count;$i++){
			if(strstr($content,$ban[$i]) || strstr($title,$ban[$i]) || strstr($phone,$ban[$i]) || strstr($qq,$ban[$i])){
				echo('您发布的信息中有违禁词语<br>');
			}
		}
	}

	$count = count($_FILES);

	$CFG['post_check'] == '1' ? $is_check = '0' : $is_check = '1';

    $sql = "INSERT INTO {$table}info (userid,catid,areaid,title,content,linkman,email,qq,phone,password,postarea,postdate,mappoint,addresss,enddate,ip,is_check) VALUES ('$userid','$catid','$areaid','$bt','$content','$linkman','$email','$qq','$phone','$password','$postarea','$postdate','$mappoint','$address','$enddate','$ip',$is_check)";
    $res = $db->query($sql);
	$id = $db->insert_id();



	$res?$msg="恭喜您，发布成功！<br>":$msg="抱歉发布失败，请与客服联系。";
	$link = "view.php?id=$id";
	echo($msg);
	echo("</p>");
	echo("<anchor>查看信息");
	echo("<go href='$link'></go>");
	echo("</anchor>");
	echo("<anchor>返回首页");
    echo("<go href='default.php'></go>");
    echo("</anchor>");

	echo("</card>");
	echo("</wml>");
}
?>