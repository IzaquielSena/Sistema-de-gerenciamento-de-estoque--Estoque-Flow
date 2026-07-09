<?php 
session_start();
if(isset($_SESSION['usuario'])){
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>EstoqueFlow - Gestão de Usuários</title>
	<?php require_once "menu.php"; ?>
</head>
<body>

<div class="content-area fade-in">
	<div class="page-header">
		<h1>Gestão de Usuários</h1>
		<p>Adicione, edite ou remova usuários do sistema.</p>
	</div>

	<div class="row">
		<div class="col-sm-4">
			<div class="card-modern">
				<div class="card-header-modern">
					<h4><span class="glyphicon glyphicon-plus"></span> Novo Usuário</h4>
				</div>
				<div class="card-body-modern">
					<form id="frmRegistro">
						<div class="form-group-modern">
							<label>Nome <span class="required">*</span></label>
							<input type="text" class="form-control-modern" name="nome" id="nome" placeholder="Nome completo">
						</div>
						<div class="form-group-modern">
							<label>Usuário <span class="required">*</span></label>
							<input type="text" class="form-control-modern" name="usuario" id="usuario" placeholder="Nome de usuário">
						</div>
						<div class="form-group-modern">
							<label>Email <span class="required">*</span></label>
							<input type="text" class="form-control-modern" name="email" id="email" placeholder="email@exemplo.com">
						</div>
						<div class="form-group-modern">
							<label>Senha <span class="required">*</span></label>
							<input type="password" class="form-control-modern" name="senha" id="senha" placeholder="Senha do usuário">
						</div>
						<button type="button" class="btn-modern btn-primary-modern btn-block-modern" id="registro">
							<span class="glyphicon glyphicon-plus"></span> Salvar Usuário
						</button>
					</form>
				</div>
			</div>
		</div>
		<div class="col-sm-8">
			<div class="card-modern">
				<div class="card-header-modern">
					<h4><span class="glyphicon glyphicon-list"></span> Usuários Cadastrados</h4>
				</div>
				<div class="card-body-modern">
					<div class="row">
							<div class="col-sm-6">
								<div class="input-group" style="margin-bottom: 10px;">
									<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
									<input type="text" id="inputPesquisaUsuario" class="form-control" placeholder="Pesquisar usuário...">
								</div>
							</div>
						</div>
						<div id="tabelaUsuariosLoad"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="app-footer">
	EstoqueFlow &copy; <?php echo date('Y'); ?> - Sistema de Controle de Estoque
</div>

</div><!-- Close main-content -->

<!-- Modal Editar Usuário -->
<div class="modal fade" id="atualizaUsuarioModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Editar Usuário</h4>
			</div>
			<div class="modal-body">
				<form id="frmRegistroU">
					<input type="text" hidden="" id="idUsuario" name="idUsuario">
					<div class="form-group-modern">
						<label>Nome</label>
						<input type="text" class="form-control-modern" name="nomeU" id="nomeU">
					</div>
					<div class="form-group-modern">
						<label>Usuário</label>
						<input type="text" class="form-control-modern" name="usuarioU" id="usuarioU">
					</div>
					<div class="form-group-modern">
						<label>Email</label>
						<input type="text" class="form-control-modern" name="emailU" id="emailU">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn-modern btn-outline-modern" data-dismiss="modal">Cancelar</button>
				<button id="btnAtualizaUsuario" type="button" class="btn-modern btn-warning-modern" data-dismiss="modal">
					<span class="glyphicon glyphicon-pencil"></span> Salvar
				</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Redefinir Senha -->
<div class="modal fade" id="redefinirSenhaModal" tabindex="-1" role="dialog" aria-labelledby="redefinirSenhaLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="redefinirSenhaLabel"><span class="glyphicon glyphicon-lock"></span> Redefinir Senha</h4>
			</div>
			<div class="modal-body">
				<form id="frmRedefinirSenha">
					<input type="hidden" id="idUsuarioSenha" name="idUsuarioSenha">
					<div class="form-group-modern">
						<label>Usuário</label>
						<input type="text" class="form-control-modern" id="nomeUsuarioSenha" disabled>
					</div>
					<div class="form-group-modern">
						<label>Nova Senha <span class="required">*</span></label>
						<input type="password" class="form-control-modern" name="novaSenha" id="novaSenha" placeholder="Digite a nova senha">
					</div>
					<div class="form-group-modern">
						<label>Confirmar Senha <span class="required">*</span></label>
						<input type="password" class="form-control-modern" name="confirmarSenha" id="confirmarSenha" placeholder="Confirme a nova senha">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn-modern btn-outline-modern" data-dismiss="modal">Cancelar</button>
				<button id="btnRedefinirSenha" type="button" class="btn-modern btn-primary-modern">
					<span class="glyphicon glyphicon-lock"></span> Redefinir Senha
				</button>
			</div>
		</div>
	</div>
</div>

</body>
</html>

<script type="text/javascript">
	function adicionarDados(idusuario){
		$.ajax({
			type:"POST",
			data:"idusuario=" + idusuario,
			url:"../procedimentos/usuarios/obterDados.php",
			success:function(r){
				dado=jQuery.parseJSON(r);
				$('#idUsuario').val(dado['id']);
				$('#nomeU').val(dado['nome']);
				$('#usuarioU').val(dado['user']);
				$('#emailU').val(dado['email']);
			}
		});
	}

	function prepararRedefinirSenha(idusuario, nome){
		$('#idUsuarioSenha').val(idusuario);
		$('#nomeUsuarioSenha').val(nome);
		$('#novaSenha').val('');
		$('#confirmarSenha').val('');
	}

	function eliminarUsuario(idusuario){
		alertify.confirm('Deseja excluir este usuário?', function(){ 
			$.ajax({
				type:"POST",
				data:"idusuario=" + idusuario,
				url:"../procedimentos/usuarios/eliminarUsuario.php",
				success:function(r){
					if(r==1){
						$('#tabelaUsuariosLoad').load('usuarios/tabelaUsuarios.php', function() {
				filtrarTabela("inputPesquisaUsuario", "tabelaUsuariosDataTable");
			});
						alertify.success("Usuário excluído com sucesso!");
					}else{
						alertify.error("Não foi possível excluir");
					}
				}
			});
		}, function(){ 
			alertify.error('Cancelado!')
		});
	}

	$(document).ready(function(){
    // Pesquisa em tempo real
    $(document).on('input', '#inputPesquisaUsuario', function() {
        var v = $(this).val().toLowerCase();
        $('#tabelaUsuariosDataTable tbody tr').each(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(v) > -1);
        });
    });

		$('#tabelaUsuariosLoad').load('usuarios/tabelaUsuarios.php', function() {
				filtrarTabela("inputPesquisaUsuario", "tabelaUsuariosDataTable");
			});

		$('#btnAtualizaUsuario').click(function(){
			datos=$('#frmRegistroU').serialize();
			$.ajax({
				type:"POST",
				data:datos,
				url:"../procedimentos/usuarios/atualizarUsuario.php",
				success:function(r){
					if(r==1){
						$('#tabelaUsuariosLoad').load('usuarios/tabelaUsuarios.php', function() {
				filtrarTabela("inputPesquisaUsuario", "tabelaUsuariosDataTable");
			});
						alertify.success("Usuário editado com sucesso!");
					}else{
						alertify.error("Não foi possível editar");
					}
				}
			});
		});

		$('#btnRedefinirSenha').click(function(){
			var novaSenha = $('#novaSenha').val();
			var confirmarSenha = $('#confirmarSenha').val();

			if(novaSenha == '' || confirmarSenha == ''){
				alertify.alert("Preencha todos os campos!");
				return false;
			}

			if(novaSenha != confirmarSenha){
				alertify.alert("As senhas não coincidem!");
				return false;
			}

			if(novaSenha.length < 4){
				alertify.alert("A senha deve ter pelo menos 4 caracteres!");
				return false;
			}

			datos=$('#frmRedefinirSenha').serialize();
			$.ajax({
				type:"POST",
				data:datos,
				url:"../procedimentos/usuarios/redefinirSenha.php",
				success:function(r){
					if(r==1){
						$('#redefinirSenhaModal').modal('hide');
						$('#frmRedefinirSenha')[0].reset();
						alertify.success("Senha redefinida com sucesso!");
					}else if(r==2){
						alertify.error("As senhas não coincidem!");
					}else if(r==3){
						alertify.error("A senha deve ter pelo menos 4 caracteres!");
					}else{
						alertify.error("Falha ao redefinir senha");
					}
				}
			});
		});

		$('#registro').click(function(){
			vazios=validarFormVazio('frmRegistro');
			if(vazios > 0){
				alertify.alert("Preencha os campos!");
				return false;
			}
			datos=$('#frmRegistro').serialize();
			$.ajax({
				type:"POST",
				data:datos,
				url:"../procedimentos/login/registrarUsuario.php",
				success:function(r){
					if(r==1){
						$('#frmRegistro')[0].reset();
						$('#tabelaUsuariosLoad').load('usuarios/tabelaUsuarios.php', function() {
				filtrarTabela("inputPesquisaUsuario", "tabelaUsuariosDataTable");
			});
						alertify.success("Usuário adicionado com sucesso!");
					}else{
						alertify.error("Falha ao adicionar usuário");
					}
				}
			});
		});
	});
</script>

<?php
}else{
	header("location:../index.php");
}
?>
