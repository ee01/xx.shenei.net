<?php

/*
 * $Id: common.php 2008-8-1 9:12:02z happyboy $
 * --------------------------------------------
 * 网址：www.phpmps.com
 * --------------------------------------------
 * 这是一个自由软件，遵循BSD协议。
*/
/*
if (!defined('IN_PHPMPS'))
{
    die('Access Denied');
}

//定义错误级别
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// 取得根目录
define('PHPMPS_ROOT', str_replace("\\", '/', substr(dirname(__FILE__), 0, -7)));

set_magic_quotes_runtime(0);

if (!file_exists(PHPMPS_ROOT . 'data/install.lock'))
{
	header("Location:./install/index.php");
	exit;
}

require PHPMPS_ROOT . 'data/config.php';
require PHPMPS_ROOT . 'include/global.fun.php';

$php_self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];

//header('Content-type: text/html; charset='.$charset);

//转义处理客户端提交的数据
if(!get_magic_quotes_gpc())
{
	$_POST   = stripslashes_deep($_POST);
	$_GET    = stripslashes_deep($_GET);
	$_COOKIE = stripslashes_deep($_COOKIE);
}

if(function_exists('date_default_timezone_set'))
{
    date_default_timezone_set('Asia/Shanghai');
}

//初始化数据库类
require PHPMPS_ROOT . 'include/mysql.class.php';
$db = new mysql($db_host, $db_user, $db_pass, $db_name);
$db_host = $db_user = $db_pass = $db_name = NULL;

//取得系统信息
$CFG = get_config();
*/



require '../include/global.inc.php';
//初始化数据库类
require MYMPS_DATA . '/config.db.php';
//$db_name = "phpmps";
$db_name    = "mymps";

require 'mysql.class.php';
$db = new mysql($db_host, $db_user, $db_pass, $db_name);
/*
$db = @mysql_connect($db_host.":3306",$db_user,$db_pass) or die ('数据库连接失败！');
@mysql_select_db($db_name, $db) or die('数据库选定失败！');
mysql_query("set names 'gbk'");
*/
$db_host = $db_user = $db_pass = $db_name = NULL;
$table = 'my_';


//Default.php

function get_cat_list()
{
	global $db,$table;
	
	static $cats = NULL;
	if ($cats === NULL)
    {
		$sql = "select a.catid, a.catname, a.catorder as catorder ,b.catid as childid, b.catname as childname, b.catorder as chiorder from {$table}category as a left join {$table}category as b on b.parentid = a.catid where a.parentid = '0' order by catorder,a.catid,chiorder asC";
		$res = $db->getAll($sql);
/*
		$query = mysql_query($sql, $db);
		while ($value = mysql_fetch_array($query)) {
			$res[] = $value;
		}
*/

		$cats = array();

		foreach ($res as $row)
		{
			$cats[$row['catid']]['catid']   = $row['catid'];
			$cats[$row['catid']]['catname'] = $row['catname'];
			$cats[$row['catid']]['caturl']  = url_rewrite('category',array('cid'=>$row[catid]));

			if(!empty($row['childid']))
			{
				$cats[$row['catid']]['children'][$row['childid']]['id']   = $row['childid'];
				$cats[$row['catid']]['children'][$row['childid']]['name'] = $row['childname'];
				$cats[$row['catid']]['children'][$row['childid']]['url']  = url_rewrite('category',array('cid'=>$row[childid]));
			}
		}
	}
	return $cats;
}

