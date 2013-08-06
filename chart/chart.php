<?php
//We've included ../Includes/FusionCharts.php, which contains functions
//to help us easily embed the charts.
include("./Includes/FusionCharts.php");
//We've also included ../Includes/FC_Colors.asp, having a list of colors
//to apply different colors to the chart's columns. We provide a function for it - getFCColor()
?>
<HTML>
<HEAD>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<TITLE>
	FusionCharts Free - Array Example using Single Series Column 3D Chart
	</TITLE>
	<link rel="stylesheet" type="text/css" href="../css/easyui.css" />
	<link rel="stylesheet" type="text/css" href="../css/icon.css" />
	<link rel="stylesheet" type="text/css" href="../css/page.css" />
	
	<?php
	//You need to include the following JS file, if you intend to embed the chart using JavaScript.
	//Embedding using JavaScripts avoids the "Click to Activate..." issue in Internet Explorer
	//When you make your own charts, make sure that the path to this JS file is correct. Else, you would get JavaScript errors.
	?>	
	<SCRIPT LANGUAGE="Javascript" SRC="./FusionCharts/FusionCharts.js"></SCRIPT>
	<script type="text/javascript"  language="JavaScript" src="../js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript"  language="JavaScript" src="../js/jquery.easyui.min.js"></script>
	<script type="text/javascript"  language="JavaScript" src="../js/page.js"></script>
	<style type="text/css">
	<!--
	body {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
	}
	-->
	</style>
</HEAD>
<BODY>

<CENTER>

<?php

	$data_url=$_GET["data_url"];
	$comefrom=$_GET["comefrom"];
	$task=$_GET["task"];
	$type=$_GET["type"]; 
	$keyword=$_GET["keyword"];
	$form_start_date = $_GET["startdate"]; 
	$form_end_date = $_GET["enddate"];
	$con=mysql_connect("localhost","root","1234");
		if(!$con)
		{
			die('Could not connect: '.mysql_error());
		}
	
		mysql_select_db("inet",$con);
		mysql_query("set names utf8");
		$sql_chart = 'SELECT `number`,`time` FROM `webpage` WHERE `url`="'.$data_url.'" AND `comefrom`="'.$comefrom.'" AND `task`="'.$task.'"AND `type`="'.$type.'"AND `keyword`="'.$keyword.'"';
		//$result_url=mysql_query('SELECT `number`,`time` FROM `webpage` WHERE `url`="'.$data_url.'" AND `comefrom`="'.$comefrom.'" AND `task`="'.$task.'"AND `type`="'.$type.'"AND `keyword`="'.$keyword.'"AND `time`>="'.$startdate.'"AND `time` <= "'.$enddate.' ORDER BY `time` asc');
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
		$sql_chart = $sql_chart.'AND `time`>="'.$startdate.'"';
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
		$sql_chart = $sql_chart.'AND `time`<="'.$enddate.'"';
		//echo $enddate.'---';
	}

	$sql_chart = $sql_chart.' ORDER BY `time` asc';

	echo '<h3><BR /><BR />来自&nbsp;&nbsp;任务: '.$task.'&nbsp;&nbsp;子任务: '.$type.'&nbsp;&nbsp;关键字: '.$keyword.'&nbsp;&nbsp;来源: '.$comefrom;
	echo "<BR />URL: ".$data_url."的动态分布折线图<BR /><BR /></h3>";
	
/*	echo 'SELECT `number`,`time` FROM `webpage` WHERE `url`="'.$data_url.'" AND `comefrom`="'.$comefrom.'" AND `task`="'.$task.'"AND `type`="'.$type.'"AND `keyword`="'.$keyword.'"AND `time`>="'.$startdate.'"AND `time` <= "'.$enddate.' ORDER BY `time` asc';
	echo 'SELECT `number`,`time` FROM `webpage` WHERE `url`="'.$data_url.'" AND `comefrom`="'.$comefrom.'" ORDER BY `time` asc((((';
	echo $result_url.'))))';
	
	echo '======';
	$t = mysql_fetch_array($result_url) or die(mysql_error());
	echo var_dump($t);
	echo '+++';
*/
	$result_url = mysql_query($sql_chart);
	$i=0;
	while($t = mysql_fetch_array($result_url))
	{	
		$arrData[$i][1]=substr($t['time'],4);
		$arrData[$i][2]=$t['number'];
		$i=$i+1;	
	}
/*
	$arrData[0][1] = "Product A";
	$arrData[1][1] = "Product B";
	$arrData[2][1] = "Product C";
	$arrData[3][1] = "Product D";
	$arrData[4][1] = "Product E";
	$arrData[5][1] = "Product F";
	//Store sales data
	$arrData[0][2] = 567500;
	$arrData[1][2] = 815300;
	$arrData[2][2] = 556800;
	$arrData[3][2] = 734500;
	$arrData[4][2] = 676800;
	$arrData[5][2] = 648500;
*/

	//Now, we need to convert this data into XML. We convert using string concatenation.
	//Initialize <graph> element
	$strXML = "<graph caption='' formatNumberScale='0' decimalPrecision='0'>";
	//Convert data to XML and append
	foreach ($arrData as $arSubData)
		$strXML .= "<set name='" . $arSubData[1] . "' value='" . $arSubData[2] ."' color='FF8000' />";

	//Close <graph> element
	$strXML .= "</graph>";
	
	//Create the chart - Column 3D Chart with data contained in strXML
	echo renderChart("./FusionCharts/FCF_Line.swf", "", $strXML, "productSales", 600, 300);
  	echo '<form action="chart.php" method="get" name="form_keyword">';
	echo '<input type="hidden" name="task" value='.$task.'>';
	echo '<input type="hidden" name="type" value='.$type.'>';
	echo '<input type="hidden" name="keyword" value='.$keyword.'>';
	echo '<input type="hidden" name="comefrom" value='.$comefrom.'>';
	echo '<input type="hidden" name="data_url" value='.$data_url.'>';
	echo '开始日期:<input name="startdate" type="text" class="easyui-datebox" value="'.$form_start_date.'">&nbsp;';
	echo '终止日期:<input name="enddate" type="text" class="easyui-datebox" value="'.$form_end_date.'">&nbsp;&nbsp;';
	echo '<input type="submit" value="显示" /></form>';
	echo '<input type="button" value="导出" />';
?>
<BR><BR>


</CENTER>
</BODY>
</HTML>
