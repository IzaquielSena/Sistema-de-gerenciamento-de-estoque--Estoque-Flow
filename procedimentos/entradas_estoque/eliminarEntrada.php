<?php 
	require_once "../../classes/conexao.php";
	require_once "../../classes/entradas_estoque.php";

	$obj = new entradas_estoque();

	if(isset($_POST['identrada'])){
		echo $obj->excluir($_POST['identrada']);
	}else{
		echo 0;
	}
?>
