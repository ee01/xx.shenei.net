<?php
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * 网站地址: http://www.mymps.com.cn；
 * 交流论坛：http://bbs.mymps.com.cn；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 不允许对代码作修改后以任何形式任何目的的再发布。
 * 如果你觉得本软件不错，请介绍给你的站长朋友，让更多的人用上mymps
 * ============================================================================
 * 软件作者: 彭介平（村长）
 * 联系方式：QQ:3037821 MSN:business@live.it
`*/
require_once(dirname(__FILE__)."/global.php");
require_once(MYMPS_DATA."/info_posttime.php");

$id			= isset($id) 	   ? intval($id) 	: '';
$catid		= isset($catid)    ? intval($catid) : '';
$areaid		= isset($areaid)   ? intval($areaid): '';

if(!empty($id)&&empty($catid)&&empty($areaid)){
	require_once(MYMPS_INC."/ip.class.php");

	$smarty -> cache_lifetime = $mymps_cache['info']['time'];
	$smarty -> caching        = $mymps_cache['info']['open'];
	
	if (!$smarty -> is_cached(mymps_tpl('info','smarty'),$id)){
		$info = $db->getRow("SELECT a.id,a.title,a.catid,b.parentid as bcatid,b.catname,c.catname as bcatname,a.areaid,a.begintime,a.endtime,a.qq,a.email,a.tel,a.ismember,a.manage_pwd,a.ip,a.info_level,a.userid,a.contact_who,a.content,a.img_count FROM `{$db_mymps}information` AS a LEFT JOIN `{$db_mymps}category` AS b ON a.catid = b.catid LEFT JOIN `{$db_mymps}category` AS c ON b.parentid = c.catid WHERE id = '$id' AND info_level != 0");
		
		if(!$info){
			write_msg("该信息不存在，或者未通过审核！","/");
			exit();
		}
		
		if($info[ismember]==1){
			$re = $db->getRow("SELECT prelogo,jointime FROM `{$db_mymps}member` WHERE userid = '$info[userid]'");
			$info['prelogo'] 	= "<a href=\"../public/space.php?user=".$info[userid]."\" target=\"_blank\"><img src=\"".$re[prelogo]."\"/></a>";
			$info['member'] 	= "<a href=\"../public/space.php?user=".$info[userid]."\" target=\"_blank\">".$info[userid]."</a>";
		}else{
			$info['prelogo'] = "<img src=\"../images/nophoto.jpg\" />";
			$info['member'] = "游客";
		}
		
		if(function_exists("gd_info") && $mymps_global['cfg_info_if_img'] == 1){

			$info['email'] 		= !empty($info['email'])?"<img src=\"../include/chkcode.php?part=info_image&strings=".base64_encode($info['email'])."\">":$info['email'];
			$info['tel'] 		= !empty($info['tel'])?"<img src=\"../include/chkcode.php?part=info_image&strings=".base64_encode($info['tel'])."\">":$info['tel'];
		}
		
		/*IP库使用判断*/
		$nowip = $info['ip'];
		if($mymps_mymps['ipdat_choose'] != 1){
			$info['ip2area'] 	= ip2area($nowip);
		}else{
			$ipchoose = new ipLocation();
			$address  =	$ipchoose -> getaddress($nowip);
			$ipchoose =	NULL;
			$info['ip2area'] = $address["area1"];
		}
		
		
		if(mymps_count("info_extra","WHERE infoid = $id") > 0){
			$extr = $db -> getAll("SELECT name,value FROM `{$db_mymps}info_extra` WHERE infoid = '$id'");
			$info[extra] .= "<TBODY>";
			foreach ($extr as $k => $v){
				$option = get_info_option_des($v[name]);
				if($option[type] == 'radio' || $option[type] == 'select'){
					$tmp 			= unserialize($option[rules]);
					$new_array  	= arraychange($tmp[choices]);
					$new_value		= $new_array[$v[value]];
					$info[extra]   .= "<tr><td width='15%' align='right' bgcolor='#eff1f7' ><b>".$option[title]."：                                                             </b></td><td width='85%' align='left'>".$new_value." ".$option[units]."</td></tr>";
				}elseif($option[type] == 'checkbox'){
					$value = explode(",",$v[value]);
					foreach ($value as $m =>$n){
						$tmp = unserialize($option[rules]);
						$new_array = arraychange($tmp[choices]);
						$nvalue .= $new_array[$n]."&nbsp";
					}
					$info[extra] .= "<tr><td width='15%' align='right' bgcolor='#eff1f7' ><b>".$option[title]."：</b></td><td width='85%' align='left'>".$nvalue." ".$option[units]."</td></tr>";
				}else{
					$new_value = $v[value];
					$rules = unserialize($option[rules]);
					$info[extra] .= "<tr><td width='15%' align='right' bgcolor='#eff1f7' ><b>".$option[title]."：                                        </b></td><td width='85%' align='left'>".$new_value." ".$rules[units]."</td></tr>";
				}
			}
		}else{
			$info[extra] = '';
		}
		
		$cat['catid'] 		= $info['bcatid'];
		$catid 				= $info['catid'];
		$areaid 			= $info['areaid'];
		$info['ip'] 		= !empty($info['ip'])?part_ip($info['ip']):'';
		$info['lifetime'] 	= get_info_life_time($info['endtime']);
		$info['upgrade'] 	= " <a href=../member/info.php?part=upgrade&id=".$info[id].">置顶</a>";
		$info['edit'] 		= " <a href=../member/info.php?part=edit&id=".$info[id]." class=\"edit\">修改</a>";
		$info['del']		= " <a class=\"del\" href=../member/info.php?part=del&id=".$info[id]." onClick=\"return confirm('您确定要删除该信息吗，如不确定请点取消')\">删除</a>";
		$info['refresh'] 	= " <a href=../member/info.php?part=refresh&id=".$info[id].">刷新</a>";
		$info['begintime'] 	= GetTime($info['begintime']);
		
		if($info['img_count'] > 0){
			$smarty -> assign("image",$db -> getAll("SELECT prepath,path FROM `{$db_mymps}info_img` WHERE infoid = '$id' ORDER BY id DESC"));
		}
		
		$nav_bar = '<a href="/">网站首页</a> &raquo; <a href="'.Rewrite('info',array('catid'=>$info[bcatid])).'">'.$info[bcatname].'</a> &raquo; <a href="'.Rewrite('info',array('catid'=>$catid)).'">'.$info[catname].'</a> &raquo; 信息详情';
		
		$right[GetInfoPostTime] = GetInfoPostTime($posttime);

		mymps_global_assign();
		$smarty -> assign("cat"			 , $cat);
		$smarty -> assign('right'	 	 , $right);
		$smarty -> assign("nav_bar" 	 , $nav_bar);
		$smarty -> assign("info"		 , $info);
		$smarty -> assign('info_list_cat', mymps_get_info_list(10,'','','',$info[catid]));
		$smarty -> assign("info_cat_list", get_cat_info($info[bcatid],'list'));
	}
	
	$smarty -> display(mymps_tpl("info","smarty"),$id);
	
}elseif(empty($id)){

	$keywords	= isset($keywords) ? trim($keywords): '';
	$posttime	= isset($posttime) ? trim($posttime): '';
	$part		= isset($part)     ? trim($part)	: '';
	$page 		= isset($page)	   ? intval($page)	: 1;
	
	if($part == 'search'){
		$smarty -> caching           = 0;
	} else {
		$smarty -> cache_lifetime    = $mymps_cache['list']['time'];
		$smarty -> caching           = $mymps_cache['list']['open'];
	}
	
	if (!$smarty->is_cached(mymps_tpl('info_list','smarty'),$catid,$areaid.$page)){
	
		$FirstSecond = $catid ? FirstSecond($catid) : '';
		
		$now = time();
		
		$db->query("UPDATE `{$db_mymps}information` SET upgrade_type = '1' WHERE upgrade_time < ".$now);
		
		if($FirstSecond == 'first'){
			$catids = get_cat_children($catid);
			$cat_limit = "AND a.catid IN (".$catids.")";
		}elseif($FirstSecond == 'second'){
			$cat_limit = "AND a.catid = '$catid'";
		}else{
			$cat_limit = "";
		}
		
		$area_limit 	= $areaid 		   ? "AND a.areaid IN (".get_area_children($areaid).")":"";
		
		$keywords_limit = $keywords 	   ? "AND a.title LIKE '%".$keywords."%' OR a.content LIKE '%".$keywords."%'":"";
		
		$posttime_limit = empty($posttime) ? "" : "AND a.begintime >= (".time()."-".$posttime."*3600*24)";

		$limit 			= $cat_limit.$area_limit.$keywords_limit.$posttime_limit;
		
		$sql 			= "SELECT a.*,b.areaname,c.catname FROM `{$db_mymps}information` AS a LEFT JOIN `{$db_mymps}area` AS b ON a.areaid = b.areaid LEFT JOIN `{$db_mymps}category` AS c ON a.catid = c.catid  WHERE a.info_level > 0  {$limit} ORDER BY upgrade_type DESC,begintime DESC";
		
		$rows_num 		= $db -> getOne("SELECT COUNT(id) FROM `{$db_mymps}information` AS a WHERE a.info_level > 0 $limit");
		
		if($part != 'search'){
			$param		= setParam(array('catid','areaid','keywords','posttime'),'public','info-');
		}else{
			$param		= setParam(array('part','catid','areaid','keywords','posttime'),'mymps');
		}
		
		$info_list 		= array();
		
		foreach(page1($sql) AS $k => $row){
			$arr['id']       	= $row['id'];
			$arr['info_level']  = $row['info_level'];
			$arr['title']    	= HighLight($row['title'],$keywords);
			$arr['areaname']    = $row['areaname'];
			$arr['content']   	= HighLight(substring(SpHtml2Text($row['content']),0,70),$keywords);
			$arr['begintime'] 	= get_format_time($row['begintime']);
			$arr['upgrade_type']= ($row['upgrade_time'] >= $now)?$row['upgrade_type']:1;
			$arr['upgrade_time']= $row['upgrade_time'];
			$arr['img_count']	= $row['img_count'];
			$arr['catname'] 	= $row['catname'];
			$arr['uri'] 		= Rewrite('info',array('id'=>$row['id']));
			$info_list[]      	= $arr;
		}
		
		if(!empty($areaid)){
			
			$row = $db-> getRow("SELECT areaname FROM `{$db_mymps}area` WHERE areaid = '$areaid'");
			$areaname = $row[areaname];
		}
		
		if(is_array($area_cache)){
			$arealist = array();
			foreach($area_cache as $k => $v){
			$arealist[$k][areaid] 	= $v[areaid];
			$arealist[$k][areaname] = $v[areaname];
			$arealist[$k][parentid] = $v[parentid];
			$arealist[$k][uri] 		= Rewrite('info',array('catid'=>$catid,'areaid'=>$v['areaid'],'page'=>$page));
			}
		}
		
		switch ($FirstSecond){
		
			case "first":
				$cat 		= get_cat_info($catid,'frow');
				$catlist 	= get_cat_info($catid,'list');
				$CatTitle	= $cat[title]?$mymps_global[SiteCity].$areaname.$cat[title]:$mymps_global[SiteCity].$areaname.$cat[catname];
				$nav_bar	= '<a href=../>网站首页</a> &raquo; '.$cat[catname];
				
			break;
			
			case "second":
				$cat 		= get_cat_info($catid,'srow');
				$catlist 	= get_cat_info($cat[catid]);
				$CatTitle	= $cat[title]?$mymps_global[SiteCity].$areaname.$cat[title]:$mymps_global[SiteCity].$cat[scatname].$areaname.$cat[catname];
				$caturi		= Rewrite("info",array("catid"=>$cat[catid]));
				$nav_bar	= '<a href=../>网站首页</a> &raquo; <a href='.$caturi.'>'.$cat[catname].'</a> &raquo; '.$cat[scatname];
				
			break;
			
			default:
				$cat[title] = "所有分类信息类别";
				$cat[catname] = "分类信息";
				$CatTitle=$cat[title]?$mymps_global[SiteCity].$areaname.$cat[title]:$mymps_global[SiteCity].$areaname.$cat[catname];
				$catlist = get_cat_info();
				$nav_bar	= '<a href=../>网站首页</a> &raquo; 所有分类信息类别';
			break;
			
		}
		
		$right[GetInfoPostTime] = GetInfoPostTime($posttime);
		$right[keywords] 		= $keywords;
		$right[catname] 		= $cat[scatname]?$cat[scatname]:$cat[catname];
		
		$page2 = ($part != 'search')?page2('public'):page2();
		
		mymps_global_assign();
		$smarty -> assign('right'	  ,$right);
		$smarty -> assign('cat'		  ,$cat);
		$smarty -> assign('area'	  ,$arealist);
		$smarty -> assign('CatTitle'  ,$CatTitle);
		$smarty -> assign('nav_bar'	  ,$nav_bar);
		$smarty -> assign('catid'	  ,$catid);
		$smarty -> assign('areaid'	  ,$areaid);
		$smarty -> assign('catlist'	  ,$catlist);
		$smarty -> assign('info_list' ,$info_list);
		$smarty -> assign('pageview'  ,$page2);
		$smarty -> assign('page'	  ,$page);
	}
	$smarty -> display(mymps_tpl("info_list","smarty"),$catid,$areaid.$page);
	
}else{
	unknown_err_msg();
}
?>