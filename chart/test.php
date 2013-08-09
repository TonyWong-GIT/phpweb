<?php
$startdate = date("Ymd",strtotime("-7 day"));
$enddate = date('Ymd');
while($startdate != $enddate){
		echo '\''.$startdate.'\',';
		$startdate = date('Ymd',strtotime("1 day",strtotime($startdate)));
	//echo $startdate;
}
echo '\''.$startdate.'\',';
?>
