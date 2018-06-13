<?php

session_start();

if (isset($_SESSION['logado'])) {
	header("Location: ../../dashboard/home/");
	exit();
} else if (isset($_POST['bcadastrar'])) {
	$username = $_POST['username'];
	$email = $_POST['email'];
	$senha = $_POST['senha'];
	$senhaConf = $_POST['senhaconf'];
	$dataNasc = $_POST['datanasc'];
	$sexo = $_POST['sexo'];

	if (empty($username) || empty($email) || empty($senha) || empty($senhaConf) || empty($dataNasc) || empty($sexo)) {
		$erro = "Complete todos os campos!";
		$_SESSION['cadastro_erro'] = $erro;
		header("Location: ../../");
		exit();
	}
	else {
		include_once '../classes/usuario.class.php';

		$usuario = new Usuario($email, $senha);
		$usuario->setUsuarioInfo($username, $dataNasc, $sexo, $senhaConf);

		if ($usuario->cadastrar()) {
			$_SESSION['alerta_sucesso'] = "cadastro";
			header("Location: ../../");
			exit();
		} else {
			$erro = $usuario->getErro();
			$_SESSION['cadastro_erro'] = $erro;
			header("Location: ../../");
			exit();
		}
	}
} else {
		header("Location: ../../");;
		exit();
	}
?>