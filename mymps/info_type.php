<?php
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * ��Ȩ���� mymps�з��Ŷӣ���������Ȩ����
 * ��վ��ַ: http://www.mymps.com.cn��
 * ������̳��http://bbs.mymps.com.cn��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�á�
 * ������Դ������޸ĺ����κ���ʽ�κ�Ŀ�ĵ��ٷ������������ǽ�׷�����ķ�������!!
 * ============================================================================
 * �������: ���ƽ���峤��
 * ��ϵ��ʽ��QQ:3037821 MSN:business@live.it
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

	chk_admin_purview("purview_ѡ�����");
	require_once(MYMPS_DATA."/info.type.inc.php");
	
	$classid = $classid ? $classid : '1' ;
	$here = '��Ϣ����ѡ��';
	$options=$db->getAll("SELECT * FROM `{$db_mymps}info_typeoptions` WHERE classid ='0' ORDER BY optionid DESC");
	$detail = $db->getRow("SELECT title,optionid FROM `{$db_mymps}info_typeoptions` WHERE optionid ='$classid'");
	$option=$db->getAll("SELECT * FROM `{$db_mymps}info_typeoptions` WHERE classid ='$classid' ORDER BY displayorder DESC");
	include (mymps_tpl("info_option"));
	
}elseif($part == 'option_add'){

	if(empty($title)||empty($identifier)||empty($type)||empty($classid)){
		write_msg("����д���������Ϣ��");
		exit();
	}
	
	if(mymps_count("info_typeoptions","WHERE identifier = '$identifier'")>0){write_msg("������".$identifer."�Ѵ��ڣ��뻻һ��������");exit();}
	$res = $db->query("INSERT INTO `{$db_mymps}info_typeoptions` (title,identifier,type,displayorder,classid)VALUES('$title','$identifier','$type','$displayorder','$classid')");
	
	if($res){
		write_msg("������Ϣѡ��".$id."���ӳɹ���","info_type.php?classid=".$classid,"mymps.com.cn");
	}else{
		write_msg("������Ϣѡ��".$id."����ʧ�ܣ�");
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
				write_msg("��Ϣѡ�� <b>".$title."</b> �����޸ĳɹ���","?part=option_edit&optionid=".$optionid,"MyMps");
			}else{
				write_msg("��Ϣѡ��".$title."�����޸�ʧ�ܣ�","","MyMps");
				exit();
			}
			
		break;
		default:
		
			$options=$db->getAll("SELECT * FROM `{$db_mymps}info_typeoptions` WHERE classid ='0' ORDER BY optionid DESC");
			$edit=$db->getRow("SELECT * FROM `{$db_mymps}info_typeoptions` WHERE optionid ='$optionid'");
			$here="����ѡ��";
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
	write_msg('��Ϣģ��ѡ��ɾ���ɹ���','?classid='.$class_id,'record');
	
}elseif($part == 'option_type'){

	switch($action){
		case 'insert':
		
			$title = trim($_POST['title']);
			if(empty($title)){write_msg("����д��������");exit();}
			$mymps_in=$db->query("INSERT `{$db_mymps}info_typeoptions` (title,classid) VALUES ('$title','0')");
			if($mymps_in){
				write_msg("��Ϣģ�ͷ���".$title."��ӳɹ���","?part=option_type","MYMPS.COM.CN");
			}else{
				write_msg("��Ϣģ�ͷ������ʧ�ܣ�");
			}
			
		break;
		case 'update':

			if(empty($title)){write_msg("����д��������");exit();}
			$mymps_rs=$db->query("UPDATE `{$db_mymps}info_typeoptions` SET title = '$title' WHERE optionid = '$id'");
			
			if($mymps_rs){
				write_msg("��Ϣģ�ͷ���".$title."�޸ĳɹ���","?part=option_type","MYMPS.COM.CN");
			}else{
				write_msg("��Ϣģ�ͷ��ౣ��ʧ�ܣ�");
			}
			
		break;
		case 'del':

			if(empty($id)){write_msg("����û��ѡ�����");exit();}
			$mymps_del = mymps_delete("info_typeoptions","WHERE optionid = '$id'");
			if($mymps_del){
				write_msg("��Ϣģ�ͷ���".$id."ɾ���ɹ���","?part=option_type","WWW.MYMPS.COM.CN");	
			}else{
				write_msg("����ʧ�ܣ�");
			}
			
		break;
		default:
		
			$here = "ѡ��������";
			$sql = "SELECT optionid,classid,title FROM `{$db_mymps}info_typeoptions` WHERE classid = 0";
			$type= $db->getAll($sql);
			include (mymps_tpl("info_option_type"));
			
		break;
	}
	
}elseif($part == 'mod'){

	switch ($action){
		case 'insert':

			if(empty($name)){write_msg("����д��Ϣģ�͵�����");exit();}
			$displayorder = trim($_POST['displayorder'])?trim($_POST['displayorder']):'0';

			$sql = "INSERT `{$db_mymps}info_typemodels` (id,name,type,displayorder) VALUES ('','$name','0','$displayorder')";
			
			if($db->query($sql)){
				write_msg("��Ϣģ�� ".$name." ���ӳɹ���","?part=mod","MyMPS.Com.cn");
			}else{
				write_msg("��Ϣģ�� ".$name." ����ʧ�ܣ�");
			}
			
		break;
		case 'update':

			if(empty($name)){write_msg("��������Ϣģ�͵����ƣ�");exit();}
			$post_opt = !empty($_POST['options']) ? implode (',', $_POST['options']) : '';
			if($db->query("UPDATE `{$db_mymps}info_typemodels` SET name='$name',displayorder='$displayorder',options='$post_opt' WHERE id = '$id'")){
				write_msg("������Ϣģ�� ".$name." �޸ĳɹ�","?part=mod&action=edit&id=".$id,"BBS.MYMPS");
			}else{
				write_msg("������Ϣģ���޸�ʧ�ܣ�");
			}
			
		break;
		case 'edit':
		
			$here="������Ϣģ������";
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
			write_msg('ɾ��������Ϣģ�� '.$id.' �ɹ�', '?part=mod',"mymps");
			
		break;
		default:
		
			chk_admin_purview("purview_ģ�͹���");
			$here="������Ϣģ�͹���";
			$mod=$db->getAll("SELECT * FROM `{$db_mymps}info_typemodels` ORDER BY displayorder DESC");
			include (mymps_tpl("info_mod"));
			
		break;
	}
}
?>