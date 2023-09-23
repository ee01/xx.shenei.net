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
if (!defined('IN_MYMPS'))
{
    die('FORBIDDEN');
}
//change mymps textarea post to htmlcode
function textarea_post_change($mymps_string)
{
	return nl2br(str_replace(' ','&nbsp;',htmlspecialchars(trim($mymps_string))));
}
//change mymps htmlcode post to textarea
function de_textarea_post_change($mymps_string)
{
	return str_replace('<br />',' ',str_replace('&nbsp;',' ',trim($mymps_string)));
}
/*
random str
*/
function random($length=5,$strtolower=1)
{
	$hash = '';
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	$max = strlen($chars) - 1;
	mt_srand((double)microtime() * 1000000);
	for($i = 0; $i < $length; $i++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	if($strtolower==1){
		$hash=strtolower($hash);
	}
	return $hash;
}

/* get the ext of the file */
function FileExt($file)
{  
	$ext=end(explode(".",$file));
	return $ext;
}

//creat file
function createfile($file,$str)
{
	if (is_file($file)){
		@unlink ($file);
	}
  	$fp=fopen($file,"w");
	if (!is_writable ($file)){
		return false;
	}
	if (!fwrite($fp,$str)){
		return false;
	}
	fclose ($fp);
	return $file;
}

/*
creat folder
*/
function createdir($path)
{
	if (!file_exists($path)){
		mkdir($path, 0777);
	}
	else{
		return false;
	}
}

//del the dir
function DelDir($dirName) 
{
	if(! is_dir($dirName)){
		return false;
	}     
	$handle = @opendir($dirName);     
	while(($file = @readdir($handle)) !== false){         
		if($file != '.' && $file != '..'){
			 $dir = $dirName . '/' . $file;             
			 is_dir($dir) ? DelDir($dir) : @unlink($dir);         
		}     
	}     
	closedir($handle);
	return rmdir($dirName);
}
/*die message*/
function die_msg($msg)
{
	return '<p style="font-family: Verdana, Tahoma; font-size: 11px;"><b>Mymps info:</b>'.$msg.'</p>';
}
/*
use the fckeditor
*/
function fck_editor($editor_name,$type,$value = '',$width='90%',$height='350')
{
    $editor = new FCKeditor($editor_name);
    $editor->BasePath   = '../include/fckeditor/';
    $editor->ToolbarSet = $type;
    $editor->Width      = $width;
    $editor->Height     = $height;
    $editor->Value      = $value;
    $FCKeditor = $editor->CreateHtml();
	return $FCKeditor;
}
/*
check the randcode image
*/
function mymps_chk_randcode()
{
	global $checkcode,$chkcode;
	$checkcode=trim($_REQUEST["checkcode"]);
	session_start();
	$chkcode = $_SESSION["chkcode"];
	if(empty($chkcode) || $chkcode != $checkcode){
		write_msg('验证码输入错误，请返回重新输入');
		exit();
	}
}
/*
get the ip
*/
function GetIP()
{
	if(!empty($_SERVER["HTTP_CLIENT_IP"])){
		$cip = $_SERVER["HTTP_CLIENT_IP"];
	}elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
		$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}elseif(!empty($_SERVER["REMOTE_ADDR"])){
		$cip = $_SERVER["REMOTE_ADDR"];
	}else{
		$cip = '';
	}
	preg_match("/[\d\.]{7,15}/", $cip, $cips);
	$cip = isset($cips[0]) ? $cips[0] : 'unknown';
	unset($cips);
	return $cip;
}
//utf-8 unserliaze
function utf8_unserialize ($serial_str) { 
	$out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str ); 
	return unserialize ($out); 
}
/*
write_admin_record
*/
function write_admin_record($msg)
{
	global $admin_id,$db_mymps,$db;
	$db->query("INSERT INTO `{$db_mymps}admin_record_action` (id,adminid,ip,pubdate,action) VALUES ('','$admin_id','".GetIP()."','".time()."','$msg')");
}
/*
global info box
*/
function write_msg($msg="",$url="javascript:history.go(-1);",$action="")
{
	global $charset,$db,$adminid,$db_mymps,$mymps_global;
	if (!empty($msg)&&!empty($url)){
		if(!empty($action)){write_admin_record($msg);}
		if($url=="javascript:history.go(-1);"){
			$time_echo = "setTimeout('JumpUrl()',6000);";
			$msg = "<img src='../images/warn.gif' align='absmiddle'> ".$msg;
		}else{
			$time_echo = "setTimeout('JumpUrl()',2000);";
		}
		include(MYMPS_ROOT."/mypub/box/mymps_message.html");
	}elseif(empty($msg)&&!empty($url)){
		Header("HTTP/1.1 303 See Other");
		Header("Location: $url");
	}
	exit();
}
/*
no redirect info box
*/
function show_msg($msgs,$ms="")
{
	global $charset,$db,$db_mymps;
	if(!empty($ms)&&$ms=='record'){
		while (list($k,$v)=each($msgs)){$str .=	"<br>".$v."<br>";};
		write_admin_record(SpHtml2Text($str));
	}elseif(!empty($ms)&&$ms!='record'){
		write_admin_record($ms);
	}
	$title = $mymps_global[SiteName].'提示信息!';
	include(MYMPS_ROOT."/mypub/box/mymps_msg.html");
	while (list($k,$v)=each($msgs)){$str .=	"<br>".$v."<br>";};
	echo $str."</div></div></div></center></body></html>";
}
/*goto*/
function mymps_goto($url='')
{
    return "<script>window.document.location.href='$url';</script>";
}
/*
global page function
*/
function setParam($param1,$type='mymps',$pre='',$para='')
{
	global $mymps_global;
    $rewrite = $mymps_global['cfg_if_rewrite']?$mymps_global['cfg_if_rewrite']:0;
	$param = ($rewrite==1 && $type == 'public')?$pre:'';
	foreach($param1 as $key)
	{
		global ${$key};
		if($rewrite == 1 && $type == 'public' && empty($para)){
			$param .= ${$key}?urlencode($key).'-'.urlencode(${$key}).'-':'';
		}elseif($rewrite == 1 && $type == 'public' && $para == 'about'){
			$param .= '';
		}elseif($rewrite != 1 || $type == 'mymps'){
			$param .= ${$key}?urlencode($key).'='.urlencode(${$key}).'&':'';
		}
	}
	return $param;
}

