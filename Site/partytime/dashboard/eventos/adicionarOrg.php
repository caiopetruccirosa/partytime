<?php
	session_start();

	include_once("../../php/classes/conexao.class.php");
	$conexao = new Conexao();
	$dadosAmigos = $conexao->selecionarAmigos($_SESSION['usuario']['id']);
	$qntsAmigos = count($dadosAmigos);
	$arrayConv = [];
	for ($i = 0; $i < $qntsAmigos; $i++)
		if (isset($_POST[$i]))
			array_push($arrayConv, $dadosAmigos[$i]['idUsuario']);

	$conexao->adicionarOrg($arrayConv, $_POST['idEvento'], $_SESSION['usuario']['id']);
	header("Location : ../");
	exit();
?>