<?php
$cert_data["bg_tpl"] = "cert_SINDIV_01.png";
$cert_data["nome"] = $cert_data["nome"] != false ? $cert_data["nome"] : "";
$cert_data["curso"] = $cert_data["curso"] != false ? $cert_data["curso"] : "";
$cert_data["localData"] = $cert_data["localData"] != false ? $cert_data["localData"] : "";
$cert_data["filename"] = $cert_data["filename"] != false ? $cert_data["filename"] : "temp";
$cert_data["output"] = $cert_data["output"] != false ? $cert_data["output"] : "F";

if($cert_data["texto"] == false){
	$cert_data["texto"][] = "participou como ouvinte do VI Simpósio Internacional de Diagnóstico";
	$cert_data["texto"][] = "por Imagem Veterinário - SINDIV, Realizado nos dias 24 e 25 de novembro";
	$cert_data["texto"][] = "de 2016, na cidade de Florianópolis, Santa Catarina, Brasil, promovido pela";
	$cert_data["texto"][] = "Associação Brasileira de Radiologia Veterinária - ABRV. Carga horária: 16 horas.";
}


$pdf = new FPDF();
// $pdf->AddFont('Museo','','Museo.php');
$pdf->AddPage('L', "A4");

$pdf->Image("certificados/".$cert_data["bg_tpl"],0,0, -300);


$pdf->SetFont('Helvetica', "B",32);
$pdf->Cell(0,60, "", 0, 1, 'C' );
$pdf->Cell(0,16, utf8_decode($cert_data["nome"]), 0, 1, 'C' );

$pdf->SetFont('Helvetica', "",18);
$pdf->Cell(0,8 , "", 0, 1, 'C' );
$pdf->MultiCell(0,8, utf8_decode($cert_data["texto"][0]) , 0,'C',false);

$pdf->SetFont('Helvetica', "",18);
$pdf->MultiCell(0,8, utf8_decode($cert_data["texto"][1]) , 0,'C',false);

$pdf->SetFont('Helvetica', "",18);
$pdf->MultiCell(0,8, utf8_decode($cert_data["texto"][2]) , 0,'C',false);

$pdf->SetFont('Helvetica', "",18);
$pdf->MultiCell(0,8, utf8_decode($cert_data["texto"][3]) , 0,'C',false);

$fullpath = "../../download/certificados/".$cert_data["filename"] .".pdf";

$pdf->Output($cert_data["output"] , $fullpath);
// $pdf->Output("F", "filename.pdf");
?>
