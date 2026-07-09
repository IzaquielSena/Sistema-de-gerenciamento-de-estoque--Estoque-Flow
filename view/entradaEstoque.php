<?php 
session_start();
if(isset($_SESSION['usuario'])){
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>EstoqueFlow - Entrada de Estoque</title>
	<?php require_once "menu.php"; ?>
	<?php require_once "../classes/conexao.php"; 
	$c= new conectar();
	$conexao=$c->conexao();
	$sql="SELECT id_produto, nome FROM produtos ORDER BY nome";
	$result=mysqli_query($conexao,$sql);
	?>
</head>
<body>

<div class="content-area fade-in">
	<div class="page-header">
		<h1>Entrada de Estoque</h1>
		<p>Registre a entrada de produtos, informando quantidade, preço de custo e preço de venda.</p>
	</div>

	<div class="row">
		<div class="col-sm-5">
			<div class="card-modern">
				<div class="card-header-modern">
					<h4><span class="glyphicon glyphicon-import"></span> Registrar Entrada</h4>
				</div>
				<div class="card-body-modern">
					<form id="frmEntradaEstoque">
						<div class="form-group-modern">
							<label>Produto <span class="required">*</span></label>
							<select class="form-control input-sm" id="produtoSelect" name="produtoSelect" required>
								<option value="">Selecionar Produto</option>
								<?php while($mostrar=mysqli_fetch_row($result)): ?>
									<option value="<?php echo $mostrar[0] ?>"><?php echo $mostrar[1]; ?></option>
								<?php endwhile; ?>
							</select>
						</div>

						<div class="form-group-modern">
							<label>Quantidade <span class="required">*</span></label>
							<input type="number" class="form-control-modern" id="quantidade" name="quantidade" placeholder="Digite a quantidade" min="1" required>
						</div>

						<div class="form-group-modern">
							<label>Preço de Custo (Compra) <span class="required">*</span></label>
							<div class="input-group">
								<span class="input-group-addon">R$</span>
								<input type="text" class="form-control-modern" id="preco" name="preco" placeholder="0,00" required>
							</div>
							<small style="color: var(--text-muted); font-size: 0.8rem;">Preço que você pagou pelo produto</small>
						</div>

						<div class="form-group-modern">
							<label>Preço de Venda <span class="required">*</span></label>
							<div class="input-group">
								<span class="input-group-addon">R$</span>
								<input type="text" class="form-control-modern" id="precoVenda" name="precoVenda" placeholder="0,00" required>
							</div>
							<small style="color: var(--text-muted); font-size: 0.8rem;">Preço que será cobrado na venda</small>
						</div>

						<div id="lucroPreview" style="display:none; padding: 10px; background: var(--success-light, #d1fae5); border-radius: 8px; margin-bottom: 12px;">
							<strong style="color: var(--success, #059669);">Lucro por unidade: <span id="lucroValor">R$ 0,00</span></strong>
							<br>
							<small style="color: var(--success, #059669);">Margem: <span id="margemValor">0%</span></small>
						</div>

						<div class="form-group-modern">
							<label>Data de Entrada <span class="required">*</span></label>
							<input type="date" class="form-control-modern" id="dataEntrada" name="dataEntrada" required>
						</div>

						<div style="display:flex; gap:8px;">
							<button type="button" id="btnAddEntrada" class="btn-modern btn-primary-modern" style="flex:1;">
								<span class="glyphicon glyphicon-plus"></span> Registrar Entrada
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
					<h4><span class="glyphicon glyphicon-list"></span> Histórico de Entradas</h4>
				</div>
				<div class="card-body-modern">
					<div class="row">
							<div class="col-sm-6">
								<div class="input-group" style="margin-bottom: 10px;">
									<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
									<input type="text" id="inputPesquisaEntrada" class="form-control" placeholder="Pesquisar entrada...">
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

<!-- Modal para Editar Entrada -->
<div class="modal fade" id="abremodalUpdateEntrada" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Editar Entrada de Estoque</h4>
			</div>
			<div class="modal-body">
				<form id="frmEntradaU">
					<input type="text" id="idEntrada" hidden="" name="idEntrada">
					<div class="form-group-modern">
						<label>Quantidade</label>
						<input type="number" class="form-control-modern" id="quantidadeU" name="quantidadeU" min="1">
					</div>
					<div class="form-group-modern">
						<label>Preço de Custo</label>
						<div class="input-group">
							<span class="input-group-addon">R$</span>
							<input type="text" class="form-control-modern" id="precoU" name="precoU">
						</div>
					</div>
					<div class="form-group-modern">
						<label>Preço de Venda</label>
						<div class="input-group">
							<span class="input-group-addon">R$</span>
							<input type="text" class="form-control-modern" id="precoVendaU" name="precoVendaU">
						</div>
					</div>
					<div class="form-group-modern">
						<label>Data de Entrada</label>
						<input type="date" class="form-control-modern" id="dataEntradaU" name="dataEntradaU">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn-modern btn-outline-modern" data-dismiss="modal">Cancelar</button>
				<button id="btnAtualizarEntrada" type="button" class="btn-modern btn-warning-modern">
					<span class="glyphicon glyphicon-pencil"></span> Salvar Alterações
				</button>
			</div>
		</div>
	</div>
</div>

</body>
</html>

<script type="text/javascript">
	document.getElementById('dataEntrada').valueAsDate = new Date();

	$(document).ready(function(){
    // Pesquisa em tempo real
    $(document).on('input', '#inputPesquisaEntrada', function() {
        var v = $(this).val().toLowerCase();
        $('#tabelaEntradasDataTable tbody tr').each(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(v) > -1);
        });
    });

		$('#produtoSelect').select2({
			placeholder: 'Pesquise ou selecione um produto',
			allowClear: true,
			width: '100%'
		});
	});

	function calcularLucro(){
		var precoCusto = parseFloat($('#preco').val().replace(',', '.')) || 0;
		var precoVenda = parseFloat($('#precoVenda').val().replace(',', '.')) || 0;
		
		if(precoCusto > 0 && precoVenda > 0){
			var lucro = precoVenda - precoCusto;
			var margem = ((lucro / precoCusto) * 100).toFixed(1);
			$('#lucroValor').text('R$ ' + lucro.toFixed(2).replace('.', ','));
			$('#margemValor').text(margem + '%');
			$('#lucroPreview').show();

			if(lucro < 0){
				$('#lucroPreview').css('background', 'var(--danger-light, #fee2e2)');
				$('#lucroPreview strong, #lucroPreview small').css('color', 'var(--danger, #dc2626)');
			} else {
				$('#lucroPreview').css('background', 'var(--success-light, #d1fae5)');
				$('#lucroPreview strong, #lucroPreview small').css('color', 'var(--success, #059669)');
			}
		} else {
			$('#lucroPreview').hide();
		}
	}

	$('#preco, #precoVenda').on('input', calcularLucro);

	function addDadosEntrada(identrada){
		$.ajax({
			type:"POST",
			data:"identrada=" + identrada,
			url:"../procedimentos/entradas_estoque/obterDados.php",
			success:function(r){
				dado=jQuery.parseJSON(r);
				$('#idEntrada').val(dado['id_entrada']);
				$('#quantidadeU').val(dado['quantidade']);
				$('#precoU').val(dado['preco']);
				$('#precoVendaU').val(dado['preco_venda']);
				$('#dataEntradaU').val(dado['data_entrada']);
			}
		});
	}

	function eliminarEntrada(idEntrada){
		alertify.confirm('Deseja excluir esta entrada?', function(){ 
			$.ajax({
				type:"POST",
				data:"identrada=" + idEntrada,
				url:"../procedimentos/entradas_estoque/eliminarEntrada.php",
				success:function(r){
					if(r==1){
						$('#tabelaProdutosLoad').load("entradas_estoque/tabelaEntradasEstoque.php", function() {
				filtrarTabela("inputPesquisaEntrada", "tabelaEntradasDataTable");
			});
						alertify.success("Entrada excluída com sucesso!");
					}else{
						alertify.error("Erro ao excluir entrada");
					}
				}
			});
		}, function(){ 
			alertify.error('Cancelado!')
		});
	}

	$(document).ready(function(){
		$('#tabelaProdutosLoad').load("entradas_estoque/tabelaEntradasEstoque.php", function() {
				filtrarTabela("inputPesquisaEntrada", "tabelaEntradasDataTable");
			});

		$('#btnAtualizarEntrada').click(function(){
			dados=$('#frmEntradaU').serialize();
			$.ajax({
				type:"POST",
				data:dados,
				url:"../procedimentos/entradas_estoque/atualizarEntrada.php",
				success:function(r){
					if(r==1){
						$('#tabelaProdutosLoad').load("entradas_estoque/tabelaEntradasEstoque.php", function() {
				filtrarTabela("inputPesquisaEntrada", "tabelaEntradasDataTable");
			});
						$('#abremodalUpdateEntrada').modal('hide');
						alertify.success("Entrada editada com sucesso!");
					}else{
						alertify.error("Erro ao editar entrada");
					}
				}
			});
		});

		$('#btnAddEntrada').click(function(){
			vazios=validarFormVazio('frmEntradaEstoque');
			if(vazios > 0){
				alertify.alert("Preencha todos os campos!");
				return false;
			}
			dados=$('#frmEntradaEstoque').serialize();
			$.ajax({
				url: "../procedimentos/entradas_estoque/inserirEntrada.php",
				type: "post",
				dataType: "html",
				data: dados,
				success:function(r){
					if(r == 1){
						$('#frmEntradaEstoque')[0].reset();
						document.getElementById('dataEntrada').valueAsDate = new Date();
						$('#lucroPreview').hide();
						$('#tabelaProdutosLoad').load("entradas_estoque/tabelaEntradasEstoque.php", function() {
				filtrarTabela("inputPesquisaEntrada", "tabelaEntradasDataTable");
			});
						alertify.success("Entrada registrada com sucesso!");
					}else{
						alertify.error("Falha ao registrar entrada: " + r);
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
