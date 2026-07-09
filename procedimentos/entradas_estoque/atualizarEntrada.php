<?php 
	require_once "../../classes/conexao.php";
	require_once "../../classes/entradas_estoque.php";

	$obj = new entradas_estoque();

	if(isset($_POST['idEntrada']) && isset($_POST['quantidadeU']) && isset($_POST['precoU']) && isset($_POST['dataEntradaU'])){
		$preco = str_replace(",", ".", $_POST['precoU']);
		$precoVenda = isset($_POST['precoVendaU']) ? str_replace(",", ".", $_POST['precoVendaU']) : 0;
		
		$dados = array(
			$_POST['idEntrada'],
			$_POST['quantidadeU'],
			$preco,
			$_POST['dataEntradaU'],
			$precoVenda
		);

		echo $obj->atualizar($dados);
	}else{
		echo 0;
	}
?>
