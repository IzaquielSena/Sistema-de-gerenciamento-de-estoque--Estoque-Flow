<?php 
require_once "../../classes/conexao.php";

$c = new conectar();
$conexao = $c->conexao();

$sql = "SELECT p.nome, 
            p.descricao,
            COALESCE(SUM(e.quantidade), 0) as quantidade,
            COALESCE(MAX(e.preco), 0) as preco,
            p.dataCaptura
        FROM produtos p
        LEFT JOIN entradas_estoque e ON p.id_produto = e.id_produto
        GROUP BY p.id_produto
        ORDER BY p.nome";

$result = mysqli_query($conexao, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; margin: 20px; display: block; }
        table { width: 100%; border-collapse: collapse; display: table; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div style="display: block;">
        <h2 style="text-align: center;">Relatório Geral de Estoque</h2>
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
                <?php while($ver = mysqli_fetch_row($result)): ?>
                <tr>
                    <td><?php echo $ver[0]; ?></td>
                    <td><?php echo $ver[1]; ?></td>
                    <td><?php echo $ver[2]; ?></td>
                    <td><?php echo "R$ " . number_format($ver[3], 2, ',', '.'); ?></td>
                    <td><?php echo date("d/m/Y", strtotime($ver[4])); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>