function page1($sqlstr,$per_page ='')
{
	global $rows_num,$page,$pages_num,$perpage,$rows_offset,$per_screen,$mymps_global,$db;
	$page = (empty($page) || $page <0 ||!is_numeric($page))?1:$page;
	$per_page = empty($per_page)?$mymps_global[cfg_page_line]:$per_page;
	$per_screen = !isset($per_screen)?10:$per_screen;
	$pages_num = ceil(($rows_num-$rows_offset)/$per_page);
	return $db->getAll($sqlstr." limit ".(($page-1)*$per_page+$rows_offset).", ".$per_page);
}
 
function page2($type = 'mymps')
{
	global $rows_num,$page,$pages_num,$per_page,$rows_offset,$param,$per_screen,$mymps_global;
    $rewrite = $mymps_global['cfg_if_rewrite']?$mymps_global['cfg_if_rewrite']:0;
	$font_size="10pt";
	$mid = ceil(($per_screen+1)/2);
	$nav = '';
	if($page <= $mid ){
		$begin = 1;
	}elseif($page > $pages_num-$mid) {
		$begin = $pages_num-$per_screen+1;
	}else{
		$begin = $page-$mid+1;
	}
	$begin = ($begin < 0)?1:$begin;
	if($rewrite == 1 && $type == 'public'){
		$nav .="<span>共".$rows_num."记录</span> ";
		if($page>1)$nav .= "<a href='/$param"."page-".($page-1)."/' title='第".($page-1)."页'>上一页</a>";
		if($begin!=1)$nav .= "<a href='/$param"."page-1/' title='第1页'>1 ...</a>";
		$end = ($begin+$per_screen>$pages_num)?$pages_num+1:$begin+$per_screen;
		for($i=$begin; $i<$end; $i++) {
			if (!empty($i)){
				$nav .=($page!=$i)?"<a href='/$param"."page-$i/' title='第{$i}页'>$i</a> ":" <span class=current>$i</span> ";
			}
		}
		if($end!=$pages_num+1) $nav .= "<a href='/$param"."page-$pages_num/' title='第{$pages_num}页'>... {$pages_num}</a>";
		if($page<$pages_num)   $nav .= "<a href='/$param"."page-".($page+1)."/' title='第".($page+1)."页'>下一页</a>";
	}elseif($rewrite != 1 || $type == 'mymps'){
		$nav .="<span>共".$rows_num."记录</span> ";
		if($page>1)$nav .= "<a href='?$param"."page=".($page-1)."' title='第".($page-1)."页'>上一页</a>";
		if($begin!=1)$nav .= "<a href='?$param' title='第1页'>1 ...</a>";
		$end = ($begin+$per_screen>$pages_num)?$pages_num+1:$begin+$per_screen;
		for($i=$begin; $i<$end; $i++) {
			if (!empty($i)){
				$nav .=($page!=$i)?"<a href='?$param"."page=$i' title='第{$i}页'>$i</a> ":" <span class=current>$i</span> ";
			}
		}
		if($end!=$pages_num+1) $nav .= "<a href='?$param"."page=$pages_num' title='第{$pages_num}页'>... {$pages_num}</a>";
		if($page<$pages_num)   $nav .= "<a href='?$param"."page=".($page+1)."' title='第".($page+1)."页'>下一页</a>";
	}
	return $nav; 
}
/*
get now url
*/
function GetUrl(){ 
	$url="http://".$_SERVER["HTTP_HOST"]; 
	if(isset($_SERVER["REQUEST_URI"])){ 
		$url.=$_SERVER["REQUEST_URI"]; 
	} else{ 
		$url.=$_SERVER["PHP_SELF"]; 
		if(!empty($_SERVER["QUERY_STRING"])){$url.="?".$_SERVER["QUERY_STRING"];} 
	} 
	return $url; 
}
/*
gettime function
*/
function GetTime($time)
{
	if(!empty($time)){
		$time=date("Y-m-d H:i:s",$time);
		return $time;
	}else {
		return "未知时间";
	}
}
/*
template function
*/
function mymps_tpl($str,$smarty='')
{
	global $mymps_global;
	$tpldir = $mymps_global[cfg_tpl_dir] ? $mymps_global[cfg_tpl_dir] : 'default';
	if ($smarty == 'smarty'){
		return MYMPS_TPL.'/'.$tpldir.'/'.$str.'.html';
	}elseif (empty($smarty)){
		$db_mymps = 'template/'.$str.'.html';
		return $db_mymps;
	}elseif ($smarty == '1'){
		$db_mymps = '../template/'.$str.'.html';
		return $db_mymps;
	}
}
/*
 *change the disallowed words to allowed words
 *related file:/data/filter.php
 */
