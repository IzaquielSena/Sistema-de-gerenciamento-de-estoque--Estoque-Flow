<?php 
	require_once "../../classes/conexao.php";
	require_once "../../classes/usuarios.php";

	$obj = new usuarios();

	if(isset($_POST['idUsuarioSenha']) && isset($_POST['novaSenha']) && isset($_POST['confirmarSenha'])){
		
		$idUsuario = $_POST['idUsuarioSenha'];
		$novaSenha = $_POST['novaSenha'];
		$confirmarSenha = $_POST['confirmarSenha'];

		if($novaSenha != $confirmarSenha){
			echo 2;
			exit;
		}

		if(strlen($novaSenha) < 4){
			echo 3;
			exit;
		}

		$result = $obj->redefinirSenha($idUsuario, $novaSenha);
		
		if($result){
			echo 1;
		}else{
			echo 0;
		}
	}else{
		echo 0;
	}
?>
