<?php 

require_once "../../classes/conexao.php";
	$c= new conectar();
	$conexao=$c->conexao();
?>

<h4 style="margin-bottom: 16px; font-weight: 600;">Vender Produto</h4>
<div class="row">
	<div class="col-sm-4">
		<div class="card-modern" style="padding: 20px;">
			<form id="frmVendasProdutos">
				<div class="form-group-modern">
					<label class="form-label-modern">Selecionar Cliente</label>
					<select class="form-control-modern" id="clienteVenda" name="clienteVenda">
						<option value="A">Selecionar</option>
						<option value="0">Sem Clientes</option>
						<?php
						$sql="SELECT id_cliente,nome,sobrenome 
						from clientes";
						$result=mysqli_query($conexao,$sql);
						while ($cliente=mysqli_fetch_row($result)):
							?>
							<option value="<?php echo $cliente[0] ?>"><?php echo $cliente[1]." ".$cliente[2] ?></option>
						<?php endwhile; ?>
					</select>
				</div>
				<div class="form-group-modern">
					<label class="form-label-modern">Produto</label>
					<select class="form-control-modern" id="produtoVenda" name="produtoVenda">
						<option value="A">Selecionar</option>
						<?php
						$sql="SELECT id_produto,
						nome
						from produtos";
						$result=mysqli_query($conexao,$sql);

						while ($produto=mysqli_fetch_row($result)):
							?>
							<option value="<?php echo $produto[0] ?>"><?php echo $produto[1] ?></option>
						<?php endwhile; ?>
					</select>
				</div>
				<div class="form-group-modern">
					<label class="form-label-modern">Descrição</label>
					<textarea readonly="" id="descricaoV" name="descricaoV" class="form-control-modern" rows="2"></textarea>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group-modern">
							<label class="form-label-modern">Qtd. Estoque</label>
							<input readonly="" type="text" class="form-control-modern" id="quantidadeV" name="quantidadeV">
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group-modern">
							<label class="form-label-modern">Preço de Venda</label>
							<input readonly="" type="text" class="form-control-modern" id="precoV" name="precoV">
						</div>
					</div>
				</div>
				<input type="hidden" id="precoCustoV" name="precoCustoV" value="0">
				<div class="form-group-modern">
					<label class="form-label-modern">Quantidade Vendida</label>
					<input type="text" class="form-control-modern" id="quantV" name="quantV" placeholder="Informe a quantidade">
				</div>
				<div style="display: flex; gap: 8px; margin-top: 12px;">
					<button type="button" class="btn-modern btn-primary-modern" id="btnAddVenda" style="flex:1;">
						<span class="glyphicon glyphicon-plus"></span> Adicionar
					</button>
					<button type="button" class="btn-modern btn-danger-modern" id="btnLimparVendas" style="flex:1;">
						<span class="glyphicon glyphicon-trash"></span> Limpar
					</button>
				</div>
			</form>
		</div>
	</div>
	<div class="col-sm-3">
		<div id="imgProduto" style="text-align: center;"></div>
	</div>
	<div class="col-sm-5">
		<div id="tabelaVendasTempLoad"></div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){

		$('#tabelaVendasTempLoad').load("vendas/tabelaVendasTemp.php");

		$('#produtoVenda').change(function(){

			$.ajax({
				type:"POST",
				data:"idproduto=" + $('#produtoVenda').val(),
				url:"../procedimentos/vendas/obterDadosProdutos.php",
				success:function(r){
					dado=jQuery.parseJSON(r);

					$('#descricaoV').val(dado['descricao']);

					$('#quantidadeV').val(dado['quantidade']);
					$('#precoV').val(dado['preco']);
					$('#precoCustoV').val(dado['preco_custo']);
					
					$('#imgProduto').empty();
					$('#imgProduto').prepend('<img class="img-thumbnail" id="imgp" src="' + dado['url'] + '" style="width: 200px; height: 200px; object-fit: cover; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" />');
					
				}
			});
		});

		$('#btnAddVenda').click(function(){
			vazios=validarFormVazio('frmVendasProdutos');

			quant = 0;
			quantidade = 0;

			quant = $('#quantV').val();
			quantidade = $('#quantidadeV').val();

			if(parseInt(quant) > parseInt(quantidade)){
				alertify.alert("Quantidade inexistente em estoque!!");
				quant = $('#quantV').val("");
				return false;
			}else{
				quantidade = $('#quantidadeV').val();
			}

			if(vazios > 0){
				alertify.alert("Preencha os Campos!!");
				return false;
			}

			dados=$('#frmVendasProdutos').serialize();
			$.ajax({
				type:"POST",
				data:dados,
				url:"../procedimentos/vendas/adicionarProdutoTemp.php",
				success:function(r){
					$('#tabelaVendasTempLoad').load("vendas/tabelaVendasTemp.php");
				}
			});
		});

		$('#btnLimparVendas').click(function(){

		$.ajax({
			url:"../procedimentos/vendas/limparTemp.php",
			success:function(r){
				$('#tabelaVendasTempLoad').load("vendas/tabelaVendasTemp.php");
			}
		});
	});

	});
</script>

<script type="text/javascript">

	function editarP(dados){
		
		$.ajax({
			type:"POST",
			data:"dados=" + dados,
			url:"../procedimentos/vendas/editarEstoque.php",
			success:function(r){
				
				$('#tabelaVendasTempLoad').load("vendas/tabelaVendasTemp.php");
				alertify.success("Estoque Atualizado com Sucesso!!");
			}
		});
	}


	function fecharP(index){
		$.ajax({
			type:"POST",
			data:"ind=" + index,
			url:"../procedimentos/vendas/fecharProduto.php",
			success:function(r){
				$('#tabelaVendasTempLoad').load("vendas/tabelaVendasTemp.php");
				alertify.success("Produto Removido com Sucesso!!");
			}
		});
	}

	function criarVenda(){
		$.ajax({
			url:"../procedimentos/vendas/criarVenda.php",
			success:function(r){
				
				if(r > 0){
					$('#tabelaVendasTempLoad').load("vendas/tabelaVendasTemp.php");
					$('#frmVendasProdutos')[0].reset();
					$('#imgProduto').empty();
					alertify.alert("Venda #" + r + " Criada com Sucesso!");
				}else if(r==0){
					alertify.alert("Não possui lista de Vendas");
				}else{
					alertify.error("Venda não efetuada");
				}
			}
		});
	}
</script>

<script type="text/javascript">
	$(document).ready(function(){
		$('#clienteVenda').select2();
		$('#produtoVenda').select2();

	});
</script>
