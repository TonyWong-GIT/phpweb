<?php 
require('chinese.php'); 

$testarr = array('超越PHP', '上海市', '中华人民共和国', 'www.phpe.net');
$test = $testarr[array_rand($testarr)];

$pdf=new PDF_Chinese(); 

$pdf->AddGBFont('simsun','宋体'); 
$pdf->AddGBFont('simhei','黑体'); 
$pdf->AddGBFont('simkai','楷体_GB2312'); 
$pdf->AddGBFont('sinfang','仿宋_GB2312'); 

$pdf->Open(); 

$pdf->AddPage(); 

$pdf->SetFont('simsun','',20); 
$pdf->Write(10,$test); 
$pdf->SetFont('simhei','',20); 
$pdf->Write(10,$test); 
$pdf->SetFont('simkai','',20); 
$pdf->Write(10,$test); 
$pdf->SetFont('sinfang','',20); 
$pdf->Write(10,$test); 

$pdf->AddPage(); 

$pdf->SetFont('simsun','',20); 
$pdf->Write(10,$test); 
$pdf->SetFont('simhei','',20); 
$pdf->Write(10,$test); 
$pdf->SetFont('simkai','',20); 
$pdf->Write(10,$test); 
$pdf->SetFont('sinfang','',20); 
$pdf->Write(10,$test); 

$pdf->Output(); 
?>