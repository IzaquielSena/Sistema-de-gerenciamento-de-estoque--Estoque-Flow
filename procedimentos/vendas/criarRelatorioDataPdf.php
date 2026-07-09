<?php
// Silencia avisos de compatibilidade
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
ob_start(); 

require_once '../../lib/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$dataInicio = $_POST['dataInicio'];
$dataFim = $_POST['dataFim'];

$_GET['inicio'] = $dataInicio;
$_GET['fim'] = $dataFim;

ob_start();
require_once "../../view/vendas/relatorioVendasDataPdf.php";
$html = ob_get_clean();

// Se o HTML vier vazio ou der erro na URL, interrompe antes de dar erro no Dompdf
if (!$html || trim($html) == "") {
    die("Erro: O conteúdo do relatório está vazio ou a URL é inacessível. <br> Verifique se este link abre no seu navegador: <a href='$url'>$url</a>");
}

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);

$pdf = new Dompdf($options);
$pdf->setPaper("letter", "portrait");

// Carrega o HTML garantindo que ele não tenha espaços extras no início
$pdf->loadHtml(trim($html));

$pdf->render();

ob_end_clean(); 
$pdf->stream('Relatorio_Vendas_Periodo.pdf', array("Attachment" => false));