<?php
$con = mysql_connect("localhost","root","1234");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

  mysql_select_db("inet", $con);
  mysql_query("set names utf8");     //php解决中文显示??????
  $resKeywordType = mysql_query('SELECT distinct `task` FROM `keyword` ');

  echo '<div region="west" border="true" split="true" title="任务列表" class="cs-west">';
  echo '<div class="easyui-accordion" fit="true" border="false">';
  $flag = 0;
  while($tKeywordType = mysql_fetch_array($resKeywordType))
  {
       $result = mysql_query("SELECT distinct `type` FROM `keyword` where `task`='".$tKeywordType[0]."'");
       //取回结果数据，使用了mysql_fetch_row()
	echo '<div title="'.$tKeywordType['task'];
	if ($flag == 0){
		echo '" selected="true">';
	}else{
		echo '">';	
	}
	$flag += 1;
	while($t = mysql_fetch_array($result))
	{	
		echo '<a href="javascript:void(0);" src=\'keyword.php?task='.$tKeywordType[0].'&type='.$t['type'].'\' class="cs-navi-tab">'.$t['type'].'</a><p></p>';//javascript:void(0) 仅仅表示一个死链接
					
	}
	echo '</div>';

  }

  echo '</div>';
  echo '</div>';



?>



