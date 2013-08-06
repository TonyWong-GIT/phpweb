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

if($keyword == '' || $task == '' || $type == ''){
	echo '添加失败！信息填写不完整';
	echo '<meta http-equiv="refresh" content="0.8;url=setup.php">';
}else{

	$result = mysql_query('select distinct `keyword` from `keyword` where `type`="'.$type.'" and `task`="'.$task.'"');
	//取回结果数据，使用了mysql_fetch_row()
	$i = 0;
	//echo mysql_fetch_array($result) or die(mysql_error()); //null
	while($t = mysql_fetch_array($result))
	{	
		$keyword_test1 = $keyword;
		//echo strpos($keyword_test1,' ');
		while('' != strpos($keyword_test1,' ')){
			$pos = strpos($keyword_test1,' ');
			$keyword_insert1 = substr($keyword_test1,0,$pos);
			$keyword_test1 = trim(substr($keyword_test1,$pos));
			if($keyword_insert1 == $t['keyword'] || $keyword_test1 == $t['keyword'] )
			{
				//mysql_query('DELETE FROM social WHERE keyword = "'.$keyword.'"');
				//mysql_query('INSERT INTO social (keyword, type) VALUES ("'.$keyword.'", "'.$type.'")');
				echo '添加失败，关键字"'.$t['keyword'].'"在该类别中已经存在！！';
				echo '<meta http-equiv="refresh" content="3;url=setup.php">';
				$i = 1;	
				mysql_close($con);
			}
		}
		if($t['keyword'] == $keyword_test1){
			echo '添加失败，关键字"'.$t['keyword'].'"在该类别中已经存在！！';
			$i = 1;	
			echo '<meta http-equiv="refresh" content="3;url=setup.php">';
		}
	}
	if($i == 0)
	{
		$keyword_test = $keyword;
		//此处为字符转操作
		while('' != strpos($keyword_test,' ')){
			$pos = strpos($keyword_test,' ');
			$keyword_insert = substr($keyword_test,0,$pos);
			mysql_query('INSERT INTO keyword(`keyword`,`type`,`task`) VALUES ("'.$keyword_insert.'", "'.$type.'","'.$task.'")');	
			//echo '---INSERT INTO keyword(`keyword`,`type`,`task`) VALUES ("'.$keyword_insert.'", "'.$type.'","'.$task.'")----';
			$keyword_test = trim(substr($keyword_test,$pos));
		}
		//echo '----INSERT INTO keyword(`keyword`,`type`,`task`) VALUES ("'.$keyword_test.'", "'.$type.'","'.$task.'")';
		mysql_query('INSERT INTO keyword(`keyword`,`type`,`task`) VALUES ("'.$keyword_test.'", "'.$type.'","'.$task.'")');
		echo '<script language="javascript">location.href=\''.$_SERVER["HTTP_REFERER"].'\';</script>';
		mysql_close($con);
	}
}
?>




</head>
</html>
