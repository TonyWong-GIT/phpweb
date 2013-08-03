<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
<html lang="zh-CN">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<title>與情</title>
<link rel="stylesheet" type="text/css" href="css/easyui.css" />
<link rel="stylesheet" type="text/css" href="css/page.css" />
<link rel="stylesheet" type="text/css" href="css/icon.css" />

<script type="text/javascript"  language="JavaScript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript"  language="JavaScript" src="js/jquery.easyui.min.js"></script>
<script type="text/javascript"  language="JavaScript" src="js/page.js"></script>
<script>
function showChart_special(data_url, comefrom, task, type, keyword, startdate, enddate)
{
	//window.open("chart/chart.php?data_url="+data_url+" &comefrom="+comefrom);
	window.open("chart/specialchart.php?data_url="+data_url+" &comefrom="+comefrom+" &task="+task+" &type="+type+" &keyword="+keyword+" &startdate="+startdate+" &enddate="+enddate+"\"",'url_window','height=500,width=800,top=150,left=300,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
}
</script>
<?php
  
  if($_GET['special_flag']!=''){
	  $special_flag = 1;
  }else{
	  $special_flag = 0;
  }

  if($_GET['form_page']!=''){
	  $form_page = $_GET['form_page'];
  }else{
	  $form_page = 20;
  }
  if($_GET['form_number']!=''){
	  $form_number = $_GET['form_number'];
  }else{
	  $form_number = 0;
  }
  $form_page_number = $form_number+$form_page;

  if($_GET['form_task']!='' and $_GET['form_type']!=''){
	  $task = $_GET['form_task'];
	  $type = $_GET['form_type'];
  }else{
	  $task = $_GET['task'];
	  $type = $_GET['type'];
	  //$task = substr(substr($task,1),0,strlen($task)-2);
	  //$type = substr(substr($type,1),0,strlen($type)-2);//去掉传递过来的字符串的左右"",有时候会出现此问题，不清楚是什么问题
  }

  $form_keyword = $_GET['keyword'];
  $form_start_date = $_GET['start_date'];
  $form_end_date = $_GET['end_date'];
  $form_source = $_GET['source'];


//使用时需将此处注释去掉
/*
  if($special_flag == 1){
  	//此处用于特殊的URL的入库
	$con = mysql_connect("localhost","root","1234");
	  if (!$con)
	  {
	  	die('Could not connect: ' . mysql_error());
	  }

	  mysql_select_db("inet", $con);
	  mysql_query("set names utf8");     //php解决中文显示??????
  
	$flag_F = 0;
	$comefrom_array = array('baidu','sousou','google_hk');
	$sql_keyword = mysql_query('select distinct `keyword` from `timerecord` where `task`="'.$task.'" and `type`="'.$type.'"and `time`>="'.(date('Ymd')-7).'"and `time` <= "'.date('Ymd').'"');
	//echo 'select distinct `keyword` from `timerecord` where `task`="'.$task.'" and `type`="'.$type.'"';
	while($row_keyword = mysql_fetch_array($sql_keyword)){
	//echo '============'.$row_keyword[0];//nba
		foreach($comefrom_array as $row_comefrom){
			$res_specialurl = mysql_query('select count(*) from `timerecord` where `keyword`="'.$row_keyword[0].'" and `comefrom`="'.$row_comefrom.'" and `task`="'.$task.'" and `type`="'.$type.'"and `time`>="'.(date('Ymd')-7).'"and `time` <= "'.date('Ymd').'"');

	  		$row_specialurl=mysql_fetch_array($res_specialurl);
			$alreadyCount=$row_specialurl[0];//$alreadyCount是已经下载的次数.
			//echo $alreadyCount;//8,0,0
			$count_specialurl = mysql_query('select distinct `url` from `webpage` where `keyword`="'.$row_keyword[0].'" and `comefrom`="'.$row_comefrom.'" and `task`="'.$task.'" and `type`="'.$type.'"and `time`>="'.(date('Ymd')-7).'"and `time` <= "'.date('Ymd').'"');
	
			while($row = mysql_fetch_array($count_specialurl))
			{

				$result_count=mysql_query('select count(*) from `webpage` where `keyword`="'.$row_keyword[0].'" and `comefrom`="'.$row_comefrom.'" and `url`="'.$row['url'].'" and `task`="'.$task.'" and `type`="'.$type.'"and `time`>="'.(date('Ymd')-7).'"and `time` <= "'.date('Ymd').'"');
				//echo 'select count(*) from `webpage` where `keyword`="'.$row_keyword[0].'" and `comefrom`="'.$row_comefrom.'" and `url`="'.$row['url'].'" and `task`="'.$task.'" and `type`="'.$type.'"';//3
				$row2=mysql_fetch_array($result_count);	
		
				if($alreadyCount!=$row2[0])
				{
					//echo $row2[0].'------';//5
					$result_info=mysql_query('select * from `webpage` where `keyword`="'.$row_keyword[0].'" and `comefrom`="'.$row_comefrom.'" and `url`="'.$row['url'].'" and `task`="'.$task.'" and `type`="'.$type.'"and `time`>="'.(date('Ymd')-7).'"and `time` <= "'.date('Ymd').'"');
					//echo 'select * from `webpage` where `keyword`="'.$row_keyword[0].'" and `comefrom`="'.$row_comefrom.'" and `url`="'.$row['url'].'" and `task`="'.$task.'" and `type`="'.$type.'"';
					
					while($row3 = mysql_fetch_array($result_info)){
						//echo '---';
						if($flag_F == 0){
							mysql_query('DELETE FROM `specialurl` WHERE 1');
							//echo '+';
							$flag_F = 1;
						}
						mysql_query('insert into `specialurl`(`url`,`keyword`,`type`,`task`,`time`,`comefrom`,`number`,`title`) values("'.$row3['url'].'","'.$row3['keyword'].'","'.$row3['type'].'","'.$row3['task'].'","'.$row3['time'].'","'.$row3['comefrom'].'","'.$row3['number'].'","'.$row3['title'].'")');
						
					}
				}

			}
		}	
	}
	mysql_close($con);
  }
*/
  $con = mysql_connect("localhost","root","1234");
  if (!$con)
  {
	 die('Could not connect: ' . mysql_error());
  }
  mysql_select_db("inet", $con);
  mysql_query("set names utf8");     //php解决中文显示??????

  $resKeywordType = mysql_query('SELECT distinct `keyword` FROM `specialurl` WHERE `task`="'.$task.'" and `type`="'.$type.'"');
  
  //echo mysql_fetch_array($resKeywordType) or die(mysql_error()).'_________';//1
  echo '<div region="north" border="true" class="menu-north">';
  echo '<form action="special.php" method="get" name="form">';
  //?task='.$task.'&type='.$type.'

  echo '关键字:'.'<select name="keyword" >';
  while($tKeywordType = mysql_fetch_array($resKeywordType))
  {
	echo '<option value ="'.$tKeywordType[0].'"';
	echo '>'.$tKeywordType[0].'</option>';
  }  
  echo '<option value ="" selected="selected"></option>';
  echo '</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
  echo '<input type="hidden" name="form_task" value='.$task.'>';
  echo '<input type="hidden" name="form_type" value='.$type.'>';
  echo '起始时间:'.'<input name="start_date" type="text" class="easyui-datebox"  value="'.$form_start_date.'">';
  echo '终止时间:'.'<input name="end_date" type="text" class="easyui-datebox"  value="'.$form_end_date.'">';
  echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
  echo '来源:&nbsp;&nbsp;&nbsp;'.'<input type="checkbox" id="form_source" name="source[]" value="baidu">百度&nbsp;&nbsp;';
  echo '<input type="checkbox" id="form_source" name="source[]" value="sousou">搜搜&nbsp;&nbsp;';
  echo '<input type="checkbox" id="form_source" name="source[]" value="google_hk">谷歌&nbsp;&nbsp;&nbsp;';
  echo '<input type="submit" value="筛选" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
  //echo '<a href=\'special.php?task='.$task.'&type='.$type.' &keyword='.$form_keyword.'&source='.$form_source.'&start_date='.$form_start_date.'&end_date='.$form_end_date.'\'  value="specialurl" class="easyui-linkbutton">特殊URL</a>';
  echo '</form>';
  echo '</div>';


  
  $sql_special_query = '';
  $sql_special_count = '';
  if($form_keyword != ''){
	$sql_special_query = 'SELECT * FROM `specialurl` WHERE `task` ="'.$task.'" AND `type`="'.$type.'" AND `keyword`="'.$row_keyword[0].'" ';
	$sql_special_count = 'SELECT count(*) FROM `specialurl` WHERE `task` ="'.$task.'" AND `type`="'.$type.'" AND `keyword`="'.$row_keyword[0].'" ';
  }else{
	$sql_special_query = 'SELECT * FROM `specialurl` WHERE `task` ="'.$task.'" AND `type`="'.$type.'"';
	$sql_special_count = 'SELECT count(*) FROM `specialurl` WHERE `task` ="'.$task.'" AND `type`="'.$type.'"';
  }
  //echo $sql_special_query;
  //日期格式转化

  if($form_start_date != ''){
	//$form_start_date;
	$pos = strpos($form_start_date,'/');
	if($pos == 1){ 
		$month = '0'.substr($form_start_date,0,1);
		$pos2 = strpos(substr($form_start_date,2),'/');
		if($pos2 == 1){
			$day = '0'.substr(substr($form_start_date,2),0,1);
			$year = substr($form_start_date,4);
		}else{
			$day = substr(substr($form_start_date,2),0,2); 
			$year = substr($form_start_date,5);
		}
		
	}else{ 
		$month = substr($form_start_date,0,2); 
		$pos2 = strpos(substr($form_start_date,2),'/');
		if($pos2 == 1){
			$day = '0'.substrs(substr($form_start_date,3),0,1);
			$year = substr($form_start_date,5);
		}else{
			$day = substr(substr($form_start_date,3),0,2); 
			$year = substr($form_start_date,6);
		}
	}
	$date = $year.''.$month.''.$day;
	if($date < (date('Ymd')-7)){
		$date = (date('Ymd')-7);
	}else{
		$date = $date;
	}

	$sql_special_query = $sql_special_query.'and `time` >="'.$date.'"';
	$sql_special_count = $sql_special_count.'and `time` >="'.$date.'"';
  }else{
	$form_start_date = (date('Ymd')-7);
  }
  
  if($form_end_date != ''){
	$pos = strpos($form_end_date,'/');
	if($pos == 1){ 
		$month='0'.substr($form_end_date,0,1);
		$pos2 = strpos(substr($form_end_date,2),'/');
		if($pos2 == 1){
			$day = '0'.substr(substr($form_end_date,2),0,1);
			$year = substr($form_end_date,4);
		}else{
			$day = substr(substr($form_end_date,2),0,2); 
			$year = substr($form_end_date,5);
		}
		
	}else{ 
		$month = substr($form_end_date,0,2); 
		$pos2 = strpos(substr($form_end_date,2),'/');
		if($pos2 == 1){
			$day = '0'.substr(substr($form_end_date,3),0,1);
			$year = substrs($form_end_date,5);
		}else{
			$day = substr(substr($form_end_date,3),0,2); 
			$year = substr($form_end_date,6);
		}
	}
	$date = $year.''.$month.''.$day;
/*	if($date > (date('Ymd')){
		$date = date('Ymd');
	}else{
		$date = $date;
	}
*/
	$sql_special_query = $sql_special_query.' and `time` <="'.$date.'"';
	$sql_special_count = $sql_special_count.' and `time` <="'.$date.'"';
  }
	
  if($form_source != ''){
	if(is_array($form_source)){
		foreach( $form_source as $var){
			$comefrom = $comefrom.'"'.$var.'",';
		}
		$comefrom = $comefrom.'"")';   //此处""解决,的出现问题
		$sql_special_query = $sql_special_query.' and `comefrom` in ('.$comefrom;
		$sql_special_count = $sql_special_count.' and `comefrom` in ('.$comefrom;
  	}else{
		$sql_special_query = $sql_special_query.' and `comefrom` = "'.$form_source.'"';
		$sql_special_count = $sql_special_count.' and `comefrom` = "'.$form_source.'"';
	}
  }
  
  $sql_special_query = $sql_special_query.'  limit '.$form_number.','.$form_page;
  
  $special_count_sql = mysql_query($sql_special_count);
  $special_url_sql = mysql_query($sql_special_query);
  //echo mysql_fetch_array($sql_special_count) or die(mysql_error());//null
  while($t_count = mysql_fetch_array($special_count_sql)){
		$data_count = $t_count[0];
  }

  echo '<div class="table-content">';
  echo '<table class="table-center">';
  echo '<tr>
		<th width="30px" class="table-center-th">编号</th>
	        <th width="300px" class="table-center-th">URL</th>
		<th width="300px" class="table-center-th">标题</th>
	        <th width="190px" class="table-center-th">时间</th>
		<th width="140px" class="table-center-th">来源</th>
		<th width="130px" class="table-center-th">权重</th>
	</tr>';
  //echo $data_count;
  //echo mysql_fetch_array($sql_query) or die(mysql_error());//null
  //echo $form_number;//0
  while($tKeyword = mysql_fetch_array($special_url_sql)){
	$form_number += 1;
	//echo date('Ymd')-7;
	echo '<tr>
			<td class="table-grid">'.$form_number.'</td>
			<td class="table-grid"><a href="#" class="easyui-linkbutton" onclick=\'showChart_special("'.$tKeyword['url'].'","'.$tKeyword['comefrom'].'","'.$tKeyword['task'].'","'.$tKeyword['type'].'","'.$tKeyword['keyword'].'","'.(date('Ymd')-7).'","'.date('Ymd').'")\'>'.$tKeyword['url'].'</a></td>
			<td class="table-grid">'.$tKeyword['title'].'</td>
			<td class="table-grid">'.$tKeyword['time'].'</td>
			<td class="table-grid">'.$tKeyword['comefrom'].'</td>
			<td class="table-grid">'.$tKeyword['number'].'</td>
		</tr>';
  }
  	

  echo '</table></div>';


  echo '<div class="datagrid-pager">';
  echo '<table cellspacing="0" cellpadding="0" border="0">';
  echo '<tbody>';
  echo '<tr>';
  echo '<td><select name="form_page" class="pagination-page-list">
	<option selected="selected">20</option>
	<option>30</option>
	<option>40</option>
	<option>50</option>
        </select></td>';
  echo '<td><div class="pagination-btn-separator"></div></td>';
  if(ceil($form_number/$form_page) <= 1){
	echo '<td><a href="#" class="l-btn l-btn-plain l-btn-disabled">';
  }else{
	echo '<td><a href=\'special.php?task='.$task.'&type='.$type.'&form_number=0 &keyword='.$form_keyword.'&source='.$form_source.'&start_date='.$form_start_date.'&end_date='.$form_end_date.'\' class="l-btn l-btn-plain">';
  }

	echo '  <span class="l-btn-left"><span class="l-btn-text">
		<span class="l-btn-empty pagination-first">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></span></a></td>';//首页跳转


	if(ceil($form_number/$form_page) <= 1){
		echo '<td><a href="#" class="l-btn l-btn-plain l-btn-disabled">';
	}else{
		echo '<td><a href=\'special.php?task='.$task.'&type='.$type.'&form_number='.((ceil(($form_number-$form_page)/$form_page)-1)*$form_page).'&keyword='.$form_keyword.'&source='.$form_source.'&start_date='.$form_start_date.'&end_date='.$form_end_date.'\' class="l-btn l-btn-plain">';
	}
	echo '  <span class="l-btn-left"><span class="l-btn-text">
		<span class="l-btn-empty pagination-prev">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></span></a></td>';//前页跳转

	
	echo '<td><div class="pagination-btn-separator"></div></td>';
	echo '<td><span style="padding-left:6px">Page</span>';

	echo '<td><input class="pagination-num" type="text" value='.ceil($form_number/$form_page).' size="4"></td>';//当前页ceil($form_number/$form_page)
	echo '<td><span style="padding-right:6px">of '.ceil($data_count/$form_page).'</span></td>';//总页数ceil($data_count/$form_page)
	echo '<td><div class="pagination-btn-separator"></div></td>';

	if(ceil($form_number/$form_page) >= ceil($data_count/$form_page)){
		echo '<td><a href="#" class="l-btn l-btn-plain l-btn-disabled">';
	}else{
		echo '<td><a href=\'special.php?task='.$task.'&type='.$type.'&form_number='.$form_number.'&keyword='.$form_keyword.'&source='.$form_source.'&start_date='.$form_start_date.'&end_date='.$form_end_date.'\' class="l-btn l-btn-plain">';
	}
	echo '  <span class="l-btn-left"><span class="l-btn-text">
		<span class="l-btn-empty pagination-next">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></span></a></td>';//下页跳转

	if(ceil($form_number/$form_page) >= ceil($data_count/$form_page)){
		echo '<td><a href="#" class="l-btn l-btn-plain l-btn-disabled">';
	}else{
		echo '<td><a href=\'special.php?task='.$task.'&type='.$type.'&form_number='.((ceil($data_count/$form_page)-1)*$form_page).'&keyword='.$form_keyword.'&source='.$form_source.'&start_date='.$form_start_date.'&end_date='.$form_end_date.'\' class="l-btn l-btn-plain">';
	}
	echo '  <span class="l-btn-left"><span class="l-btn-text">
		<span class="l-btn-empty pagination-last">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></span></a></td>';//后页跳转


	echo '<td><div class="pagination-btn-separator"></div></td>';

	echo '<td><span style="padding-left:6px">Total&nbsp;'.$data_count.'&nbsp;Counts&nbsp;&nbsp;</span>';;

	echo '<td><div class="pagination-btn-separator"></div></td>';

	echo '<td><a href=\'special.php?task='.$task.'&type='.$type.'\' class="l-btn l-btn-plain" style="text-decoration:none">
		<sapn class="l-btn-left"><sapn class="l-btn-text">
		<span class="l-btn-empty pagination-load">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></span></a></td>';//刷新
	//echo '</form>';
	echo '</tr></tbody></table></div>';
	
?>

</body>
</html>

