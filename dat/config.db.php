<?php

$charset    = "gb2312";

//ϵͳ�ַ�������

$db_charset = "gbk";

//���ݿ��ַ�������

$db_host    = "localhost";

//���ݿ��������ַ��һ��Ϊlocalhost

$db_name    = "shenebwx_xx";

//ʹ�õ����ݿ�����

$db_user    = "shenebwx_xx";

//���ݿ��ʺ�

$db_pass    = urldecode("%78%78%40%53%68%65%4E%65%69%30%31");

//���ݿ�����

$db_mymps   = "my_";

//���ݿ�ǰ׺

$db_intype  = "2";

$db_mixcode = "57ose6sejbq";

//cookies����ǰ׺


/*UC���ϲ���*/
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

$dbhost = $db_host; // ���ݿ������
$dbuser = $db_user; // ���ݿ��û���
$dbpw = $db_pass; // ���ݿ�����
$dbname = $db_name; // ���ݿ���
$pconnect = 0; // ���ݿ�־����� 0=�ر�, 1=��
$tablepre = $db_mymps; // ����ǰ׺, ͬһ���ݿⰲװ�����̳���޸Ĵ˴�
$dbcharset = $db_charset; // MySQL �ַ���, ��ѡ 'gbk', 'big5', 'utf8', 'latin1', ����Ϊ������̳�ַ����趨

//ͬ����¼ Cookie ����
$cookiedomain = ''; // cookie ������
$cookiepath = '/'; // cookie ����·��
?>