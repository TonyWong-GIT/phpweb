<html lang="zh-CN">
  <head>
    <meta charset="utf-8">

<?php
$con = mysql_connect("localhost","root","1234");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("inet", $con);
mysql_query("set names utf8");     //php解决中文显示,,,,,,important!!!!!!!!!!!!!!!!!!!!

$keyword = trim($_GET['keyword']);
$type = trim($_GET['type']);
$task = trim($_GET['task']);
$comefrom = trim($_GET['comefrom']);
$title = trim($_GET['title']);
$url = trim($_GET['url']);
$number = $_GET['number'];
$time = trim($_GET['time']);
$source = trim($_GET['source']);

mysql_query('UPDATE `appear` SET `flag_whitelist`=0 where `url`="'.$url.'" and `keyword`=\''.$keyword.'\' and `time`="'.$time.'" and `comefrom`="'.$comefrom.'" and `number`="'.$number.'" and `type`="'.$type.'" and `task`="'.$task.'"');
mysql_query('UPDATE `specialurl` SET `flag_whitelist`=0 where `url`="'.$url.'" and `keyword`=\''.$keyword.'\' and `time`="'.$time.'" and `comefrom`="'.$comefrom.'" and `number`="'.$number.'" and `type`="'.$type.'" and `task`="'.$task.'"');
mysql_query('UPDATE `disappear` SET `flag_whitelist`=0 where `url`="'.$url.'" and `keyword`=\''.$keyword.'\' and `time`="'.$time.'" and `comefrom`="'.$comefrom.'" and `number`="'.$number.'" and `type`="'.$type.'" and `task`="'.$task.'"');
mysql_query('UPDATE `webpage` SET `flag_whitelist`=0 where `url`="'.$url.'" and `keyword`=\''.$keyword.'\' and `time`="'.$time.'" and `comefrom`="'.$comefrom.'" and `number`="'.$number.'" and `type`="'.$type.'" and `task`="'.$task.'"');
mysql_query('DELETE FROM `whitelist` where `title`="'.$title.'" and `url`="'.$url.'" and `keyword`=\''.$keyword.'\' and `time`="'.$time.'" and `comefrom`="'.$comefrom.'" and `number`="'.$number.'" and `type`="'.$type.'" and `task`="'.$task.'"');
//echo 'DELETE FROM `whitelist` where `title`="'.$title.'" and `url`="'.$url.'" and `keyword`="'.$keyword.'" and `time`="'.$time.'" and `comefrom`="'.$comefrom.'" and `number`="'.$number.'" and `type`="'.$type.'" and `task`="'.$task.'"';

//echo '点击<a href="#" onclick="window.location.href=\''.$_SERVER["HTTP_REFERER"].'\';return false;">此处</a>返回';
//echo '<meta http-equiv="refresh" content="0.8;url="'.$source.'">';
echo '<script language="javascript">location.href=\''.$_SERVER["HTTP_REFERER"].'\';</script>';


mysql_close($con);

?>




</head>
</html>
