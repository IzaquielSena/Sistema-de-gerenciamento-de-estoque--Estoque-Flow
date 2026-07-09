<?php 
    require_once "../../classes/conexao.php";
    $c= new conectar();
    $conexao=$c->conexao();

    $sql="SELECT id, nome, user, email from usuarios";
    $result=mysqli_query($conexao, $sql);
    $total = mysqli_num_rows($result);
?>

<?php if($total > 0): ?>
<table class="table-modern" id="tabelaUsuariosDataTable">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Usuário</th>
            <th>Email</th>
            <th style="width:70px;text-align:center;">Editar</th>
            <th style="width:70px;text-align:center;">Senha</th>
            <th style="width:70px;text-align:center;">Excluir</th>
        </tr>
    </thead>
    <tbody>
    <?php while($mostrar = mysqli_fetch_row($result)): ?>
        <tr>
            <td><?php echo $mostrar[1]; ?></td>
            <td><?php echo $mostrar[2]; ?></td>
            <td><?php echo $mostrar[3]; ?></td>
            <td style="text-align:center;">
                <button data-toggle="modal" data-target="#atualizaUsuarioModal" class="btn-modern btn-warning-modern btn-sm-modern" onclick="adicionarDados('<?php echo $mostrar[0]; ?>')">
                    <span class="glyphicon glyphicon-pencil"></span>
                </button>
            </td>
            <td style="text-align:center;">
                <button data-toggle="modal" data-target="#redefinirSenhaModal" class="btn-modern btn-primary-modern btn-sm-modern" onclick="prepararRedefinirSenha('<?php echo $mostrar[0]; ?>', '<?php echo $mostrar[1]; ?>')">
                    <span class="glyphicon glyphicon-lock"></span>
                </button>
            </td>
            <td style="text-align:center;">
                <button class="btn-modern btn-danger-modern btn-sm-modern" onclick="eliminarUsuario('<?php echo $mostrar[0]; ?>')">
                    <span class="glyphicon glyphicon-remove"></span>
                </button>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<?php else: ?>
<div class="alert-modern alert-info-modern">
    Nenhum usuário cadastrado.
</div>
<?php endif; ?>
