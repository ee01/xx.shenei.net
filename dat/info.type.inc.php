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
if (!defined('IN_MYMPS'))
{
    die('FORBIDDEN');
}
$var_type = array(
	'text'=>'�ִ�',
	'textarea'=>'�༭��',
	'number'=>'����',
	'radio'=>'��ѡ',
	'checkbox'=>'��ѡ',
	'select'=>'ѡ��'
);

//user for option_edit
$mymps_admin_info_type=array(
	"text"=>'
		<tr>
		<td bgcolor="#f5fbff" width="45%" >
		<b>�ַ���󳤶�:</b>
		</td>
		<td bgcolor="#f5fbff">
		<input type="text" size="50" name="rules[text][maxlength]" value="'.$rules[text].'" >
		</td>
		</tr>
		',
	"textarea"=>'
		<tr>
		<td bgcolor="#f5fbff" width="45%" >
		<b>�ַ���󳤶�:</b>
		</td>
		<td bgcolor="#f5fbff">
		<input type="text" size="50" name="rules[textarea][maxlength]" value="'.$rules[textarea].'" >
		</td>
		</tr>
		',
	"radio"=>'
		<tr>
		<td bgcolor="#f5fbff" width="45%">
		<b>ѡ������:</b><br />ֻ����ĿΪ��ѡʱ��Ч��ÿ��һ��ѡ��Ⱥ�ǰ��Ϊѡ������(����������)������Ϊ���ݣ�����: <br /><i>1=ƻ��<br />2=�㽶<br />3=û��ˮ��</i><br />ע��: ѡ��ȷ���������޸����������ݵĶ�Ӧ��ϵ�����Կ�������ѡ����������ʾ˳�򣬿���ͨ���ƶ����е�ǰ��λ����ʵ��
		</td>
		<td bgcolor="#f5fbff">
		<textarea  rows="8" name="rules[radio][choices]" id="rules[radio][choices]" cols="50">'.$rules[radio].'</textarea>
		</td>
		</tr>
		',
	"checkbox"=>'
		<tr>
		<td bgcolor="#f5fbff" width="45%">
		<b>ѡ������:</b><br />ֻ����ĿΪ��ѡʱ��Ч��ÿ��һ��ѡ��Ⱥ�ǰ��Ϊѡ������(����������)������Ϊ���ݣ�����: <br /><i>1=ƻ��<br />2=�㽶<br />3=����</i><br />ע��: ѡ��ȷ���������޸����������ݵĶ�Ӧ��ϵ�����Կ�������ѡ����������ʾ˳�򣬿���ͨ���ƶ����е�ǰ��λ����ʵ��</td>
		<td bgcolor="#f5fbff">
		<textarea  rows="8" name="rules[checkbox][choices]" id="rules[checkbox][choices]" cols="50">'.$rules[checkbox].'</textarea>
		</td>
		</tr>
		',
	"select"=>'
		<tr>
		<td bgcolor="#f5fbff" width="25%">
		<b>ѡ������:</b><br />ֻ����ĿΪ��ѡʱ��Ч��ÿ��һ��ѡ��Ⱥ�ǰ��Ϊѡ������(����������)������Ϊ���ݣ�����: <br /><i>1= mymps������Ϣϵͳ<br />2=mymps��ҵ��վϵͳ<br />3=mympsB2B����ϵͳ</i><br /><br />ע��: ѡ��ȷ���������޸����������ݵĶ�Ӧ��ϵ�����Կ�������ѡ����������ʾ˳�򣬿���ͨ���ƶ����е�ǰ��λ����ʵ��</td>
		<td bgcolor="#f5fbff">
	<textarea rows="8" name="rules[select][choices]" id="rules[select][choices]" cols="50">'.$rules[select].'</textarea>
		</td>
		</tr>
		',
	"number"=>'
		<tr>
		<td bgcolor="#f5fbff" width="45%" >
		<b>��λ����ѡ��:</b>
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
			$mymps_rule_str = $rules[maxlength]?$rules[maxlength]."�ַ�����":"";
			$mymps .= "<input name=\"extra[".$name."]\" value=\"".$value."\"  class=\"textInt\" type=\"text\" size=\"40\"> ".$mymps_rule_str;
		break;
		case 'textarea':
			$mymps_rule_str = $rules[maxlength]?"<br />���ó���".$rules[maxlength]."���ַ�":"";
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
				$mymps .= "<li><span class=\"span_1\">".$required.$res[title]."��</span><span class=\"span_2\">".get_info_var_type($res[type],$res[identifier],$extra,$get_value)."</span></li>";
			}
		}
	}
	return $mymps;
}
?>