<?php
	if (isset($_POST['bmudar'])) {
		$email = $_POST['email'];

		if (empty($email)) {
			$erro = "Preencha o campo!";
			$_SESSION['novaSenha_erro'] = $erro;
			header("Location: ../../");
			exit();
		} else {
			include_once '../classes/usuario.class.php';

			$usuario = new Usuario($email, "");

			if ($usuario->enviarEmail()) {
				$_SESSION['alerta_sucesso'] = "enviouEmail";
				header("Location: ../../");
				exit();
			} else {
				$erro = $usuario->getErro();
				$_SESSION['novaSenha_erro'] = $erro;
				header("Location: ../../");
				exit();
			}
		}
	} else {
		header("Location: ../../");
		exit();
	}
?>