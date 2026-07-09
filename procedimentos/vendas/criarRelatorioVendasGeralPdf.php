<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
ob_start(); 

require_once '../../lib/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

ob_start();
require_once "../../view/vendas/relatorioVendasGeralPdf.php";
$html = ob_get_clean();

if (!$html) {
    die("Erro: Não foi possível carregar o layout de vendas geral.");
}

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);

$pdf = new Dompdf($options);
$pdf->setPaper("letter", "portrait");
$pdf->loadHtml(trim($html));
$pdf->render();

ob_end_clean(); 
$pdf->stream('Relatorio_Geral_Vendas.pdf', array("Attachment" => false));