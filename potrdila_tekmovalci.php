<?php
include 'SQL_branje.php';
require_once('tcpdf_include.php');

$ozadje=false;

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Miha Kočar');
$pdf->SetTitle('Potrdilo udeležbe tekmovanja - tekmovalci');
$pdf->SetSubject('V1.0');
$pdf->SetKeywords('OŠ,potridlo,tekmovalci');

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
Vatera Ascender K-5 Blazer
$pdf->AddPage();
$pdf->SetFillColor(255, 255, 255);
$pdf->setCellPaddings(0, 0, 0, 0);
$pdf->setCellMargins(0, 0, 0, 0);
$pdf->SetFont('freeserif', 'B', 15,'',false);
$pdf->SetY(15);
//$pdf->SetX(50);
$na_strani=0;
$mesto=1;
$prejsni_tock=-1;
//$pdf->SetLeftMargin(60);
//$te=$pdf->Image('ozadje_priznanjaV.jpg', 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
$startne=SQL_RangLista();
//$pdf->SetFillColor(100,100,100);
    while ($startna = $startne->fetchArray(SQLITE3_NUM) ){
    	if(($prejsni_tock>$startna[1])&&($prejsni_tock!=-1)){
				$mesto++;
				}
				
    	//$kjeX=$pdf->GetX();
    	//$kjeY=$pdf->GetY();
    	if($ozadje){
    	$te=$pdf->Image('ozadje_priznanjaV.jpg', 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
    	}
		//$pdf->write2DBarcode('http://kolesarji.vov.si/tekmovalec.php?startna='.$startna[0], 'QRCODE,L', 15, $kjeY, 40, 40, $style, 'N'); 	
		//$pdf->SetX($kjeX);
		//$pdf->SetY($kjeY);*/
		$pdf->SetFont('freeserif', 'B', 12,'',false);
		$pdf->SetXY(58,22.5);
		$pdf->MultiCell(95, 7, 'mestne občine Ljubljana', 0, 'C', 1, 1, '', '', true);
		$pdf->SetXY(23,121);
		$pdf->SetFont('freeserif', 'B', 14,'',false);
		$pdf->MultiCell(117, 7, $startna[5].' '.$startna[6], 0, 'C', 1, 1, '', '', true);
		$pdf->SetXY(30,137);
		$pdf->SetFont('freeserif', 'B', 10,'',false);
    	$pdf->MultiCell(102.5, 8, $startna[7], 0, 'L', 1, 1, '', '', true);
    	$pdf->SetXY(30,152.5);
    	$pdf->MultiCell(93, 10, 'doseženo '.$mesto.' mesto', 0, 'L', 1, 1, '', '', true);
    	$pdf->SetXY(30,167.5);
    	$pdf->MultiCell(92, 10, 'kvalifikacijskem', 0, 'L', 1, 1, '', '', true);
    	$pdf->SetXY(30,195);
    	$pdf->MultiCell(83, 10, 'Ljubljana', 0, 'L', 1, 1, '', '', true);
    	$pdf->SetXY(30,206);
    	$pdf->MultiCell(81, 20, date("d. m. Y"), 0, 'L', 1, 1, '', '', true);
    //	$na_strani++;
    //	if($na_strani==5){
    	//	$na_strani=0;
    		$pdf->AddPage();
    		$pdf->SetY(15);
    //	}
    $prejsni_tock=$startna[1];
    }
   
  
//$pdf->write2DBarcode("http://kolesarji.vov.si/tekmovalec.php?startna=4", 'QRCODE,L', 15, 10, 20, 20, $style, 'N');
//$pdf->SetX(168);
//$qY=$pdf->Gety();
//$pdf->write2DBarcode("http://kolesarji.vov.si/tekmovalec.php?startna=4", 'QRCODE,L', 15, 10, 20, 20, $style, 'N');

$pdf->lastPage();



//Close and output PDF document
$pdf->Output('priznanja_tekmovalci.pdf', 'I');//D za vsiljen download I za odprtje v brskaniku

?>