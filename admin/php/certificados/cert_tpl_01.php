<?php
$cert_data["bg_tpl"] = "cert_tpl_01.png";
$cert_data["nome"] = $cert_data["nome"] != false ? $cert_data["nome"] : "";
$cert_data["curso"] = $cert_data["curso"] != false ? $cert_data["curso"] : "";
$cert_data["localData"] = $cert_data["localData"] != false ? $cert_data["localData"] : "em São Paulo, no dia 26 de junho de 2016, com duração de 07 horas.";
$cert_data["filename"] = $cert_data["filename"] != false ? $cert_data["filename"] : "temp";
$cert_data["output"] = $cert_data["output"] != false ? $cert_data["output"] : "F"; 

if($cert_data["texto"] == false){
	$cert_data["texto"][] = "A Associação Brasileira de Radiologia Veterinária certifica que";
	$cert_data["texto"][] = "participou como ouvinte do curso";
	$cert_data["texto"][] = "apoiado pelo Colégio Brasileiro de Radiologia Veterinária
	e realizado na Universidade Paulista (Campus Indianópolis)";
}


$pdf = new FPDF();
$pdf->AddPage('L', "A4");

$pdf->Image("certificados/".$cert_data["bg_tpl"],0,0, -150);

$pdf->SetFont('Helvetica', "",18);
$pdf->Cell(0,75, "", 0, 1, 'C' );
$pdf->Cell(0,8, utf8_decode($cert_data["texto"][0]), 0, 1, 'C' );

$pdf->SetFont('Helvetica', "B",18);
$pdf->Cell(0,8, utf8_decode($cert_data["nome"]), 0, 1, 'C' );

$pdf->SetFont('Helvetica', "",18);
$pdf->MultiCell(0,8, utf8_decode($cert_data["texto"][1]) , 0,'C',false);

$pdf->SetFont('Helvetica', "B",18);
$pdf->Cell(0,8, utf8_decode($cert_data["curso"]), 0, 1, 'C' );

$pdf->SetFont('Helvetica', "",18);
$pdf->MultiCell(0,8, utf8_decode($cert_data["texto"][2]) , 0,'C',false);

$pdf->SetFont('Helvetica', "",18);
$pdf->Cell(0,8, utf8_decode($cert_data["localData"]), 0, 1, 'C' );

$fullpath = "../../download/certificados/".$cert_data["filename"] .".pdf";

$pdf->Output($cert_data["output"] , $fullpath);
// $pdf->Output("F", "filename.pdf");
?>