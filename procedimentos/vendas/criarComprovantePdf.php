<?php
ob_start();

require_once '../../lib/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$codigovenda = isset($_GET['codigovenda']) ? $_GET['codigovenda'] : null;

if (!$codigovenda) {
    die("Código da venda não fornecido.");
}

// Em vez de usar cURL com URL estática (que causa erro 404 se a pasta mudar), 
// usamos inclusão direta para capturar o HTML.
$_GET['codigovenda'] = $codigovenda;
ob_start();
require_once "../../view/vendas/comprovanteVendaPdf.php";
$html = ob_get_clean();

if (!$html) {
    ob_end_clean();
    die("Erro ao capturar o HTML do comprovante.");
}

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);

$pdf = new Dompdf($options);
$pdf->setPaper(array(0, 0, 125, 250)); 
$pdf->loadHtml(trim($html));
$pdf->render();

ob_end_clean();

$pdf->stream('comprovante_venda.pdf', array("Attachment" => false));
