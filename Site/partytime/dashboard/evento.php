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
	$dados = $conexao->selecionarEvento($_POST['idEvento'])[0];

    $convidadosPodemConvidar = $dados['convidadosPodemConvidar'];
    $podeConvidar = $conexao->ehConvidadoOuOrg($_SESSION['usuario']['id'], $_POST['idEvento']);
    $ehOrg = $conexao->ehOrganizador($_SESSION['usuario']['id'], $_POST['idEvento']);

    $dataCriacao = time_elapsed_string($dados['dataCriacao']);
    $objDataInicio = new DateTime($dados['dataInicio']);
    $objDataFim = new DateTime($dados['dataFim']);

    $dataInicio = $objDataInicio->format('d/m/Y') . " às " . $objDataInicio->format('G:ia');
    $dataFim = $objDataFim->format('d/m/Y') . " às " . $objDataFim->format('G:ia');

    $html = "";

    if ($podeConvidar == true && $convidadosPodemConvidar == 1) {
        $html .= "<div>
                    <input type='button' class = 'btn btn-primary' value ='+ Convidar amigos' onclick ='mostrarListaDeAmigos(".$_POST['idEvento'].")' id = 'convidarAmigos'>
                  </div>";
    }

    if ($ehOrg == true) {
        $html .= "<div>
                    <input type='button' class = 'btn btn-primary' value ='+ Add organizadores' onclick ='mostrarOrgs(".$_POST['idEvento'].")' id = 'addOrg'>
                  </div>";
    }

    $html .= "<div class='cabecalhoEvento'>";
        if ($dados['capaEvento'] != null) {
            $html .= "<div class='capaEvento'>";
                $html .= "<img src='fotos/eventos/capas/".$dados['capaEvento']."'></img>";
            $html .= "</div>";
        }
        
        $html .= "<center>
                    <h1 class='tituloEvento'><strong>".$dados['nomeEvento']."</strong></h1>
                    <br>Criado ".$dataCriacao."
                </center>";
    $html .= "</div>";

    $html .= "<div class='publicacao'>";
        $html .= "<div class='conteudoPublicacao'>";
            $html .= "<h3 style='border-bottom: 2px solid #596a7b'>Descrição do evento:</h3>".$dados['descricao'];
        $html .= "</div>";

        $html .= "<h3 style='border-bottom: 2px solid #596a7b'>Organizadores:</h3>";
        $organizadores = $conexao->selecionarOrganizadores($dados['idEvento']);
        if (count($organizadores) > 0) {
            $html .= "<ul>";
            for ($i = 0; $i < count($organizadores); $i++) {
                $organizador = $conexao->selectUsuarioPorId($organizadores[$i]['idOrganizador'])[0];
                $html .= "<li onclick='carregarOutroPerfil(".$organizador['idUsuario'].")'><a class='linksFeed'>".$organizador['username']."</a></li>";
            }
            $html .= "</ul>";
        } else {
            $html .= "Este eventos não possui organizadores!";
        }




        $html .= "<h3 style='border-bottom: 2px solid #596a7b'>Convidados:</h3>";
        $convidados = $conexao->selecionarConvidados($dados['idEvento']);
        if (count($convidados) > 0) {
            for ($i = 0; $i < count($convidados); $i++) {
                $convidado = $conexao->selectUsuarioPorId($convidados[$i]['idConvidado'])[0];
                $html .= "<li onclick='carregarOutroPerfil(".$convidado['idUsuario'].")'><a class='linksFeed'>".$convidado['username']."</a></li>";
            }
        } else {
            $html .= "Este eventos não possui convidados!";
        }

        $html .= "<div class='row'>";
            $html .= "<div class='datasEvento'>";
                $html .= "<h3 style='border-bottom: 2px solid #596a7b'>Informações:</h3>";
                $html .= "Data de início do evento: <label>" . $dataInicio . "</label>.";
                $html .= "<br>";
                $html .= "Data de término do evento: <label>" . $dataFim . "</label>.";
                $html .= "<br>";
                $html .= "Local do evento: <label>" . $dados['localizacao'] . "</label>.";
            $html .= "</div>";
        $html .= "</div>";

    $html .= "</div>";

	echo $html;
?>