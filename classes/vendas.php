<?php 

class vendas{

    public function obterDadosProduto($idproduto){
        $c= new conectar();
        $conexao=$c->conexao();

        $sql="SELECT pro.nome,
        pro.descricao,
        img.url
        from produtos as pro 
        inner join imagens as img
        on pro.id_imagem=img.id_imagem 
        where pro.id_produto='$idproduto'";
        $result=mysqli_query($conexao,$sql);
        $ver=mysqli_fetch_row($result);

        // Quantidade total em estoque
        $sqlQtd="SELECT COALESCE(SUM(quantidade), 0) as total
                 from entradas_estoque 
                 where id_produto='$idproduto'";
        $resultQtd=mysqli_query($conexao,$sqlQtd);
        $rowQtd=mysqli_fetch_assoc($resultQtd);
        $quantidade=$rowQtd['total'];

        // Preço de venda (última entrada)
        $sqlPreco="SELECT preco_venda
                   from entradas_estoque 
                   where id_produto='$idproduto' AND preco_venda > 0
                   ORDER BY data_entrada DESC
                   LIMIT 1";
        $resultPreco=mysqli_query($conexao,$sqlPreco);
        $rowPreco=mysqli_fetch_assoc($resultPreco);
        $preco_venda = $rowPreco ? $rowPreco['preco_venda'] : 0;

        if($preco_venda == 0){
            $sqlPrecoCusto="SELECT preco
                       from entradas_estoque 
                       where id_produto='$idproduto'
                       ORDER BY data_entrada DESC
                       LIMIT 1";
            $resultPrecoCusto=mysqli_query($conexao,$sqlPrecoCusto);
            $rowPrecoCusto=mysqli_fetch_assoc($resultPrecoCusto);
            $preco_venda=$rowPrecoCusto ? $rowPrecoCusto['preco'] : 0;
        }

        // Preço de custo (última entrada)
        $sqlCusto="SELECT preco
                   from entradas_estoque 
                   where id_produto='$idproduto'
                   ORDER BY data_entrada DESC
                   LIMIT 1";
        $resultCusto=mysqli_query($conexao,$sqlCusto);
        $rowCusto=mysqli_fetch_assoc($resultCusto);
        $preco_custo=$rowCusto ? $rowCusto['preco'] : 0;

        $d=explode('/', $ver[2]);
        $img=$d[1].'/'.$d[2].'/'.$d[3];

        $dados=array(
            'nome'        => $ver[0],
            'descricao'   => $ver[1],
            'quantidade'  => $quantidade,
            'url'         => $img,
            'preco'       => $preco_venda,
            'preco_custo' => $preco_custo
        );      
        return $dados;
    }

    public function criarVenda(){
        $c= new conectar();
        $conexao=$c->conexao();

        $data      = date('Y-m-d');
        $idusuario = $_SESSION['iduser'];
        $dados     = $_SESSION['tabelaComprasTemp'];

        // Gerar codigo_venda único
        $sqlCodigo = "SELECT COALESCE(MAX(codigo_venda), 0) + 1 as proximo FROM vendas";
        $resultCodigo = mysqli_query($conexao, $sqlCodigo);
        $rowCodigo = mysqli_fetch_assoc($resultCodigo);
        $codigoVenda = $rowCodigo['proximo'];

        // Pegar id_cliente do primeiro item (todos compartilham o mesmo cliente)
        $primeiroItem = explode("||", $dados[0]);
        $idCliente    = $primeiroItem[8];

        // Inserir cabeçalho na tabela vendas
        $sqlVenda = "INSERT INTO vendas (codigo_venda, id_cliente, id_usuario, dataCompra)
                     VALUES ('$codigoVenda', '$idCliente', '$idusuario', '$data')";

        if(!mysqli_query($conexao, $sqlVenda)){
            return false;
        }

        $idVenda = mysqli_insert_id($conexao);
        $r = 0;

        // Inserir cada item em item_pedido
        for ($i = 0; $i < count($dados); $i++) {
            $d          = explode("||", $dados[$i]);
            $idproduto  = $d[0];
            $precoVenda = $d[3];
            $precoCusto = isset($d[9]) ? $d[9] : 0;
            $quantidade = $d[6];

            $sqlItem = "INSERT INTO item_pedido (id_venda, id_produto, preco, preco_custo, quantidade)
                        VALUES ('$idVenda', '$idproduto', '$precoVenda', '$precoCusto', '$quantidade')";

            if(mysqli_query($conexao, $sqlItem)){
                // Registrar saída no estoque (entrada negativa)
                $quantidadeNegativa = -$quantidade;
                $sqlSaida = "INSERT INTO entradas_estoque 
                                (id_produto, id_usuario, quantidade, preco, preco_venda, data_entrada, dataCaptura)
                             VALUES 
                                ('$idproduto', '$idusuario', '$quantidadeNegativa', '$precoCusto', '$precoVenda', '$data', '$data')";
                if(mysqli_query($conexao, $sqlSaida)){
                    $r++;
                }
            }
        }

        unset($_SESSION['tabelaComprasTemp']);
        return $codigoVenda;
    }

