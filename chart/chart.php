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
	echo "<BR><BR><h2 align='center'>来自".$comefrom."的url=".$data_url."的动态分布折线图</h2><BR><BR>";
	$con=mysql_connect("localhost","root","1234");
	if(!$con)
	{
		die('Could not connect: '.mysql_error());
	}
	
	mysql_select_db("inet",$con);
	mysql_query("set names utf8");
	$result_url=mysql_query('SELECT `number`,`time` FROM `webpage` WHERE `url`="'.$data_url.'" AND `comefrom`="'.$comefrom.'" AND `task`="'.$task.'"AND `type`="'.$type.'"AND `keyword`="'.$keyword.'"AND `time`>="'.$startdate.'"AND `time` <= "'.$enddate.' ORDER BY `time` asc');
/*	echo 'SELECT `number`,`time` FROM `webpage` WHERE `url`="'.$data_url.'" AND `comefrom`="'.$comefrom.'" AND `task`="'.$task.'"AND `type`="'.$type.'"AND `keyword`="'.$keyword.'"AND `time`>="'.$startdate.'"AND `time` <= "'.$enddate.' ORDER BY `time` asc';
	echo 'SELECT `number`,`time` FROM `webpage` WHERE `url`="'.$data_url.'" AND `comefrom`="'.$comefrom.'" ORDER BY `time` asc((((';
	echo $result_url.'))))';
	
	echo '======';
	$t = mysql_fetch_array($result_url) or die(mysql_error());
	echo var_dump($t);
	echo '+++';
*/
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
?>
<BR><BR>


</CENTER>
</BODY>
</HTML>
