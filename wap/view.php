<?php
header("content-type:text/vnd.wap.wml");
echo("<?xml version=\"1.0\" encoding=\"UTF-8\"?> \n");
echo("<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/DTD/wml_1.1.xml\">\n");


define('IN_PHPMPS', true);
require 'wap.php';

if(isset($_REQUEST['id']))$id = intval($_REQUEST['id']);
if(empty($id)){
	header("Location: ./\n");
	exit;
}

$sql = "SELECT a.*,c.catid,c.catname,r.areaid,r.areaname FROM {$table}information AS a LEFT JOIN {$table}category AS c ON c.catid=a.catid LEFT JOIN {$table}area AS r ON r.areaid = a.areaid WHERE id='$id'";
$res = $db->query($sql);
while($row = $db->fetchRow($res))
{
	$id       = $row['id'];
	$title    = $row['title'];
	$content  = $row['content'];
	$catid    = $row['catid'];
	$catname  = $row['catname'];
	$areaid   = $row['areaid'];
	$areaname = $row['areaname'];
	$linkman  = $row['contact_who'];
	$phone_c  = $row['tel'];
	$phone    = empty($row['tel'])?'':encrypt($row['tel'], $CFG['crypt']);
	$email    = empty($row['email'])?'':encrypt($row['email'], $CFG['crypt']);
	$email_c  = $row['email'];
	$qq       = empty($row['qq'])?'':encrypt($row['qq'], $CFG['crypt']);
	$qq_c     = $row['qq'];
	$postarea = $row['postarea'];
	$postdate = date('Y年m月d日', $row['begintime']);
	$lastdate = round(($row['endtime']>0 ? ($row['endtime']-time()) : '0')/(3600*24));
	$lastdate = enddate($lastdate);
	$hit    = $row['hit'];
//	$is_check = $row['is_check'];
	$ip       = $row['ip'];
	$address  = $row['addresss'];
	$mappoint = $row['mappoint'] ? explode(',', $row['mappoint']) : '';
}

//include_once MYMPS_ROOT.'/uc_client/lib/xml.class.php';
$sql2 = "SELECT name,value FROM `{$table}info_extra` WHERE infoid = '$id'";
$res2 = $db->query($sql2);
$extra .= "<P>";
while($extr = $db->fetchRow($res2))
{
//	foreach ($extr as $k => $v){
	$v = $extr;
		$option = get_info_option_des($v[name]);
		if($option[type] == 'radio' || $option[type] == 'select'){
			$tmp 			= unserialize($option[rules]);
			$new_array  	= arraychange($tmp[choices]);
			$new_value		= $new_array[$v[value]];
			$extra   .= "".$option[title]."：".$new_value." ".$option[units]."<br />";
		}elseif($option[type] == 'checkbox'){
			$value = explode(",",$v[value]);
			foreach ($value as $m =>$n){
				$tmp = unserialize($option[rules]);
				$new_array = arraychange($tmp[choices]);
				$nvalue .= $new_array[$n]."&nbsp";
			}
			$extra .= "".$option[title]."：".$nvalue." ".$option[units]."<br />";
		}else{
			$new_value = $v[value];
			$rules = unserialize($option[rules]);
			$extra .= "".$option[title]."：".$new_value." ".$rules[units]."<br />";
		}
//	}
}
$extra .= "</P>";

if(empty($title)){
	header("Location: ./\n");
	exit;
}
/*
if(!$is_check){
	showmsg('信息尚未审核，审核后可浏览！', 'default.php');
}
*/
$custom = get_info_custom($id); //取得附加属性

$db->query("UPDATE {$table}information SET hit=hit+1 WHERE id='$id'");//更新点击量

$here = array('name'=>"$catname",'url'=>url_rewrite('category',array('cid'=>$catid)));

//取图片
$sql = "SELECT * FROM {$table}info_img WHERE infoid = '$id' ";
$res = $db->query($sql);
$images = array();
while($img = $db->fetchRow($res))
{
	$arr['id']  = $img['id'];
	$arr['infoid'] = $img['infoid'];
	$arr['path'] = $img['path'];
	$arr['prepath'] = $img['prepath'];
	$images[] = $arr;
}

$phone_count =  $db->getOne("select count(*) from {$table}information where tel='$phone_c'");
$qq_count = $db->getOne("select count(*) from {$table}information where qq='$qq_c'");
$email_count = $db->getOne("select count(*) from {$table}information where email='$email_c'");

//上一篇，下一篇
$sql = "select id,title from {$table}information where 1 and id>$id and catid=$catid ";
$next = $db->getRow($sql);
if(!empty($next))$next[url] = url_rewrite('view', array('vid'=>$next['id']));
$pid = $db->getOne("select max(id) from {$table}information where 1 and id<$id and catid=$catid limit 1");
if(!empty($pid))
{
	$sql = "select id,title from {$table}information where id = '$pid' ";
	$pre = $db->getRow($sql);
	if(!empty($pre))$pre[url] = url_rewrite('view', array('vid'=>$pre['id']));
}

$view_info = get_info($catid,'','5');

?>
<wml>
<card id="info" title="<?=$title?>">
<?php
echo $extra;
?>
<p><hr /></p>
<p>
<B>详细内容:</B><br />
<?php
echo $content;
?>
</p>
<p><hr /></p>
<p>地址:<?=$address?></p>
<p>
联系人:<?=$linkman?>
<? if($phone_c){ ?>
<br />
电话:<?=$phone_c?>
<?
}
if($qq_c){?> 
<br />
QQ:<?=$qq_c?>
<?
}
if($email_c){?>
<br />
邮箱:<?=$email_c?>
<?}?>
</p>
<p>预览图:<? for($i=0;$i<4;$i++){ if($images[$i][prepath]){ ?><img src="..<?=$images[$i][prepath]?>" align="absMiddle" /><?} }?></p>
<anchor>首页
 <go href="default.php"></go>
</anchor>
<do type="accept" label="返回">
 <prev/>
</do>
        <p></p>
        <p><a href="http://u.shenei.net/wap" target=_blank>一起进舍内家园交流去~</a></p>
</card>
</wml>