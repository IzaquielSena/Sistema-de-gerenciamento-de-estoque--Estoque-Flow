<?php 
	session_start();
	require_once "../../classes/conexao.php";
	require_once "../../classes/vendas.php";
	$c= new conectar();
	
	$obj= new vendas();

	if(!isset($_SESSION['tabelaComprasTemp']) || count($_SESSION['tabelaComprasTemp'])==0){
		echo 0;
	}else{
		$result=$obj->criarVenda();
		echo $result;
	}
 ?>
