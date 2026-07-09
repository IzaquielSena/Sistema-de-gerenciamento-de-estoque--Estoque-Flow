<?php 
	session_start();
	if(isset($_SESSION['usuario'])){
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>EstoqueFlow - Vendas</title>
	<?php require_once "menu.php"; ?>
    <!-- Adicione este estilo para controlar o tamanho da imagem da venda -->
    <style>
        #vendaProdutos img, 
        .content-area img[src*="arquivos"] {
            max-width: 210px !important;
            max-height: 190px !important;
            object-fit: cover;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="content-area fade-in">
	<div class="page-header">
		<h1>Vendas</h1>
		<p>Realize vendas de produtos e consulte o histórico.</p>
	</div>

	<div class="row" style="margin-bottom: 16px;">
		<div class="col-sm-12">
			<div style="display:flex; gap:8px;">
				<button class="btn-modern btn-primary-modern" id="vendaProdutosBtn">
					<span class="glyphicon glyphicon-shopping-cart"></span> Vender Produto
				</button>
				<button class="btn-modern btn-outline-modern" id="vendasFeitasBtn">
					<span class="glyphicon glyphicon-list-alt"></span> Lista de Vendas
				</button>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<div id="vendaProdutos"></div>
			<div id="vendasFeitas">
				
				<div id="secaoRelatoriosVendas" style="display: none;">
					<div class="card-modern" style="margin-bottom: 16px;">
						<div class="card-body-modern">
							<div class="row" style="align-items:flex-end;">
								<form id="frmRelatorioDatas" action="../procedimentos/vendas/criarRelatorioDataPdf.php" method="POST" target="_blank">
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
									<form action="../procedimentos/vendas/criarRelatorioVendasGeralPdf.php" method="POST" target="_blank" style="display:inline;">
										<button type="submit" class="btn-modern btn-success-modern">
											<span class="glyphicon glyphicon-list-alt"></span> Relatório Total
										</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div id="tabelaVendasRelatorios"></div>

			</div>
		</div>
	</div>
</div>


</div><!-- Close main-content -->

</body>
</html>
	
<script type="text/javascript">
	$(document).ready(function(){
		$('#vendaProdutosBtn').click(function(){
			esconderSessaoVenda();
			$('#vendaProdutos').load('vendas/vendasDeProdutos.php');
			$('#vendaProdutos').show();
		});
		$('#vendasFeitasBtn').click(function(){
			esconderSessaoVenda();
			$('#tabelaVendasRelatorios').load('vendas/vendasRelatorios.php');
			$('#vendasFeitas').show();
			$('#secaoRelatoriosVendas').show();
		});
	});

	function esconderSessaoVenda(){
		$('#vendaProdutos').hide();
		$('#vendasFeitas').hide();
		$('#secaoRelatoriosVendas').hide();
	}
</script>

<?php 
	}else{
		header("location:../index.php");
	}
?>