    public function nomeCliente($idCliente){
        $c = new conectar();
        $conexao = $c->conexao();

        if(empty($idCliente) || $idCliente == 0 || $idCliente == "Sem Cliente"){
            return "Sem Cliente";
        }

        $sql = "SELECT nome, sobrenome 
                FROM clientes 
                WHERE id_cliente='$idCliente'";
        $result = mysqli_query($conexao, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $ver = mysqli_fetch_row($result);
            return $ver[0] . " " . $ver[1];
        }
        return "Sem Cliente";
    }

    // Total da venda calculado via item_pedido (sem armazenar total)
    public function obterTotal($codigoVenda){
        $c= new conectar();
        $conexao=$c->conexao();

        $sql="SELECT SUM(ip.preco * ip.quantidade) as total_venda
              FROM item_pedido ip
              INNER JOIN vendas v ON ip.id_venda = v.id_venda
              WHERE v.codigo_venda = '$codigoVenda'";
        $result = mysqli_query($conexao, $sql);
        $ver    = mysqli_fetch_assoc($result);
        return $ver['total_venda'] ? $ver['total_venda'] : 0;
    }

    // Lucro calculado via item_pedido
    public function obterLucro($codigoVenda){
        $c= new conectar();
        $conexao=$c->conexao();

        $sql="SELECT SUM((ip.preco - ip.preco_custo) * ip.quantidade) as lucro
              FROM item_pedido ip
              INNER JOIN vendas v ON ip.id_venda = v.id_venda
              WHERE v.codigo_venda = '$codigoVenda'";
        $result = mysqli_query($conexao, $sql);
        $ver    = mysqli_fetch_assoc($result);
        return $ver['lucro'] ? $ver['lucro'] : 0;
    }

    public function obterTodasVendasGeral(){
        $c = new conectar();
        $conexao = $c->conexao();

        $sql = "SELECT v.codigo_venda,
                       v.dataCompra,
                       v.id_cliente,
                       SUM(ip.preco * ip.quantidade) as total
                FROM vendas v
                INNER JOIN item_pedido ip ON ip.id_venda = v.id_venda
                GROUP BY v.id_venda, v.codigo_venda, v.dataCompra, v.id_cliente
                ORDER BY v.codigo_venda DESC";
        return mysqli_query($conexao, $sql);
    }

    public function relatorioVendasPorData($dataInicio, $dataFim){
        $c = new conectar();
        $conexao = $c->conexao();

        $sql = "SELECT v.codigo_venda,
                       v.dataCompra,
                       v.id_cliente,
                       SUM(ip.preco * ip.quantidade) as total
                FROM vendas v
                INNER JOIN item_pedido ip ON ip.id_venda = v.id_venda
                WHERE v.dataCompra BETWEEN '$dataInicio' AND '$dataFim'
                GROUP BY v.id_venda, v.codigo_venda, v.dataCompra, v.id_cliente
                ORDER BY v.dataCompra ASC";
        return mysqli_query($conexao, $sql);
    }
}
?>
