<?php
header("content-type:text/vnd.wap.wml");
echo("<?xml version=\"1.0\" encoding=\"UTF-8\"?> \n");
echo("<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/DTD/wml_1.1.xml\">\n");

define('IN_PHPMPS', true);
require 'wap.php';
$cats  = get_cat_list();//分类数组
?>
<wml>
	<card id="MainCard" title="舍内知天下 - 分类信息平台">
    <p>舍内分类信息手机版,掌上知天下.</p>
    <p></p>
    <p>选择分类浏览信息</p>
    <?php
      foreach($cats AS $cat)
      {
    echo "<p><a href='$cat[caturl]'>$cat[catname]</a> </p>";// --> <a href='post.php?id=$cat[catid]'>我要发布</a></p>";
	  }
	  ?>
        <p></p>
        <p><a href="http://u.shenei.net/wap" target=_blank>一起进舍内家园交流去~</a></p>
	</card>
</wml>
