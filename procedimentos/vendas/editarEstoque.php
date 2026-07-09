<?php 
require_once "../../classes/conexao.php";
$c= new conectar();
$conexao=$c->conexao();

// Recebe o ID e a nova quantidade exata
$idproduto = $_POST['idproduto'];
$quantidade = $_POST['quantidade'];

// Atualiza o estoque com o valor correto
$sqlU = "UPDATE produtos SET quantidade = '$quantidade' WHERE id_produto = '$idproduto'";
mysqli_query($conexao, $sqlU);
?>