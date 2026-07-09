<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
ob_start();

require_once '../../lib/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

require_once '../../classes/conexao.php';
require_once '../../classes/entradas_estoque.php';

session_start();

$c = new conectar();
$conexao = $c->conexao();
$obj_entrada = new entradas_estoque();

$sql = "SELECT DISTINCT p.id_produto, 
            p.nome, 
            c.nome_categoria
        FROM produtos p
        INNER JOIN categorias c ON p.id_categoria = c.id_categoria
        ORDER BY p.nome";

$result = mysqli_query($conexao, $sql);

$rows = "";
$valor_total_estoque = 0;

while($mostrar = mysqli_fetch_assoc($result)){
    $quantidade = $obj_entrada->obterQuantidadeTotal($mostrar['id_produto']);
    $preco      = $obj_entrada->obterPrecoAtual($mostrar['id_produto']);
    $valor_total = $quantidade * $preco;
    $valor_total_estoque += $valor_total;

    $rows .= "<tr>
        <td>{$mostrar['nome']}</td>
        <td>{$mostrar['nome_categoria']}</td>
        <td style='text-align:center;'>{$quantidade}</td>
        <td style='text-align:right;'>R$ " . number_format($preco, 2, ',', '.') . "</td>
        <td style='text-align:right;'>R$ " . number_format($valor_total, 2, ',', '.') . "</td>
    </tr>";
}

$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'sistema';

$html = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        h2 { text-align: center; margin-bottom: 4px; }
        p { text-align: center; margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px 8px; }
        th { background-color: #f2f2f2; text-align: center; }
        tr.total-row td { background-color: #e0e0e0; font-weight: bold; }
        .rodape { margin-top: 20px; font-size: 11px; color: #666; text-align: center; }
    </style>
</head>
<body>
    <h2>Lista de Produtos em Estoque</h2>
    <p>Gerado em: " . date('d/m/Y H:i:s') . "</p>
    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Categoria</th>
                <th>Quantidade</th>
                <th>Preço Unit.</th>
                <th>Valor Total</th>
            </tr>
        </thead>
        <tbody>
            {$rows}
            <tr class='total-row'>
                <td colspan='4' style='text-align:right;'>TOTAL DO ESTOQUE:</td>
                <td style='text-align:right;'>R$ " . number_format($valor_total_estoque, 2, ',', '.') . "</td>
            </tr>
        </tbody>
    </table>
    <p class='rodape'>Relatório gerado em " . date('d/m/Y H:i:s') . " por {$usuario}</p>
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
$pdf->stream('Lista_Produtos.pdf', array("Attachment" => false));
