<script type="text/javascript">
  
   /*$(document).ready(function(){
    $("#formEvento").submit(function (e) {
      var nomeEvento    = $('#nomeEvento').val();
      var dataInicio    = $('#dataInicio').val();
      var dataFim       = $('#dataFim').val();
      var localizacao   = $('#localizacao').val();
      var descricao     = $('#descricao').val();
      var maxConvidados = $('#maxConvidados').val();

      if (ehVazia(nomeEvento) || ehVazia(dataInicio) || ehVazia(dataFim) || !ehVazia(localizacao) || !ehVazia(descricao) || !ehVazia(maxConvidados)){
        alert("Preencha todos os campos!");
        e.preventDefault();
      }
      
      return true;  
    });
  });*/

</script>
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="fecharEvento()">&times;</button>
    <h4 class="modal-title">Criar Evento</h4>
  </div>

  <form action="eventos/criarEvento.php" method="POST" id="formEvento" name="formEvento" enctype="multipart/form-data">
    <div class="modal-body">
      <div class="form-group">
        <label class="control-label" for="inputDefault">Nome do evento:</label>
        <input type="text" class="form-control" id="nomeEvento" name="nomeEvento" maxlength="50">
      </div>

      <div class="form-group">
        <label class="control-label" for="inputDefault">Local:</label>
        <input type="text" class="form-control input-sm" id="localizacao" name="localizacao" maxlength="50">
      </div>

      <div class="form-group">
        <div>
          <label class="control-label" for="inputDefault">Capa do evento:</label>
        </div>
        
        <input type="file" class="btn btn-warning btn-xs" value="Carregar imagem" id="capaEvento" name="capaEvento">
      </div>

      <div id="divDatas">
        <h4>Data/horário:</h4>
        <div class="form-horizontal">
          <div class="form-group">
            <label class="control-label col-sm-4" for="inputDefault">Data/hora de início:</label>
            <div class="col-sm-7">
              <input type="datetime-local" class="form-control input-sm" id="dataInicio" name="dataInicio">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-4" for="inputDefault">Data/hora de término:</label>
            <div class="col-sm-7">
              <input type="datetime-local" class="form-control input-sm" id="dataFim" name="dataFim">
            </div>
          </div>
        </div>
      </div>

      <br>

      <div class="form-inline">
        <div class="form-group">
          <label class="control-label" for="inputDefault" style="margin-right: 10px;">Máximo convidados:</label>
          <input type="number" class="form-control input-sm" id="maxConvidados" name="maxConvidados" style="width: 426px;">
        </div>
      </div>

      <br>

      <div class="form-group">
        <label for="textArea" class="control-label">Descrição do evento:</label>
        <textarea class="form-control" rows="3" id="descricao" name="descricao" maxlength="500"></textarea>
      </div>

      <div class="checkbox">
        <label>
          <input type="hidden" name="convidadosPodemConvidar" value="0">
          <input type="checkbox" id="convidadosPodemConvidar" name="convidadosPodemConvidar" value="1"> Convidados podem convidar amigos
        </label>
      </div>
    </div>
      
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal" onclick="fecharEvento()">Cancelar</button>
      <button type="submit" class="btn btn-primary" id="btnenviar">Criar Evento!</button>
    </div>
  </form>
</div>