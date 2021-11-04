<?php
require_once(dirname(__FILE__)."/../../../../frontend/components/MyPDF.php");

$html=$header;
$html.=$body;
$html.=$footer;
//$html.="</body></html>";

$pdf = new MyPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);

$pdf->SetAuthor('Simple Solutions');
$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT, 0);

$pdf->SetTitle(date("d.m.Y",time()));
$pdf->SetSubject('Simple Solutions');
$pdf->setDefaultFont();
$html=explode("<pagebreak>",$html);
for($i=0;$i<count($html);$i++){
	if(trim($html[$i])){
		$pdf->AddPage();
		$pdf->writeHTML($html[$i]);
		}
	}
$pdf->Output($fileName, 'D');
?>