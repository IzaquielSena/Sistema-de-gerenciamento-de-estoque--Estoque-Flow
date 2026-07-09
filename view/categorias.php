<?php 
session_start();
if(isset($_SESSION['usuario'])){
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>EstoqueFlow - Categorias</title>
	<?php require_once "menu.php"; ?>
</head>
<body>

<div class="content-area fade-in">
	<div class="page-header">
		<h1>Categorias</h1>
		<p>Gerencie as categorias dos seus produtos.</p>
	</div>

	<div class="row">
		<div class="col-sm-4">
			<div class="card-modern">
				<div class="card-header-modern">
					<h4><span class="glyphicon glyphicon-plus"></span> Nova Categoria</h4>
				</div>
				<div class="card-body-modern">
					<form id="frmCategorias">
						<div class="form-group-modern">
							<label>Nome da Categoria <span class="required">*</span></label>
							<input type="text" class="form-control-modern" name="categoria" id="categoria" placeholder="Ex: Eletrônicos, Alimentos...">
						</div>
						<button type="button" class="btn-modern btn-primary-modern btn-block-modern" id="btnAdicionarCategoria">
							<span class="glyphicon glyphicon-plus"></span> Adicionar Categoria
						</button>
					</form>
				</div>
			</div>
		</div>
		<div class="col-sm-8">
			<div class="card-modern">
				<div class="card-header-modern">
					<h4><span class="glyphicon glyphicon-list"></span> Categorias Cadastradas</h4>
				</div>
				<div class="card-body-modern">
					<div class="row">
							<div class="col-sm-6">
								<div class="input-group" style="margin-bottom: 10px;">
									<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
									<input type="text" id="inputPesquisaCategoria" class="form-control" placeholder="Pesquisar categoria...">
								</div>
							</div>
						</div>
						<div id="tabelaCategoriaLoad"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="app-footer">
	EstoqueFlow &copy; <?php echo date('Y'); ?> - Sistema de Controle de Estoque
</div>

</div><!-- Close main-content -->

<!-- Modal Atualizar -->
<div class="modal fade" id="atualizaCategoria" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				<h4 class="modal-title">Editar Categoria</h4>
			</div>
			<div class="modal-body">
				<form id="frmCategoriaU">
					<input type="text" hidden="" id="idcategoria" name="idcategoria">
					<div class="form-group-modern">
						<label>Nome da Categoria</label>
						<input type="text" id="categoriaU" name="categoriaU" class="form-control-modern">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn-modern btn-outline-modern" data-dismiss="modal">Cancelar</button>
				<button type="button" id="btnAtualizaCategoria" class="btn-modern btn-warning-modern" data-dismiss="modal">Salvar Alterações</button>
			</div>
		</div>
	</div>
</div>

</body>
</html>

<script type="text/javascript">
	$(document).ready(function(){
    // Pesquisa em tempo real
    $(document).on('input', '#inputPesquisaCategoria', function() {
        var v = $(this).val().toLowerCase();
        $('#tabelaCategoriasDataTable tbody tr').each(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(v) > -1);
        });
    });

		$('#tabelaCategoriaLoad').load("categorias/tabelaCategorias.php", function() {
				filtrarTabela("inputPesquisaCategoria", "tabelaCategoriasDataTable");
			});

		$('#btnAdicionarCategoria').click(function(){
			vazios=validarFormVazio('frmCategorias');
			if(vazios > 0){
				alertify.alert("Preencha os campos!");
				return false;
			}
			dados=$('#frmCategorias').serialize();
			$.ajax({
				type:"POST",
				data:dados,
				url:"../procedimentos/categorias/adicionarCategorias.php",
				success:function(r){
					if(r==1){
						$('#frmCategorias')[0].reset();
						$('#tabelaCategoriaLoad').load("categorias/tabelaCategorias.php", function() {
				filtrarTabela("inputPesquisaCategoria", "tabelaCategoriasDataTable");
			});
						alertify.success("Categoria adicionada com sucesso!");
					}else{
						alertify.error("Não foi possível adicionar a categoria");
					}
				}
			});
		});

		$('#btnAtualizaCategoria').click(function(){
			dados=$('#frmCategoriaU').serialize();
			$.ajax({
				type:"POST",
				data:dados,
				url:"../procedimentos/categorias/atualizarCategorias.php",
				success:function(r){
					if(r==1){
						$('#tabelaCategoriaLoad').load("categorias/tabelaCategorias.php", function() {
				filtrarTabela("inputPesquisaCategoria", "tabelaCategoriasDataTable");
			});
						alertify.success("Atualizado com sucesso!");
					}else{
						alertify.error("Não foi possível atualizar");
					}
				}
			});
		});
	});

	function adicionarDado(idCategoria,categoria){
		$('#idcategoria').val(idCategoria);
		$('#categoriaU').val(categoria);
	}

	function eliminaCategoria(idcategoria){
		alertify.confirm('Deseja excluir esta categoria?', function(){ 
			$.ajax({
				type:"POST",
				data:"idcategoria=" + idcategoria,
				url:"../procedimentos/categorias/eliminarCategorias.php",
				success:function(r){
					if(r==1){
						$('#tabelaCategoriaLoad').load("categorias/tabelaCategorias.php", function() {
				filtrarTabela("inputPesquisaCategoria", "tabelaCategoriasDataTable");
			});
						alertify.success("Excluído com sucesso!");
					}else{
						alertify.error("Não foi possível excluir");
					}
				}
			});
		}, function(){ 
			alertify.error('Cancelado!')
		});
	}
</script>

<?php 
}else{
	header("location:../index.php");
}
?>
