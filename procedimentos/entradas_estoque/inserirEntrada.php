<?php 
	session_start();
	$iduser=$_SESSION['iduser'];
	require_once "../../classes/conexao.php";
	require_once "../../classes/entradas_estoque.php";

	$obj = new entradas_estoque();

	if(!isset($_POST['produtoSelect']) || !isset($_POST['quantidade']) || !isset($_POST['preco']) || !isset($_POST['dataEntrada']) || !isset($_POST['precoVenda'])){
		echo 0;
		exit;
	}

	$idProduto = $_POST['produtoSelect'];
	$quantidade = $_POST['quantidade'];
	$preco = str_replace(",", ".", $_POST['preco']);
	$precoVenda = str_replace(",", ".", $_POST['precoVenda']);
	$dataEntrada = $_POST['dataEntrada'];

	$c = new conectar();
	$conexao = $c->conexao();
	$sql = "SELECT id_produto FROM produtos WHERE id_produto = '$idProduto'";
	$result = mysqli_query($conexao, $sql);
	
	if(mysqli_num_rows($result) == 0){
		echo 0;
		exit;
	}

	$dados = array(
		$idProduto,
		$iduser,
		$quantidade,
		$preco,
		$dataEntrada,
		$precoVenda
	);

	echo $obj->inserirEntrada($dados);
?>
