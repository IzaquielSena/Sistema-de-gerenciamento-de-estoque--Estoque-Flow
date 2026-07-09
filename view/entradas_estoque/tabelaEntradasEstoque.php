<?php 
    require_once "../../classes/conexao.php";
    require_once "../../classes/entradas_estoque.php";

    $obj = new entradas_estoque();
    $result = $obj->listarTodasEntradas();
    $total = ($result) ? mysqli_num_rows($result) : 0;

    if($total > 0):
?>
<div class="table-responsive">
<table class="table-modern" id="tabelaEntradasDataTable">
    <thead>
        <tr>
            <th>Produto</th>
            <th style="text-align:center;">Quantidade</th>
            <th style="text-align:right;">Preço de Custo</th>
            <th style="text-align:right;">Preço de Venda</th>
            <th style="text-align:right;">Lucro Unit.</th>
            <th style="text-align:center;">Data de Entrada</th>
            <th style="width:140px;text-align:center;">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php while($mostrar = mysqli_fetch_assoc($result)): 
            $lucroUnit = $mostrar['preco_venda'] - $mostrar['preco'];
            $corLucro = $lucroUnit >= 0 ? 'var(--success, #059669)' : 'var(--danger, #dc2626)';
        ?>
            <tr>
                <td><strong><?php echo $mostrar['nome']; ?></strong></td>
                <td style="text-align:center;">
                    <span class="badge-modern badge-primary-modern"><?php echo $mostrar['quantidade']; ?> un.</span>
                </td>
                <td style="text-align:right;">R$ <?php echo number_format($mostrar['preco'], 2, ',', '.'); ?></td>
                <td style="text-align:right;">R$ <?php echo number_format($mostrar['preco_venda'], 2, ',', '.'); ?></td>
                <td style="text-align:right;color:<?php echo $corLucro; ?>;font-weight:600;">
                    R$ <?php echo number_format($lucroUnit, 2, ',', '.'); ?>
                </td>
                <td style="text-align:center;"><?php echo date('d/m/Y', strtotime($mostrar['data_entrada'])); ?></td>
                <td style="text-align:center;">
                    <div style="display:flex;gap:4px;justify-content:center;">
                        <button class="btn-modern btn-warning-modern btn-sm-modern" data-toggle="modal" data-target="#abremodalUpdateEntrada" onclick="addDadosEntrada(<?php echo $mostrar['id_entrada']; ?>)">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </button>
                        <button class="btn-modern btn-danger-modern btn-sm-modern" onclick="eliminarEntrada(<?php echo $mostrar['id_entrada']; ?>)">
                            <span class="glyphicon glyphicon-trash"></span>
                        </button>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</div>
<?php 
    else:
?>
<div class="alert-modern alert-info-modern">
    Nenhuma entrada registrada! Comece registrando uma entrada de estoque.
</div>
<?php 
    endif;
?>
