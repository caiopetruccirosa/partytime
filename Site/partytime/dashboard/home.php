<?php
	session_start();

	include_once "../php/classes/conexao.class.php";

	$conexao = new Conexao();

	$eventosPopulares = $conexao->selecionarEventosPopulares();
	$orgPopulares = $conexao->selecionarOrgsPopulares();

	$html = "";

	$html .= "<h1>Destaques</h1>";
	$html .= "<hr>";

	$html .= "<div class='row'>";
		$html .= "<div class='col-md-6'>";
			$html .= "<div class='secoesHome'>";
				$html .= "<h3>Eventos mais populares:</h3>";
				$html .= "<ul>";
				for ($i=0; $i < count($eventosPopulares); $i++) {
					if ($eventosPopulares[$i]['qntsConv'] > 0) {
						$evento = $conexao->selecionarEvento($eventosPopulares[$i]['idEvento'])[0];
						$html .= "<li onclick='carregarEvento(".$eventosPopulares[$i]['idEvento'].")'><a class='linksFeed'>".$evento['nomeEvento']."</a></li>";
					}
				}
				$html .= "</ul>";
			$html .= "</div>";
		$html .= "</div>";

		$html .= "<div class='col-md-6'>";
			$html .= "<div class='secoesHome'>";
				$html .= "<h3>Organizadores mais populares:</h3>";
				$html .= "<ul>";
				for ($i=0; $i < count($orgPopulares); $i++) {
					if ($orgPopulares[$i]['qntsAmigos'] > 0) {
						$usuario = $conexao->selectUsuarioPorId($orgPopulares[$i]['idOrganizador'])[0];
						$html .= "<li onclick='carregarOutroPerfil(".$orgPopulares[$i]['idOrganizador'].")'><a class='linksFeed'>".$usuario['username']."</a></li>";
					}
				}
				$html .= "</ul>";
			$html .= "</div>";
		$html .= "</div>";
	$html .= "</div>";

	$html .= "<br>";

	echo $html;
?>