<?php 
session_start();
if(isset($_SESSION['usuario'])){
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>EstoqueFlow - Fornecedores</title>
	<?php require_once "menu.php"; ?>
</head>
<body>

<div class="content-area fade-in">
	<div class="page-header">
		<h1>Fornecedores</h1>
		<p>Gerencie o cadastro de fornecedores do sistema.</p>
	</div>

	<div class="row">
		<div class="col-sm-4">
			<div class="card-modern">
				<div class="card-header-modern">
					<h4><span class="glyphicon glyphicon-plus"></span> Novo Fornecedor</h4>
				</div>
				<div class="card-body-modern">
					<form id="frmFornecedores">
						<div class="form-group-modern">
							<label>Razão Social <span class="required">*</span></label>
							<input type="text" class="form-control-modern" id="rasaosocial" name="rasaosocial" placeholder="Razão social da empresa">
						</div>
						<div class="form-group-modern">
							<label>Nome Fantasia <span class="required">*</span></label>
							<input type="text" class="form-control-modern" id="nomefantasia" name="nomefantasia" placeholder="Nome fantasia">
						</div>
						<div class="form-group-modern">
							<label>Endereço</label>
							<input type="text" class="form-control-modern" id="endereco" name="endereco" placeholder="Endereço completo">
						</div>
						<div class="form-group-modern">
							<label>Email</label>
							<input type="text" class="form-control-modern" id="email" name="email" placeholder="email@empresa.com">
						</div>
						<div class="form-group-modern">
							<label>Telefone</label>
							<input type="text" class="form-control-modern" id="telefone" name="telefone" placeholder="(00) 0000-0000">
						</div>
						<div class="form-group-modern">
							<label>CNPJ</label>
							<input type="text" class="form-control-modern" id="cnpj" name="cnpj" placeholder="00.000.000/0000-00">
						</div>
						<button type="button" class="btn-modern btn-primary-modern btn-block-modern" id="btnAdicionarFornecedores">
							<span class="glyphicon glyphicon-plus"></span> Salvar Fornecedor
						</button>
					</form>
				</div>
			</div>
		</div>
		<div class="col-sm-8">
			<div class="card-modern">
				<div class="card-header-modern">
					<h4><span class="glyphicon glyphicon-list"></span> Fornecedores Cadastrados</h4>
				</div>
				<div class="card-body-modern">
					<div class="input-group" style="max-width:320px;margin-bottom:14px;">
						<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
						<input type="text" id="inputPesquisaFornecedor" class="form-control" placeholder="Pesquisar fornecedor...">
					</div>
					<div id="tabelaFornecedoresLoad"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="app-footer">
	EstoqueFlow &copy; <?php echo date('Y'); ?> - Sistema de Controle de Estoque
</div>

</div><!-- Close main-content -->

<!-- Modal Atualizar Fornecedor -->
<div class="modal fade" id="abremodalFornecedoresUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Editar Fornecedor</h4>
			</div>
			<div class="modal-body">
				<form id="frmFornecedoresU">
					<input type="text" hidden="" id="idfornecedorU" name="idfornecedorU">
					<div class="form-group-modern">
						<label>Razão Social</label>
						<input type="text" class="form-control-modern" id="rasaosocialU" name="rasaosocialU">
					</div>
					<div class="form-group-modern">
						<label>Nome Fantasia</label>
						<input type="text" class="form-control-modern" id="nomefantasiaU" name="nomefantasiaU">
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
						<label>CNPJ</label>
						<input type="text" class="form-control-modern" id="cnpjU" name="cnpjU">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn-modern btn-outline-modern" data-dismiss="modal">Cancelar</button>
				<button id="btnAdicionarFornecedorU" type="button" class="btn-modern btn-warning-modern" data-dismiss="modal">
					<span class="glyphicon glyphicon-pencil"></span> Salvar
				</button>
			</div>
		</div>
	</div>
</div>

</body>
</html>

<script type="text/javascript">
	function adicionarDado(idfornecedor){
		$.ajax({
			type:"POST",
			data:"idfornecedor=" + idfornecedor,
			url:"../procedimentos/fornecedores/obterDadosFornecedores.php",
			success:function(r){
				dado=jQuery.parseJSON(r);
				$('#idfornecedorU').val(dado['id_fornecedor']);
				$('#rasaosocialU').val(dado['rasaosocial']);
				$('#nomefantasiaU').val(dado['nomefantasia']);
				$('#enderecoU').val(dado['endereco']);
				$('#emailU').val(dado['email']);
				$('#telefoneU').val(dado['telefone']);
				$('#cnpjU').val(dado['cnpj']);
			}
		});
	}

	function eliminar(idfornecedor){
		alertify.confirm('Deseja excluir este fornecedor?', function(){ 
			$.ajax({
				type:"POST",
				data:"idfornecedor=" + idfornecedor,
				url:"../procedimentos/fornecedores/eliminarFornecedores.php",
				success:function(r){
					if(r==1){
						$('#tabelaFornecedoresLoad').load("fornecedores/tabelaFornecedores.php", function() { filtrarTabela("inputPesquisaFornecedor", "tabelaFornecedoresDataTable"); });
						alertify.success("Fornecedor excluído com sucesso!");
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
    $(document).on('input', '#inputPesquisaFornecedor', function() {
        var v = $(this).val().toLowerCase();
        $('#tabelaFornecedoresDataTable tbody tr').each(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(v) > -1);
        });
    });

		$('#tabelaFornecedoresLoad').load("fornecedores/tabelaFornecedores.php", function() { filtrarTabela("inputPesquisaFornecedor", "tabelaFornecedoresDataTable"); });

		$('#btnAdicionarFornecedores').click(function(){
			vazios=validarFormVazio('frmFornecedores');
			if(vazios > 0){
				alertify.alert("Preencha os campos!");
				return false;
			}
			dados=$('#frmFornecedores').serialize();
			$.ajax({
				type:"POST",
				data:dados,
				url:"../procedimentos/fornecedores/adicionarFornecedores.php",
				success:function(r){
					if(r==1){
						$('#frmFornecedores')[0].reset();
						$('#tabelaFornecedoresLoad').load("fornecedores/tabelaFornecedores.php", function() { filtrarTabela("inputPesquisaFornecedor", "tabelaFornecedoresDataTable"); });
						alertify.success("Fornecedor adicionado com sucesso!");
					}else{
						alertify.error("Não foi possível adicionar");
					}
				}
			});
		});

		$('#btnAdicionarFornecedorU').click(function(){
			dados=$('#frmFornecedoresU').serialize();
			$.ajax({
				type:"POST",
				data:dados,
				url:"../procedimentos/fornecedores/atualizarFornecedores.php",
				success:function(r){
					if(r==1){
						$('#frmFornecedores')[0].reset();
						$('#tabelaFornecedoresLoad').load("fornecedores/tabelaFornecedores.php", function() { filtrarTabela("inputPesquisaFornecedor", "tabelaFornecedoresDataTable"); });
						alertify.success("Fornecedor atualizado com sucesso!");
					}else{
						alertify.error("Não foi possível atualizar fornecedor");
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
