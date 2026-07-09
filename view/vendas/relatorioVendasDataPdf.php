<?php 
require_once "../../classes/conexao.php";
require_once "../../classes/vendas.php";

$obj = new vendas();
$dataInicio = $_GET['inicio'];
$dataFim = $_GET['fim'];

$result = $obj->relatorioVendasPorData($dataInicio, $dataFim);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório de Vendas</title>
    <style>
        /* CSS para garantir elementos de bloco */
        body { font-family: sans-serif; margin: 20px; display: block; }
        h2 { text-align: center; display: block; }
        table { width: 100%; border-collapse: collapse; display: table; }
        th, td { border: 1px solid #333; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div>
        <h2>Relatório de Vendas</h2>
        <p style="text-align: center;">Período: <?php echo date("d/m/Y", strtotime($dataInicio)) ?> até <?php echo date("d/m/Y", strtotime($dataFim)) ?></p>
        
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Data</th>
                    <th>Cliente</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalGeral = 0;
                while($ver = mysqli_fetch_row($result)): 
                    $totalVenda = $obj->obterTotal($ver[0]);
                    $totalGeral += $totalVenda;
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
                <tr style="background-color: #eee; font-weight: bold;">
                    <td colspan="3" style="text-align: right;">Total Geral:</td>
                    <td><?php echo "R$ ".number_format($totalGeral, 2, ',', '.') ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>