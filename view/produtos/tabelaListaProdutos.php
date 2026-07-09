<?php 
	require_once "../../classes/conexao.php";
	require_once "../../classes/entradas_estoque.php";

	$c = new conectar();
	$conexao = $c->conexao();
	$obj_entrada = new entradas_estoque();

	$sql = "SELECT DISTINCT p.id_produto, 
				p.nome, 
				p.descricao,
				c.nome_categoria,
				i.url
			FROM produtos p
			INNER JOIN categorias c ON p.id_categoria = c.id_categoria
			INNER JOIN imagens i ON p.id_imagem = i.id_imagem
			ORDER BY p.nome";

	$result = mysqli_query($conexao, $sql);
	$total = mysqli_num_rows($result);

	if($total > 0):
?>
<div class="table-responsive">
<table class="table-modern">
	<thead>
		<tr>
			<th style="width: 80px;">Imagem</th>
			<th>Nome</th>
			<th>Categoria</th>
			<th>Descrição</th>
			<th style="text-align: center; width: 100px;">Quantidade</th>
			<th style="text-align: right; width: 120px;">Valor Unitário</th>
			<th style="text-align: right; width: 120px;">Valor Total</th>
		</tr>
	</thead>
	<tbody>
		<?php while($mostrar = mysqli_fetch_assoc($result)): 
			$quantidade = $obj_entrada->obterQuantidadeTotal($mostrar['id_produto']);
			$preco = $obj_entrada->obterPrecoAtual($mostrar['id_produto']);
			$valorTotal = $quantidade * $preco;
			
			$imgUrl = $mostrar['url'];
			$imgExibir = str_replace("../../arquivos/", "../arquivos/", $imgUrl);
		?>
			<tr>
				<td style="text-align: center;">
					<img src="<?php echo $imgExibir; ?>" alt="<?php echo $mostrar['nome']; ?>" style="max-width: 60px; max-height: 60px; border-radius: 8px;">
				</td>
				<td><strong><?php echo $mostrar['nome']; ?></strong></td>
				<td><span class="badge-modern badge-primary-modern"><?php echo $mostrar['nome_categoria']; ?></span></td>
				<td><?php echo substr($mostrar['descricao'], 0, 40) . (strlen($mostrar['descricao']) > 40 ? '...' : ''); ?></td>
				<td style="text-align: center;">
					<span class="badge-modern <?php echo $quantidade <= 20 ? 'badge-danger-modern' : 'badge-success-modern'; ?>">
						<?php echo $quantidade; ?> un.
					</span>
				</td>
				<td style="text-align: right;">
					<?php if($preco > 0): ?>
						R$ <?php echo number_format($preco, 2, ',', '.'); ?>
					<?php else: ?>
						<span class="text-muted-modern">-</span>
					<?php endif; ?>
				</td>
				<td style="text-align: right;">
					<?php if($preco > 0 && $quantidade > 0): ?>
						<strong>R$ <?php echo number_format($valorTotal, 2, ',', '.'); ?></strong>
					<?php else: ?>
						<span class="text-muted-modern">-</span>
					<?php endif; ?>
				</td>
			</tr>
		<?php endwhile; ?>
	</tbody>
	<tfoot>
		<tr>
			<th colspan="6" style="text-align: right;"><strong>Valor Total do Estoque:</strong></th>
			<th style="text-align: right;">
				<strong style="color: var(--success-color, #16a34a);">
					<?php 
						$sql_total = "SELECT COALESCE(SUM(e.quantidade * e.preco), 0) as valor_total FROM entradas_estoque e";
						$result_total = mysqli_query($conexao, $sql_total);
						$row_total = mysqli_fetch_assoc($result_total);
						echo "R$ " . number_format($row_total['valor_total'], 2, ',', '.');
					?>
				</strong>
			</th>
		</tr>
	</tfoot>
</table>
</div>
<?php 
	else:
?>
<div class="alert-modern alert-info-modern">
	Nenhum produto cadastrado. Comece cadastrando um novo produto.
</div>
<?php 
	endif;
?>
