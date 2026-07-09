<?php
	require_once "classes/conexao.php";
	$obj = new conectar();
	$conexao = $obj->conexao();

	$sql = "SELECT * from usuarios where email='admin'";
	$result = mysqli_query($conexao, $sql);

	$validar = 0;
	if(mysqli_num_rows($result) > 0){
		$validar = 1;
	}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>EstoqueFlow - Login</title>
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
			<h1 class="login-title">EstoqueFlow</h1>
			<p class="login-subtitle">Sistema de Controle de Estoque</p>
			
			<form id="frmLogin">
				<div class="form-group-modern">
					<label>Email</label>
					<input type="text" class="form-control-modern" name="email" id="email" placeholder="Digite seu email">
				</div>
				<div class="form-group-modern">
					<label>Senha</label>
					<input type="password" name="senha" id="senha" class="form-control-modern" placeholder="Digite sua senha">
				</div>
				<button type="button" class="btn-login" id="entrarSistema">
					Entrar no Sistema
				</button>
			</form>

			<?php if(!$validar): ?>
			<div class="login-footer">
				Primeiro acesso? <a href="registrar.php">Registrar Administrador</a>
			</div>
			<?php endif; ?>
		</div>
	</div>
</body>
</html>

<script type="text/javascript">
	$(document).ready(function(){
		$('#frmLogin').on('keypress', function(e) {
			if (e.which === 13) {
				$('#entrarSistema').click();
			}
		});

		$('#entrarSistema').click(function(){
			vazios = validarFormVazio('frmLogin');

			if(vazios > 0){
				alert("Preencha os campos!");
				return false;
			}

			dados = $('#frmLogin').serialize();
			$.ajax({
				type: "POST",
				data: dados,
				url: "procedimentos/login/login.php",
				success: function(r){
					if(r == 1){
						window.location = "view/inicio.php";
					} else {
						alert("Acesso Negado! Verifique email e senha.");
					}
				}
			});
		});
	});
</script>