function filter_bad_words($str)
{
	global $cfg_badwords;
	$cfg_bwords=explode(',',$cfg_badwords[0]);
	foreach ($cfg_bwords as $i){
		$str=str_replace($cfg_bwords,$cfg_badwords[1],$str);
		return $str."<br>";
	}
}
/*show_unknown_err_msg*/
function unknown_err_msg()
{
	$msgs[]="未知错误，可能与你提交的参数有关<br /><br />获取更多帮助请前往<a href=\"http://www.mymps.com.cn\" target=\"_blank\">mymps官方网站</a>或<a href=\"http://bbs.mymps.com.cn\" target=\"_blank\">mymps交流论坛</a>";
	show_msg($msgs);
	exit();
}
/*text str html*/
function Spcnw_mid($str,$start,$slen){
  $str_len = strlen($str);
  $strs = Array();
  for($i=0;$i<$str_len;$i++){
  	if(ord($str[$i])>0x80){
  		if($str_len>$i+1) $strs[] = $str[$i].$str[$i+1];
  		else $strs[] = '';
  	  $i++;
  	}
  	else{ $strs[] = $str[$i]; }
  }
  $wlen = count($strs);
  if($wlen < $start) return "";
  $restr = "";
  $startdd = $start;
  $enddd = $startdd + $slen;
  for($i=$startdd;$i<$enddd;$i++){
  	if(!isset($strs[$i])) break;
  	$restr .= $strs[$i];
  }
  return $restr;
}
//text to html
function Text2Html($txt){
	$txt = str_replace("  ","　",$txt);
	$txt = str_replace("<","&lt;",$txt);
	$txt = str_replace(">","&gt;",$txt);
	$txt = preg_replace("/[\r\n]{1,}/isU","<br/>\r\n",$txt);
	return $txt;
}
//sp html to text
function SpHtml2Text($str){
	$str = preg_replace("/<sty(.*)\\/style>|<scr(.*)\\/script>|<!--(.*)-->/isU","",$str);
	$alltext = "";
	$start = 1;
	for($i=0;$i<strlen($str);$i++){
	if($start==0 && $str[$i]==">") $start = 1;
	else if($start==1){
	  if($str[$i]=="<"){ $start = 0; $alltext .= " "; }
	  else if(ord($str[$i])>31) $alltext .= $str[$i];
	}
	}
	$alltext = str_replace("　"," ",$alltext);
	$alltext = preg_replace("/&([^;&]*)(;|&)/","",$alltext);
	$alltext = preg_replace("/[ ]+/s"," ",$alltext);
	return $alltext;
}
//clear the html
function ClearHtml($str){
	$str = Html2Text($str);
	$str = str_replace('<','&lt;',$str);
	$str = str_replace('>','&gt;',$str);
	return $str;
}
//count total nums of the table of mymps sql
function mymps_count($table,$where='')
{
	global $db,$db_mymps;
	$count = $db->getOne("SELECT count(*) FROM {$db_mymps}".$table." ".$where);
	if($count){
		return $count;
	}else{
		return 0;
		exit();
	}
}
//delete records of the table of mymps sql
function mymps_delete($table,$where='')
{
	global $db,$db_mymps;
	$delete = $db->query("DELETE FROM {$db_mymps}".$table." ".$where);
	if($delete){return true;}else{return false;}
}
//delete all of the table of mymps sql
function mymps_del_all($table,$id,$idor='')
{
	global $db,$db_mymps;
	$id = !empty($id) ? join(',', $id) : 0;
	$idor = $idor?$idor:'id';
	$delete = $db->query("DELETE FROM {$db_mymps}".$table." WHERE ".$idor." IN (".$id.")");
	if($delete){return $id;}else{return false;}
}
//sub chinese str
function substring($str, $start, $length)
{
	$len = $length;
	if($length < 0){
		$str = strrev($str); 
		$len = -$length;
	}
	$len= ($len < strlen($str)) ? $len : strlen($str);
	for ($i= $start; $i < $len; $i ++){
		   if (ord(substr($str, $i, 1)) > 0xa0){
			 $tmpstr .= substr($str, $i, 2);
			 $i++;
		   }else {
			 $tmpstr .= substr($str, $i, 1);
		   }
	}
	if($length < 0) $tmpstr = strrev($tmpstr);
	return $tmpstr;
}
//get the life time of the information
function get_info_life_time($time)
{
	$last_time = round(($time > 0 ? ($time - time()) : 0)/(3600*24));
	if($last_time >= 5){
		$last_time = "<font color=green>$last_time</font> 天";
	}elseif($last_time > 0 && $lastime < 5){
		$last_time = "<font color=red>$last_time</font> 天";
	}elseif($last_time == 0){
		$last_time = '<font color=green>长期有效</font>';
	}else{
		$last_time = '已过期';
	}
	return $last_time;
}
/*check the purview of member*/
function chk_member_purview($purview)
{
	global $db,$db_mymps,$s_uid;
	$sql = "SELECT b.purviews FROM `{$db_mymps}member` AS a LEFT JOIN `{$db_mymps}member_level` AS b ON a.levelid = b.id WHERE a.userid='$s_uid'";
	$row = $db->getRow($sql);
	if(!strstr($row['purviews'],$purview)){
		write_msg("很抱歉，您当前的会员级别没有该栏目的操作权限！");
		exit();
	}
}
//to part if this information is member info or not
function is_member_info($id,$type='public')
{
	global $db,$db_mymps;
	if(empty($id)){write_msg("操作失败！您没有选择对象！");exit();}
	$type = ($type == 'public')?'AND info_level != 0':'';
	$post = $db->getRow("SELECT * FROM `{$db_mymps}information` WHERE id = '$id' $type");
	if(!$post){
		write_msg("操作失败！您所指定的信息不存在或者未通过审核");
		exit();
	}
	return $post;
}
//get the images upload box when write the information or edit the information
function get_upload_image_view($if_upimg = 1 , $infoid = '')
{
	global $mymps_global,$db,$db_mymps;
	if($if_upimg == 1){
		$cfg_upimg_number = $mymps_global[cfg_upimg_number]?$mymps_global[cfg_upimg_number]:'3';
		$mymps .='<li><span class=span_1>上传图片：</span>';
		for($i=0;$i<$cfg_upimg_number;$i++){
			$mymps .= '<span class=span_2 style="font-size: 12px; color: #888"><input class="textInt" size=50 type="file" name=mymps_img_'.$i.'> 图片必须是'.$mymps_global[cfg_upimg_type].'格式！图片大小不得超过'.$mymps_global[cfg_upimg_size].'KB </span><span class=span_1></span>
			';
		}
		$mymps .= '</li>';
	}
	return $mymps;
}
//edit the info images
function get_upload_image_edit($if_upimg = 1,$infoid,$admin='no'){
	global $mymps_global,$db,$db_mymps;
	if($if_upimg == 1){
		$cfg_upimg_number = $mymps_global[cfg_upimg_number]?$mymps_global[cfg_upimg_number]:'4';
		if($admin == 'no'){
			$mymps .='<li class="upload_img"><span class=span_1>上传图片：</span><span class=span_2>';
		}else{
			$mymps .='<li class="upload_img"><span>';
		}
		for($i=0;$i<$cfg_upimg_number;$i++){
			if(!empty($infoid)){
				$view = $db->getRow("SELECT image_id,prepath FROM `{$db_mymps}info_img` WHERE infoid = '$infoid' AND image_id = '$i'");
				$imagei = $view?'..'.$view[prepath]:'../images/nopic.gif';
			}else{
				$imagei = "images/nopic.gif";
			}
			$mymps .= '
			<ul>
			<div class="preview"><img src="'.$imagei.'" name="img'.$i.'" id="img'.$i.'" style="width:120px; height:120px;"></div>
			<li><input class="img_input" type="file" onpropertychange="if(CheckFile(this.value,this))img'.$i.'.src=this.value" name=mymps_img_'.$i.' onkeydown="return false" onpaste="return false" ondragenter="return false"></li>
			</ul>
			';
		}
		$mymps .= '</span></li>';
	}
	return $mymps;
}
//get the new array
function arraychange($oldarray)
{
	$oldarray = explode("\r\n",$oldarray);
	$new_array = array();
	foreach($oldarray as $t) { 
		$t = explode('=', $t);
		if(!isset($new_array[$t[0]])){
			$new_array[$t[0]] = $t[1];
		}
	}
	return $new_array;
}
//get the format_date
function get_format_time($time)
{
    $limit = time() - $time;
    if($limit < 60){
        return $limit . '秒钟之前';
    }elseif($limit >= 60 && $limit < 3600){
        return floor($limit/60) . '分钟之前';
    }elseif($limit >= 3600 && $limit < 86400){
        return floor($limit/3600) . '小时之前';
    }
    elseif($limit >= 86400)
    {
        return date('y-m-d', $time);
    }
}
function Rewrite($type, $params, $page = 0, $size = 0){
	global $mymps_global;
    static $rewrite = NULL;
    if($rewrite === NULL)$rewrite = intval($mymps_global['cfg_if_rewrite']);
    $args = array('id' => 0,'catid' => 0,'areaid' => 0,'page' => 0 ,'part' => 0 ,'user' => 0,'action' => 0);
    extract(array_merge($args, $params));

    $uri .= $mymps_global[SiteUrl];
    switch($type){
        case 'info':
            if (empty($id) && empty($catid)){
                return false;
            }elseif($id && $rewrite){
				$uri .= '/info-id-'.$id;
				if(!empty($areaid))$uri .= 'areaid-'.$areaid;
				if(!empty($page))$uri .= 'page-'.$page;
			}elseif($id && empty($rewrite)){
				$uri .= '/public/info.php?id='.$id;
			}elseif($catid && $rewrite){
				$uri .= '/info-catid-' . $catid;
				$uri .= $areaid?'-areaid-'.$areaid:'';
				$uri .= $page?'-page-'.$page:'-page-1';
			}elseif($catid && empty($rewrite)){
				$uri .= '/public/info.php?catid=' . $catid;
				if($areaid)$uri .= '&amp;areaid=' . $areaid;
				if($page)$uri .= '&amp;page=' . $page;
			}
        break;

        case 'about':
            if(empty($part)){
                return false;
            }elseif($id&&empty($page)){
                $uri .= $rewrite ? '/'.$part.'-id-'.$id : '/public/about.php?part='.$part.'&amp;id=' . $id;
            }elseif($page&&empty($id)){
				$uri .= $rewrite ? '/'.$part.'-page-'.$page : '/public/about.php?part='.$part.'&amp;page=' . $page;
			}elseif(empty($id)&&empty($page)&&empty($action)){
				$uri .= $rewrite ? '/'.$part : '/public/about.php?part='.$part;
			}elseif(empty($id)&&empty($page)&&$action){
				$uri .= $rewrite ? '/'.$part.'-action-'.$action : '/public/about.php?part='.$part.'&action='.$action;
			}
        break;

		case 'space':
            if(empty($user)){
                return false;
            }else{
                $uri .= $rewrite ? '/space-' . $user : '/public/space.php?user='.$user;
            }
        break;
   
        default:
            return false;
        break;
    }
    if($rewrite)$uri .= '/';
    return $uri;
}
//write the cache of category and area
function write_class_cache($type="category",$pre="cat")
{
	global $db,$db_mymps;
	$view = $db->getAll("SELECT a.*,b.".$pre."name AS parentname FROM `{$db_mymps}".$type."`  AS a LEFT JOIN `{$db_mymps}".$type."` AS b ON a.parentid = b.".$pre."id WHERE a.parentid = '0' ORDER BY a.".$pre."order ASC");
	$mymps .= "<?php\n\$".$type."_cache = array();\n";
	if($type == 'category'){
		foreach ($view as $k => $v)
		{
			$typeid 	= $pre."id";
			$typename 	= $pre."name";
			$typeorder 	= $pre."order";
			$mymps .= "\$".$type."_cache[]=array(";
			$mymps .= "\"".$pre."id\" => \"".$v[$typeid]."\",";
			$mymps .= "\"".$pre."name\" => \"".$v[$typename]."\",";
			$mymps .= "\"".$pre."order\" => \"".$v[$typeorder]."\",";
			$mymps .= "\"parentid\" => \"".$v[parentid]."\",";
			$mymps .= "\"parentname\" => \"".$v[parentname]."\"";
			$mymps .= ",'title' => '".str_replace("'","\'",$v[title])."'";
			$mymps .= ",'notice' => '".str_replace("'","\'",$v[notice])."'";
			$mymps .= ",'keywords' => '".str_replace("'","\'",$v[keywords])."'";
			$mymps .= ",'description' => '".str_replace("'","\'",$v[description])."'";
			$mymps .= ");\n";
		}
		$view = $db->getAll("SELECT a.*,b.".$pre."name AS parentname FROM `{$db_mymps}category`  AS a LEFT JOIN `{$db_mymps}category` AS b ON a.parentid = b.catid WHERE a.parentid > 0 ORDER BY a.catorder ASC");
		foreach ($view as $k => $v){
			
			$typeid 	= $pre."id";
			$typename 	= $pre."name";
			$typeorder 	= $pre."order";
			$mymps .= "\$category_cache_s[".$v[parentid]."][]=array(";
			$mymps .= "\"".$pre."id\" => \"".$v[$typeid]."\",";
			$mymps .= "\"".$pre."name\" => \"".$v[$typename]."\",";
			$mymps .= "\"".$pre."order\" => \"".$v[$typeorder]."\",";
			$mymps .= "\"modid\" => \"".$v[modid]."\",";
			$mymps .= "\"color\" => \"".$v[color]."\",";
			$mymps .= "\"parentid\" => \"".$v[parentid]."\",";
			$mymps .= "\"parentname\" => \"".$v[parentname]."\"";
			$mymps .= ",'title' => '".str_replace("'","\'",$v[title])."'";
			$mymps .= ",'notice' => '".str_replace("'","\'",$v[notice])."'";
			$mymps .= ",'keywords' => '".str_replace("'","\'",$v[keywords])."'";
			$mymps .= ",'description' => '".str_replace("'","\'",$v[description])."'";
			$mymps .= ",'uri' => '".Rewrite('info',array('catid'=>$v[catid],'page'=>$page))."'";
			$mymps .= ");\n";
			
		}
	}else{
		foreach ($view as $k => $v)
		{
			$typeid 	= $pre."id";
			$typename 	= $pre."name";
			$typeorder 	= $pre."order";
			$mymps .= "\$".$type."_cache[]=array(";
			$mymps .= "\"".$pre."id\" => \"".$v[$typeid]."\",";
			$mymps .= "\"".$pre."name\" => \"".$v[$typename]."\",";
			$mymps .= "\"".$pre."order\" => \"".$v[$typeorder]."\",";
			$mymps .= "\"parentid\" => \"".$v[parentid]."\",";
			$mymps .= "\"parentname\" => \"".$v[parentname]."\"";
			$mymps .= ");\n";
				$second = $db -> getAll("SELECT * FROM `{$db_mymps}area` WHERE parentid = '$v[areaid]' ORDER BY areaorder ASC");
				foreach($second as $u => $w){
					$mymps .= "\$".$type."_cache[]=array(";
					$mymps .= "\"".$pre."id\" => \"".$w[$typeid]."\",";
					$mymps .= "\"".$pre."name\" => \"".$w[$typename]."\",";
					$mymps .= "\"".$pre."order\" => \"".$w[$typeorder]."\",";
					$mymps .= "\"parentid\" => \"".$w[parentid]."\",";
					$mymps .= "\"parentname\" => \"".$w[parentname]."\"";
					$mymps .= ");\n";
				}
		}
	}
	$mymps .= "?>";
	$write_c=createfile(MYMPS_DATA.'/'.$type.'.inc.php',$mymps);
	if(!$write_c){
		write_msg(MYMPS_DATA."/".$type.".inc.php 文件不可写，请检查相应权限");
		exit();
	}
}

