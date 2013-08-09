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

if($keyword == '' || $task == '' || $type == ''){
	echo '添加失败！信息填写不完整';
	echo '<meta http-equiv="refresh" content="0.8;url=setup.php">';
}else{

	$result = mysql_query('select distinct `type` from `keyword` where `task`="'.trim($task).'"');
	//取回结果数据，使用了mysql_fetch_row()
	$i = 0;
	//echo mysql_fetch_array($result) or die(mysql_error()); //null
	while($t = mysql_fetch_array($result))
	{	
		if($t['type'] == $type){
			echo '添加失败，任务"'.$task.'",子任务"'.$type.'"在该类别中已经存在！！';
			echo '<meta http-equiv="refresh" content="3;url=setup.php">';
			$i = 1;	
			mysql_close($con);
		}
	}
	if($i == 0)
	{
		//strtr($keyword,'"','“');
		mysql_query('INSERT INTO keyword(`keyword`,`type`,`task`) VALUES (\''.trim($keyword).'\', \''.trim($type).'\',\''.trim($task).'\')');
		echo $keyword.'添加成功！！！';
		echo '<script language="javascript">location.href=\''.$_SERVER["HTTP_REFERER"].'\';</script>';
		mysql_close($con);
	}
}
?>




</head>
</html>
