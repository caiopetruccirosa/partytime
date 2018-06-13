<!DOCTYPE html>
<html>

<head>
	<title>party time.</title>
	<meta charset="utf-8">

	<?php
		session_start();
	?>

	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/animate.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">

	<link rel="icon" href="imagens/icone.ico">

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/script.js"></script>

	<script type="text/javascript">

		$(document).ready(function(){
			$(document).find("#divPrincipal").hide();
				<?php 
					if (isset($_SESSION['login_erro'])) {
						echo "$('#frmLogin').show();\n";
						echo "$(document).find('#divPrincipal').css({ 'height' : '390px' });\n";
					}
					else if (isset($_SESSION['cadastro_erro'])) {
						echo "$('#frmCadastro').show();\n";
						echo "$(document).find('#divPrincipal').css({ 'height' : '625px' });\n";
					}
					else if (isset($_SESSION['novaSenha_erro'])) {
						echo "$('#frmMudarSenha').show();\n";
						echo "$(document).find('#divPrincipal').css({ 'height' : '360px' });\n";
					}
					else if (isset($_SESSION['alerta_sucesso'])) {
						echo "$('#frmLogin').show();\n";
						echo "$(document).find('#divPrincipal').css({ 'height' : '390px' });\n";
					}
					else {
						echo "$('#frmLogin').show();\n";
						echo "$(document).find('#divPrincipal').css({ 'height' : '390px' });\n";
					}
				?>
			$(document).find("#divPrincipal").ready(function(){
				$(document).find("#divPrincipal").show();
				$(document).find('#divPrincipal').addClass('animated bounceInDown').one('webkitAnimationEnd', function() {
					$(document).find('#divPrincipal').removeClass('animated bounceInDown');
				});
			});
		});
	</script>
</head>

	<?php
		if (isset($_SESSION['logado']) && $_SESSION['logado'] == true) {
			header("Location:dashboard/");
			exit();
		} else if (isset($_SESSION['login_erro'])) {
			$erro = $_SESSION['login_erro'];
			unset($_SESSION['login_erro']);
		} else if (isset($_SESSION['cadastro_erro'])) {
			$erro = $_SESSION['cadastro_erro'];
			unset($_SESSION['cadastro_erro']);
		} else if (isset($_SESSION['novaSenha_erro'])) {
			$erro_novaSenha = $_SESSION['novaSenha_erro'];
			unset($_SESSION['novaSenha_erro']);
		} else if (isset($_SESSION['alerta_sucesso'])) {
			$alerta_sucesso = $_SESSION['alerta_sucesso'];
			unset($_SESSION['alerta_sucesso']);
		}
	?>

