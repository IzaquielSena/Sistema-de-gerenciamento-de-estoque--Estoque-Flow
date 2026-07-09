<?php 
	require_once "../../classes/conexao.php";
	require_once "../../classes/entradas_estoque.php";

	$obj = new entradas_estoque();

	if(isset($_POST['identrada'])){
		$dados = $obj->obterDados($_POST['identrada']);
		echo json_encode($dados);
	}
?>
