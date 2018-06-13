<?php
    session_start();

    include_once "../../php/classes/conexao.class.php";

	$conexao = new Conexao();
    $dados = $conexao->selecionarAmigosOnline($_SESSION['usuario']['id']);

    $html = "<h4>Amigos online</h4>
            <hr>
            <div class='quais_amigos online'>";

    if (!count($dados) > 0) {
        $html.="Nenhum amigo está online.";
    } else {
        $html.="<ul>";
        for ($i=0; $i < count($dados); $i++)
            $html.="<li class='algumAmigo' onclick='carregarOutroPerfil(".$dados[$i]['idUsuario'].")'><a>".$dados[$i]['username']."</a></li>";
         $html.="</ul>";
    }

    $html .= "</div>

            <h4>Amigos offline</h4>
            <hr>";

    $dados = $conexao->selecionarAmigosOffline($_SESSION['usuario']['id']);

    $html .= "<div class='quais_amigos'>";
    if (!count($dados) > 0) {
        $html.="Você não tem amigos offline.";
    } else {
        $html.="<ul>";
        for ($i=0; $i < count($dados); $i++)
            $html.="<li class='algumAmigo' onclick='carregarOutroPerfil(".$dados[$i]['idUsuario'].")'><a>".$dados[$i]['username']."</a></li>";
         $html.="</ul>";
    }
    $html .= "</div>";

    echo $html;
?>