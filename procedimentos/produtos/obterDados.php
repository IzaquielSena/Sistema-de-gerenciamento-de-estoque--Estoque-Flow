<?php 
	require_once "../../classes/conexao.php";
	require_once "../../classes/produtos.php";

	$obj= new produtos();
	$idpro = $_POST['idpro'];
	
	$dados = $obj->obterDados($idpro);
	
	// Adicionar quantidade e preço aos dados retornados para preencher o formulário de edição
	$c = new conectar();
	$conexao = $c->conexao();
	
	// Busca quantidade total em estoque
	$sqlQtd="SELECT COALESCE(SUM(quantidade), 0) as total
			 from entradas_estoque 
			 where id_produto='$idpro'";
	$resultQtd=mysqli_query($conexao,$sqlQtd);
	$rowQtd=mysqli_fetch_assoc($resultQtd);
	$dados['quantidade'] = $rowQtd['total'];

	// Busca preço atual (última entrada)
	$sqlPreco="SELECT preco
			   from entradas_estoque 
			   where id_produto='$idpro'
			   ORDER BY data_entrada DESC
			   LIMIT 1";
	$resultPreco=mysqli_query($conexao,$sqlPreco);
	$rowPreco=mysqli_fetch_assoc($resultPreco);
	$dados['preco'] = $rowPreco ? $rowPreco['preco'] : 0;

	echo json_encode($dados);
 ?>
