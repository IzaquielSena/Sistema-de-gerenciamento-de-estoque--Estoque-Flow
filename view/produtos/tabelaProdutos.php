<?php 
	require_once "../../classes/conexao.php";
	$c= new conectar();
	$conexao=$c->conexao();
	
	$sql="SELECT pro.nome,
					pro.descricao,
					img.url,
					cat.nome_categoria,
					pro.id_produto
		  from produtos as pro 
		  inner join imagens as img
		  on pro.id_imagem=img.id_imagem
		  inner join categorias as cat
		  on pro.id_categoria=cat.id_categoria";
	$result=mysqli_query($conexao,$sql);
 ?>

<table class="table table-hover table-condensed table-bordered" style="text-align: center;">
	<caption><label>Produtos</label></caption>
	<tr>
		<td>Nome</td>
		<td>Descrição</td>
		<td>Quantidade</td>
		<td>Preço</td>
		<td>Imagem</td>
		<td>Categoria</td>
		<td>Editar</td>
		<td>Excluir</td>
	</tr>

	<?php while($mostrar=mysqli_fetch_row($result)): ?>

	<tr>
		<td style="vertical-align: middle;"><?php echo $mostrar[0]; ?></td>
		<td style="vertical-align: middle;"><?php echo $mostrar[1]; ?></td>
		<td style="vertical-align: middle;">
			<?php 
				$idp = $mostrar[4];
				$sqlQtd="SELECT COALESCE(SUM(quantidade), 0) as total
						 from entradas_estoque 
						 where id_produto='$idp'";
				$resultQtd=mysqli_query($conexao,$sqlQtd);
				$rowQtd=mysqli_fetch_assoc($resultQtd);
				echo $rowQtd['total'];
			?>
		</td>
		
		<td style="vertical-align: middle;">
			<?php 
				$sqlPreco="SELECT preco
						   from entradas_estoque 
						   where id_produto='$idp'
						   ORDER BY data_entrada DESC
						   LIMIT 1";
				$resultPreco=mysqli_query($conexao,$sqlPreco);
				$rowPreco=mysqli_fetch_assoc($resultPreco);
				$preco = $rowPreco ? $rowPreco['preco'] : 0;
				echo "R$ " . number_format($preco, 2, ',', '.'); 
			?>
		</td>
		
		<td style="vertical-align: middle;">
			<?php 
			$imgUrl = $mostrar[2];
			$imgExibir = str_replace("../../arquivos/", "../arquivos/", $imgUrl);
			?>
			<img width="100" height="100" src="<?php echo $imgExibir ?>" style="object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
		</td>
		<td style="vertical-align: middle;"><?php echo $mostrar[3]; ?></td>
		<td style="vertical-align: middle;">
			<span  data-toggle="modal" data-target="#abremodalUpdateProduto" class="btn btn-warning btn-xs" onclick="addDadosProduto('<?php echo $mostrar[4] ?>')">
				<span class="glyphicon glyphicon-pencil"></span>
			</span>
		</td>
		<td style="vertical-align: middle;">
			<span class="btn btn-danger btn-xs" onclick="eliminarProduto('<?php echo $mostrar[4] ?>')">
				<span class="glyphicon glyphicon-remove"></span>
			</span>
		</td>
	</tr>
<?php endwhile; ?>
</table>
