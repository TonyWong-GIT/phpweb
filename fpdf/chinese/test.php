<?php 
require('chinese.php'); 

$testarr = array('��ԽPHP', '�Ϻ���', '�л����񹲺͹�', 'www.phpe.net');
$test = $testarr[array_rand($testarr)];

$pdf=new PDF_Chinese(); 

$pdf->AddGBFont('simsun','����'); 
$pdf->AddGBFont('simhei','����'); 
$pdf->AddGBFont('simkai','����_GB2312'); 
$pdf->AddGBFont('sinfang','����_GB2312'); 

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