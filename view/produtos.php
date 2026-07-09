<?php 
session_start();
if(isset($_SESSION['usuario'])){
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Produtos - EstoqueFlow</title>
        <?php require_once "menu.php"; ?>
        <?php require_once "../classes/conexao.php"; 
            $c= new conectar();
            $conexao=$c->conexao();
            $sql="SELECT id_categoria,nome_categoria from categorias";
            $result=mysqli_query($conexao,$sql);
        ?>
    </head>
    <body>
        <div class="content-area">
            <div class="page-header mb-3">
                <h1>Produtos</h1>
                <p>Gerencie seu catálogo de produtos e estoque</p>
            </div>

            <div class="row">
                <!-- Relatórios -->
                <div class="col-sm-12 mb-3">
                    <div class="card-modern">
                        <div class="card-header-modern">
                            <h4>Relatórios de Estoque</h4>
                        </div>
                        <div class="card-body-modern">
                            <div class="row">
                                <div class="col-sm-8">
                                    <form id="frmRelatorioEstoqueDatas" action="../procedimentos/produtos/criarRelatorioEstoqueDataPdf.php" method="POST" target="_blank" style="display: flex; gap: 10px; align-items: flex-end; flex-wrap: wrap;">
                                        <div class="form-group">
                                            <label class="mb-1">Início (Cadastro)</label>
                                            <input type="date" name="dataInicio" class="form-control-modern" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="mb-1">Fim (Cadastro)</label>
                                            <input type="date" name="dataFim" class="form-control-modern" required>
                                        </div>
                                        <button type="submit" class="btn-modern btn-primary-modern">
                                            <span class="glyphicon glyphicon-calendar"></span> Estoque por Período
                                        </button>
                                    </form>
                                </div>
                                <div class="col-sm-4 text-right">
                                    <a href="../procedimentos/produtos/criarRelatorioEstoquePdf.php" target="_blank" class="btn-modern btn-success-modern">
                                        <span class="glyphicon glyphicon-list-alt"></span> Estoque Geral Completo
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Formulário de Cadastro -->
                <div class="col-sm-4">
                    <div class="card-modern">
                        <div class="card-header-modern">
                            <h4>Novo Produto</h4>
                        </div>
                        <div class="card-body-modern">
                            <form id="frmProdutos" enctype="multipart/form-data">
                                <div class="form-group mb-2">
                                    <label>Categoria</label>
                                    <select class="form-control-modern" id="categoriaSelect" name="categoriaSelect">
                                        <option value="A">Selecionar Categoria</option>
                                        <?php while($mostrar=mysqli_fetch_row($result)): ?>
                                            <option value="<?php echo $mostrar[0] ?>"><?php echo $mostrar[1]; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label>Nome</label>
                                    <input type="text" class="form-control-modern" id="nome" name="nome">
                                </div>
                                <div class="form-group mb-2">
                                    <label>Descrição</label>
                                    <input type="text" class="form-control-modern" id="descricao" name="descricao">
                                </div>
                                <div class="form-group mb-2">
                                    <label>Quantidade</label>
                                    <input type="text" class="form-control-modern" id="quantidade" name="quantidade">
                                </div>
                                <div class="form-group mb-2">
                                    <label>Preço</label>
                                    <input type="text" class="form-control-modern" id="preco" name="preco">
                                </div>
                                <div class="form-group mb-3">
                                    <label>Imagem</label>
                                    <input type="file" class="form-control-modern" id="imagem" name="imagem">
                                </div>
                                <button type="button" id="btnAddProduto" class="btn-modern btn-primary-modern btn-block-modern">
                                    Adicionar Produto
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tabela de Produtos -->
                <div class="col-sm-8">
                    <div class="card-modern">
                        <div class="card-header-modern">
                            <h4>Lista de Produtos</h4>
                        </div>
                        <div class="card-body-modern">
                            <div id="tabelaProdutosLoad"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Edição -->
        <div class="modal fade" id="abremodalUpdateProduto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Editar Produto</h4>
                    </div>
                    <div class="modal-body">
                        <form id="frmProdutosU" enctype="multipart/form-data">
                            <input type="text" id="idProduto" hidden="" name="idProduto">
                            <div class="form-group mb-2">
                                <label>Categoria</label>
                                <select class="form-control-modern" id="categoriaSelectU" name="categoriaSelectU">
                                    <option value="A">Selecionar Categoria</option>
                                    <?php 
                                    $sql="SELECT id_categoria,nome_categoria from categorias";
                                    $result=mysqli_query($conexao,$sql);
                                    while($mostrar=mysqli_fetch_row($result)): ?>
                                        <option value="<?php echo $mostrar[0] ?>"><?php echo $mostrar[1]; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label>Nome</label>
                                <input type="text" class="form-control-modern" id="nomeU" name="nomeU">
                            </div>
                            <div class="form-group mb-2">
                                <label>Descrição</label>
                                <input type="text" class="form-control-modern" id="descricaoU" name="descricaoU">
                            </div>
                            <div class="form-group mb-2">
                                <label>Quantidade</label>
                                <input type="text" class="form-control-modern" id="quantidadeU" name="quantidadeU">
                            </div>
                            <div class="form-group mb-2">
                                <label>Preço</label>
                                <input type="text" class="form-control-modern" id="precoU" name="precoU">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="btnAtualizarProduto" type="button" class="btn-modern btn-warning-modern" data-dismiss="modal">Salvar Alterações</button>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            function addDadosProduto(idproduto){
                $.ajax({
                    type:"POST",
                    data:"idpro=" + idproduto,
                    url:"../procedimentos/produtos/obterDados.php",
                    success:function(r){
                        dado=jQuery.parseJSON(r);
                        $('#idProduto').val(dado['id_produto']);
                        $('#categoriaSelectU').val(dado['id_categoria']);
                        $('#nomeU').val(dado['nome']);
                        $('#descricaoU').val(dado['descricao']);
                        $('#quantidadeU').val(dado['quantidade']);
                        $('#precoU').val(dado['preco']);
                    }
                });
            }

            function eliminarProduto(idProduto){
                alertify.confirm('Deseja Excluir este Produto?', function(){ 
                    $.ajax({
                        type:"POST",
                        data:"idproduto=" + idProduto,
                        url:"../procedimentos/produtos/eliminarProdutos.php",
                        success:function(r){
                            if(r==1){
                                $('#tabelaProdutosLoad').load("produtos/tabelaProdutos.php");
                                alertify.success("Excluido com sucesso!!");
                            }else{
                                alertify.error("Não Excluido :(");
                            }
                        }
                    });
                }, function(){ 
                    alertify.error('Cancelado !')
                });
            }

            $(document).ready(function(){
                $('#tabelaProdutosLoad').load("produtos/tabelaProdutos.php");

                $('#btnAtualizarProduto').click(function(){
                    dados=$('#frmProdutosU').serialize();
                    $.ajax({
                        type:"POST",
                        data:dados,
                        url:"../procedimentos/produtos/atualizarProdutos.php",
                        success:function(r){
                            if(r==1){
                                $('#tabelaProdutosLoad').load("produtos/tabelaProdutos.php");
                                alertify.success("Editado com sucesso!!");
                            }else{
                                alertify.error("Erro ao editar");
                            }
                        }
                    });
                });

                $('#btnAddProduto').click(function(){
                    vazios=validarFormVazio('frmProdutos');
                    if(vazios > 0){
                        alertify.alert("Preencha todos os campos!!");
                        return false;
                    }
                    var formData = new FormData(document.getElementById("frmProdutos"));
                    $.ajax({
                        url: "../procedimentos/produtos/inserirProdutos.php",
                        type: "post",
                        dataType: "html",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success:function(r){
                            if(r == 1){
                                $('#frmProdutos')[0].reset();
                                $('#tabelaProdutosLoad').load("produtos/tabelaProdutos.php");
                                alertify.success("Adicionado com sucesso!!");
                            }else{
                                alertify.error("Falha ao Adicionar");
                            }
                        }
                    });
                });
            });
        </script>
    </body>
    </html>
    <?php 
}else{
    header("location:../index.php");
}
?>
