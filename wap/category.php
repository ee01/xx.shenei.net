<?php
header("content-type:text/vnd.wap.wml");
echo("<?xml version=\"1.0\" encoding=\"UTF-8\"?> \n");
echo("<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/DTD/wml_1.1.xml\">\n");

define('IN_PHPMPS', true);
require_once 'wap.php';

if(isset($_REQUEST['id']))$catid = intval($_REQUEST['id']);
if(isset($_REQUEST['area']))$areaid = intval($_REQUEST['area']);

if(empty($catid) && empty($areaid))
{
	header("Location: ./");
	exit;
}

//判断是否真实存在分类或地区
if(!empty($catid))
{
	$sql = "select count(*) from {$table}category where catid = '$catid' ";
	if(!$db->getOne($sql))
	{
		header("Location: ./");
		exit;
	}
}

if(!empty($areaid))
{
	$sql = "select count(*) from {$table}area where areaid = '$areaid' ";
	if(!$db->getOne($sql))
	{
		header("Location: ./");
		exit;
	}
}

if($areaid)
{
	$here_area = $db->getRow("select areaid,areaname from {$table}area where areaid='$areaid'");
	$here[] = array('name'=>$here_area['areaname'],'url'=>url_rewrite('category',array('eid'=>$here_area['areaid'])));
}
if($catid)
{
	$here_cat = get_cat_info($catid);
	$here[] = array('name'=>$here_cat['catname'],'url'=>url_rewrite('category',array('cid'=>$here_cat['catid'])));
}

if(!empty($catid))
{
	$cat_custom = get_cat_custom($catid);//取得可用于搜索的属性
}

//判断并取得分类
if(empty($catid))
{
	$sql = "select catid,catname from {$table}category where parentid = '0' ";
	$row = $db->getAll($sql);
}
else
{
	$sql = "select parentid from {$table}category where catid = '$catid' ";
	$cat_parent = $db->getOne($sql);
	if(empty($cat_parent))
	{
		$sql = "select catid,catname from {$table}category where parentid = '$catid'";
		$row = $db->getAll($sql);
		
		foreach($row AS $cat)
		{
			$s_cat .= "<option value=$cat[catid]>$cat[catname]</option>";
		}
		$cats = get_cat_children($catid);
		$cat_sql = " and catid in ($cats) ";
	}
	else
	{
		$cats = $catid;
		$cat_sql = " and catid = '$cats' ";
		$s_cat = $catid;
	}
}
if(!empty($row))
{
	$cat_arr = array();
	foreach($row as $val)
	{
		$cat_arr[$val['catid']]['catname'] = $val['catname'];
		$cat_arr[$val['catid']]['url'] = url_rewrite('category',array('cid'=>$val['catid'],'eid'=>$areaid));
	}
}
//判断并取得地区
if(empty($areaid))
{
	$sql = "select areaid,areaname from {$table}area where parentid = '0' ";
	$area_row = $db->getAll($sql);
	foreach($area_row AS $cat)
	{
		$s_area .= "<option value=$cat[areaid]>$cat[areaname]</option>";
	}
}
else
{
	$sql = "select parentid from {$table}area where areaid = '$areaid' ";
	$area_parent = $db->getOne($sql);
	if(empty($area_parent))
	{
		$sql = "select areaid,areaname from {$table}area where parentid = '$areaid' ";
		$area_row = $db->getAll($sql);
		
		foreach($area_row AS $cat)
		{
			$s_area .= "<option value=$cat[areaid]>$cat[areaname]</option>";
		}
		$areas = get_area_children($areaid);
		$area_sql = " and i.areaid in ($areas) ";
	}
	else
	{
		$sql='';
		$areas = $areaid;
		$area_sql = " and i.areaid = '$areas' ";
	}
}
if(!empty($area_row))
{
	$area_arr = array();
	foreach($area_row as $val)
	{
		$area_arr[$val['areaid']]['areaname'] = $val['areaname'];
		$area_arr[$val['areaid']]['url'] = url_rewrite('category',array('cid'=>$catid,'eid'=>$val['areaid']));
	}
}
$page = empty($_REQUEST['page']) ? '1' : intval($_REQUEST['page']);

//取得信息总数
$sql = "SELECT COUNT(*) FROM {$table}information as i WHERE 1 $cat_sql $area_sql";
$count = $db->getOne($sql);

//分页
$pager = page('category.php',$catid,$areaid,$count,'10',$page);

//取得信息
$sql = "SELECT id,title,begintime,endtime,catid,areaname FROM {$table}information AS i LEFT JOIN {$table}area AS a ON a.areaid=i.areaid WHERE 1 $cat_sql $area_sql ORDER BY begintime DESC limit $pager[start],$pager[size]";
$res = $db->query($sql);

$articles = array();
while($row=$db->fetchRow($res))
{
	$arr['id']       = $row['id'];
	$arr['catid']    = $row['catid'];
	$arr['title']    = cut_str($row['title'],'18');
	$arr['area']     = $row['areaname'];
	$arr['begintime'] = date('Y/m/d', $row['begintime']);
	$arr['lastdate'] = round(($row['endtime']>0 ? ($row['endtime']-time()) : '0')/(3600*24));
	$arr['lastdate'] = enddate($arr['lastdate']);
	$arr['url']      = url_rewrite('view',array('vid'=>$row['id']));
	$articles[]      = $arr;
}


?>
<wml>
<card id="info" title="<?=$here_cat['catname']?> - 舍内知天下">
<p>信息列表<?//  [<a href="post.php?id=?><?//=$catid?><?//">发布</a>]?></p>
<?
foreach($articles AS $article)
{
?>
<p><img src="li.gif" align="absMiddle" /><A href="<?=$article['url']?>" target="_blank"><?=$article['title']?></A>  
     <?=$article['begintime']?></p>
<?
}
?>
<p>
<div>
共<?=$pager[count]?>记录
<?if($pager[first]){?><a href="<?=$pager[first]?>">第一页  </a><?}else{?>第一页  <?}?>
<?if($pager[prev]){?><a href="<?=$pager[prev]?>">上一页  </a><?}else{?>上一页  <?}?>
<?if($pager[next]){?><a href="<?=$pager[next]?>">下一页  </a><?}else{?>下一页  <?}?>
<?if($pager[last]){?><a href="<?=$pager[last]?>">最后一页  </a><?}else{?>最后一页  <?}?>
</div>
</p>

<do type="accept" label="返回">
 <prev/>
</do>
        <p></p>
        <p><a href="http://u.shenei.net/wap" target=_blank>一起进舍内家园交流去~</a></p>
</card>
</wml>