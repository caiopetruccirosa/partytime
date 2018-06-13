<?php
  session_start();

  include "../../php/classes/conexao.class.php";
  $conexao = new Conexao();

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

  $dados = $conexao->selecionarNotificacoes($_SESSION['usuario']['id']);
  $num_notif = $conexao->quantasNotifNaoLidas($_SESSION['usuario']['id']);
  $html = "";

  if (count($dados) > 0) {
    for ($i=0; $i < count($dados); $i++) {
      $dadosUsuario = $conexao->selectUsuarioPorId($dados[$i]['idUsuarioMandou'])[0]; 

      $horario = time_elapsed_string($dados[$i]['dataNotif']);

      $visualizado = "";

      if ($dados[$i]['foiLida'] == 0)
        $visualizado .= "class='naoLida'";

      $html .= "
      <li ".$visualizado.">
        <div class='row'>
          <div class='col-sm-4 divFotoNotif'>
            <span class='chat-img pull-left'>
              <img src='fotos/usuario/".$dadosUsuario['fotoPerfil']."' alt='".$dadosUsuario['username']."' class='img-circle fotoNotif'>
            </span>
          </div>
      ";

      $relacao = null;
      if ($dados[$i]['conteudoNotif'] == $dadosUsuario['username']." enviou uma solicitação de amizade para você.")
        $relacao = $conexao->selecionarRelacao($_SESSION['usuario']['id'], $dadosUsuario['idUsuario'])[0];

      if ($dados[$i]['conteudoNotif'] == $dadosUsuario['username']." enviou uma solicitação de amizade para você." && $relacao['situacao'] == 3)
        $html .= "
          <div class='col-sm-7 conteudoNotif pedido'>
            <strong>".$dadosUsuario['username']."</strong>
            <br>
            <small>".$dados[$i]['conteudoNotif']."</small>
            <br>
            <button id='brecusar' onclick='recusarAmizade(".$dadosUsuario['idUsuario'].")'>Recusar</button>
            <button id='baceitar' onclick='aceitarAmizade(".$dadosUsuario['idUsuario'].")'>Aceitar</button>
          </div>
        </div>
        <div class='row'>
          <center><small><span class='glyphicon glyphicon-time'></span> ".$horario."</small></center>
        </div>
      ";
      else
       $html .= "
       <div class='col-sm-5 conteudoNotif'>
            <strong>".$dadosUsuario['username']."</strong>
            <br>
            <small>".$dados[$i]['conteudoNotif']."</small>
          </div>

          <div class='col-sm-3 horarioNotif'>
            <small><span class='glyphicon glyphicon-time'></span> ".$horario."</small>
          </div>
        </div>
      </li>
      ";
    }
  } else {
    $html .= '<li>Você não possui notificações.</li>';
  }
   
  echo $html;
?>