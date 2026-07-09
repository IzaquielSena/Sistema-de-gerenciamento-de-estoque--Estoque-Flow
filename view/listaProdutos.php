<?php 
session_start();
if(isset($_SESSION['usuario'])){
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>EstoqueFlow - Lista de Produtos</title>
	<?php require_once "menu.php"; ?>
</head>
<body>

<div class="content-area fade-in">
	<div class="page-header">
		<h1>Lista de Produtos</h1>
		<p>Visualize todos os produtos cadastrados, quantidade em estoque e valor atual.</p>
	</div>

	<!-- Seção de Relatórios -->
	<div class="card-modern" style="margin-bottom: 16px;">
		<div class="card-body-modern">
			<div class="row" style="align-items:flex-end;">
				<form id="frmRelatorioEstoqueData" action="../procedimentos/produtos/criarRelatorioEstoqueDataPdf.php" method="POST" target="_blank">
					<div class="col-sm-3">
						<div class="form-group-modern">
							<label>Data Início</label>
							<input type="date" name="dataInicio" class="form-control-modern" required>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group-modern">
							<label>Data Fim</label>
							<input type="date" name="dataFim" class="form-control-modern" required>
						</div>
					</div>
					<div class="col-sm-3">
						<button class="btn-modern btn-primary-modern">
							<span class="glyphicon glyphicon-calendar"></span> Relatório por Período
						</button>
					</div>
				</form>
				<div class="col-sm-3">
					<form action="../procedimentos/produtos/criarRelatorioEstoquePdf.php" method="POST" target="_blank" style="display:inline;">
						<button type="submit" class="btn-modern btn-success-modern">
							<span class="glyphicon glyphicon-list-alt"></span> Relatório Geral
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<div class="card-modern">
				<div class="card-header-modern">
					<h4><span class="glyphicon glyphicon-list"></span> Produtos em Estoque</h4>
				</div>
				<div class="card-body-modern">
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

</body>
</html>

<script type="text/javascript">
	$(document).ready(function(){
		$('#tabelaProdutosLoad').load("produtos/tabelaListaProdutos.php");

		setInterval(function(){
			$('#tabelaProdutosLoad').load("produtos/tabelaListaProdutos.php");
		}, 30000);
	});
</script>

<?php 
}else{
	header("location:../index.php");
}
?>
