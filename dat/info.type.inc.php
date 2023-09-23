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
$var_type = array(
	'text'=>'字串',
	'textarea'=>'编辑框',
	'number'=>'数字',
	'radio'=>'单选',
	'checkbox'=>'多选',
	'select'=>'选择'
);

//user for option_edit
$mymps_admin_info_type=array(
	"text"=>'
		<tr>
		<td bgcolor="#f5fbff" width="45%" >
		<b>字符最大长度:</b>
		</td>
		<td bgcolor="#f5fbff">
		<input type="text" size="50" name="rules[text][maxlength]" value="'.$rules[text].'" >
		</td>
		</tr>
		',
	"textarea"=>'
		<tr>
		<td bgcolor="#f5fbff" width="45%" >
		<b>字符最大长度:</b>
		</td>
		<td bgcolor="#f5fbff">
		<input type="text" size="50" name="rules[textarea][maxlength]" value="'.$rules[textarea].'" >
		</td>
		</tr>
		',
	"radio"=>'
		<tr>
		<td bgcolor="#f5fbff" width="45%">
		<b>选项内容:</b><br />只在项目为可选时有效，每行一个选项，等号前面为选项索引(建议用数字)，后面为内容，例如: <br /><i>1=苹果<br />2=香蕉<br />3=没有水果</i><br />注意: 选项确定后请勿修改索引和内容的对应关系，但仍可以新增选项。如需调换显示顺序，可以通过移动整行的前后位置来实现
		</td>
		<td bgcolor="#f5fbff">
		<textarea  rows="8" name="rules[radio][choices]" id="rules[radio][choices]" cols="50">'.$rules[radio].'</textarea>
		</td>
		</tr>
		',
	"checkbox"=>'
		<tr>
		<td bgcolor="#f5fbff" width="45%">
		<b>选项内容:</b><br />只在项目为可选时有效，每行一个选项，等号前面为选项索引(建议用数字)，后面为内容，例如: <br /><i>1=苹果<br />2=香蕉<br />3=菠萝</i><br />注意: 选项确定后请勿修改索引和内容的对应关系，但仍可以新增选项。如需调换显示顺序，可以通过移动整行的前后位置来实现</td>
		<td bgcolor="#f5fbff">
		<textarea  rows="8" name="rules[checkbox][choices]" id="rules[checkbox][choices]" cols="50">'.$rules[checkbox].'</textarea>
		</td>
		</tr>
		',
	"select"=>'
		<tr>
		<td bgcolor="#f5fbff" width="25%">
		<b>选项内容:</b><br />只在项目为可选时有效，每行一个选项，等号前面为选项索引(建议用数字)，后面为内容，例如: <br /><i>1= mymps分类信息系统<br />2=mymps企业建站系统<br />3=mympsB2B商务系统</i><br /><br />注意: 选项确定后请勿修改索引和内容的对应关系，但仍可以新增选项。如需调换显示顺序，可以通过移动整行的前后位置来实现</td>
		<td bgcolor="#f5fbff">
	<textarea rows="8" name="rules[select][choices]" id="rules[select][choices]" cols="50">'.$rules[select].'</textarea>
		</td>
		</tr>
		',
	"number"=>'
		<tr>
		<td bgcolor="#f5fbff" width="45%" >
		<b>单位（可选）:</b>
		</td>
		<td bgcolor="#f5fbff">
		<input type="text" size="50" name="rules[number][units]" value="'.$rules[number].'" >
		</td>
		</tr>
		'
		
);

function get_info_var_type($type,$name="",$rules="",$value=""){
	switch($type){
		case 'text':
			$mymps_rule_str = $rules[maxlength]?$rules[maxlength]."字符以内":"";
			$mymps .= "<input name=\"extra[".$name."]\" value=\"".$value."\"  class=\"textInt\" type=\"text\" size=\"40\"> ".$mymps_rule_str;
		break;
		case 'textarea':
			$mymps_rule_str = $rules[maxlength]?"<br />不得超过".$rules[maxlength]."个字符":"";
			$mymps = "<textarea name=\"extra[".$name."]\"  cols=\"100\" rows=\"10\">".$value."</textarea> ".$mymps_rule_str;
		break;
		case 'radio':
			foreach($rules as $k => $v){
				$mymps .= "<label for=\"".$name.$k."\"><input id=\"".$name.$k."\" name=\"extra[".$name."]\" type=\"radio\" value=\"".$k."\"";
				$mymps .= ($k == $value)?"checked":"";
				$mymps .= ">".$v."</label> ";
			}
		break;
		case 'checkbox':
			$new_value = explode(",",$value);
			foreach($rules as $k => $v){
				$mymps .= "<label for=\"".$name.$k."\" style=\"margin:0 10px 0 0;\"><input id=\"".$name.$k."\" name=\"extra[".$name."][]\" type=\"checkbox\" value=\"".$k."\"";
				$mymps .= in_array($k,$new_value)?"checked":"";
				$mymps .=">".$v."</label>";
			}
		break;
		case 'select':
			$mymps .= "<select name=\"extra[".$name."]\">";
			foreach($rules as $k => $v){
				$mymps .= "<option value=\"".$k."\"";
				$mymps .= ($k == $value)?"selected style=\"background-color:#6eb00c;color:white\"":"";
				$mymps .=">".$v."</option> ";
			}
			$mymps .= "</select>";
		break;
		case 'number':
			$mymps .= "<input name=\"extra[".$name."]\" value=\"".$value."\"  class=\"textInt\" type=\"text\" style=\"width:50px\"> ".$rules[units];
		break;
	}
	return $mymps;
}
//get the category options when add and edit the information 
function get_category_info_options($modid = '',$edit_id = ''){
	global $db,$db_mymps,$charset;
	$row = $db->getRow("SELECT id,options FROM `{$db_mymps}info_typemodels` WHERE id = '$modid'");
	$option=explode(",",$row[options]);
	foreach($option as $w=>$u){
		$res = $db->getRow("SELECT title,identifier,type,rules,required FROM `{$db_mymps}info_typeoptions` WHERE optionid='$u'");
		$required	= ($res[required]=='on')? '<font color=red>*</font>'	: '';
		$extra		= ($charset == 'utf-8')	? utf8_unserialize($res[rules])	: unserialize($res[rules]);
		if(is_array($extra)){
			foreach($extra as $k => $value){
				if(!empty($edit_id)){
					$get = $db -> getRow("SELECT name,value FROM `{$db_mymps}info_extra` WHERE name = '$res[identifier]' AND infoid = '$edit_id'");
					$get_value = $get[value];
				}
				if($res[type] == 'radio' || $res[type] == 'select' || $res[type] == 'checkbox'){
					$extra = arraychange($value);
				}
				$mymps .= "<li><span class=\"span_1\">".$required.$res[title]."：</span><span class=\"span_2\">".get_info_var_type($res[type],$res[identifier],$extra,$get_value)."</span></li>";
			}
		}
	}
	return $mymps;
}
?>