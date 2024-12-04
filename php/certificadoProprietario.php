<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');

define('FPDF_FONTPATH','../class/fpdf/font/');
require('../class/fpdf/fpdf.php');

$query->exec("SELECT * FROM proprietario WHERE id_proprietario = {$id_proprietario}");
$query->result($query->linha);

$pdf = new FPDF("L");
$pdf->Open();
$pdf->AddPage();
$font = "Arial";

$left_margin = 20;
$top_margin = 30;

// Logo estado.
$pdf->SetXY($left_margin, $top_margin);
$pdf->Cell(250, 30, null, 1);
$pdf->Image("../img/BrasaoPelotas.jpg", 25 + $left_margin, $top_margin + 2, 26, 26);

$pdf->SetFont($font, 'B', 16);
$pdf->SetXY(65 + $left_margin, 4 + $top_margin);
$pdf->Cell(0, 10, "PREFEITURA MUNICIPAL DE PELOTAS");

$pdf->SetFont($font, '', 12);
$pdf->SetXY(65 + $left_margin, 10 + $top_margin);
$pdf->Cell(0, 10, "SECRETARIA DE DESENVOLVIMENTO RURAL");

$pdf->SetFont($font, 'B', 14);
$pdf->SetXY(65 + $left_margin, 16 + $top_margin);
$pdf->Cell(0, 10, utf8_decode("SERVIÇO DE APOIO ADMINISTRATIVO"));


$pdf->SetFont($font, '', 14);
$pdf->SetXY($left_margin, $top_margin + 31);
$pdf->Cell(250, 8, "CERTIFICADO DE REGISTROS DE MARCAS", 1, 0, "C");


$pdf->SetXY($left_margin, $top_margin + 40);
$pdf->Cell(250, 110, null, 1);

$pdf->Image("../assets/images/marcas/{$query->record['desenho_marca']}", 10 + $left_margin, 50 + $top_margin, 60, 60);

$pdf->SetFont($font, '', 11);
$pdf->SetXY(80 + $left_margin, 60 + $top_margin);
$pdf->MultiCell(
    160,
    6,
    utf8_decode(
        "\t\t\t\tCertifico, para todos os efeitos legais, de conformidade com o disposto no capítulo XV, " .
        "artigo 167, do Código de Posturas do Município de Pelotas, que o(a) Sr(a). {$query->record['nome']} " .
        "registrou nessa data, a marca desenhada à margem deste título, que usará em animais de sua propriedade."));
$pdf->SetXY(80 + $left_margin, $pdf->GetY() + 5);
$pdf->Cell(
    160,
    6,
    "Pelotas, ". strftime("%d/%m/%Y"),
    0,
    0,
    "R");

$pdf->SetFont($font, '', 10);
$pdf->SetXY(90 + $left_margin, 115 + $top_margin);
$pdf->MultiCell(50, 5, utf8_decode("_______________________ Ass. Secretário"), 0, "C");
$pdf->SetXY(160 + $left_margin, 115 + $top_margin);
$pdf->MultiCell(50, 5, utf8_decode("_______________________ Ass. Servidor"), 0, "C");

$pdf->Output();
?>
