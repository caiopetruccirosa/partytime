<?php

    session_start();

    include_once "../../php/classes/conexao.class.php";

    $conexao = new Conexao();
    $dados = $conexao->selecionarAmigos($_SESSION['usuario']['id']);

    $html = "";

    if (!count($dados) > 0) {
        $html.="<center><h3>Você não tem amigos :c</h3></center>";
    } else {
      for ($i=0; $i < count($dados); $i++) {
        if (!$conexao->ehConvidadoOuOrg($dados[$i]['idUsuario'], $_POST['idEvento'])) {
          $html.="
          <h4>
          <label class='btn btn-secondary'>
            <input type='checkbox' class name = '".$i."' value = '".$dados[$i]['idUsuario']."'>".$dados[$i]['username']."
          </label>
          </h4>";
        }
      }
      $html .= "<input type='hidden' name='idEvento' value='".$_POST['idEvento']."'></input>";
    }

    $html .= "</div>";

    echo $html;
?>
