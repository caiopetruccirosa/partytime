<!DOCTYPE html>
<html>

<head>
	<title>Carregando...</title>

	<script type="text/javascript" src="../../js/script.js"></script>

	<link rel="icon" href="../../imagens/loading.png">
</head>

<body>

	<?php

	session_start();

	if (isset($_SESSION['logado'])) {
		header("Location: ../../dashboard/");
		exit();
	} else if (isset($_POST['blogar'])) {
		$email = $_POST['email'];
		$senha = $_POST['senha'];

		if (empty($email) || empty($senha)) {
			$erro = "Complete todos os campos!";
			$_SESSION['login_erro'] = $erro;
			header("Location: ../../");
			exit();
		} else {
			include_once '../classes/usuario.class.php';

			$usuario = new Usuario($email, $senha);

			if ($usuario->logar()) {
				$_SESSION['logado'] = true;
				//preencher os dados do usuario
				$_SESSION['usuario'] = $usuario->getInfo();
				$usuario->ficarOnline();
                header("Location: ../../dashboard/");
                exit();
			} else {
				$erro = $usuario->getErro();
				$_SESSION['login_erro'] = $erro;
				header("Location: ../../");
				exit();
			}
		} 
	} else {
		header("Location: ../../");
		exit();
	}
	?>

</body>

</html>