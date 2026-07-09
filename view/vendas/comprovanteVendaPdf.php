<?php 
	require_once "../../classes/conexao.php";
	require_once "../../classes/vendas.php";

	$objv= new vendas();

	$c= new conectar();
	$conexao=$c->conexao();
	$codigovenda=$_GET['codigovenda'];

    $sql="SELECT ve.codigo_venda, ve.dataCompra, ve.id_cliente
	        from vendas as ve 
	        where ve.codigo_venda='$codigovenda'
	        LIMIT 1";

    $result=mysqli_query($conexao,$sql);
	$ver=mysqli_fetch_row($result);

	$comp=$ver[0];
	$data=$ver[1];
	$idcliente=$ver[2];
 ?>	

 	<link rel="stylesheet" type="text/css" href="../../lib/bootstrap/css/bootstrap.css">
 
 		<img src="../../img/ximac.jpg" width="200" height="120">
 		<br>
 		<table class="table">
 			<tr>
 				<td>Data: <?php echo date("d/m/Y", strtotime($data)) ?></td>
 			</tr>
 			<tr>
 				<td>Comprovante: <?php echo $comp ?></td>
 			</tr>
 			<tr>
 				<td>Cliente: <?php echo $objv->nomeCliente($idcliente); ?></td>
 			</tr>
 		</table>

 		<table class="table">
 			<tr>
 				<td>Produto</td>
 				<td>Preço Unit.</td>
 				<td>Quantidade</td>
 				<td>Subtotal</td>
 			</tr>

 			<?php 
 			$sql="SELECT pro.nome,
				        ip.preco,
				        ip.quantidade,
				        (ip.preco * ip.quantidade) as total_venda
					FROM vendas v
					INNER JOIN item_pedido ip ON ip.id_venda = v.id_venda
					INNER JOIN produtos pro ON ip.id_produto = pro.id_produto
					WHERE v.codigo_venda='$codigovenda'";

			$result=mysqli_query($conexao,$sql);
			$total=0;
			while($mostrar=mysqli_fetch_row($result)):
 			 ?>

			<tr>
				<td><?php echo $mostrar[0]; ?></td>
				<td><?php echo "R$ " . number_format($mostrar[1], 2, ',', '.'); ?></td>
				<td><?php echo $mostrar[2]; ?></td>
				<td><?php echo "R$ " . number_format($mostrar[3], 2, ',', '.'); ?></td>
			</tr>
			<?php 
				$total = $total + $mostrar[3];
			endwhile;
			?>
			<tr>
				<td colspan="4" style="text-align: right; font-weight: bold;">
					Total: <?php echo "R$ " . number_format($total, 2, ',', '.'); ?>
				</td>
			</tr>
 		</table>
