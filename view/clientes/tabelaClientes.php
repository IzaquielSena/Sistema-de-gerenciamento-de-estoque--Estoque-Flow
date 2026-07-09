<?php 
require_once "../../classes/conexao.php";
$c = new conectar();
$conexao=$c->conexao();

$sql = "SELECT id_cliente, nome, sobrenome, endereco, email, telefone, cpf FROM clientes";
$result = mysqli_query($conexao, $sql);
$total = mysqli_num_rows($result);
?>

<?php if($total > 0): ?>
<div class="table-responsive">
<table class="table-modern" id="tabelaClientesDataTable">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Sobrenome</th>
            <th>Endereço</th>
            <th>Email</th>
            <th>Telefone</th>
            <th>CPF</th>
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
                <button class="btn-modern btn-warning-modern btn-sm-modern" data-toggle="modal" data-target="#abremodalClientesUpdate" onclick="adicionarDado('<?php echo $mostrar[0]; ?>')">
                    <span class="glyphicon glyphicon-pencil"></span>
                </button>
            </td>
            <td style="text-align:center;">
                <button class="btn-modern btn-danger-modern btn-sm-modern" onclick="eliminarCliente('<?php echo $mostrar[0]; ?>')">
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
    Nenhum cliente cadastrado. Adicione um novo cliente.
</div>
<?php endif; ?>
