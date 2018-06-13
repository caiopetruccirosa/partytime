<?php
session_start();

include_once "../classes/conexao.class.php";

if (isset($_SESSION['logado'])) {
	$conexao = new Conexao();
	$conexao->ficarOffline($_SESSION['usuario']['id']);
	
	unset($_SESSION);
}

header('Location: ../../');

session_destroy();
?>