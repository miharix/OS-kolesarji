<?php
include 'SQL_branje.php';
require_once('tcpdf_include.php');
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Miha Kočar');
$pdf->SetTitle('Startni listki za tekmovanje');
$pdf->SetSubject('V1.0');
$pdf->SetKeywords('OŠ,kolesarji,tekmovanje,listki');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(0,0);//(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

// set auto page breaks
$pdf->SetAutoPageBreak(false);//(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

 //set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/slv.php')) {
	require_once(dirname(__FILE__).'/lang/slv.php');
	$pdf->setLanguageArray($l);
}
$style = array(
    'border' => false,
    'padding' => 0,
    'fgcolor' => array(0,0,0),
    'bgcolor' => false
);

$pdf->AddPage();
$pdf->SetFillColor(255, 255, 255);
$pdf->setCellPaddings(0, 0, 0, 0);
$pdf->setCellMargins(0, 0, 0, 0);
$pdf->SetFont('freeserif', 'B', 15,'',false);
$pdf->SetY(15);
//$pdf->SetX(50);
$na_strani=0;
$pdf->SetLeftMargin(60);
$startne=SQL_StartneStevilkeTekmovalcev();
    while ($startna = $startne->fetchArray(SQLITE3_NUM) ){
    	$kjeX=$pdf->GetX();
    	$kjeY=$pdf->GetY();
		$pdf->write2DBarcode('http://kolesarji.vov.si/tekmovalec.php?startna='.$startna[0], 'QRCODE,L', 15, $kjeY, 40, 40, $style, 'N'); 	
		$pdf->SetX($kjeX);
		$pdf->SetY($kjeY);
		$pdf->SetFont('freeserif', 'B', 10,'',false);
		$pdf->MultiCell(0, 7, 'Tekmovanje "Kaj veš o prometu" na OŠ Martina Krpana ', 0, 'C', 1, 1, '', '', true);
		$pdf->SetFont('freeserif', 'B', 15,'',false);
    	$pdf->MultiCell(0, 8, 'Štartna številka: '.$startna[0], 0, 'C', 1, 1, '', '', true);
    	$pdf->MultiCell(0, 10, $startna[1].' '.$startna[2], 0, 'C', 1, 1, '', '', true);
    	$pdf->SetFont('freeserif', 'B', 10,'',false);
    	$pdf->MultiCell(0, 10, $startna[3], 0, 'C', 1, 1, '', '', true);
    	if($startna[4]!=''){
    	$pdf->MultiCell(0, 20, 'Mentor/ica: '.$startna[4], 0, 'C', 1, 1, '', '', true);
    	}else{
    	$pdf->MultiCell(0, 20, '', 0, 'C', 1, 1, '', '', true);	
    	}
    	$na_strani++;
    	if($na_strani==5){
    		$na_strani=0;
    		$pdf->AddPage();
    		$pdf->SetY(15);
    	}
    }
   
  
//$pdf->write2DBarcode("http://kolesarji.vov.si/tekmovalec.php?startna=4", 'QRCODE,L', 15, 10, 20, 20, $style, 'N');
//$pdf->SetX(168);
//$qY=$pdf->Gety();
//$pdf->write2DBarcode("http://kolesarji.vov.si/tekmovalec.php?startna=4", 'QRCODE,L', 15, 10, 20, 20, $style, 'N');

$pdf->lastPage();



//Close and output PDF document
$pdf->Output('Startne_stevilke.pdf', 'I');//D za vsiljen download I za odprtje v brskaniku

?>