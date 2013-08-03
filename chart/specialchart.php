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
	<?php
	//You need to include the following JS file, if you intend to embed the chart using JavaScript.
	//Embedding using JavaScripts avoids the "Click to Activate..." issue in Internet Explorer
	//When you make your own charts, make sure that the path to this JS file is correct. Else, you would get JavaScript errors.
	?>	
	<SCRIPT LANGUAGE="Javascript" SRC="./FusionCharts/FusionCharts.js"></SCRIPT>
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
	$startdate=$_GET["startdate"]; 
	$enddate=$_GET["enddate"]; 
	echo "<BR><BR><h2 align='center'>来自".$comefrom."的“特殊的url”=".$url."的动态分布折线图</h2><BR><BR>";
	$con=mysql_connect("localhost","root","1234");
	if(!$con)
	{
		die('Could not connect: '.mysql_error());
	}
	mysql_select_db("inet",$con);//选择数据库inet；
	mysql_query("set names utf8");//解决中文显示的问题

	$resultTimeRecord=mysql_query('select `time` from `timerecord` where `keyword`="'.$keyword.'" and `comefrom`="'.$comefrom.'"and `task`="'.$task.'"and `type`="'.$type.'"and `time` >="'.$startdate.'"and `time` <= "'.$enddate.' order by time asc');

	//echo 'select `time` from `timerecord` where `keyword`="'.$keyword.'" and `comefrom`="'.$comefrom.'"and `task`="'.$task.'"and `type`="'.$type.'"and `time` >="'.$startdate.'"and `time` <= "'.$enddate.' order by time asc';
	$iTimeRecord=0;
	while($row=mysql_fetch_array($resultTimeRecord))
	{	
		
		$arrData[$iTimeRecord][1]=$row['time'];
		$arrData[$iTimeRecord][2]=0;
		$iTimeRecord=$iTimeRecord+1;
		
	}
	
		
	
	$result=mysql_query('select `number`,`time` from `specialurl` where `keyword`="'.$keyword.'" and `url`="'.$data_url.'" and `comefrom`="'.$comefrom.'"and `task`="'.$task.'" and `type`="'.$type.'"and `time` >="'.$startdate.'"and `time` <= "'.$enddate.' order by time asc');
	
	//echo 'select `number`,`time` from `specialurl` where `keyword`="'.$keyword.'" and `url`="'.$data_url.'" and `comefrom`="'.$comefrom.'"and `task`="'.$task.'" and `type`="'.$type.'"and `time` >="'.$startdate.'"and `time` <= "'.$enddate.' order by time asc';
	$i=0;
	while($row=mysql_fetch_array($result))
	{	
		for($i=0;$i<$iTimeRecord;$i++)
		{
			if($arrData[$i][1]==$row['time'])
			{
				$arrData[$i][2]=$row['number'];
				break;
			}
		}
		
		
	}
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
?>
<BR><BR>


</CENTER>
</BODY>
</HTML>
