<?php 
session_start();
if(isset($_SESSION['usuario'])){
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>EstoqueFlow - Cadastro de Produtos</title>
	<?php require_once "menu.php"; ?>
	<?php require_once "../classes/conexao.php"; 
	$c= new conectar();
	$conexao=$c->conexao();
	$sql="SELECT id_categoria,nome_categoria from categorias";
	$result=mysqli_query($conexao,$sql);
	?>
</head>
<body>

<div class="content-area fade-in">
	<div class="page-header">
		<h1>Cadastro de Produtos</h1>
		<p>Cadastre novos produtos com nome, categoria, descrição e imagem.</p>
	</div>

	<div class="row">
		<div class="col-sm-5">
			<div class="card-modern">
				<div class="card-header-modern">
					<h4><span class="glyphicon glyphicon-plus"></span> Novo Produto</h4>
				</div>
				<div class="card-body-modern">
					<form id="frmCadastroProdutos" enctype="multipart/form-data">
						<div class="form-group-modern">
							<label>Categoria <span class="required">*</span></label>
							<select class="form-control input-sm" id="categoriaSelect" name="categoriaSelect" required>
								<option value="">Selecionar Categoria</option>
								<?php while($mostrar=mysqli_fetch_row($result)): ?>
									<option value="<?php echo $mostrar[0] ?>"><?php echo $mostrar[1]; ?></option>
								<?php endwhile; ?>
							</select>
						</div>

						<div class="form-group-modern">
							<label>Nome do Produto <span class="required">*</span></label>
							<input type="text" class="form-control-modern" id="nome" name="nome" placeholder="Digite o nome do produto" required>
						</div>

						<div class="form-group-modern">
							<label>Descrição <span class="required">*</span></label>
							<textarea class="form-control-modern" id="descricao" name="descricao" placeholder="Digite a descrição do produto" rows="3" required></textarea>
						</div>

						<div class="form-group-modern">
							<label>Imagem do Produto <span class="required">*</span></label>
							<input type="file" class="form-control-modern" id="imagem" name="imagem" accept="image/*" required>
							<small style="color: var(--text-muted); font-size: 0.8rem;">Formatos aceitos: JPG, PNG, GIF, WEBP</small>
						</div>

						<div style="display:flex; gap:8px;">
							<button type="button" id="btnAddProduto" class="btn-modern btn-primary-modern" style="flex:1;">
								<span class="glyphicon glyphicon-plus"></span> Cadastrar Produto
							</button>
							<button type="reset" class="btn-modern btn-outline-modern">
								<span class="glyphicon glyphicon-refresh"></span> Limpar
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="col-sm-7">
			<div class="card-modern">
				<div class="card-header-modern">
					<h4><span class="glyphicon glyphicon-list"></span> Produtos Cadastrados</h4>
				</div>
				<div class="card-body-modern">
					<div class="row">
							<div class="col-sm-6">
								<div class="input-group" style="margin-bottom: 10px;">
									<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
									<input type="text" id="inputPesquisaProduto" class="form-control" placeholder="Pesquisar produto...">
								</div>
							</div>
						</div>
						<div id="tabelaProdutosLoad"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="app-footer">
	EstoqueFlow &copy; <?php echo date('Y'); ?> - Sistema de Controle de Estoque
</div>

</div><!-- Close main-content -->

<!-- Modal para Editar Produto -->
<div class="modal fade" id="abremodalUpdateProduto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Editar Produto</h4>
			</div>
			<div class="modal-body">
				<form id="frmProdutosU" enctype="multipart/form-data">
					<input type="text" id="idProduto" hidden="" name="idProduto">
					<div class="form-group-modern">
						<label>Categoria</label>
						<select class="form-control input-sm" id="categoriaSelectU" name="categoriaSelectU">
							<option value="">Selecionar Categoria</option>
							<?php 
							$sql="SELECT id_categoria,nome_categoria from categorias";
							$result=mysqli_query($conexao,$sql);
							?>
							<?php while($mostrar=mysqli_fetch_row($result)): ?>
								<option value="<?php echo $mostrar[0] ?>"><?php echo $mostrar[1]; ?></option>
							<?php endwhile; ?>
						</select>
					</div>
					<div class="form-group-modern">
						<label>Nome</label>
						<input type="text" class="form-control-modern" id="nomeU" name="nomeU">
					</div>
					<div class="form-group-modern">
						<label>Descrição</label>
						<textarea class="form-control-modern" id="descricaoU" name="descricaoU" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn-modern btn-outline-modern" data-dismiss="modal">Cancelar</button>
				<button id="btnAtualizarProduto" type="button" class="btn-modern btn-warning-modern">
					<span class="glyphicon glyphicon-pencil"></span> Salvar Alterações
				</button>
			</div>
		</div>
	</div>
</div>

</body>
</html>

<script type="text/javascript">
	function addDadosProduto(idproduto){
		$.ajax({
			type:"POST",
			data:"idpro=" + idproduto,
			url:"../procedimentos/produtos/obterDados.php",
			success:function(r){
				dado=jQuery.parseJSON(r);
				$('#idProduto').val(dado['id_produto']);
				$('#categoriaSelectU').val(dado['id_categoria']);
				$('#nomeU').val(dado['nome']);
				$('#descricaoU').val(dado['descricao']);
			}
		});
	}

	function eliminarProduto(idProduto){
		alertify.confirm('Deseja excluir este produto?', function(){ 
			$.ajax({
				type:"POST",
				data:"idproduto=" + idProduto,
				url:"../procedimentos/produtos/eliminarProdutos.php",
				success:function(r){
					if(r==1){
						$('#tabelaProdutosLoad').load("produtos/tabelaCadastroProdutos.php", function() {
				filtrarTabela("inputPesquisaProduto", "tabelaProdutosDataTable");
			});
						alertify.success("Produto excluído com sucesso!");
					}else{
						alertify.error("Erro ao excluir produto");
					}
				}
			});
		}, function(){ 
			alertify.error('Cancelado!')
		});
	}

	$(document).ready(function(){
    // Pesquisa em tempo real
    $(document).on('input', '#inputPesquisaProduto', function() {
        var v = $(this).val().toLowerCase();
        $('#tabelaProdutosDataTable tbody tr').each(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(v) > -1);
        });
    });

		$('#categoriaSelect').select2({
			placeholder: 'Pesquise ou selecione uma categoria',
			allowClear: true,
			width: '100%'
		});
		
		$('#categoriaSelectU').select2({
			placeholder: 'Pesquise ou selecione uma categoria',
			allowClear: true,
			width: '100%'
		});
		
		$('#tabelaProdutosLoad').load("produtos/tabelaCadastroProdutos.php", function() {
				filtrarTabela("inputPesquisaProduto", "tabelaProdutosDataTable");
			});

		$('#btnAtualizarProduto').click(function(){
			dados=$('#frmProdutosU').serialize();
			$.ajax({
				type:"POST",
				data:dados,
				url:"../procedimentos/produtos/atualizarProdutos.php",
				success:function(r){
					if(r==1){
						$('#tabelaProdutosLoad').load("produtos/tabelaCadastroProdutos.php", function() {
				filtrarTabela("inputPesquisaProduto", "tabelaProdutosDataTable");
			});
						$('#abremodalUpdateProduto').modal('hide');
						alertify.success("Produto editado com sucesso!");
					}else{
						alertify.error("Erro ao editar produto");
					}
				}
			});
		});

		$('#btnAddProduto').click(function(){
			vazios=validarFormVazio('frmCadastroProdutos');
			if(vazios > 0){
				alertify.alert("Preencha todos os campos!");
				return false;
			}
			var formData = new FormData(document.getElementById("frmCadastroProdutos"));
			$.ajax({
				url: "../procedimentos/produtos/inserirProdutos.php",
				type: "post",
				dataType: "html",
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success:function(r){
					if(r == 1){
						$('#frmCadastroProdutos')[0].reset();
						$('#tabelaProdutosLoad').load("produtos/tabelaCadastroProdutos.php", function() {
				filtrarTabela("inputPesquisaProduto", "tabelaProdutosDataTable");
			});
						alertify.success("Produto cadastrado com sucesso!");
					}else{
						alertify.error("Falha ao cadastrar produto: " + r);
					}
				},
				error:function(e){
					alertify.error("Erro na requisição");
					console.log(e);
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
