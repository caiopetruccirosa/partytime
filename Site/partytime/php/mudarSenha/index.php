<!DOCTYPE html>
<html>

<head>
	<title>party time.</title>
	<meta charset="utf-8">

	<?php
		session_start();

		if (isset($_SESSION['logado']) && $_SESSION['logado'] == true) {
			header("Location:../../dashboard/");
			exit();
		}

		if (isset($_GET['usuario'])) {
			$_SESSION['chave'] = $_GET['usuario'];
		} else {
			header("Location:../../");
			exit();
		}
	?>

	<link rel="stylesheet" type="text/css" href="../../css/style.css">
	<link rel="stylesheet" type="text/css" href="../../css/animate.css">
	<link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../../css/font-awesome.min.css">

	<link rel="icon" href="../../imagens/icone.ico">

	<script type="text/javascript" src="../../js/jquery.js"></script>
	<script type="text/javascript" src="../../js/script.js"></script>

	<script type="text/javascript">

		$(document).ready(function(){
			$(document).find("#divPrincipal").hide();
			$('#frmMudar').show();
			$(document).find('#divPrincipal').css({ 'height' : '390px' });
			
			$(document).find("#divPrincipal").ready(function(){
				$(document).find("#divPrincipal").show();
				$(document).find('#divPrincipal').addClass('animated bounceInDown').one('webkitAnimationEnd', function() {
					$(document).find('#divPrincipal').removeClass('animated bounceInDown');
				});
			});
		});
	</script>
</head>

<body id="main">
	<div id="divPrincipal" class="center-div">

		<center><h1 style="font-size: 80px;">party time.</h1></center>
		<br>
		<div id="divForm">
			<!-- 											FORM MUDAR 											-->
			<form action="mudarSenha.php" method="post" name="frmLogin" id="frmMudar">

				<label>Digite sua nova senha:</label>
				<br>
				<div class='form-group input-group'>
					<span class='input-group-addon'><i class='fa fa-lock fa-fw' aria-hidden='true'></i></span>
					<input type='password' name='novaSenha' id='inputDefault' class='form-control' required placeholder='Nova senha...'>
				</div>

				<label>Confirme sua nova senha:</label>
				<br>
				<div class='form-group input-group'>
					<span class='input-group-addon'><i class='fa fa-lock fa-fw' aria-hidden='true'></i></span>
					<input type='password' name='novaSenhaConf' id='inputDefault' class='form-control' required placeholder='Confirme a nova senha...'>
				</div>

				<br>

				<input type="submit" name="bmudar" value="Entrar" class="btn btn-primary btn-lg" id="btnLogar">

			</form>
		</div>

	</div>
</body>
</html>