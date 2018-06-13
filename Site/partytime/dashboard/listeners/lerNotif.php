<?php
  session_start();

  include "../../php/classes/conexao.class.php";
  $conexao = new Conexao();
  $conexao->lerNotificacoes($_SESSION['usuario']['id']);
?>