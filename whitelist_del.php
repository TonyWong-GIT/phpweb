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

$keyword = $_GET['keyword'];
$type = $_GET['type'];
$task = $_GET['task'];
$comefrom = $_GET['comefrom'];
$title = $_GET['title'];
$url = $_GET['url'];
$number = $_GET['number'];
$time = $_GET['time'];
$source = $_GET['source'];

mysql_query('UPDATE `webpage` SET `flag_whitelist`=0 where `url`="'.$url.'" and `keyword`="'.$keyword.'" and `time`="'.$time.'" and `comefrom`="'.$comefrom.'" and `number`="'.$number.'" and `type`="'.$type.'" and `task`="'.$task.'"');
mysql_query('DELETE FROM `whitelist` where `title`="'.$title.'" and `url`="'.$url.'" and `keyword`="'.$keyword.'" and `time`="'.$time.'" and `comefrom`="'.$comefrom.'" and `number`="'.$number.'" and `type`="'.$type.'" and `task`="'.$task.'"');
//echo 'DELETE FROM `whitelist` where `title`="'.$title.'" and `url`="'.$url.'" and `keyword`="'.$keyword.'" and `time`="'.$time.'" and `comefrom`="'.$comefrom.'" and `number`="'.$number.'" and `type`="'.$type.'" and `task`="'.$task.'"';
echo '删除成功！！';
echo '点击<a href="#" onclick="window.location.href=\''.$_SERVER["HTTP_REFERER"].'\';return false;">此处</a>返回';
//echo '<meta http-equiv="refresh" content="0.8;url="'.$source.'">';
//echo '<script language="javascript">history.go(-1);</script>';


mysql_close($con);

?>




</head>
</html>
