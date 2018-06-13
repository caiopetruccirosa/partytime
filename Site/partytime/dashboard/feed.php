<?php
	function time_elapsed_string($datetime, $full = false) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);

		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		$string = array(
		    'y' => 'ano',
		    'm' => 'mês',
		    'w' => 'semana',
		    'd' => 'dia',
		    'h' => 'hora',
		    'i' => 'minuto',
		    's' => 'segundo',
		);

		foreach ($string as $k => &$v) {
		    if ($diff->$k) {
		        $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
		    } else {
		        unset($string[$k]);
		    }
		}

		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' atrás.' : 'agora pouco.';
	}


	session_start();

	include_once "../php/classes/conexao.class.php";
	
	$conexao = new Conexao();
	$dados = $conexao->selecionarEventosAmigos($_SESSION['usuario']['id']);

	$html = "<h1>Feed</h1>";
	$html .= "<hr>";

	if (count($dados) > 0) {
		for ($i=0; $i < count($dados); $i++) { 
			$dadosUsuario = $conexao->selectUsuarioPorId($dados[$i]['idCriador'])[0];

		 	$dataCriacao = time_elapsed_string($dados[$i]['dataCriacao']);
			$objDataInicio = new DateTime($dados[$i]['dataInicio']);
			$objDataFim = new DateTime($dados[$i]['dataFim']);

			$dataInicio = $objDataInicio->format('d/m/Y') . " às " . $objDataInicio->format('G:ia');
			$dataFim = $objDataFim->format('d/m/Y') . " às " . $objDataFim->format('G:ia');;

		 	$html .= "<div class='publicacao'>";
		 		$html .= "<div class='row cabecalhoPublicacao'>";
		 			$html .= "<div class='nomeEvento'>";
		 				$html .= "<a class='linksFeed'><strong onclick='carregarOutroPerfil(".$dadosUsuario['idUsuario'].")'>".$dadosUsuario['username']."</strong><a>";
		 			$html .= "</div>";

		 			$html .= "<a class='linksFeed'><strong onclick='carregarEvento(".$dados[$i]['idEvento'].")'>".$dados[$i]['nomeEvento']."</strong></a> <small class='horarioEvento'><span class='glyphicon glyphicon-time'></span> ".$dataCriacao."</small>";
		 		$html .= "</div>";


		 		$html .= "<div class='row'>";
		 			$html .= "<div class='datasEvento'>";
			 			$html .= "• Data de início do evento: <label>" . $dataInicio . "</label>.";
			 			$html .= "<br>";
			 			$html .= "• Data de término do evento: <label>" . $dataFim . "</label>.";
			 			$html .= "<br>";
			 			$html .= "• Local do evento: <label>" . $dados[$i]['localizacao'] . "</label>.";
		 			$html .= "</div>";

			 		$html .= "<div class='conteudoPublicacao'>";
		 				$html .= $dados[$i]['descricao'];
		 			$html .= "</div>";
		 		$html .= "</div>";
		 	$html .= "</div>";
		}
	} else {
		$html .= "<br><center><h2>Não existem eventos relacionados a você.</h2></center><br><br>";
	}

	echo $html;
?>