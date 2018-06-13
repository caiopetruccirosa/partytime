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
        if (!$conexao->ehOrganizador($dados[$i]['idUsuario'], $_POST['idEvento'])) {
          $html.="
          <h4>
          <label class='btn btn-secondary'>
          <input type='checkbox' name = '".$i."' value = '".$dados[$i]['idUsuario']."'>".$dados[$i]['username']."
          </label>
          </h4>
          ";
        }
      }
      $html .= "<input type='hidden' name='idEvento' value='".$_POST['idEvento']."'></input>";
    }
?>

<div class="modal-content">
  <form action="eventos/adicionarOrg.php" method="POST" id="formEvento" name="formEvento">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="fecharModalOrg()">&times;</button>
    <h4 class="modal-title">Adicione outros organizadores!</h4>
  </div>
  <div class="modal-body">
    <div class="form-group"><div class="btn-group-vertical" role="group" aria-label="Basic example"><?php echo $html; ?></div></div>
  </div>
  <div class="modal-footer">
    <button type="submit" class="btn btn-primary" id="btnenviar">Adicionar!</button>
  </div>
  </form>
</div>


</div>