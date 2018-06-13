<script type="text/javascript">
  $(document).ready(function(){
    carregarAmigosNoForm(<?php echo $_POST['idEvento'] ?>);
  }); 
</script>

<div class="modal-content">
  <form action="eventos/convidar.php" method="POST" id="formEvento" name="formEvento">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="fecharListaDeAmigos()">&times;</button>
    <h4 class="modal-title">Selecione seus amigos!</h4>
  </div>
  <div class="modal-body">
    <div class="form-group">
      <div class="btn-group-vertical" id="divAmigos"></div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="submit" class="btn btn-primary" id="btnenviar">Convidar!</button>
  </div>
  </form>
</div>
