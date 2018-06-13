<?php
  session_start();

  include_once "../../php/classes/conexao.class.php";

  $conexao = new Conexao();
  $dados = $conexao->carregarConversas($_SESSION['usuario']['id']);

  $html = "";

  if (!(count($dados) > 0)) {
    $html .= "<center><h3>Você não possui conversas.</h3></center>";
  } else {
    $html .= "<ul class='conversas'>";
    for ($i=0; $i < count($dados); $i++) {
      if (strlen($dados[$i]['ultimaMensagem']) > 50)
        $mensagem = substr($dados[$i]['ultimaMensagem'], 0, 50) . "...";
      else
        $mensagem = $dados[$i]['ultimaMensagem'];
      
      $html .= "<li class='item-conversa' onclick='carregarConversaCom(".$dados[$i]['idOutroUsuario'].")'>";
        $html .= "<div class='row'>";

          $html .= "<div class='col-md-1 fotoConversas'>";
           $html .= "<span class='chat-img pull-left'>
                      <img src='fotos/usuario/".$dados[$i]['fotoOutroUsuario']."' alt='".$dados[$i]['usernameOutroUsuario']."' class='img-circle fotoChat'>
                    </span>";
          $html .= "</div>";

          $html .= "<div class='col-md-8 mensagem'>";
            $html .= "<div class='row'>
                        <label><strong>".$dados[$i]['usernameOutroUsuario']."</strong></label>
                      </div>";

            $html .= "<div class='row'>";
              if ($dados[$i]['quemMandou'] == $_SESSION['usuario']['id']) {
                if ($dados[$i]['visualizado'] == 1) {
                  $html .= "<i class='fa fa-check visualizado' aria-hidden='true'></i> ";
                } else {
                  $html .= "<i class='fa fa-check' aria-hidden='true'></i> ";
                }
              }
              $html .= $mensagem;
            $html .= "</div>";

          $html .= "</div>";

          $data = new DateTime($dados[$i]['horarioMensagem']);
          $horario = $data->format('G:ia');

          $html .= "<div class='col-md-2'>";
            
            $html .= "<div class='row horario'><center>$horario</center></div>";

            if ($dados[$i]['qntsMsgs'] > 0) {
              $html .= "<div class='row qntsMsgs'>";
              $html .= "<center><span class='badge'>".$dados[$i]['qntsMsgs']."</span></center>";
              $html .= "</div>";
            }

          $html .= "</div>";
        $html .= "</div>";
      $html .= "</li>";
    }
    $html .= "</ul>";
  }

  echo $html;
?>