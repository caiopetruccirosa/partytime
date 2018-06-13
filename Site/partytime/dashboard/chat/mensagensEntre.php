<?php
  session_start();

  include_once "../../php/classes/conexao.class.php";

  $conexao = new Conexao();
  $outroUsuario = $conexao->selectUsuarioPorId($_POST['idOutroUsuario'])[0];
  $conexao->visualizarMensagens($_SESSION['usuario']['id'], $_POST['idOutroUsuario']);
  $dados = $conexao->selecionarMensagensEntre($_SESSION['usuario']['id'], $_POST['idOutroUsuario']);

  $html = "";

  if (count($dados) > 0) {
    $ultimoUsuario = 0;

    $html .= "<ul class='chat'>";
    for ($i=0; $i < count($dados); $i++) {
      $data = new DateTime($dados[$i]['dataMensagem']);
      $horario = $data->format('G:ia');

      if ($dados[$i]['visualizado'] == 1)
        $visualizado = "<i class='fa fa-check visualizado foiVistoChat' aria-hidden='true'></i> ";
      else
        $visualizado = "<i class='fa fa-check foiVistoChat' aria-hidden='true'></i> ";

      if ($ultimoUsuario > 0 && $ultimoUsuario == $dados[$i]['idEnviou']) {
        if ($dados[$i]['idEnviou'] == $_SESSION['usuario']['id']) {
          $html .= "
            <li class='right clearfix'>
              <div class='chat-body clearfix'>
                <div class='header'>
                  <small class='text-muted'><span class='glyphicon glyphicon-time'></span>".$horario."</small>
                </div>
                <p>
                ".
                $dados[$i]['conteudoMsg']." ".$visualizado
                ."
                </p>
              </div>
            </li>
          ";
        } else {
          $html .= "
            <li class='left clearfix'>
              <div class='chat-body clearfix'>
                <div class='header'>
                  <small class='text-muted'><span class='glyphicon glyphicon-time'></span>".$horario."</small>
                </div>
                <p>
                ".
                $visualizado." ".$dados[$i]['conteudoMsg']
                ."
                </p>
              </div>
            </li>
          ";
        }
      } else {
        if ($dados[$i]['idEnviou'] == $_SESSION['usuario']['id']) {
          $html .= "
            <li class='right clearfix'>
              <span class='chat-img pull-right'>
                <img src='fotos/usuario/".$_SESSION['usuario']['fotoPerfil']."' alt='".$_SESSION['usuario']['username']."' class='img-circle fotoChat'>
              </span>
              <div class='chat-body clearfix'>
                <div class='header'>
                  <small class=' text-muted'><span class='glyphicon glyphicon-time'></span>".$horario."</small>
                  <strong class='pull-right primary-font'>".$_SESSION['usuario']['username']."</strong>
                </div>
                <p>
                ".
                $dados[$i]['conteudoMsg']." ".$visualizado
                ."
                </p>
              </div>
            </li>
          ";
          $ultimoUsuario = $dados[$i]['idEnviou'];
        } else {
          $html .= "
          <li class='left clearfix'>
            <span class='chat-img pull-left'>
              <img src='fotos/usuario/".$outroUsuario['fotoPerfil']."' alt='".$outroUsuario['username']."' class='img-circle fotoChat'>
            </span>
            <div class='chat-body clearfix'>
              <div class='header'>
                <strong class='primary-font'>".$outroUsuario['username']."</strong><small class='pull-right text-muted'>
                <span class='glyphicon glyphicon-time'></span>".$horario."</small>
              </div>
              <p>
              ".
              $visualizado." ".$dados[$i]['conteudoMsg']
              ."
              </p>
            </div>
          </li>
          ";
          $ultimoUsuario = $dados[$i]['idEnviou'];
        }
      }
    }
    $html .= "</ul>";
  } else {
    $html = "<center><h4>Comece uma conversa com ".$outroUsuario['username']."!</h4></center>";
  }

  echo $html;
?>