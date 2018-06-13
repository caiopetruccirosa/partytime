<?php
	session_start();

	include_once "../../php/classes/conexao.class.php";
	
	$conexao = new Conexao();
	$conexao->recusarPedido($_SESSION['usuario']['id'], $_POST['idOutro']);
?>