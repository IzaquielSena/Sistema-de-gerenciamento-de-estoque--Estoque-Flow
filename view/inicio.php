<?php 
	session_start();
	if(isset($_SESSION['usuario'])){
 ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>EstoqueFlow - Dashboard</title>
	<?php require_once "menu.php" ?>
</head>
<body>

<!-- Content Area -->
<div class="content-area fade-in">
	<div class="page-header">
		<h1>Painel Executivo</h1>
		<p>Bem-vindo ao EstoqueFlow. Aqui está o resumo das suas atividades.</p>
	</div>

	<div id="dashboardLoad"></div>
</div>

<!-- Footer -->
<div class="app-footer">
	EstoqueFlow &copy; <?php echo date('Y'); ?> - Sistema de Controle de Estoque
</div>

</div><!-- Close main-content from menu.php -->

</body>
</html>

<script type="text/javascript">
	$(document).ready(function(){
		$('#dashboardLoad').load('dashboard/painelDashboard.php');
		$('#dashboardLoad').show();
	});
</script>

<?php 
} else{
	header("location:../index.php");
}
 ?>
