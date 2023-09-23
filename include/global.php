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
if (!defined('IN_MYMPS')){
    die('FORBIDDEN');
}

error_reporting(E_ALL ^ E_NOTICE);
set_magic_quotes_runtime(0);

$mtime = explode(' ', microtime());
$mymps_starttime = $mtime[1] + $mtime[0];

@ini_set('memory_limit',          '640M');
@ini_set('session.cache_expire',  180);
@ini_set('session.use_trans_sid', 0);
@ini_set('session.use_cookies',   1);
@ini_set('session.auto_start',    0);
@ini_set('display_errors',        1);

if(function_exists('date_default_timezone_set')){date_default_timezone_set('Hongkong');}

if(PHP_VERSION < '4.1.0') {
	$_GET = &$HTTP_GET_VARS;
	$_POST = &$HTTP_POST_VARS;
	$_COOKIE = &$HTTP_COOKIE_VARS;
	$_SERVER = &$HTTP_SERVER_VARS;
	$_ENV = &$HTTP_ENV_VARS;
	$_FILES = &$HTTP_POST_FILES;
}

foreach(array('_COOKIE', '_POST', '_GET') as $_request) {
	foreach($$_request as $_key => $_value) {
		$_key{0} != '_' && $$_key = my_addslashes($_value);
	}
}

if (__FILE__ == ''){
    die('Fatal error code: 0');
}

if (DIRECTORY_SEPARATOR == '\\'){
    @ini_set('include_path', '.;' . ROOT_PATH);
}else{
    @ini_set('include_path', '.:' . ROOT_PATH);
}

if (defined('DEBUG_MODE') == false){
    define('DEBUG_MODE', 0);
}

$php_self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
if ('/' == substr($php_self, -1)){
    $php_self .= 'index.php';
}

//filter some dangerous strings
function my_addslashes($string, $force = 0) {
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = my_addslashes($val, $force);
			}
		} else {
			$string = addslashes($string);
		}
	}
	return $string;
}

require_once(MYMPS_INC."/class.fun.php");
require_once(MYMPS_INC."/safe.fun.php");
require_once(MYMPS_INC."/version.php");
require_once(MYMPS_DATA."/config.inc.php");
require_once(MYMPS_INC."/global.fun.php");
require_once(MYMPS_INC."/check.fun.php");

//set the path of session
session_save_path($mymps_mymps['cfg_session_dir']);
?>