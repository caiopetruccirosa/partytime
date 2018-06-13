<?php
  session_start();

  include "../../php/classes/conexao.class.php";
  $conexao = new Conexao();
  $num_notif = $conexao->quantasNotifNaoLidas($_SESSION['usuario']['id']);

  $qnts_notif = "";

  if ($num_notif > 0)
  	$qnts_notif .= $num_notif;

  echo $qnts_notif;
?>