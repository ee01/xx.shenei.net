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