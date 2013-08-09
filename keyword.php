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
<!--<script type="text/javascript"  language="JavaScript" src="js/win.js"></script>-->
<script>
function showChart(data_url, comefrom, task, type, keyword, startdate, enddate)
{
	//window.open("chart/chart.php?data_url="+data_url+" &comefrom="+comefrom);
	window.open("chart/Chart.php?data_url="+data_url+" &comefrom="+comefrom+" &task="+task+" &type="+type+" &keyword="+keyword+" &startdate="+startdate+" &enddate="+enddate+"\"",'url_window','height=600,width=1200,top=150,left=300,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
}
function whitelistadd(url, comefrom, task, type, keyword, time, number, title, source)
{
	if(confirm("任务名: "+task+"\n子任务名: "+type+"\n关键字: "+keyword+"\n来源: "+comefrom+"\nURL: "+url+"\n你确信要加入白名单？"))
    	{//如果是true ，那么就把页面转向whitelist_add.php
        	window.location.href="whitelist_add.php?task="+task+"&type="+type+"&keyword="+keyword+"&url="+url+"&comefrom="+comefrom+"&time="+time+"&number="+number+"&title="+title+"&source="+source;
   	}

}
</script>
<!--<script type="text/javascript"  language="JavaScript" src="js/pagination.js"></script>-->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>

<?php
  $con = mysql_connect("localhost","root","1234");
  if (!$con)
  {
  	die('Could not connect: ' . mysql_error());
  }

  mysql_select_db("inet", $con);
  mysql_query("set names utf8");     //php解决中文显示??????
  
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
  $comefrom = '';

  if($_GET['form_task']!='' and $_GET['form_type']!=''){
	  $task = $_GET['form_task'];
	  $type = $_GET['form_type'];
  }else{
	  $task = $_GET['task'];
	  $type = $_GET['type'];
	  //$task = substr(substr($task,1),0,strlen($task)-2);
	  //$type = substr(substr($type,1),0,strlen($type)-2);//去掉传递过来的字符串的左右"",有时候会出现此问题，不清楚是什么问题
  }

  $res_day = 'select * from `metaetc` where 1';
  $rows_day = mysql_query($res_day);
  while($row_day = mysql_fetch_array($rows_day)){
	$form_day = $row_day['day'];
  }
  //echo '-----------'.$form_day;
  $form_day_start = $form_day-2*$form_day;

  $form_start_date = $_GET['start_date'];
  $form_end_date = $_GET['end_date'];
  

  echo '<div region="north" border="true" class="menu-north">';
  echo '<form action="keyword.php" method="get" name="form_keyword">';

  echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

  echo '<input type="hidden" name="form_task" value='.$task.'>';
  echo '<input type="hidden" name="form_type" value='.$type.'>';
  echo '起始时间:'.'<input name="start_date" type="text" class="easyui-datebox" value="'.$form_start_date.'">';
  echo '终止时间:'.'<input name="end_date" type="text" class="easyui-datebox" value="'.$form_end_date.'" >';
  echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
  echo '来源:&nbsp;&nbsp;&nbsp;'.'<input type="checkbox" id="form_source" name="form_source[]" value="百度">百度&nbsp;&nbsp;';
  echo '<input type="checkbox" id="form_source" name="form_source[]" value="搜搜">搜搜&nbsp;&nbsp;';
  echo '<input type="checkbox" id="form_source" name="form_source[]" value="谷歌">谷歌&nbsp;&nbsp;&nbsp;';
  echo '<input type="submit" value="筛选" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
  echo '<a href=\'special.php?task='.$task.'&type='.$type.'\'  value="specialurl" class="easyui-linkbutton">特殊URL</a>&nbsp;';
  echo '<a href=\'disappear.php?task='.$task.'&type='.$type.'\'  value="specialurl" class="easyui-linkbutton">消失URL</a>&nbsp;';
  echo '<a href=\'appear.php?task='.$task.'&type='.$type.'\'  value="specialurl" class="easyui-linkbutton">出现URL</a>&nbsp;';
  echo '<a href=\'whitelist.php?task='.$task.'&type='.$type.'\'  value="specialurl" class="easyui-linkbutton">白名单</a>';
  echo '</form>';
  echo '</div>';
  echo '<div class="table-content">';

  echo '<table class="table-center">';
  echo '<tr>
		<th width="30px" class="table-center-th">编号</th>
	        <th width="300px" class="table-center-th">URL</th>
		<th width="370px" class="table-center-th">标题</th>
	        <th width="100px" class="table-center-th">日期</th>
		<th width="100px" class="table-center-th">来源</th>
		<th width="100px" class="table-center-th">权重</th>
		<th width="90px" class="table-center-th">加入白名单</th>
	</tr>';

  $i = 0;
  $sql_query = '';

  $sql_query = 'SELECT * FROM `webpage` WHERE `task`="'.$task.'" and `type`="'.$type.'"';
  $sql_count = 'SELECT COUNT(*) FROM `webpage` WHERE `task`="'.$task.'" and `type`="'.$type.'"';
  //日期格式转化
  if($form_start_date != ''){
	//$form_start_date;
	$pos = strpos($form_start_date,'/');
	if($pos == 1){ 
		$month = '0'.substr($form_start_date,0,1);
		$pos2 = strpos(substr($form_start_date,2),'/');
		if($pos2 == 1){
			$day = '0'.substr(substr($form_start_date,2),0,1);
			$year = substr($form_start_date,4,4);
		}else{
			$day = substr(substr($form_start_date,2),0,2); 
			$year = substr($form_start_date,5,4);
		}
		
	}else{ 
		$month = substr($form_start_date,0,2); 
		$pos2 = strpos(substr($form_start_date,2),'/');
		if($pos2 == 1){
			$day = '0'.substrs(substr($form_start_date,3),0,1);
			$year = substr($form_start_date,5,4);
		}else{
			$day = substr(substr($form_start_date,3),0,2); 
			$year = substr($form_start_date,6,4);
		}
	}
	$date = $year.''.$month.''.$day;
	$sql_query = $sql_query.' and `time` >="'.$date.'"';
	$sql_count = $sql_count.' and `time` >="'.$date.'"';
  }

  if($form_end_date != ''){
	$pos = strpos($form_end_date,'/');
	if($pos == 1){ 
		$month='0'.substr($form_end_date,0,1);
		$pos2 = strpos(substr($form_end_date,2),'/');
		if($pos2 == 1){
			$day = '0'.substr(substr($form_end_date,2),0,1);
			$year = substr($form_end_date,4,4);
		}else{
			$day = substr(substr($form_end_date,2),0,2); 
			$year = substr($form_end_date,5,4);
		}
		
	}else{ 
		$month = substr($form_end_date,0,2); 
		$pos2 = strpos(substr($form_end_date,2),'/');
		if($pos2 == 1){
			$day = '0'.substr(substr($form_end_date,3),0,1);
			$year = substrs($form_end_date,5,4);
		}else{
			$day = substr(substr($form_end_date,3),0,2); 
			$year = substr($form_end_date,6,4);
		}
	}
	$date = $year.''.$month.''.$day;
	$sql_query = $sql_query.' and `time` <="'.$date.'"';
	$sql_count = $sql_count.' and `time` <="'.$date.'"';
  }

  $comefrom = '';
  if($_GET['form_source'] != ''){
	$form_source = $_GET['form_source'];
	foreach( $form_source as $var){
		$comefrom = $comefrom.'"'.$var.'",';
		//echo $comefrom.'========';
		//$source_array = ;
	}
	$comefrom = $comefrom.'""';   //此处""解决,的出现问题
	//echo '$comefrom:'.$comefrom;
	$sql_query = $sql_query.' and `comefrom` in ('.$comefrom.')';
	$sql_count = $sql_count.' and `comefrom` in ('.$comefrom.')';
  }else if($_GET['source'] != ''){
	$form_source = $_GET['source'];
	$sql_query = $sql_query.' and `comefrom` in ('.$form_source.')';
	$sql_count = $sql_count.' and `comefrom` in ('.$form_source.')';
  }else{
	$form_source = '';
	$comefrom = '';
  }
  //echo '--'.$form_source.'--';

  //echo '$sql_query:'.$sql_query;
  $sql_query = $sql_query.'  limit '.$form_number.','.$form_page;
  //$resKeyword = mysql_query('SELECT * FROM webpage WHERE task='.$task.' and type='.$type.' limit 0,20');
  //echo $sql_query;


  //echo '<div id="dd" title="Login" closed="true" style="width:300px;height:180px;"></div>';

  

  $chart_date_start = date("Ymd",strtotime("$form_day_start day"));
  $chart_year = substr($chart_date_start,0,4); 
  if(substr($chart_date_start,4,1) == '0'){
	$chart_month = substr($chart_date_start,5,1);
  }else{
	$chart_month = substr($chart_date_start,4,2);
  }
  if(substr($chart_date_start,6,1) == '0'){
	$chart_day = substr($chart_date_start,7,1);
  }else{
	$chart_day = substr($chart_date_start,6,2);
  }

  $chart_date_start = $chart_month.'/'.$chart_day.'/'.$chart_year;
  //echo $chart_date_start;  //  7/9/2013
  $chart_date_end = date("Ymd");
  $chart_year = substr($chart_date_end,0,4); 
  if(substr($chart_date_end,4,1) == '0'){
	$chart_month = substr($chart_date_end,5,1);
  }else{
	$chart_month = substr($chart_date_end,4,2);
  }
  if(substr($chart_date_end,6,1) == '0'){
	$chart_day = substr($chart_date_end,7,1);
  }else{
	$chart_day = substr($chart_date_end,6,2);
  }

  $chart_date_end = $chart_month.'/'.$chart_day.'/'.$chart_year;

  //echo $chart_date_end;  //  8/5/2013
  $res_count = mysql_query($sql_count);
  $resKeyword = mysql_query($sql_query);
  $data_count = 0;

	  while($t_count = mysql_fetch_array($res_count)){
		$data_count = $t_count[0];
	  }
	  while($tKeyword = mysql_fetch_array($resKeyword)){
		$form_number += 1;
		//echo date('Ymd')-7;
		if($tKeyword['flag_whitelist'] == 1){
			echo '<tr class="tr-background">';
		}
		else{
			echo '<tr>';
		}
		echo '
					<td class="table-grid">'.$form_number.'</td>
					<td class="table-grid"><a href="#" class="easyui-linkbutton" onclick=\'showChart("'.$tKeyword['url'].'","'.$tKeyword['comefrom'].'","'.$tKeyword['task'].'","'.$tKeyword['type'].'","'.$tKeyword['keyword'].'","'.$chart_date_start.'","'.$chart_date_end.'")\'>'.substr($tKeyword['url'],0,40).'</a></td>
					<td class="table-grid">'.$tKeyword['title'].'</td>
					<td class="table-grid">'.$tKeyword['time'].'</td>
					<td class="table-grid">'.$tKeyword['comefrom'].'</td>
					<td class="table-grid">'.$tKeyword['number'].'</td>';
		if($tKeyword['flag_whitelist'] == 1){
			echo '<td class="table-grid">已加入</td>';
		}else{
			echo '			<td class="table-grid"><a href="#" class="easyui-linkbutton" onclick=\'whitelistadd("'.$tKeyword['url'].'","'.$tKeyword['comefrom'].'","'.$tKeyword['task'].'","'.$tKeyword['type'].'","'.$tKeyword['keyword'].'","'.$tKeyword['time'].'","'.$tKeyword['number'].'","'.$tKeyword['title'].'","keyword.php")\'>加入</a></td>';
		}
		echo '</tr>';
		
	  }

	
  


	echo '</table>';
	echo '</div>';

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
	//print $form_source;
	if(ceil($form_number/$form_page) <= 1){
		echo '<td><a href="#" class="l-btn l-btn-plain l-btn-disabled">';
	}else{
		echo '<td><a href=\'keyword.php?task='.$task.'&type='.$type.'&form_number=0 &source='.$comefrom.'&start_date='.$form_start_date.'&end_date='.$form_end_date.'\' class="l-btn l-btn-plain">';
	}
	echo '  <span class="l-btn-left"><span class="l-btn-text">
		<span class="l-btn-empty pagination-first">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></span></a></td>';//首页跳转


	if(ceil($form_number/$form_page) <= 1){
		echo '<td><a href="#" class="l-btn l-btn-plain l-btn-disabled">';
	}else{
		echo '<td><a href=\'keyword.php?task='.$task.'&type='.$type.'&form_number='.((ceil(($form_number-$form_page)/$form_page)-1)*$form_page).'&source='.$comefrom.'&start_date='.$form_start_date.'&end_date='.$form_end_date.'\' class="l-btn l-btn-plain">';
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
		echo '<td><a href=\'keyword.php?task='.$task.'&type='.$type.'&form_number='.$form_number.'&source='.$comefrom.'&start_date='.$form_start_date.'&end_date='.$form_end_date.'\' class="l-btn l-btn-plain">';
	}
	echo '  <span class="l-btn-left"><span class="l-btn-text">
		<span class="l-btn-empty pagination-next">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></span></a></td>';//下页跳转

	if(ceil($form_number/$form_page) >= ceil($data_count/$form_page)){
		echo '<td><a href="#" class="l-btn l-btn-plain l-btn-disabled">';
	}else{
		echo '<td><a href=\'keyword.php?task='.$task.'&type='.$type.'&form_number='.((ceil($data_count/$form_page)-1)*$form_page).'&source='.$comefrom.'&start_date='.$form_start_date.'&end_date='.$form_end_date.'\' class="l-btn l-btn-plain">';
	}
	echo '  <span class="l-btn-left"><span class="l-btn-text">
		<span class="l-btn-empty pagination-last">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></span></a></td>';//后页跳转


	echo '<td><div class="pagination-btn-separator"></div></td>';

	echo '<td><span style="padding-left:6px">Total&nbsp;'.$data_count.'&nbsp;Counts&nbsp;&nbsp;</span>';

	echo '<td><div class="pagination-btn-separator"></div></td>';

	echo '<td><a href=\'keyword.php?task='.$task.'&type='.$type.'&form_number=0 \' class="l-btn l-btn-plain" style="text-decoration:none">
		<sapn class="l-btn-left"><sapn class="l-btn-text">
		<span class="l-btn-empty pagination-load">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></span></a></td>';//刷新
	//echo '</form>';
	echo '</tr></tbody></table></div>';
	
?>

</body>
</html>