function url_rewrite($app, $params, $page = 0, $size = 0)
{
	global $CFG;
    static $rewrite = NULL;

    if($rewrite === NULL)$rewrite = intval($CFG['rewrite']);
    $args = array('aid'=> 0,'bid'=>'0','cid'=> 0,'vid'=> 0,'eid'=> '0','tid'=>'0','hid'=>'0' );
    extract(array_merge($args, $params));
    $uri = '';
    switch($app)
    {
        case 'category':
            if (empty($cid) && empty($eid))
			{
                return false;
            }
			else
			{
                if($rewrite)
				{
                    $uri = 'cat-' . $cid;
					if(!empty($eid))$uri .= '-area-' . $eid;
                    if(!empty($page))$uri .= '-page-' . $page;
                }else{
                    $uri = 'category.php?id=' . $cid;
					if(!empty($eid))$uri .= '&amp;area=' . $eid;
                    if(!empty($page))$uri .= '&amp;page=' . $page;
                }
            }
        break;

        case 'view':
            if(empty($vid))
			{
                return false;
            }else{
                $uri = $rewrite ? 'view-' . $vid : 'view.php?id=' . $vid;
            }
        break;

		case 'about':
            if(empty($aid))
            {
                return false;
            }else{
                $uri = $rewrite ? 'about-' . $aid : 'about.php?id=' . $aid;
            }
        break;

		case 'help':
            if($act=='list' && $tid)
            {
                if($rewrite)
				{
                    $uri = 'help-list-' . $tid;
                }else{
                    $uri = 'help.php?act=list&amp;typeid=' . $tid;
                }
            }
			elseif($act=='view' && $hid)
			{
                if($rewrite)
				{
                    $uri = 'help-view-' . $hid;
                }else{
                    $uri = 'help.php?act=view&amp;id=' . $hid;
                }
            }
        break;
   
        default:
            return false;
        break;
    }
    if($rewrite)$uri .= '.html';
    return $uri;
}

//Category.php

function get_cat_info($catid)
{
	global $db,$table;

	$sql = "select * from {$table}category where catid='$catid' ";
	$cat_info = $db->getrow($sql);

	return $cat_info;
}

function get_cat_custom($catid)
{
	global $db,$table;
/*
	$cat_info = get_cat_info($catid);
	$parentid = $cat_info['parentid'];
	$search_id = $parentid ? $parentid : $catid;

	$sql = "select cusid, cusname, custype, value, search, listorder, unit from {$table}custom  where  catid = '$search_id' order by catid, listorder asc";
	$cat_custom = $db->getall($sql);
*/
	return $cat_custom;
}

function get_cat_children($catid,$type='int')
{
	$cats = get_cat_list();
	$cat_children = $cats[$catid]['children'];
	if(is_array($cat_children))
	{
		if($type=='int')
		{
			foreach($cat_children as $child)
			{
				$id .= $child['id'].',';
			}
			$result = substr($id,0,-1);
		}
		elseif($type=='array')
		{
			$result = $cat_children;
		}
	}
	else
	{
		if($type=='int')
		{
			$result = $catid;
		}
		elseif($type=='array')
		{
			$result = '';
		}
	}
	return $result;
}

function page($file,$cat,$area,$count,$size=20,$page=1)
{
	global $tpl;
    $page = intval($page);
    if($page<1)$page = 1;

    $page_count = $count > 0 ? intval(ceil($count / $size)) : 1;
    $page_prev  = ($page > 1) ? $page - 1 : 1;
    $page_next  = ($page < $page_count) ? $page + 1 : $page_count;

	$pager['start']      = ($page-1)*$size;
    $pager['page']       = $page;
    $pager['size']       = $size;
    $pager['count']		 = $count;
    $pager['page_count'] = $page_count;
	
	if($page_count <= '1')
	{
	    $pager['first'] = $pager['prev']  = $pager['next']  = $pager['last']  = '';
	}
	elseif($page_count > '1')
	{
		if($page == $page_count)
		{
			$pager['first'] = url_rewrite('category', array('cid'=>$cat,'eid'=>$area), 1);
			$pager['prev']  = url_rewrite('category', array('cid'=>$cat,'eid'=>$area), $page_prev);
			$pager['next']  = '';
			$pager['last']  = '';
		}
		elseif($page_prev == '1' && $page == '1')
		{
			$pager['first'] = '';
			$pager['prev']  = '';
			$pager['next']  = url_rewrite('category', array('cid'=>$cat,'eid'=>$area), $page_next);
			$pager['last']  = url_rewrite('category', array('cid'=>$cat,'eid'=>$area), $page_count);
		}
		else
		{
			$pager['first'] = url_rewrite('category', array('cid'=>$cat,'eid'=>$area), 1);
			$pager['prev']  = url_rewrite('category', array('cid'=>$cat,'eid'=>$area), $page_prev);
			$pager['next']  = url_rewrite('category', array('cid'=>$cat,'eid'=>$area), $page_next);
			$pager['last']  = url_rewrite('category', array('cid'=>$cat,'eid'=>$area), $page_count);
		}
	}
    return $pager;
}

