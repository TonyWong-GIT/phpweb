<?php
	$flag_xAxis = 0;
	$flag_baidu = 0;
	$flag_soso = 0;
	$flag_google = 0;
	$startdate = '';
	$enddate = '';

	//$data_url=$_GET["url"];
	$date=$_GET["date"];
	//print $_GET["url"];
	//$change_url = str_replace('!','&',$data_url);  //url转向出现问题的解决
	//$change_url = str_replace('^','+',$change_url);  //url转向出现+问题的解决
	$task=$_GET["task"];
	$type=$_GET["type"]; 
	$number=$_GET["number"];
	$comefrom=$_GET["comefrom"];
	$form_start_date = $_GET["startdate"]; 
	$form_end_date = $_GET["enddate"];
	$con=mysql_connect("localhost","root","1234");
		if(!$con)
		{
			die('Could not connect: '.mysql_error());
		}
	
		mysql_select_db("inet",$con);
		mysql_query("set names utf8");
	$url_res = mysql_query('SELECT `url` FROM `webpage` WHERE `task`="'.$task.'" and `type`="'.$type.'" and `time`="'.$date.'" and `comefrom`="'.$comefrom.'" and `number`="'.$number.'"');
	$url_res_row = mysql_fetch_array($url_res);
	$change_url = $url_res_row[0];
	//print 'SELECT `url` FROM `webpage` WHERE `task`="'.$task.'" and `type`="'.$type.'" and `time`="'.$date.'" and `comefrom`="'.$comefrom.'" and `number`="'.$number.'"';

	$rel_keyword_chart = mysql_query('SELECT `keyword` FROM `keyword` WHERE `task`="'.$task.'" and `type`="'.$type.'"');
	$row_rel_keyword_chart = mysql_fetch_array($rel_keyword_chart);
	$keyword=$row_rel_keyword_chart[0];
		$sql_baidu_chart = 'SELECT `number`,`time` FROM `webpage` WHERE `url`="'.$change_url.'" AND `comefrom`="百度" AND `task`="'.$task.'"AND `type`="'.$type.'"';
		$sql_soso_chart = 'SELECT `number`,`time` FROM `webpage` WHERE `url`="'.$change_url.'" AND `comefrom`="搜搜" AND `task`="'.$task.'"AND `type`="'.$type.'"';
		$sql_google_chart = 'SELECT `number`,`time` FROM `webpage` WHERE `url`="'.$change_url.'" AND `comefrom`="谷歌" AND `task`="'.$task.'"AND `type`="'.$type.'"';

		
	if($_GET["startdate"] != ''){
		
		//echo $form_start_date.'---'.$form_end_date;
		$start_pos = strpos($form_start_date,'/');
		if($start_pos == 1){ 
			$start_month = '0'.substr($form_start_date,0,1);
			$start_pos2 = strpos(substr($form_start_date,2),'/');
			if($start_pos2 == 1){
				$start_day = '0'.substr(substr($form_start_date,2),0,1);
				$start_year = substr($form_start_date,4,4);
			}else{
				$start_day = substr(substr($form_start_date,2),0,2); 
				$start_year = substr($form_start_date,5,4);
			}
		
		}else{ 
			$start_month = substr($form_start_date,0,2); 
			$start_pos2 = strpos(substr($form_start_date,2),'/');
			if($start_pos2 == 1){
				$start_day = '0'.substrs(substr($form_start_date,3),0,1);
				$start_year = substr($form_start_date,5,4);
			}else{
				$start_day = substr(substr($form_start_date,3),0,2); 
				$start_year = substr($form_start_date,6,4);
			}
		}
		$startdate = $start_year.$start_month.$start_day;      //起始日期的转换
		$startdate_xAxis = $startdate;
		$startdate_baidu = $startdate;
		$startdate_soso = $startdate;
		$startdate_google = $startdate;
		$sql_baidu_chart = $sql_baidu_chart.'AND `time`>="'.$startdate.'"';
		$sql_soso_chart = $sql_soso_chart.'AND `time`>="'.$startdate.'"';
		$sql_google_chart = $sql_google_chart.'AND `time`>="'.$startdate.'"';
	}
	if($_GET["enddate"] != ''){
		$end_pos = strpos($form_end_date,'/');
		if($end_pos == 1){ 
			$end_month = '0'.substr($form_end_date,0,1);
			$end_pos2 = strpos(substr($form_end_date,2),'/');
			if($end_pos2 == 1){
				$end_day = '0'.substr(substr($form_end_date,2),0,1);
				$end_year = substr($form_end_date,4,4);
			}else{
				$end_day = substr(substr($form_end_date,2),0,2); 
				$end_year = substr($form_end_date,5,4);
			}
		
		}else{ 
			$end_month = substr($form_end_date,0,2); 
			$end_pos2 = strpos(substr($form_end_date,2),'/');
			if($end_pos2 == 1){
				$end_day = '0'.substrs(substr($form_end_date,3),0,1);
				$end_year = substr($form_end_date,5,4);
			}else{
				$end_day = substr(substr($form_end_date,3),0,2); 
				$end_year = substr($form_end_date,6,4);
			}
		}
		$enddate = $end_year.''.$end_month.''.$end_day;
		$sql_baidu_chart = $sql_baidu_chart.'AND `time`<="'.$enddate.'"';
		$sql_soso_chart = $sql_soso_chart.'AND `time`<="'.$enddate.'"';
		$sql_google_chart = $sql_google_chart.'AND `time`<="'.$enddate.'"';
	}

	$sql_baidu_chart = $sql_baidu_chart.' ORDER BY `time` asc';
	$sql_soso_chart = $sql_soso_chart.' ORDER BY `time` asc';
	$sql_google_chart = $sql_google_chart.' ORDER BY `time` asc';
	//print $sql_baidu_chart;
	$result_baidu_url = mysql_query($sql_baidu_chart);
	$result_soso_url = mysql_query($sql_soso_chart);
	$result_google_url = mysql_query($sql_google_chart);
	$i_baidu=0;
	while($t_baidu = mysql_fetch_array($result_baidu_url))
	{	
		$arrData_baidu[$i_baidu][1]=substr($t_baidu['time'],4,4);
		$arrData_baidu[$i_baidu][2]=$t_baidu['number'];
		$i_baidu=$i_baidu+1;	
	}
	$i_soso=0;
	while($t_soso = mysql_fetch_array($result_soso_url))
	{	
		$arrData_soso[$i_soso][1]=substr($t_soso['time'],4,4);
		$arrData_soso[$i_soso][2]=$t_soso['number'];
		$i_soso=$i_soso+1;	
	}
	$i_google=0;
	while($t_google = mysql_fetch_array($result_google_url))
	{	
		$arrData_google[$i_baidu][1]=substr($t_google['time'],4,4);
		$arrData_google[$i_baidu][2]=$t_google['number'];
		$i_google=$i_google+1;	
	}


