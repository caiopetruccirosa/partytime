<?php
	session_start();

	include_once "../../php/classes/conexao.class.php";

	$conexao = new Conexao();
	$dados = $conexao->carregarConversas($_SESSION['usuario']['id']);

	$qntsMsg = 0;
	for ($i=0; $i < count($dados); $i++) 
		$qntsMsg += $dados[$i]['qntsMsgs'];

	$html = "";

	if ($qntsMsg > 0)
		$html .= $qntsMsg;

	echo $html;
?>