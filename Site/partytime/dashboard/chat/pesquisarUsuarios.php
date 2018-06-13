<?php
  session_start();

  include_once "../../php/classes/conexao.class.php";

  $conexao = new Conexao();
  $dados = $conexao->procurarUsuario($_POST['nomePesquisado']);
?>

<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="fecharMsg()">&times;</button>
    <button type="button" class="backPesquisa close" data-dismiss="modal" aria-hidden="true" onclick="carregarConversas()"><</button>
    <h3 class="modal-title" style="margin-left: 30px;">Pesquisa | "<?php echo $_POST['nomePesquisado'] ?>"</h3>
  </div>

  <div class="modal-body" id="modalMensagens">
    <?php
      $html = "";

      if (count($dados) > 0) {
        $html .= "<ul class='conversas'>";

        for ($i=0; $i < count($dados); $i++) {
          $html .= "<li class='item-conversa' onclick='carregarConversaCom(".$dados[$i]['idUsuario'].")'>";
            $html .= "<div class='row'>";

              $html .= "<div class='col-md-1 fotoConversas'>";
               $html .= "<span class='chat-img pull-left'>
                          <img src='fotos/usuario/".$dados[$i]['fotoPerfil']."' alt='".$dados[$i]['username']."' class='img-circle fotoChat'>
                        </span>";
              $html .= "</div>";

              $html .= "<div class='col-md-8 mensagem'>";
                $html .= "<div class='row'>
                            <label><strong>".$dados[$i]['username']."</strong></label>
                          </div>";

                $html .= "<div class='row'>";
                  $html .= "Converse com ".$dados[$i]['username'];
                $html .= "</div>";

              $html .= "</div>";
            $html .= "</div>";
          $html .= "</li>";
        }
        
        $html .= "</ul>";
      } else {
        $html .= "<br><center><h4>Nenhum usuário foi encontrado.</h4></center><br>";
      }

      echo $html;
    ?>
  </div>
</div>