<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
ob_start();

require_once '../../lib/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

require_once '../../classes/conexao.php';

$dataInicio = $_POST['dataInicio'];
$dataFim    = $_POST['dataFim'];

$c = new conectar();
$conexao = $c->conexao();

$sql = "SELECT p.nome, 
            p.descricao,
            COALESCE(SUM(e.quantidade), 0) as quantidade,
            COALESCE(MAX(e.preco), 0) as preco,
            p.dataCaptura
        FROM produtos p
        LEFT JOIN entradas_estoque e ON p.id_produto = e.id_produto
        WHERE p.dataCaptura BETWEEN '$dataInicio' AND '$dataFim'
        GROUP BY p.id_produto
        ORDER BY p.nome";

$result = mysqli_query($conexao, $sql);

$rows = "";
$total = 0;
while($ver = mysqli_fetch_row($result)){
    $valorTotal = $ver[2] * $ver[3];
    $total += $valorTotal;
    $rows .= "<tr>
        <td>{$ver[0]}</td>
        <td>{$ver[1]}</td>
        <td>{$ver[2]}</td>
        <td>R$ " . number_format($ver[3], 2, ',', '.') . "</td>
        <td>" . date('d/m/Y', strtotime($ver[4])) . "</td>
    </tr>";
}

if(empty($rows)){
    $rows = "<tr><td colspan='5'>Nenhum produto encontrado neste período.</td></tr>";
}

$dataInicioFormatada = date('d/m/Y', strtotime($dataInicio));
$dataFimFormatada    = date('d/m/Y', strtotime($dataFim));

$html = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        h2 { text-align: center; }
        p { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .total { font-weight: bold; background-color: #eee; }
    </style>
</head>
<body>
    <h2>Relatório de Estoque por Período</h2>
    <p>De: $dataInicioFormatada até: $dataFimFormatada</p>
    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Descrição</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <th>Data Cadastro</th>
            </tr>
        </thead>
        <tbody>
            $rows
        </tbody>
    </table>
</body>
</html>";

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);

$pdf = new Dompdf($options);
$pdf->setPaper("letter", "portrait");
$pdf->loadHtml(trim($html));
$pdf->render();

ob_end_clean();
$pdf->stream('Estoque_Periodo.pdf', array("Attachment" => false));