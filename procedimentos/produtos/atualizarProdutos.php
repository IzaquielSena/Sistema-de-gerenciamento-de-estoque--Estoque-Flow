<?php 

	require_once "../../classes/conexao.php";
	require_once "../../classes/produtos.php";

	$obj= new produtos();

	// Tratamento para aceitar vírgula do usuário e converter para ponto (padrão do banco)
	$precoTratado = str_replace(",", ".", $_POST['precoU']);

	$dados=array(
		$_POST['idProduto'],
	    $_POST['categoriaSelectU'],
	    $_POST['nomeU'],
	    $_POST['descricaoU'],
	    $_POST['quantidadeU'],
	    $precoTratado // Valor corrigido aqui
			);

    echo $obj->atualizar($dados);

 ?>