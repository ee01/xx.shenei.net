<?php

$charset    = "gb2312";

//系统字符集编码

$db_charset = "gbk";

//数据库字符集编码

$db_host    = "localhost";

//数据库服务器地址，一般为localhost

$db_name    = "shenebwx_xx";

//使用的数据库名称

$db_user    = "shenebwx_xx";

//数据库帐号

$db_pass    = urldecode("%78%78%40%53%68%65%4E%65%69%30%31");

//数据库密码

$db_mymps   = "my_";

//数据库前缀

$db_intype  = "2";

$db_mixcode = "57ose6sejbq";

//cookies加密前缀


/*UC整合部分*/
define('UC_CONNECT', 'mysql');
define('UC_DBHOST', 'localhost');
define('UC_DBUSER', 'shenebwx_uc');
define('UC_DBPW', urldecode("%75%63%40%53%68%65%4E%65%69%30%31"));
define('UC_DBNAME', 'shenebwx_uc');
define('UC_DBCHARSET', 'gbk');
define('UC_DBTABLEPRE', '`' . UC_DBNAME . '`.uc_');
define('UC_DBCONNECT', '0');
define('UC_KEY', 'sheneifengleixinxi');
define('UC_API', 'http://uc.eexx.me/');
define('UC_CHARSET', 'gbk');
define('UC_IP', '');
define('UC_APPID', '4');
define('UC_PPP', '20');

$dbhost = $db_host; // 数据库服务器
$dbuser = $db_user; // 数据库用户名
$dbpw = $db_pass; // 数据库密码
$dbname = $db_name; // 数据库名
$pconnect = 0; // 数据库持久连接 0=关闭, 1=打开
$tablepre = $db_mymps; // 表名前缀, 同一数据库安装多个论坛请修改此处
$dbcharset = $db_charset; // MySQL 字符集, 可选 'gbk', 'big5', 'utf8', 'latin1', 留空为按照论坛字符集设定

//同步登录 Cookie 设置
$cookiedomain = ''; // cookie 作用域
$cookiepath = '/'; // cookie 作用路径
?>