function cut_str($str, $length, $start=0)
{
	global $charset;
	if(function_exists("mb_substr"))
	{
	    if(mb_strlen($str, $charset) <= $length) return $str;
	    $slice = mb_substr($str, $start, $length, $charset);
	}
	else
	{
		$re['utf-8']  = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		if(count($match[0]) <= $length) return $str;
		$slice = join("",array_slice($match[0], $start, $length));
	}
	return $slice;
}

function enddate($date)
{
	$date = round(($date>0 ? ($date-time()) : '0')/(3600*24));
	if($date > 0)
	{
		$day = "<font color=red>$date</font>天";
	}
	elseif($date == 0)
	{
		$day = '长期有效';
	}
	else
	{
		$day = '已过期';
	}
	return $day;
}

//View.php

function encrypt($string, $password)
{
	$password = base64_encode($password);
    $count_pwd = strlen("a".$password);
    for($i = 1;$i<$count_pwd;$i++)
	{
		$pwd+=ord($password{$i});
    }
	$string = base64_encode($string);
    $count = strlen("a".$string);
    for($i = 0;$i<$count;$i++)
	{
    	$asciis.=(ord($string{$i})+$pwd)."|";
    }
    $asciis = base64_encode($asciis);
	return $asciis;
}

function get_info_custom($infoid)
{
	global $db,$table;
/*
	$sql = "select a.cusid, a.cusname, a.unit, g.cusvalue from {$table}cus_value as g left join {$table}custom as a on a.cusid = g.cusid where g.infoid = '$infoid' order by a.listorder, a.cusid";
    $res = $db->query($sql);
	$cus = array();
    while($row = $db->fetchRow($res))
    {
		$arr['name']  = $row['cusname'];
		$arr['value'] = $row['cusvalue'];
		$arr['unit']  = $row['unit'];
		$cus[] = $arr;
    }
*/
    return $cus;
}

function get_info($cat='',$area='',$num='10',$protype='',$listtype='',$len='20',$thumb='')
{
	global $db,$table;
	
	$where = " where 1";
	if(!empty($cat))
	{
		$where .= " AND i.catid in ($cat)";
	}
	if(!empty($area))
	{
		$where .= " AND i.areaid in ($area)";
	}
	if($thumb=='1')
	{
		$where .= " and prepath != '' ";
	}

	if(!empty($protype))
	{
		switch($protype)
		{
			case 'pro':
				$where .= " AND is_pro >=".time();
			break;
			
			case 'top':
				$where .= " AND is_top >=".time();
			break;
		}
	}	
	if(!empty($listtype))
	{
		switch($listtype)
		{
			case 'date':
				$order = " order by begintime DESC";
			break;
			
			case 'click':
				$order = " order by click desc,begintime desc ";
			break;
		}
	}
	$limit = " LIMIT 0,$num ";
	
	$sql = "select i.id,i.title,i.begintime,img.prepath,c.catid,c.catname,a.areaname from {$table}information as i left join {$table}category as c on i.catid = c.catid left join {$table}area as a on a.areaid = i.areaid left join {$table}info_img as img on i.id = img.infoid $where $order $limit";
	$res = $db->query($sql);
	$info = array();
	while($row=$db->fetchRow($res))
	{
		$row['title']    = cut_str($row['title'], $len);
		$row['begintime'] = date('y-m-d',$row['begintime']);
		$row['url']      = url_rewrite('view',array('vid'=>$row['id']));
		$row['caturl']   = url_rewrite('category',array('cid'=>$row['catid']));
		$row['areaurl']; url_rewrite('category',array('eid'=>$row['areaid']));
		$info[]          = $row;
	}
	return $info;
}

function get_info_option_des($identifier){
	global $db,$table;
	$mymps = $db->fetchRow($db->query("SELECT title,type,rules FROM `{$table}info_typeoptions` WHERE identifier = '$identifier'"));
	$arr=array();
	$arr[title]  	= $mymps[title];
	$arr[type] 		= $mymps[type];
	$arr[rules]		= $mymps[rules];
	return $arr;
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

?>