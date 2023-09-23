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
if (!defined('IN_MYMPS')){
    die('FORBIDDEN');
}

function cache_cat_options($catid='')
{
	global $category_cache;
	if(is_array($category_cache)){
		foreach($category_cache as $k => $v){
			$option .= "<option value=$v[catid]";
			$option .= ($catid == $v[catid]) ? " selected='selected' style='background-color:#6eb00c; color:white!important;'" : " ";
			$option .= ">$v[catname]</option>";
		}
	}
	return $option;
}

function get_cat_options($catid = ''){
	global $db,$db_mymps,$category_cache,$category_cache_s;
	if(is_array($category_cache)){
		foreach($category_cache as $k => $v){
			$cat .= "<option value=$v[catid]";
			$cat .= ($catid == $v[catid]) ? " selected='selected' style='background-color:#6eb00c; color:white!important;'>$v[catname]</option>" : " style=color:#006acd>$v[catname]</option>";
			$scat = $db->getAll("SELECT * FROM `{$db_mymps}category` WHERE parentid = $v[catid] ORDER BY catorder ASC");
			foreach($category_cache_s[$v[catid]] as $u => $w){
				$cat .= "<option value=$w[catid]";
				$cat .= ($catid == $w[catid]) ? " selected='selected' style='background-color:#6eb00c; color:white!important;'>&nbsp;&nbsp;&nbsp;&nbsp;$w[catname]</option>" : ">&nbsp;&nbsp;&nbsp;&nbsp;$w[catname]</option>";
			}
		}
	}
	return $cat;
}

function area_options($areaid='')
{
	global $area_cache;
	if(is_array($area_cache)){
		foreach($area_cache as $k => $v){
			$option .= "<option value=$v[areaid]";
			$option .= ($areaid == $v[areaid]) ? " selected='selected' style='background-color:#6eb00c; color:white!important;'" : "";
			$option .= ($v[parentid] == 0)?" style=color:#006acd>":">&nbsp;&nbsp;&nbsp;&nbsp;";
			$option .= "$v[areaname]</option>";
		}
	}
	return $option;
}

function get_area_children($areaid)
{
	global $db,$db_mymps;
	if($row = $db->getAll("SELECT areaid FROM `{$db_mymps}area` WHERE parentid = '$areaid'")){
		$area = array();
		foreach ($row as $k => $v){
			$area[$v['areaid']] = $v['areaid'];
		}
		$areas = implode(',', $area).','.$areaid;
		return $areas;
	}else{
		return $areaid;
	}
}

function get_cat_children($catid)
{
	global $db,$db_mymps;
	if($row = $db->getAll("SELECT catid FROM `{$db_mymps}category` WHERE parentid = '$catid'")){
		$cat = array();
		foreach ($row as $k => $v){
			$cat[$v['catid']] = $v['catid'];
		}
		$cats = implode(',', $cat).','.$catid;
		return $cats;
	}else{
		return $catid;
	}
}
?>