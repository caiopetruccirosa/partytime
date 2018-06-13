<?php
	session_start();

	include_once "../classes/conexao.class.php";

	$conexao = new Conexao();
	$conexao->ficarOffline($_SESSION['usuario']['id']);
?>