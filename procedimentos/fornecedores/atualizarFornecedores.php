<?php 


require_once "../../classes/conexao.php";
require_once "../../classes/fornecedores.php";



$obj = new fornecedores();



$dados=array(
	$_POST['idfornecedorU'],
	$_POST['rasaosocialU'],
	$_POST['nomefantasiaU'],
	$_POST['enderecoU'],
	$_POST['emailU'],
	$_POST['telefoneU'],
	$_POST['cnpjU']
	

);

echo $obj->atualizar($dados);

 ?>