<?php 
require_once "../../classes/conexao.php";
require_once "../../classes/produtos.php"; 

$obj = new produtos();

$dataInicio = isset($_GET['inicio']) ? $_GET['inicio'] : date('Y-m-d');
$dataFim = isset($_GET['fim']) ? $_GET['fim'] : date('Y-m-d');

$result = $obj->relatorioEstoquePorData($dataInicio, $dataFim);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório de Estoque por Período</title>
    <style>
        body { font-family: sans-serif; margin: 30px; display: block; }
        h2 { text-align: center; display: block; }
        table { width: 100%; border-collapse: collapse; display: table; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div style="display: block;">
        <h2>Produtos Cadastrados no Período</h2>
        <p style="text-align: center;">
            De: <?php echo date("d/m/Y", strtotime($dataInicio)); ?> 
            até: <?php echo date("d/m/Y", strtotime($dataFim)); ?>
        </p>
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
                <?php 
                if(mysqli_num_rows($result) > 0):
                    while($ver = mysqli_fetch_row($result)): 
                ?>
                <tr>
                    <td><?php echo $ver[0]; ?></td>
                    <td><?php echo $ver[1]; ?></td>
                    <td><?php echo $ver[2]; ?></td>
                    <td><?php echo "R$ " . number_format($ver[3], 2, ',', '.'); ?></td>
                    <td><?php echo date("d/m/Y", strtotime($ver[4])); ?></td>
                </tr>
                <?php 
                    endwhile; 
                else:
                ?>
                <tr>
                    <td colspan="5">Nenhum produto encontrado neste período.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>