echo '
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title></title>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script type="text/javascript">
	$(function () {
		$(\'#container\').highcharts({
		    chart: {
		        type: \'line\',
		        marginRight: 130,
		        marginBottom: 25
		    },
		    title: {
		        text: \'任务:'.$task.'   子任务:'.$type.'   关键字:'.$keyword.'\',
		        x: -20 //center
		    },
		    subtitle: {
		        text: \'URL:'.$change_url.'\',
		        x: -20
		    },
		    xAxis: {
		        categories: [';
	while($startdate_xAxis <= $enddate){
		$flag_xAxis += 1;
		if($flag_xAxis == 1){
			echo '\''.substr($startdate_xAxis,4,4).'\'';
		}else{
			echo ',\''.substr($startdate_xAxis,4,4).'\'';
		}
		$startdate_xAxis = date('Ymd',strtotime("1 day",strtotime($startdate_xAxis)));
	}
	echo ']
            },
            yAxis: {
                title: {
                    text: \'权重\'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: \'#808080\'
                }]
            },tooltip: {
                valueSuffix: \'\'
            },
            legend: {
                layout: \'vertical\',
                align: \'right\',
                verticalAlign: \'top\',
                x: -10,
                y: 100,
                borderWidth: 0
            },
            series: [';
	echo '{
                name: \'谷歌\',
                data: [';
	while($startdate_google <= $enddate){
		$flag_google += 1;
		$flag_t_google = 0;
		foreach ($arrData_google as $arSubData){
			if($arSubData[1] == substr($startdate_google,4,4)){
				$flag_t_google = 1;
				if($flag_google == 1){
					echo $arSubData[2];
					break;
				}else{
					echo ','.$arSubData[2];
					break;
				}
			}
		}
		if($flag_t_google == 0){
			if($flag_google == 1){
				echo '0';
			}else{
				echo ',0';
			}
		}
		$startdate_google = date('Ymd',strtotime("1 day",strtotime($startdate_google)));
	}
  	echo '  ] }, {
                name: \'百度\',
                data: [';

	while($startdate_baidu <= $enddate){
		$flag_baidu += 1;
		$flag_t_baidu = 0;
		foreach ($arrData_baidu as $arSubData){
			if($arSubData[1] == substr($startdate_baidu,4,4)){
				$flag_t_baidu = 1;
				if($flag_baidu == 1){
					echo $arSubData[2];
					break;
				}else{
					echo ','.$arSubData[2];
					break;
				}
			}
		}
		if($flag_t_baidu == 0){
			if($flag_baidu == 1){
				echo '0';
			}else{
				echo ',0';
			}
		}
		$startdate_baidu = date('Ymd',strtotime("1 day",strtotime($startdate_baidu)));
	}

	echo ']
            }, {
                name: \'搜搜\',
                data: [';


	while($startdate_soso <= $enddate){
		$flag_soso += 1;
		$flag_t_soso = 0;
		foreach ($arrData_soso as $arSubData){
			if($arSubData[1] == substr($startdate_soso,4,4)){
				$flag_t_soso = 1;
				if($flag_soso == 1){
					echo $arSubData[2];
					break;
				}else{
					echo ','.$arSubData[2];
					break;
				}
			}
		}
		if($flag_t_soso == 0){
			if($flag_soso == 1){
				echo '0';
			}else{
				echo ',0';
			}
		}
		$startdate_soso = date('Ymd',strtotime("1 day",strtotime($startdate_soso)));
	}

	echo ']
            }';
	echo ']
        });
    });
    

		</script>
	<link rel="stylesheet" type="text/css" href="../css/easyui.css" />
	<link rel="stylesheet" type="text/css" href="../css/icon.css" />
	<link rel="stylesheet" type="text/css" href="../css/page.css" />
	<script type="text/javascript"  language="JavaScript" src="../js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript"  language="JavaScript" src="../js/jquery.easyui.min.js"></script>
	<script type="text/javascript"  language="JavaScript" src="../js/page.js"></script>

	</head>
	<body>
<script src="./highchart/highcharts.js"></script>
<script src="./highchart/exporting.js"></script>

<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>';
echo '<div class="chart-div">';
	echo '<form action="Chart.php" method="get" name="form_keyword">';
	echo '<input type="hidden" name="task" value='.$task.'>';
	echo '<input type="hidden" name="type" value='.$type.'>';
	echo '<input type="hidden" name="date" value='.$date.'>';
	echo '<input type="hidden" name="number" value='.$number.'>';
	echo '<input type="hidden" name="comefrom" value='.$comefrom.'>';
	//echo '<input type="hidden" name="url" value='.$change_url.'>';
	echo '开始日期:<input name="startdate" type="text" class="easyui-datebox" value="'.$form_start_date.'">&nbsp;';
	echo '终止日期:<input name="enddate" type="text" class="easyui-datebox" value="'.$form_end_date.'">&nbsp;&nbsp;';
	echo '<input type="submit" value="显示" /></form>';
echo '	</div>
	</body>
</html>
';
?>


