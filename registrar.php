<?php
	require_once "classes/conexao.php";
	$obj = new conectar();
	$conexao = $obj->conexao();

	$sql = "SELECT * from usuarios where email='admin'";
	$result = mysqli_query($conexao, $sql);

	$validar = 0;
	if(mysqli_num_rows($result) > 0){
		header("location:index.php");
	}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>EstoqueFlow - Registrar</title>
	<link rel="stylesheet" type="text/css" href="lib/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/modern.css">
	<script src="lib/jquery-3.2.1.min.js"></script>
	<script src="js/funcoes.js"></script>
</head>
<body>
	<div class="login-page">
		<div class="login-card fade-in">
			<div class="login-logo">
				<img src="img/ximac.jpg" alt="EstoqueFlow">
			</div>
			<h1 class="login-title">Registrar Administrador</h1>
			<p class="login-subtitle">Crie a conta de administrador do sistema</p>
			
			<form id="frmRegistro">
				<div class="form-group-modern">
					<label>Nome</label>
					<input type="text" class="form-control-modern" name="nome" id="nome" placeholder="Nome completo">
				</div>
				<div class="form-group-modern">
					<label>Usuário</label>
					<input type="text" class="form-control-modern" name="usuario" id="usuario" placeholder="Nome de usuário">
				</div>
				<div class="form-group-modern">
					<label>Email</label>
					<input type="text" class="form-control-modern" name="email" id="email" placeholder="email@exemplo.com">
				</div>
				<div class="form-group-modern">
					<label>Senha</label>
					<input type="password" class="form-control-modern" name="senha" id="senha" placeholder="Crie uma senha segura">
				</div>
				<button type="button" class="btn-login" id="registro" style="margin-bottom: 8px;">
					Registrar
				</button>
				<a href="index.php" class="btn-modern btn-outline-modern btn-block-modern" style="text-align:center;">
					Voltar ao Login
				</a>
			</form>
		</div>
	</div>
</body>
</html>

<script type="text/javascript">
	$(document).ready(function(){
		$('#frmRegistro').on('keypress', function(e) {
			if (e.which === 13) {
				$('#registro').click();
			}
		});

		$('#registro').click(function(){
			vazios = validarFormVazio('frmRegistro');

			if(vazios > 0){
				alert("Preencha todos os campos!");
				return false;
			}

			dados = $('#frmRegistro').serialize();
			
			$.ajax({
				type: "POST",
				data: dados,
				url: "procedimentos/login/registrarUsuario.php",
				success: function(r){
					if(r == 1){
						alert("Administrador registrado com sucesso!");
						window.location = "index.php";
					} else {
						alert("Erro ao registrar. Tente novamente.");
					}
				}
			});
		});
	});
</script>
