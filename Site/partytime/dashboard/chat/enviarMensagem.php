<?php
  session_start();

  include_once "../../php/classes/conexao.class.php";

  $conexao = new Conexao();

  if (!empty($_POST['conteudoMsg'])) {
  	$conexao->enviarMensagem($_SESSION['usuario']['id'], $_POST['idOutroUsuario'], $_POST['conteudoMsg']);
  }
?>
