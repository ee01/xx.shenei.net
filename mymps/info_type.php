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
require_once(dirname(__FILE__)."/global.php");
require_once(MYMPS_INC."/db.class.php");

$part = $part ? $part : 'option_list' ;

function get_type_option($identifier=""){
	global $var_type;
	foreach ($var_type as $k=>$value){
		$mymps .= "<option value=\"".$k."\"";
		$mymps .=($identifier==$k)?" selected":"";
		$mymps .=">".$value."(".$k.")</option>";
	}
	return $mymps;
}

if($part == 'option_list'){

	chk_admin_purview("purview_选项管理");
	require_once(MYMPS_DATA."/info.type.inc.php");
	
	$classid = $classid ? $classid : '1' ;
	$here = '信息分类选项';
	$options=$db->getAll("SELECT * FROM `{$db_mymps}info_typeoptions` WHERE classid ='0' ORDER BY optionid DESC");
	$detail = $db->getRow("SELECT title,optionid FROM `{$db_mymps}info_typeoptions` WHERE optionid ='$classid'");
	$option=$db->getAll("SELECT * FROM `{$db_mymps}info_typeoptions` WHERE classid ='$classid' ORDER BY displayorder DESC");
	include (mymps_tpl("info_option"));
	
}elseif($part == 'option_add'){

	if(empty($title)||empty($identifier)||empty($type)||empty($classid)){
		write_msg("请填写完整相关信息！");
		exit();
	}
	
	if(mymps_count("info_typeoptions","WHERE identifier = '$identifier'")>0){write_msg("变量名".$identifer."已存在，请换一个变量名");exit();}
	$res = $db->query("INSERT INTO `{$db_mymps}info_typeoptions` (title,identifier,type,displayorder,classid)VALUES('$title','$identifier','$type','$displayorder','$classid')");
	
	if($res){
		write_msg("分类信息选项".$id."增加成功！","info_type.php?classid=".$classid,"mymps.com.cn");
	}else{
		write_msg("分类信息选项".$id."增加失败！");
	}
	
}elseif($part == 'option_edit'){

	$optionid = intval($optionid);
	$action = trim($action);
	switch ($action){
		case 'update':
			$rule	= $rules;
			$rules	= serialize(str_replace(" ","",$rule[$typenew]));
			$db->query("UPDATE `{$db_mymps}info_extra` SET name = '$identifier' WHERE name = '$old_identifier'");
			$res	= $db->query("UPDATE `{$db_mymps}info_typeoptions` SET title='$title',identifier='$identifier',type='$typenew',classid='$classid',displayorder='$displayorder',description='$description',rules ='$rules',available='$available',required='$required',search='$search' WHERE optionid = '$optionid'");
			
			if($res){
				write_msg("信息选项 <b>".$title."</b> 属性修改成功！","?part=option_edit&optionid=".$optionid,"MyMps");
			}else{
				write_msg("信息选项".$title."属性修改失败！","","MyMps");
				exit();
			}
			
		break;
		default:
		
			$options=$db->getAll("SELECT * FROM `{$db_mymps}info_typeoptions` WHERE classid ='0' ORDER BY optionid DESC");
			$edit=$db->getRow("SELECT * FROM `{$db_mymps}info_typeoptions` WHERE optionid ='$optionid'");
			$here="分类选项";
			//get the class options
			$class_option=$db->getAll("SELECT optionid,title FROM `{$db_mymps}info_typeoptions` WHERE classid = '0' ORDER BY displayorder,optionid DESC");
			//if existed edit rules
			if($edit[rules]){
				$rule=unserialize($edit[rules]);
				if(is_array($rule)){
					foreach($rule as $w){
						$rules[$edit[type]] .= $w;
					}
				}
			}
			
			function get_mymps_admin_info_type($rules=""){
				global $mymps_admin_info_type,$edit,$rules,$var_type;
				foreach($mymps_admin_info_type as $k => $value){
					$estyle =($edit[type]!=$k)?'style="display:none"':'';
					$str .= "<div id=\"style_".$k."\" ".$estyle." class=\"mytable\"><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"vbm\"><tr class=\"firstr\"><td colspan=\"2\">".$var_type[$k]."(".$k.")</td></tr>".$value."</table></div>";
				}
				return $str;
			}
			
			require_once(MYMPS_DATA."/info.type.inc.php");
			include (mymps_tpl("info_option_edit"));
			
		break;
		
	}
}elseif($part == 'option_delall'){
	
	foreach ($id as $k=> $v){
		$row = $db->getRow("SELECT identifier FROM {$db_mymps}info_typeoptions WHERE optionid = ".$v);
		mymps_delete("info_extra","WHERE name = '$row[identifier]'");
		mymps_delete("info_typeoptions","WHERE optionid = ".$v);
	}
	write_msg('信息模型选项删除成功！','?classid='.$class_id,'record');
	
}elseif($part == 'option_type'){

	switch($action){
		case 'insert':
		
			$title = trim($_POST['title']);
			if(empty($title)){write_msg("请填写类型名称");exit();}
			$mymps_in=$db->query("INSERT `{$db_mymps}info_typeoptions` (title,classid) VALUES ('$title','0')");
			if($mymps_in){
				write_msg("信息模型分类".$title."添加成功！","?part=option_type","MYMPS.COM.CN");
			}else{
				write_msg("信息模型分类添加失败！");
			}
			
		break;
		case 'update':

			if(empty($title)){write_msg("请填写类型名称");exit();}
			$mymps_rs=$db->query("UPDATE `{$db_mymps}info_typeoptions` SET title = '$title' WHERE optionid = '$id'");
			
			if($mymps_rs){
				write_msg("信息模型分类".$title."修改成功！","?part=option_type","MYMPS.COM.CN");
			}else{
				write_msg("信息模型分类保存失败！");
			}
			
		break;
		case 'del':

			if(empty($id)){write_msg("您还没有选定编号");exit();}
			$mymps_del = mymps_delete("info_typeoptions","WHERE optionid = '$id'");
			if($mymps_del){
				write_msg("信息模型分类".$id."删除成功！","?part=option_type","WWW.MYMPS.COM.CN");	
			}else{
				write_msg("操作失败！");
			}
			
		break;
		default:
		
			$here = "选项类别管理";
			$sql = "SELECT optionid,classid,title FROM `{$db_mymps}info_typeoptions` WHERE classid = 0";
			$type= $db->getAll($sql);
			include (mymps_tpl("info_option_type"));
			
		break;
	}
	
}elseif($part == 'mod'){

	switch ($action){
		case 'insert':

			if(empty($name)){write_msg("请填写信息模型的名称");exit();}
			$displayorder = trim($_POST['displayorder'])?trim($_POST['displayorder']):'0';

			$sql = "INSERT `{$db_mymps}info_typemodels` (id,name,type,displayorder) VALUES ('','$name','0','$displayorder')";
			
			if($db->query($sql)){
				write_msg("信息模型 ".$name." 增加成功！","?part=mod","MyMPS.Com.cn");
			}else{
				write_msg("信息模型 ".$name." 增加失败！");
			}
			
		break;
		case 'update':

			if(empty($name)){write_msg("请输入信息模型的名称！");exit();}
			$post_opt = !empty($_POST['options']) ? implode (',', $_POST['options']) : '';
			if($db->query("UPDATE `{$db_mymps}info_typemodels` SET name='$name',displayorder='$displayorder',options='$post_opt' WHERE id = '$id'")){
				write_msg("分类信息模型 ".$name." 修改成功","?part=mod&action=edit&id=".$id,"BBS.MYMPS");
			}else{
				write_msg("分类信息模型修改失败！");
			}
			
		break;
		case 'edit':
		
			$here="分类信息模型设置";
			$edit=$db->getRow("SELECT * FROM `{$db_mymps}info_typemodels` WHERE id ='$id'");
			if(!empty($edit['options'])){
				$options = explode(',',$edit['options']);
			}
			
			$sql = "SELECT optionid,title,type FROM `{$db_mymps}info_typeoptions`";
			$optgroup=$db->getAll($sql."WHERE classid = 0 ORDER BY displayorder,optionid DESC");
			
			foreach($optgroup as $k => $value){
				$opt .="<optgroup label=".$value[title].">";
				$op = $db->getAll($sql."WHERE classid != 0 AND classid = '$value[optionid]' ORDER BY displayorder,optionid DESC");
				foreach($op as $w => $y){
					$opt .="<option value=".$y[optionid].">".$y[title]."(".$y[type].")</option>";
				}
				$opt .="</optgroup>";
			}
			
			include (mymps_tpl("info_mod_edit"));
			
		break;
		case 'delall':
		
			$id = mymps_del_all("info_typemodels",$id);
			write_msg('删除分类信息模型 '.$id.' 成功', '?part=mod',"mymps");
			
		break;
		default:
		
			chk_admin_purview("purview_模型管理");
			$here="分类信息模型管理";
			$mod=$db->getAll("SELECT * FROM `{$db_mymps}info_typemodels` ORDER BY displayorder DESC");
			include (mymps_tpl("info_mod"));
			
		break;
	}
}
?>