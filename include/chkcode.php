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
define('IN_MYMPS',true);
require_once(dirname(__FILE__)."/global.inc.php");
require_once(MYMPS_INC."/global.php");
require_once(MYMPS_DATA."/config.db.php");
function chkcode($width = 70,$height = 25,$count = 4)
{
	$randnum = "";
	if(function_exists("imagecreatetruecolor") && function_exists("imagecolorallocate") && function_exists("imagestring") && function_exists("imagepng") && function_exists("imagesetpixel") && function_exists("imagefilledrectangle") && function_exists("imagerectangle")){
		$image   = imagecreatetruecolor($width,$height);
		$swhite = imagecolorallocate($image,255,255,255);
		$sblack = imagecolorallocate($image,0,0,0);
		imagefilledrectangle($image,0,0,($width -2),($height -2),$swhite);
		imagerectangle($image,0,0,$width,$height,$sblack);
		for ($i = 0; $i < 1; $i++) {
			$sjamcolor = imagecolorallocate($image,rand(0,120),rand(0,120),rand(0,120));
			imagesetpixel($image,rand(0,$width),rand(0,$height),$sjamcolor);
		}
		for ($i = 0; $i < $count; $i++) {
			$randnum .= dechex(rand(1,15));
		}
		$widthx = floor($width / $count);
		for ($i = 0; $i < strlen($randnum); $i++) {
			$irandomcolor = imagecolorallocate($image,rand(50,120),rand(50,120),rand(50,120));
			imagestring($image,5,($widthx * $i +rand(3,5)),rand(3,5),$randnum{$i},$irandomcolor);
		}
		header("Pragma:no-cache");
		header("Cache-control:no-cache");
		header("Content-type: image/png");
		imagepng($image);
		imagedestroy($image);
	}else{
		header("Pragma:no-cache");
		header("Cache-control:no-cache");
		header("Content-type: image/png");
		if (!readfile(MYMPS_DATA."/imgcode.png")) {
			return false;
		}
		$randnum = "5555";
	}
	return $randnum;
}
$part = $_GET['part'];
$part = $part ? $part : 'imagecode';
if($part == 'imagecode'){
	session_start();
	$_SESSION["chkcode"] = "";
	$chkcode = chkcode();
	$_SESSION["chkcode"] = $chkcode;
}elseif($part == 'info_image'){
	$width = "230";//width
	$height = "18";//height
	$strings = base64_decode(trim($_GET['strings']));
	$image = imagecreatetruecolor($width,$height);
	$black = imagecolorallocate($image,0,0,0);
	$white = imagecolorallocate($image,255,255,255);
	imagefill($image,0,0,$white);
	if(function_exists("imagettftext")){
		imagettftext($image,11,0,0,15,$black,MYMPS_DATA."/ttf/number.ttf",$strings);
	}else{
		imagestring($image,5,0,5,$strings,$black);
	}
	header("Content-type: image/png");
	imagepng($image);
	imagedestroy($image);
}
?>