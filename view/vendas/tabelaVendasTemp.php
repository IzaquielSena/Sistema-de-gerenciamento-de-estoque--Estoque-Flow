<?php 
	session_start();
 ?>

 <h4 style="margin-bottom: 4px;">Criar Venda</h4>
 <h4><strong><div id="nomeclienteVenda" style="color: var(--primary-color, #2563eb); margin-bottom: 12px;"></div></strong></h4>
	 <div class="table-responsive">
	 <table class="table-modern" style="text-align: center;">
 	<caption style="padding: 12px;">
 		<button class="btn-modern btn-success-modern" onclick="criarVenda()" style="font-size: 0.95rem;">
 			<span class="glyphicon glyphicon-usd"></span> Criar Venda
 		</button>
 	</caption>
 	<thead>
 		<tr>
 			<th>Nome</th>
 			<th>Descrição</th>
 			<th style="text-align:right;">Preço Unitário</th>
 			<th style="text-align:center;">Quantidade</th>
 			<th style="text-align:right;">Subtotal</th>
 			<th style="width:60px;text-align:center;">Remover</th>
 		</tr>
 	</thead>
 	<tbody>
 	<?php 
 	$total=0;
 	$cliente=""; 
 		if(isset($_SESSION['tabelaComprasTemp'])):
 			$i=0;
 			foreach (@$_SESSION['tabelaComprasTemp'] as $key) {
 				$d=explode("||", @$key);

                // d[3] deve ser o preço de uma única unidade
                $precoUnitario = (float)$d[3];
                $quantidade = (int)$d[6];
                
                // Cálculo do subtotal do item
                $subtotalItem = $precoUnitario * $quantidade;
                
                // Acumulando o total geral sem duplicar
                $total += $subtotalItem;
 	 ?>

 	<tr>
 		<td><?php echo $d[1] ?></td>
 		<td><?php echo $d[2] ?></td>
        <td style="text-align:right;"><?php echo "R$ " . number_format($precoUnitario, 2, ',', '.'); ?></td>
 		<td style="text-align:center;">
 			<span class="badge-modern badge-primary-modern"><?php echo $quantidade; ?></span>
 		</td>
        <td style="text-align:right;"><strong><?php echo "R$ " . number_format($subtotalItem, 2, ',', '.'); ?></strong></td>
 		<td style="text-align:center;">
 			<button class="btn-modern btn-danger-modern btn-sm-modern" onclick="fecharP('<?php echo $i; ?>')">
 				<span class="glyphicon glyphicon-remove"></span>
 			</button>
 		</td>
 	</tr>

 <?php 
 		$i++;
 		$cliente=$d[4];
 	}
 	endif; 
 ?>
 	</tbody>
 	<tfoot>
 		<tr>
        	<td colspan="6" style="text-align: right; font-weight: bold; font-size: 1.1em; padding: 12px 16px;">
            	Total da venda: <span style="color: var(--success-color, #16a34a);"><?php echo "R$ " . number_format($total, 2, ',', '.'); ?></span>
        	</td>
 		</tr>
 	</tfoot>
	 </table>
	 </div>

 <script type="text/javascript">
 	$(document).ready(function(){
 		nome="<?php echo @$cliente ?>";
 		$('#nomeclienteVenda').text("Nome de cliente: " + nome);
 	});
 </script>
