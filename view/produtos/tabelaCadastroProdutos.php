<?php 
    require_once "../../classes/conexao.php";
    require_once "../../classes/produtos.php";

    $obj = new produtos();
    $c = new conectar();
    $conexao = $c->conexao();

    $sql = "SELECT p.id_produto, 
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
<table class="table-modern" id="tabelaProdutosDataTable">
    <thead>
        <tr>
            <th style="width:80px;">Imagem</th>
            <th>Nome</th>
            <th>Categoria</th>
            <th>Descrição</th>
            <th style="width:120px;text-align:center;">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php while($mostrar = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td style="text-align:center;">
                    <?php 
                    $imgUrl = $mostrar['url'];
                    $imgExibir = str_replace("../../arquivos/", "../arquivos/", $imgUrl);
                    ?>
                    <img src="<?php echo $imgExibir; ?>" alt="<?php echo $mostrar['nome']; ?>" style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                </td>
                <td style="vertical-align:middle;"><strong><?php echo $mostrar['nome']; ?></strong></td>
                <td style="vertical-align:middle;"><span class="badge-modern badge-primary-modern"><?php echo $mostrar['nome_categoria']; ?></span></td>
                <td style="vertical-align:middle;"><?php echo substr($mostrar['descricao'], 0, 50) . (strlen($mostrar['descricao']) > 50 ? '...' : ''); ?></td>
                <td style="text-align:center;vertical-align:middle;">
                    <div style="display:flex;gap:4px;justify-content:center;">
                        <button class="btn-modern btn-warning-modern btn-sm-modern" data-toggle="modal" data-target="#abremodalUpdateProduto" onclick="addDadosProduto(<?php echo $mostrar['id_produto']; ?>)">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </button>
                        <button class="btn-modern btn-danger-modern btn-sm-modern" onclick="eliminarProduto(<?php echo $mostrar['id_produto']; ?>)">
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
    Nenhum produto cadastrado. Comece adicionando um novo produto.
</div>
<?php 
    endif;
?>
