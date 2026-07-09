<?php 
	require_once "../../classes/conexao.php";
	require_once "../../classes/vendas.php";
	$c= new conectar();
	$conexao=$c->conexao();

	$obj= new vendas();

	$sql="SELECT codigo_venda,
					MAX(dataCompra),
					MAX(id_cliente) 
				from vendas group by codigo_venda
				ORDER BY codigo_venda DESC";
	$result=mysqli_query($conexao,$sql); 
?>

<div class="row">
	<div class="col-sm-12">
		<div class="table-responsive">
			<table class="table-modern">
				<thead>
					<tr>
						<th>Código</th>
						<th>Data</th>
						<th>Cliente</th>
						<th>Total da Compra</th>
						<th>Lucro</th>
						<th>Ações</th>
					</tr>
				</thead>
				<tbody>
			<?php while($ver=mysqli_fetch_row($result)): ?>
					<tr>
						<td><strong>#<?php echo $ver[0] ?></strong></td>
						<td><?php echo date("d/m/Y", strtotime($ver[1])) ?></td>
						<td>
							<?php
								if($obj->nomeCliente($ver[2])==" "){
									echo "<span class='badge-modern badge-warning-modern'>S/C</span>";
								}else{
									echo $obj->nomeCliente($ver[2]);
								}
							?>
						</td>
						<td>
							<?php 
								$totalVenda = $obj->obterTotal($ver[0]);
								echo "<strong>R$ " . number_format($totalVenda, 2, ',', '.') . "</strong>"; 
							?>
						</td>
						<td>
							<?php 
								$lucro = $obj->obterLucro($ver[0]);
								$corLucro = $lucro >= 0 ? 'var(--success, #16a34a)' : 'var(--danger, #dc2626)';
								echo "<strong style='color: $corLucro;'>R$ " . number_format($lucro, 2, ',', '.') . "</strong>"; 
							?>
						</td>
						<td>
							<form action="../procedimentos/vendas/criarRelatorioPdf.php" method="POST" target="_blank" style="display:inline;">
								<input type="hidden" name="codigovenda" value="<?php echo $ver[0] ?>">
								<button type="submit" class="btn-modern btn-primary-modern btn-sm-modern">
									<span class="glyphicon glyphicon-file"></span> Comprovante
								</button>
							</form>
						</td>
					</tr>
			<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
