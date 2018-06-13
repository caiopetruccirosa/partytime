<?php
	if (isset($_POST['bmudar'])) {
		$novaSenha = $_POST['novaSenhaConf'];
		$novaSenhaConf = $_POST['novaSenha'];

		$chave = $_SESSION['chave'];

		include_once '../classes/conexao.class.php';
		$conexao = new Conexao();
		$dadosUsuario = $conexao->selectUsuarioPorChave($chave);
		$email = $dadosUsuario[0]['email'];

		if (empty($novaSenha) || empty($novaSenhaConf)) {
			header("Location: ../mudarSenha/");
			exit();
		} else {
			include_once '../classes/usuario.class.php';
			$usuario = new Usuario($email, "");

			if ($usuario->mudarSenha($chave, $novaSenha, $novaSenhaConf)) {
				$_SESSION['alerta_sucesso'] = "mudouSenha";
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