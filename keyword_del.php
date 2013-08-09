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
//echo '++++++++++++++++++'.(isset($task) && !empty($task)).'++++++++++++==';//存在变量且变量不为空的取反，，，针对字符串
if($task == ''){  
	echo '删除失败！任务信息填写不完整';
	echo '<meta http-equiv="refresh" content="1;url=setup.php">';
}else if($type == '' and $keyword != ''){
	echo '删除失败！子任务信息填写不完整';
	echo '<meta http-equiv="refresh" content="1;url=setup.php">';
}else if($type == '' and $keyword == ''){
	$i = 0;
	$result = mysql_query('select distinct `task` from `keyword` where 1');
	while($t = mysql_fetch_array($result)){
		if($t['task'] == $task){
			$i = 1;
			mysql_query('delete from `keyword` where `task` ="'.trim($task).'"');
			mysql_query('delete from `specialurl` where `task` ="'.trim($task).'"');
			mysql_query('delete from `timerecord` where `task` ="'.trim($task).'"');
			mysql_query('delete from `appear` where `task` ="'.trim($task).'"');
			mysql_query('delete from `disappear` where `task` ="'.trim($task).'"');
			mysql_query('delete from `whitelist` where `task` ="'.trim($task).'"');
			mysql_query('delete from `webpage` where `task` ="'.trim($task).'"');
			echo '删除成功！！任务"'.$task.'"已删除！！！';
			echo '<meta http-equiv="refresh" content="3;url=setup.php">';
		}
	}
	if($i == 0){
		echo '删除失败！任务"'.$task.'"不存在！！！';
		echo '<meta http-equiv="refresh" content="1;url=setup.php">';
	}		
}else if($type != '' and $keyword == ''){
	$i = 0;
	$t = 0;
	$result = mysql_query('select distinct `task` from `keyword` where 1');
	while($t = mysql_fetch_array($result)){
		if($t['task'] == $task){
			$i = 1;
			mysql_query('select distinct `type` from `keyword` where `task` ="'.$task.'"');
			while($t1 = mysql_fetch_array($result)){
				if($t1['type'] == $type){
					$t = 1;
					mysql_query('delete from `keyword` where `task` ="'.$task.'" and `type`="'.$type.'"');
					mysql_query('delete from `specialurl` where `task` ="'.$task.'" and `type`="'.$type.'"');
					mysql_query('delete from `timerecord` where `task` ="'.$task.'" and `type`="'.$type.'"');
					mysql_query('delete from `appear` where `task` ="'.$task.'" and `type`="'.$type.'"');
					mysql_query('delete from `disappear` where `task` ="'.$task.'" and `type`="'.$type.'"');
					mysql_query('delete from `whitelist` where `task` ="'.$task.'" and `type`="'.$type.'"');
					mysql_query('delete from `webpage` where `task` ="'.$task.'" and `type`="'.$type.'"');
					echo '删除成功！！子任务"'.$type.'",任务"'.$task.'"已删除！！！';
					echo '<meta http-equiv="refresh" content="1;url=setup.php">';
				}
			}
		}
	}
	if($t == 0){
		echo '删除失败！子任务"'.$type.'"不在任务"'.$task.'"中！！！';
		echo '<meta http-equiv="refresh" content="1;url=setup.php">';
	}
	if($i == 0){
		echo '删除失败！任务"'.$task.'"不存在！！！';
		echo '<meta http-equiv="refresh" content="1;url=setup.php">';
	}
}else if($type != '' and $keyword != ''){
	$result = mysql_query('select distinct `task` from `keyword` where 1');
	$flag = 0;
	$flag_type = 0;
	$flag_keyword = 0;
	while($t = mysql_fetch_array($result)){
		if($t['task'] == $task){
			$restype = mysql_query('select distinct `type` from `keyword` where `task` ="'.$task.'"');
			$flag = 1;
			while($t1 = mysql_fetch_array($restype)){
				if($t1['type'] == $type){
					$flag_type = 1;
					$reskeyword = mysql_query('select distinct `keyword` from `keyword` where `task` ="'.$task.'" and `type`="'.$type.'"');
					while($t2 = mysql_fetch_array($reskeyword)){
						if($t2['keyword'] == $keyword){
							$flag_keyword = 1;
							mysql_query('delete from `keyword` where `task` ="'.$task.'" and `type`="'.$type.'"and `keyword`=\''.$keyword.'\'');
							echo '删除成功！！';
							echo '<meta http-equiv="refresh" content="0.8;url=setup.php">';
						}
					}
				}
			}
		}
	}
	if($flag == 0){
		echo '删除失败,任务"'.$task.'"不存在！！';
		echo '<meta http-equiv="refresh" content="2;url=setup.php">';
	}else if($flag_type == 0){
		echo '删除失败,子任务"'.$type.'"不存在！！';
		echo '<meta http-equiv="refresh" content="2;url=setup.php">';
	}else if($flag_keyword == 0){
		echo '删除失败,关键字"'.$keyword.'"不存在！！';
		echo '<meta http-equiv="refresh" content="2;url=setup.php">';
	}
}else{
	mysql_close($con);
}



?>




</head>
</html>
