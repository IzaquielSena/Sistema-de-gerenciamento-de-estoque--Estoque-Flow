<?php
class dashboard {

    public function totalEntradas() {
        $c = new conectar();
        $conexao = $c->conexao();
        $sql = "SELECT COALESCE(SUM(quantidade * preco), 0) as total 
                FROM entradas_estoque 
                WHERE quantidade > 0";
        $result = mysqli_query($conexao, $sql);
        if (!$result) return 0;
        $row = mysqli_fetch_assoc($result);
        return $row['total'] ?? 0;
    }

    public function totalSaidas() {
        $c = new conectar();
        $conexao = $c->conexao();
        $sql = "SELECT COALESCE(SUM(ip.preco * ip.quantidade), 0) as total 
                FROM item_pedido ip";
        $result = mysqli_query($conexao, $sql);
        if (!$result) return 0;
        $row = mysqli_fetch_assoc($result);
        return $row['total'] ?? 0;
    }

    public function lucroTotal() {
        $c = new conectar();
        $conexao = $c->conexao();
        $sql = "SELECT COALESCE(SUM((ip.preco - ip.preco_custo) * ip.quantidade), 0) as lucro
                FROM item_pedido ip";
        $result = mysqli_query($conexao, $sql);
        if (!$result) return 0;
        $row = mysqli_fetch_assoc($result);
        return $row['lucro'] ?? 0;
    }

    public function totalVendasHoje() {
        $c = new conectar();
        $conexao = $c->conexao();
        $hoje = date('Y-m-d');
        $sql = "SELECT COALESCE(SUM(ip.preco * ip.quantidade), 0) as total 
                FROM item_pedido ip
                INNER JOIN vendas v ON ip.id_venda = v.id_venda
                WHERE v.dataCompra = '$hoje'";
        $result = mysqli_query($conexao, $sql);
        if (!$result) return 0;
        $row = mysqli_fetch_assoc($result);
        return $row['total'] ?? 0;
    }

    public function totalVendasMes() {
        $c = new conectar();
        $conexao = $c->conexao();
        $mes = date('m');
        $ano = date('Y');
        $sql = "SELECT COALESCE(SUM(ip.preco * ip.quantidade), 0) as total 
                FROM item_pedido ip
                INNER JOIN vendas v ON ip.id_venda = v.id_venda
                WHERE MONTH(v.dataCompra) = '$mes' AND YEAR(v.dataCompra) = '$ano'";
        $result = mysqli_query($conexao, $sql);
        if (!$result) return 0;
        $row = mysqli_fetch_assoc($result);
        return $row['total'] ?? 0;
    }

    public function quantidadeVendasMes() {
        $c = new conectar();
        $conexao = $c->conexao();
        $mes = date('m');
        $ano = date('Y');
        $sql = "SELECT COUNT(DISTINCT v.codigo_venda) as total 
                FROM vendas v
                WHERE MONTH(v.dataCompra) = '$mes' AND YEAR(v.dataCompra) = '$ano'";
        $result = mysqli_query($conexao, $sql);
        if (!$result) return 0;
        $row = mysqli_fetch_assoc($result);
        return $row['total'] ?? 0;
    }

    public function produtosBaixoEstoque($limite = 20) {
        $c = new conectar();
        $conexao = $c->conexao();
        $sql = "SELECT p.nome, COALESCE(SUM(e.quantidade), 0) as estoque 
                FROM produtos p 
                LEFT JOIN entradas_estoque e ON p.id_produto = e.id_produto 
                GROUP BY p.id_produto, p.nome
                HAVING estoque <= $limite 
                ORDER BY estoque ASC";
        return mysqli_query($conexao, $sql);
    }

    public function totalProdutosBaixoEstoque($limite = 20) {
        $c = new conectar();
        $conexao = $c->conexao();
        $sql = "SELECT COUNT(*) as total FROM (
                    SELECT p.id_produto, COALESCE(SUM(e.quantidade), 0) as estoque 
                    FROM produtos p 
                    LEFT JOIN entradas_estoque e ON p.id_produto = e.id_produto 
                    GROUP BY p.id_produto 
                    HAVING estoque <= $limite
                ) as sub";
        $result = mysqli_query($conexao, $sql);
        if (!$result) return 0;
        $row = mysqli_fetch_assoc($result);
        return $row['total'] ?? 0;
    }

    public function topProdutosMaisVendidos($limite = 5) {
        $c = new conectar();
        $conexao = $c->conexao();
        $sql = "SELECT p.nome, SUM(ip.quantidade) as total_vendido 
                FROM item_pedido ip
                INNER JOIN produtos p ON ip.id_produto = p.id_produto 
                GROUP BY ip.id_produto, p.nome
                ORDER BY total_vendido DESC 
                LIMIT $limite";
        return mysqli_query($conexao, $sql);
    }

    public function faturamentoPorCategoria() {
        $c = new conectar();
        $conexao = $c->conexao();
        $sql = "SELECT c.nome_categoria, SUM(ip.preco * ip.quantidade) as faturamento 
                FROM item_pedido ip
                INNER JOIN produtos p ON ip.id_produto = p.id_produto 
                INNER JOIN categorias c ON p.id_categoria = c.id_categoria 
                GROUP BY c.id_categoria, c.nome_categoria
                ORDER BY faturamento DESC";
        return mysqli_query($conexao, $sql);
    }

    public function ultimasVendas($limite = 5) {
        $c = new conectar();
        $conexao = $c->conexao();
        $sql = "SELECT v.codigo_venda, 
                       v.dataCompra, 
                       v.id_cliente, 
                       SUM(ip.preco * ip.quantidade) as total 
                FROM vendas v
                INNER JOIN item_pedido ip ON ip.id_venda = v.id_venda
                GROUP BY v.id_venda, v.codigo_venda, v.dataCompra, v.id_cliente
                ORDER BY v.codigo_venda DESC 
                LIMIT $limite";
        return mysqli_query($conexao, $sql);
    }
}
?>
