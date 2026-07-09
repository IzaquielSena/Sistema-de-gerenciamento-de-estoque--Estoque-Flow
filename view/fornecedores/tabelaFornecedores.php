<?php 
require_once "../../classes/conexao.php";
$c = new conectar();
$conexao=$c->conexao();

$sql = "SELECT id_fornecedor, rasaosocial, nomefantasia, endereco, email, telefone, cnpj FROM fornecedores";
$result = mysqli_query($conexao, $sql);
$total = mysqli_num_rows($result);
?>

<?php if($total > 0): ?>
<div class="table-responsive">
<table class="table-modern" id="tabelaFornecedoresDataTable">
    <thead>
        <tr>
            <th>Razão Social</th>
            <th>Nome Fantasia</th>
            <th>Endereço</th>
            <th>Email</th>
            <th>Telefone</th>
            <th>CNPJ</th>
            <th style="width:70px;text-align:center;">Editar</th>
            <th style="width:70px;text-align:center;">Excluir</th>
        </tr>
    </thead>
    <tbody>
    <?php while($mostrar = mysqli_fetch_row($result)): ?>
        <tr>
            <td><?php echo $mostrar[1]; ?></td>
            <td><?php echo $mostrar[2]; ?></td>
            <td><?php echo $mostrar[3]; ?></td>
            <td><?php echo $mostrar[4]; ?></td>
            <td><?php echo $mostrar[5]; ?></td>
            <td><?php echo $mostrar[6]; ?></td>
            <td style="text-align:center;">
                <button class="btn-modern btn-warning-modern btn-sm-modern" data-toggle="modal" data-target="#abremodalFornecedoresUpdate" onclick="adicionarDado('<?php echo $mostrar[0]; ?>')">
                    <span class="glyphicon glyphicon-pencil"></span>
                </button>
            </td>
            <td style="text-align:center;">
                <button class="btn-modern btn-danger-modern btn-sm-modern" onclick="eliminar('<?php echo $mostrar[0]; ?>')">
                    <span class="glyphicon glyphicon-remove"></span>
                </button>
            </td>
        </tr>
    <?php endWhile; ?>
    </tbody>
</table>
</div>
<?php else: ?>
<div class="alert-modern alert-info-modern">
    Nenhum fornecedor cadastrado. Adicione um novo fornecedor.
</div>
<?php endif; ?>