<body id="main">

	<?php
		if (isset($erro) && ($erro == "Complete todos os campos!"))
			echo "
				<center>
					<div class='alert alert-dismissible alert-danger alerta-erro'>
					  <button type='button' class='close' data-dismiss='alert' onclick='fecharAlert()'>&times;</button>
			          <strong> $erro </strong>
					</div>
				</center>

				<script type='text/javascript'>
					$(document).ready(function(){
						$(document).find('.alert').hide();
						$(document).find('.alert').ready(function(){
							setTimeout(function(){
								$(document).find('.alert').show();
								$(document).find('.alert').addClass('animated zoomIn').one('webkitAnimationEnd', function() {
									$(document).find('.alert').removeClass('animated zoomIn');
								});	
							}, 390);
						});
					});
				</script>
				";
		
		if (isset($alerta_sucesso) && $alerta_sucesso == "cadastro") {
			echo "
				<center>
					<div class='alert alert-dismissible alert-success alerta-sucesso'>
					  <button type='button' class='close' data-dismiss='alert' onclick='fecharAlert()'>&times;</button>
			          <strong>Cadastro feito com sucesso!</strong> Disponível para login.
					</div>
				</center>

				<script type='text/javascript'>
					$(document).ready(function(){
						$(document).find('.alert').hide();
						$(document).find('.alert').ready(function(){
							setTimeout(function(){
								$(document).find('.alert').show();
								$(document).find('.alert').addClass('animated zoomIn').one('webkitAnimationEnd', function() {
									$(document).find('.alert').removeClass('animated zoomIn');
								});	
							}, 390);
						});
					});
				</script>";
		} else if (isset($alerta_sucesso) && $alerta_sucesso == "enviouEmail") {
			echo "
				<center>
					<div class='alert alert-dismissible alert-success alerta-sucesso'>
					  <button type='button' class='close' data-dismiss='alert' onclick='fecharAlert()'>&times;</button>
			          <strong>E-mail enviado com sucesso!</strong> Redefina a sua senha.
					</div>
				</center>

				<script type='text/javascript'>
					$(document).ready(function(){
						$(document).find('.alert').hide();
						$(document).find('.alert').ready(function(){
							setTimeout(function(){
								$(document).find('.alert').show();
								$(document).find('.alert').addClass('animated zoomIn').one('webkitAnimationEnd', function() {
									$(document).find('.alert').removeClass('animated zoomIn');
								});	
							}, 390);
						});
					});
				</script>";
		} else if (isset($alerta_sucesso) && $alerta_sucesso == "mudouSenha") {
			echo "
				<center>
					<div class='alert alert-dismissible alert-success alerta-sucesso'>
					  <button type='button' class='close' data-dismiss='alert' onclick='fecharAlert()'>&times;</button>
			          <strong>E-mail enviado com sucesso!</strong> Redefina a sua senha.
					</div>
				</center>

				<script type='text/javascript'>
					$(document).ready(function(){
						$(document).find('.alert').hide();
						$(document).find('.alert').ready(function(){
							setTimeout(function(){
								$(document).find('.alert').show();
								$(document).find('.alert').addClass('animated zoomIn').one('webkitAnimationEnd', function() {
									$(document).find('.alert').removeClass('animated zoomIn');
								});	
							}, 390);
						});
					});
				</script>";
		}
	?>

	<div id="divPrincipal" class="center-div">

		<center><h1 style="font-size: 80px;">party time.</h1></center>
		<br>
		<div id="divForm">
			<!-- 											FORM LOGIN 											-->
			<form action="php/logar/" method="post" name="frmLogin" id="frmLogin">
				<?php
					if (isset($erro) && $erro == 'Usuário não existe') {
						echo "
						<label style='color:red'>$erro</label>
						<div class='form-group input-group has-error'>
							<span class='input-group-addon'><i class='fa fa-envelope-o fa-fw' aria-hidden='true'></i></span>
							<input type='email' name='email' id='inputError' class='form-control' required placeholder='E-mail...' maxlength='50'>
						</div>
						";
					} else
						echo "
						<div class='form-group input-group'>
							<span class='input-group-addon'><i class='fa fa-envelope-o fa-fw' aria-hidden='true'></i></span>
							<input type='email' name='email' id='inputDefault' class='form-control' required placeholder='E-mail...' maxlength='50'>
						</div>
						";

					if (isset($erro) && $erro == 'Senha incorreta') {
						echo "
						<label style='color:red'>$erro</label>
						<div class='form-group input-group has-error'>
							<span class='input-group-addon'><i class='fa fa-lock fa-fw' aria-hidden='true'></i></span>
							<input type='password' name='senha' id='inputError' class='form-control' required placeholder='Senha...'>
						</div>
						";
					} else
						echo "
						<div class='form-group input-group'>
							<span class='input-group-addon'><i class='fa fa-lock fa-fw' aria-hidden='true'></i></span>
							<input type='password' name='senha' id='inputDefault' class='form-control' required placeholder='Senha...'>
						</div>
						";
				?>

				<br>

				<input type="submit" name="blogar" value="Entrar" class="btn btn-primary btn-lg" id="btnLogar">

				<br><br>

				<center>
					<a onclick="mudarForm('cadastro')" style="color: white">Ainda não possui uma conta?</a>
					|
					<a onclick="mudarForm('mudarSenha')" style="color: white">Esqueceu a senha?</a>
				</center>
			</form>

			<!-- 											FORM CADASTRO 											-->

			<form action="php/cadastrar/" method="post" name="frmCadastro" id="frmCadastro">

				<?php
					if (isset($erro) && $erro == 'Digite um nome entre 2 e 50 caracteres') {
						echo "
						<label style='color:red'>$erro</label>
						<div class='form-group input-group has-error'>
							<span class='input-group-addon'><span class='glyphicon glyphicon-user'></span> </i></span>
							<input type='text' name='username' id='inputError' class='form-control' required placeholder='Digite seu nome de usuário...' maxlength='20'>
						</div>
						";
					} else
						echo "
						<div class='form-group input-group'>
						  <span class='input-group-addon'><span class='glyphicon glyphicon-user'></span> </i></span>
						  <input type='text' name='username' id='inputDefault' class='form-control' required placeholder='Digite seu nome de usuário...' maxlength='20'>
						</div>
						";

					if (isset($erro) && ($erro == '"E-mail inválido!"' || $erro == 'E-mail já usado' || $erro == 'Digite um email com menos de 50 caracteres')) {
						echo "
						<label style='color:red'>$erro</label>
						<div class='form-group input-group has-error'>
							<span class='input-group-addon'><i class='fa fa-envelope-o fa-fw' aria-hidden='true'></i></span>
							<input type='email' name='email' id='inputError' class='form-control' required placeholder='Digite seu e-mail...' maxlength='50'>
						</div>
						";
					} else
						echo "
						<div class='form-group input-group'>
						  <span class='input-group-addon'><i class='fa fa-envelope-o fa-fw' aria-hidden='true'></i></span>
						  <input type='email' name='email' id='inputDefault' class='form-control' required placeholder='Digite seu e-mail...' maxlength='50'>
						</div>
						";

					if (isset($erro) && ($erro == 'Digite uma senha com pelo menos 8 caracteres' || $erro == 'Senha inválida' || $erro == 'Senhas diferentes' || $erro == 'Senha fraca! Coloque letras maiúsculas e minúsculas' || $erro == 'Senha fraca! Coloque números')) {
						echo "
						<label style='color:red;'>$erro</label>
						<div class='form-group input-group has-error'>
						  	<span class='input-group-addon'><i class='fa fa-lock fa-fw' aria-hidden='true'></i></span>
						  	<input type='password' name='senha' id='inputError' class='form-control' required placeholder='Escolha uma senha...'>
						</div>

						<div class='form-group input-group has-error'>
						  	<span class='input-group-addon'><i class='fa fa-lock fa-fw' aria-hidden='true'></i></span>
						  	<input type='password' name='senhaconf' id='inputError' class='form-control' required placeholder='Confirme a senha...'>
						</div>
						";
					} else
						echo "
						<div class='form-group input-group'>
							<span class='input-group-addon'><i class='fa fa-lock fa-fw' aria-hidden='true'></i></span>
							<input type='password' name='senha' id='inputDefault' class='form-control' required placeholder='Escolha uma senha...'>
						</div>
						
						<div class='form-group input-group'>
							<span class='input-group-addon'><i class='fa fa-lock fa-fw' aria-hidden='true'></i></span>
							<input type='password' name='senhaconf' id='inputDefault' class='form-control' required placeholder='Confirme a senha...'>
						</div>
						";
				
					if (isset($erro) && ($erro == 'Selecione algum sexo')) {
						echo "
						<label style='color:red;'>$erro</label>
						<div class='form-group has-error'>
					      <label class='col-lg-2 control-label'>Sexo:</label>
					      <div class='col-lg-10'>
					        <div class='radio-inline'>
					          <label>
					            <input type='radio' name='sexo' value='M' id='inputError' required>
					            Masculino
					          </label>
					        </div>
					        <div class='radio-inline'>
					          <label>
					            <input type='radio' name='sexo' value='F' required>
					            Feminino
					          </label>
					        </div>
					        <div class='radio-inline'>
					          <label>
					            <input type='radio' name='sexo' value='O' required>
					            Outro
					          </label>
					        </div>
					      </div>
					    </div>
						";
					} else
						echo "
						<div class='form-group'>
					      <label class='col-lg-2 control-label'>Sexo:</label>
					      <div class='col-lg-10'>
					        <div class='radio-inline'>
					          <label>
					            <input type='radio' name='sexo' value='M' required>
					            Masculino
					          </label>
					        </div>
					        <div class='radio-inline'>
					          <label>
					            <input type='radio' name='sexo' value='F' required>
					            Feminino
					          </label>
					        </div>
					        <div class='radio-inline'>
					          <label>
					            <input type='radio' name='sexo' value='O' required>
					            Outro
					          </label>
					        </div>
					      </div>
					    </div>
						";

					if (isset($erro) && ($erro == "Você precisa ter no mínimo 14 anos para se cadastrar!" || $erro == "Insira uma data válida")) {
						echo "
						<label style='color:red'>$erro</label>
						<div class='form-group input-group has-error'>
							<span class='input-group-addon'><i class='fa fa-calendar' aria-hidden='true'></i></span>
							<input type='date' name='datanasc' id='inputError' class='form-control' required>
						</div>
						";
					} else
						echo "
						<div class='form-group'>
							<label class='control-label' for='inputDefault'>Data de nascimento:</label>
								<div class='form-group input-group'>
				  					<span class='input-group-addon'><i class='fa fa-calendar' aria-hidden='true'></i></span>
 									<input type='date' name='datanasc' id='inputDefault' class='form-control' required>
 								</div>
						</div>
						";
				?>

				<input type="submit" name="bcadastrar" value="Cadastrar" class="btn btn-primary btn-lg" id="btnCadastrar">

				<br><br>

				<center>
					<a onclick="mudarForm('login')" style="color: white">Já possui uma conta?</a>
					|
					<a onclick="mudarForm('mudarSenha')" style="color: white">Esqueceu a senha?</a>
				</center>
			</form>


			<!-- 											FORM MUDANÇA DE SENHA 											-->

			<form action="php/mudarSenha/enviarEmail.php" method="post" name="frmMudarSenha" id="frmMudarSenha">
				<?php
					if (isset($erro_novaSenha) && ($erro_novaSenha == "Usuário não existe" || $erro == "Preencha o campo!" || $erro == "Não foi possível alterar a senha!")) {
						echo "
						<label style='color:red'>$erro</label>
						<div class='form-group input-group has-error'>
							<span class='input-group-addon'><i class='fa fa-envelope-o fa-fw' aria-hidden='true'></i></span>
							<input type='email' name='email' id='inputError' class='form-control' required placeholder='E-mail...' maxlength='50'>
						</div>
						";
					} else
						echo "
						<div class='form-group input-group'>
							<span class='input-group-addon'><i class='fa fa-envelope-o fa-fw' aria-hidden='true'></i></span>
							<input type='email' name='email' id='inputDefault' class='form-control' required placeholder='E-mail...' maxlength='50'>
						</div>
						";
				?>

				<br>

				<input type="submit" name="bmudar" value="Enviar e-mail" class="btn btn-primary btn-lg" id="btnMudarSenha">

				<br><br>

				<center>
					<a onclick="mudarForm('cadastro')" style="color: white">Ainda não possui uma conta?</a>
					|
					<a onclick="mudarForm('login')" style="color: white">Já possui uma conta?</a>
				</center>
			</form>
		</div>
	</div>
</body>
</html>