<?php
	session_start();

	include_once "../../php/classes/usuario.class.php";
	
	$usuario = new Usuario($_SESSION['usuario']['email'], "");
	$usuario->setInfo();

	unset($_SESSION['usuario']);
	$_SESSION['usuario'] = $usuario->getInfo();

	exit();
?>