function write_admin_cache(){

	global $db,$db_mymps;
	$mymps .= "<?php\n";
	$sql = $db -> getAll("SELECT a.userid,b.typename,b.purviews FROM `{$db_mymps}admin` AS a LEFT JOIN `{$db_mymps}admin_type` AS b ON a.typeid = b.id");
	foreach ($sql as $k => $v){
		$mymps .= "\$admin_purview_cache['".$v[userid]."']=array(";	//Modify By 01
		$mymps .= "\"purviews\" => \"".$v[purviews]."\",";
		$mymps .= "\"admintype\" => \"".$v[typename]."\",";
		$mymps .= ");\n";
	}
	$mymps .= "?>";
	!createfile(MYMPS_DATA.'/admin.inc.php',$mymps) && write_msg(MYMPS_DATA."/admin.inc.php 文件不可写，请检查相应权限");
}

//write the cache of announce
function write_announce_cache()
{
	global $db,$db_mymps;
	$view = $db->getAll("SELECT id,title,pubdate,titlecolor FROM `{$db_mymps}announce` ORDER BY pubdate DESC LIMIT 0,10");
	$mymps .= "<?php\n\$announce_cache = array();\n";
	foreach ($view as $k => $v){
		$mymps .= "\$announce_cache[]=array(";
		$mymps .= "\"id\" => \"".$v[id]."\",";
		$mymps .= "\"title\" => \"".$v[title]."\",";
		$mymps .= "\"titlecolor\" => \"".$v[titlecolor]."\",";
		$mymps .= "\"pubdate\" => \"".$v[pubdate]."\");\n";
	}
	$mymps .= "?>";
	$write_c=createfile(MYMPS_DATA.'/announce.inc.php',$mymps);
	if(!$write_c){
		write_msg(MYMPS_DATA."/announce.inc.php 文件不可写，请检查相应权限");
		exit();
	}
}

function msetcookie($var, $value = '', $life = 0, $prefix = 1, $httponly = false) {
	global $cookiepre, $cookiedomain, $cookiepath, $timestamp, $_SERVER;
	$var = ($prefix ? $cookiepre : '').$var;
	if($value == '' || $life < 0) {
		$value = '';
		$life = -1;
	}
	$life = $life > 0 ? $timestamp + $life : ($life < 0 ? $timestamp - 31536000 : 0);
	$path = $httponly && PHP_VERSION < '5.2.0' ? "$cookiepath; HttpOnly" : $cookiepath;
	$secure = $_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
	if(PHP_VERSION < '5.2.0') {
		setcookie($var, $value, $life, $path, $cookiedomain, $secure);
	} else {
		setcookie($var, $value, $life, $path, $cookiedomain, $secure, $httponly);
	}
}

function clearcookies() {
	global $s_uid, $admin_id;
	foreach(array('s_uid', 'admin_id') as $k) {
		msetcookie($k);
	}
	$s_uid = $admin_id = $credits = '';
}
?>