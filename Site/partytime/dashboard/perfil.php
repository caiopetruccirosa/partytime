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
	
	if (isset($_POST['idOutro']))
		$id = $_POST['idOutro'];
	else
		$id = $_SESSION['usuario']['id'];

	$dadosUsuario = $conexao->selectUsuarioPorId($id)[0];

	$dados = $conexao->selecionarEventos($dadosUsuario['idUsuario']);

	$html = "<center><img src='fotos/usuario/".$dadosUsuario['fotoPerfil']."' class='fotoPerfil'>";
	$html .= "<h1 id='nomePerfil'>".$dadosUsuario['username']."</h1></center>";
	$html .= "<hr>";

	$html .= "<div id='container_perfil'>";
	if (count($dados)) {
		for ($i=0; $i < count($dados); $i++) { 
			$dataCriacao = time_elapsed_string($dados[$i]['dataCriacao']);
			$objDataInicio = new DateTime($dados[$i]['dataInicio']);
			$objDataFim = new DateTime($dados[$i]['dataFim']);

			$dataInicio = $objDataInicio->format('d/m/Y') . " às " . $objDataInicio->format('G:ia');
			$dataFim = $objDataFim->format('d/m/Y') . " às " . $objDataFim->format('G:ia');;

		 	$html .= "<div class='publicacao'>";
		 		$html .= "<div class='row'>";
		 			$html .= "<div class='nomeEvento' onclick='carregarEvento(".$dados[$i]['idEvento'].")'>";
		 				$html .= "<a class='linksFeed'><strong>".$dados[$i]['nomeEvento']."</a></strong>";
		 			$html .= "</div>";

		 			$html .= "<small class='horarioEvento'><span class='glyphicon glyphicon-time'></span> ".$dataCriacao."</small>";
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
		if ($dadosUsuario['idUsuario'] == $_SESSION['usuario']['id'])
			$html .= "<br><center><h3>Você não criou eventos!</h3></center><br><br>";
		else
			$html .= "<br><center><h3>Este perfil não criou eventos!</h3></center><br><br>";
	}

	$html .= "</div>";

	echo $html;
?>