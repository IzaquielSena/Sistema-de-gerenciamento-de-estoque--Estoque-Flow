<?php 
require_once "../../classes/conexao.php";
$c = new conectar();
$conexao=$c->conexao();

$sql = "SELECT id_categoria, nome_categoria FROM categorias";
$result = mysqli_query($conexao, $sql);
$total = mysqli_num_rows($result);
?>

<?php if($total > 0): ?>
<table class="table-modern" id="tabelaCategoriasDataTable">
    <thead>
        <tr>
            <th>Categoria</th>
            <th style="width:80px;text-align:center;">Editar</th>
            <th style="width:80px;text-align:center;">Excluir</th>
        </tr>
    </thead>
    <tbody>
    <?php while($mostrar = mysqli_fetch_row($result)): ?>
        <tr>
            <td><?php echo $mostrar[1]; ?></td>
            <td style="text-align:center;">
                <button class="btn-modern btn-warning-modern btn-sm-modern" data-toggle="modal" data-target="#atualizaCategoria" onclick="adicionarDado('<?php echo $mostrar[0]; ?>','<?php echo $mostrar[1]; ?>')">
                    <span class="glyphicon glyphicon-pencil"></span>
                </button>
            </td>
            <td style="text-align:center;">
                <button class="btn-modern btn-danger-modern btn-sm-modern" onclick="eliminaCategoria('<?php echo $mostrar[0]; ?>')">
                    <span class="glyphicon glyphicon-remove"></span>
                </button>
            </td>
        </tr>
    <?php endWhile; ?>
    </tbody>
</table>
<?php else: ?>
<div class="alert-modern alert-info-modern">
    Nenhuma categoria cadastrada. Adicione uma nova categoria.
</div>
<?php endif; ?>
