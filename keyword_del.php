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
//echo '++++++++++++++++++'.(isset($task) && !empty($task)).'++++++++++++==';//存在变量且变量不为空的取反，，，针对字符串
if($task == ''){  
	echo '删除失败！任务信息填写不完整';
	echo '<meta http-equiv="refresh" content="0.8;url=setup.php">';
}else if($type == '' and $keyword != ''){
	echo '删除失败！子任务信息填写不完整';
	echo '<meta http-equiv="refresh" content="0.8;url=setup.php">';
}else if($type == '' and $keyword == ''){
	$result = mysql_query('select distinct `task` from `keyword` where 1');
	$flag = 0;
	while($t = mysql_fetch_array($result)){
		if($t['task'] == $task){
			mysql_query('delete from `keyword` where `task` ="'.$task.'"');
			$flag = 1;
		}
	}
	if($flag == 0){
		echo '删除失败，任务"'.$task.'"在该类别中不存在！！';
		echo '<meta http-equiv="refresh" content="3;url=setup.php">';		
	}
	if($flag == 1){
		echo '删除成功，任务"'.$task.'"在该类别中已被删除！！';
		echo '<meta http-equiv="refresh" content="0.8;url=setup.php">';
	}
}else if($type != '' and $keyword == ''){
	$result = mysql_query('select distinct `task` from `keyword` where 1');
	$flag = 0;
	$flag_type = 0;
	while($t = mysql_fetch_array($result)){
		if($t['task'] == $task){
			$restype = mysql_query('select distinct `type` from `keyword` where `task` ="'.$task.'"');
			$flag = 1;
			while($t1 = mysql_fetch_array($restype)){
				if($t1['type'] == $type){
					mysql_query('delete from `keyword` where `task` ="'.$task.'" and `type`="'.$type.'"');
					$flag_type = 1;
				}						
			}			
		}					
	}
	if($flag_type == 1){
		echo '删除成功，任务"'.$task.'",子任务"'.$type.'"在该类别中已被删除！！';
		echo '<meta http-equiv="refresh" content="0.8;url=setup.php">';	
	}else if($flag == 1){
		echo '删除失败，子任务"'.$type.'"在该类别中不存在！！';
		echo '<meta http-equiv="refresh" content="3;url=setup.php">';
	}else if($flag == 0){
		//$flag = 1;
		echo '删除失败，任务"'.$task.'"在该类别中不存在！！';
		echo '<meta http-equiv="refresh" content="3;url=setup.php">';
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
			$keyword_test = $keyword;
			while($t1 = mysql_fetch_array($restype)){
				if($t1['type'] == $type){
					$flag_type = 1;
					while('' != strpos($keyword_test,' ')){					
						$pos = strpos($keyword_test,' ');
						$keyword_del = substr($keyword_test,0,$pos);
						mysql_query('delete from `keyword` where `task` ="'.$task.'" and `type`="'.$type.'"and `keyword`="'.$keyword_del.'"');
						$keyword_test = trim(substr($keyword_test,$pos));
						$flag_keyword = 1;
					}
					mysql_query('delete from `keyword` where `task` ="'.$task.'" and `type`="'.$type.'"and `keyword`="'.$keyword_test.'"');
					$flag_keyword = 1;
				}
			}
		}
	}
	if($flag == 0){
		echo '删除失败,任务"'.$task.'"不存在！！';
		echo '<meta http-equiv="refresh" content="0.8;url=setup.php">';
	}else if($flag_type == 0){
		echo '删除失败,子任务"'.$type.'"不存在！！';
		echo '<meta http-equiv="refresh" content="0.8;url=setup.php">';
	}else if($flag_keyword == 1){
		echo '删除成功！！';
		echo '<meta http-equiv="refresh" content="0.8;url=setup.php">';
	}
					/*$reskeyword = mysql_query('select distinct `keyword` from `keyword` where `task` ="'.$task.'" and 								           `type="'.$t1['type'].'"');
					$keyword_test = $keyword;
					while('' != strpos($keyword_test,' ')){
						$pos = strpos($keyword_test,' ');
						$keyword_insert = substr($keyword_test,0,$pos);
						while($t2 = mysql_fetch_array($reskeyword)){
							//此处删除为存在的均删除
							if($keyword_insert == $t2['keyword']){ 
								$flag_keyword = 1; 
								mysql_query('delete `keyword` from `keyword` where `task` ="'.$task.'" and `tyep`="'.$type.'" and `keyword`="'.$t2['keyword'].'"');	
								//echo '<meta http-equiv="refresh" content="0.8;url=setup.php">';		
							}	
						}		
						
					}
					if($flag_keyword == 0){
							echo '关键字"'.$keyword_insert.'"在该类别中不存在！！';
							echo '<meta http-equiv="refresh" content="3;url=setup.php">';
					}
					mysql_query('delete * from `keyword` where `task` ="'.$task.'" and `tyep`="'.$type.'"');
					$flag_type = 1;
					$flag = 1;
					echo '删除成功，任务"'.$t['task'].'",子任务"'.$t1['type'].'"在该类别中已被删除！！';
					echo '<meta http-equiv="refresh" content="0.8;url=setup.php">';
				}
				if($flag_type == 0){
					$flag = 1;
					echo '删除失败，子任务"'.$t1['type'].'"在该类别中不存在！！';
					echo '<meta http-equiv="refresh" content="3;url=setup.php">';
				}				
			}
		}
		if($flag == 0){
			echo '删除失败，任务"'.$t['task'].'"在该类别中不存在！！';
			echo '<meta http-equiv="refresh" content="3;url=setup.php">';
		}
	}
*/	
}else{
	mysql_close($con);
}



?>




</head>
</html>
