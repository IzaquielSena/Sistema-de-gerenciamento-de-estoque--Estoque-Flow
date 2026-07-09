<?php 
session_start();
if(isset($_SESSION['usuario'])){
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>EstoqueFlow - Clientes</title>
	<?php require_once "menu.php"; ?>
</head>
<body>

<div class="content-area fade-in">
	<div class="page-header">
		<h1>Clientes</h1>
		<p>Gerencie o cadastro de clientes do sistema.</p>
	</div>

	<div class="row">
		<div class="col-sm-4">
			<div class="card-modern">
				<div class="card-header-modern">
					<h4><span class="glyphicon glyphicon-plus"></span> Novo Cliente</h4>
				</div>
				<div class="card-body-modern">
					<form id="frmClientes">
						<div class="form-group-modern">
							<label>Nome <span class="required">*</span></label>
							<input type="text" class="form-control-modern" id="nome" name="nome" placeholder="Nome do cliente">
						</div>
						<div class="form-group-modern">
							<label>Sobrenome <span class="required">*</span></label>
							<input type="text" class="form-control-modern" id="sobrenome" name="sobrenome" placeholder="Sobrenome">
						</div>
						<div class="form-group-modern">
							<label>Endereço</label>
							<input type="text" class="form-control-modern" id="endereco" name="endereco" placeholder="Endereço completo">
						</div>
						<div class="form-group-modern">
							<label>Email</label>
							<input type="text" class="form-control-modern" id="email" name="email" placeholder="email@exemplo.com">
						</div>
						<div class="form-group-modern">
							<label>Telefone</label>
							<input type="text" class="form-control-modern" id="telefone" name="telefone" placeholder="(00) 00000-0000">
						</div>
						<div class="form-group-modern">
							<label>CPF</label>
							<input type="text" class="form-control-modern" id="cpf" name="cpf" placeholder="000.000.000-00">
						</div>
						<button type="button" class="btn-modern btn-primary-modern btn-block-modern" id="btnAdicionarCliente">
							<span class="glyphicon glyphicon-plus"></span> Salvar Cliente
						</button>
					</form>
				</div>
			</div>
		</div>
		<div class="col-sm-8">
			<div class="card-modern">
				<div class="card-header-modern">
					<h4><span class="glyphicon glyphicon-list"></span> Clientes Cadastrados</h4>
				</div>
				<div class="card-body-modern">
					<div class="row">
							<div class="col-sm-6">
								<div class="input-group" style="margin-bottom: 10px;">
									<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
									<input type="text" id="inputPesquisaCliente" class="form-control" placeholder="Pesquisar cliente...">
								</div>
							</div>
						</div>
						<div id="tabelaClientesLoad"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="app-footer">
	EstoqueFlow &copy; <?php echo date('Y'); ?> - Sistema de Controle de Estoque
</div>

</div><!-- Close main-content -->

<!-- Modal Atualizar Cliente -->
<div class="modal fade" id="abremodalClientesUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Editar Cliente</h4>
			</div>
			<div class="modal-body">
				<form id="frmClientesU">
					<input type="text" hidden="" id="idclienteU" name="idclienteU">
					<div class="form-group-modern">
						<label>Nome</label>
						<input type="text" class="form-control-modern" id="nomeU" name="nomeU">
					</div>
					<div class="form-group-modern">
						<label>Sobrenome</label>
						<input type="text" class="form-control-modern" id="sobrenomeU" name="sobrenomeU">
					</div>
					<div class="form-group-modern">
						<label>Endereço</label>
						<input type="text" class="form-control-modern" id="enderecoU" name="enderecoU">
					</div>
					<div class="form-group-modern">
						<label>Email</label>
						<input type="text" class="form-control-modern" id="emailU" name="emailU">
					</div>
					<div class="form-group-modern">
						<label>Telefone</label>
						<input type="text" class="form-control-modern" id="telefoneU" name="telefoneU">
					</div>
					<div class="form-group-modern">
						<label>CPF</label>
						<input type="text" class="form-control-modern" id="cpfU" name="cpfU">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn-modern btn-outline-modern" data-dismiss="modal">Cancelar</button>
				<button id="btnAdicionarClienteU" type="button" class="btn-modern btn-warning-modern" data-dismiss="modal">
					<span class="glyphicon glyphicon-pencil"></span> Salvar
				</button>
			</div>
		</div>
	</div>
</div>

</body>
</html>

<script type="text/javascript">
	function adicionarDado(idcliente){
		$.ajax({
			type:"POST",
			data:"idcliente=" + idcliente,
			url:"../procedimentos/clientes/obterDadosCliente.php",
			success:function(r){
				dado=jQuery.parseJSON(r);
				$('#idclienteU').val(dado['id_cliente']);
				$('#nomeU').val(dado['nome']);
				$('#sobrenomeU').val(dado['sobrenome']);
				$('#enderecoU').val(dado['endereco']);
				$('#emailU').val(dado['email']);
				$('#telefoneU').val(dado['telefone']);
				$('#cpfU').val(dado['cpf']);
			}
		});
	}

	function eliminarCliente(idcliente){
		alertify.confirm('Deseja excluir este cliente?', function(){ 
			$.ajax({
				type:"POST",
				data:"idcliente=" + idcliente,
				url:"../procedimentos/clientes/eliminarClientes.php",
				success:function(r){
					if(r==1){
						$('#tabelaClientesLoad').load("clientes/tabelaClientes.php", function() {
				filtrarTabela("inputPesquisaCliente", "tabelaClientesDataTable");
			});
						alertify.success("Cliente excluído com sucesso!");
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
    $(document).on('input', '#inputPesquisaCliente', function() {
        var v = $(this).val().toLowerCase();
        $('#tabelaClientesDataTable tbody tr').each(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(v) > -1);
        });
    });

		$('#tabelaClientesLoad').load("clientes/tabelaClientes.php", function() {
				filtrarTabela("inputPesquisaCliente", "tabelaClientesDataTable");
			});

		$('#btnAdicionarCliente').click(function(){
			vazios=validarFormVazio('frmClientes');
			if(vazios > 0){
				alertify.alert("Preencha os campos!");
				return false;
			}
			dados=$('#frmClientes').serialize();
			$.ajax({
				type:"POST",
				data:dados,
				url:"../procedimentos/clientes/adicionarClientes.php",
				success:function(r){
					if(r==1){
						$('#frmClientes')[0].reset();
						$('#tabelaClientesLoad').load("clientes/tabelaClientes.php", function() {
				filtrarTabela("inputPesquisaCliente", "tabelaClientesDataTable");
			});
						alertify.success("Cliente adicionado com sucesso!");
					}else{
						alertify.error("Não foi possível adicionar");
					}
				}
			});
		});

		$('#btnAdicionarClienteU').click(function(){
			dados=$('#frmClientesU').serialize();
			$.ajax({
				type:"POST",
				data:dados,
				url:"../procedimentos/clientes/atualizarClientes.php",
				success:function(r){
					if(r==1){
						$('#frmClientes')[0].reset();
						$('#tabelaClientesLoad').load("clientes/tabelaClientes.php", function() {
				filtrarTabela("inputPesquisaCliente", "tabelaClientesDataTable");
			});
						alertify.success("Cliente atualizado com sucesso!");
					}else{
						alertify.error("Não foi possível atualizar cliente");
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
