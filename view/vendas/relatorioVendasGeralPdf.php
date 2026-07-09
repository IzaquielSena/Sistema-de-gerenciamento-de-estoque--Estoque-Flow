<?php 
require_once "../../classes/conexao.php";
require_once "../../classes/vendas.php";

$obj = new vendas();
$result = $obj->obterTodasVendasGeral();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório Geral de Vendas</title>
    <style>
        body { font-family: sans-serif; margin: 30px; display: block; }
        h2 { text-align: center; display: block; }
        table { width: 100%; border-collapse: collapse; display: table; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .total { font-weight: bold; background-color: #eee; }
    </style>
</head>
<body>
    <div style="display: block;">
        <h2>Relatório Consolidado de Todas as Vendas</h2>
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Data</th>
                    <th>Cliente</th>
                    <th>Total da Venda</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalAcumulado = 0;
                while($ver = mysqli_fetch_row($result)): 
                    $totalVenda = $obj->obterTotal($ver[0]);
                    $totalAcumulado += $totalVenda;
                ?>
                <tr>
                    <td>#<?php echo $ver[0] ?></td>
                    <td><?php echo date("d/m/Y", strtotime($ver[1])) ?></td>
                    <td><?php echo $obj->nomeCliente($ver[2]) ?></td>
                    <td><?php echo "R$ ".number_format($totalVenda, 2, ',', '.') ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr class="total">
                    <td colspan="3" style="text-align: right;">Total Geral Bruto:</td>
                    <td><?php echo "R$ ".number_format($totalAcumulado, 2, ',', '.') ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>