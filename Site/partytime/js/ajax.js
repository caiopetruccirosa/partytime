var enviado = false;

function recusarAmizade(idOutro) {
  $.ajax({
    url:"listeners/recusarAmigo.php",
    async:true,
    method:"POST",
    data:{
      idOutro:idOutro
    }
  });
}

function aceitarAmizade(idOutro) {
  $.ajax({
    url:"listeners/aceitarAmigo.php",
    async:true,
    method:"POST",
    data:{
      idOutro:idOutro
    }
  });
}

function buscarUsuario() {
  $('#dadosCarregados').html("");

  var nomeBusca = $("#nomeBusca").val();

  $.ajax({
    url:"buscar.php",
    async:true,
    method:"POST",
    data: {
      nomeProcurado:nomeBusca
    },
    dataType:"html",
    success: function(result){
      $(document).find('#dadosCarregados').html(result);
    },
    beforeSend:function(){
        $("#dadosCarregados").html("<br><center><h3>Carregando...</h3><center><br><br>");
    },
    error: function(result){
      var html = "<center><h1>Erro: " + result.status + " " + result.statusText + "</h1></center>";
      $(document).find('#dadosCarregados').html(html);
    }
  });
}

function enviarMensagem(idOutro) {
  var conteudoMsg = $('#textoMsg').val();

  if (!ehVazia(conteudoMsg)) {
    $.ajax({
      url:"chat/enviarMensagem.php",
      async:true,
      method:"POST",
      data: {
        idOutroUsuario:idOutro,
        conteudoMsg:conteudoMsg
      },

      success:function(){
        $('#textoMsg').val("");
        enviado = true;
        document.getElementById("textoMsg").focus();
      },
      error:function(){
        $('#textoMsg').val("Erro ao enviar mensagem!");
      }
    });
  } else
    $('#textoMsg').val("");
}

function obterMensagens() {
  $('#conteudoMsg').html("");

  $.ajax({
    url:"chat/mensagens.php",
    async:true,
    dataType:"html",
    beforeSend: function() {
        var html = "<center><h3 style='margin: auto; display:block;'>Carregando...</h3></center>"
        $(document).find('#conteudoMsg').html(html);
    },
    success: function(result){
      $(document).find('#conteudoMsg').html(result);
      $(document).find("#modalMsg").fadeIn(400);
    }, 
    error: function(result){
      var html = "<div class='modal' id='modalMsg'>\n<div class='modal-dialog'>\n\<div class='modal-content'>\n<div class='modal-header'>\n<button type='button' class='close' data-dismiss='modal' aria-hidden='true' onclick='fecharMsg()'>&times;</button>\n<h4 class='modal-title'>Erro</h4>\n</div>\n<div class='modal-body'>\n<center><h1>Erro: " + result.status + " " + result.statusText + "</h1></center>\n</div>\n</div>\n</div>\n</div>";
        $(document).find('#mensagens').html(html);
    }
  });
}

function ehVazia(string) {
  if (string == null || string.trim().length === 0)
    return true;
    
  return false;
}

//function criarEvento() {
//  var nomeEvento = $('#nomeEvento').val();
//  var dataInicio = $('#dataInicio').val();
//  var dataFim = $('#dataFim').val();
//  var localizacao = $('#localizacao').val();
//  var descricao = $('#descricao').val();
//  var maxConvidados = $('#maxConvidados').val();
//
//
//  var convidadosPodemConvidar = 0;
//  if (document.getElementById('convidadosPodemConvidar').checked)
//    convidadosPodemConvidar = 1;
//  
//  if (!ehVazia(nomeEvento) && !ehVazia(dataInicio) && !ehVazia(dataFim) && !ehVazia(localizacao) && !ehVazia(descricao) && !ehVazia(maxConvidados)) {
//    $.ajax({
//      url:"eventos/criarEvento.php",
//      async:true,
//      method:"POST",
//      data: {
//        nomeEvento:nomeEvento,
//        dataInicio:dataInicio,
//        dataFim:dataFim,
//        localizacao:localizacao,
//        descricao:descricao,
//        convidadosPodemConvidar:convidadosPodemConvidar,
//        maxConvidados:maxConvidados
//      },
//      error:function(erro){
//        alert('Erro: '+erro.status+' '+erro.statusText);
//      },
//      success:function(){
//          alert('?');
//          fecharEvento();
//      }
//    });
//  }
//  else
//  {
//    alert('Preencha todos os campos!');
//  }
//}


function carregarConversaCom(idOutro) {
  $.ajax({
    url:"chat/conversas.php",
    async:true,
    method:"POST",
    data:{
      idOutroUsuario:idOutro
    },
    dataType:"html",
    success: function(result){ 
      $(document).find('#conteudoMsg').html(result);
      $('#conversaChat').scrollTop($('.chat').height());
      
      window.clearInterval(interval);
      interval = window.setInterval(function(){ carregarConversaEntre(idOutro); }, 500);
    }
  });

}

function carregarConversaEntre(idOutro) {
  $.ajax({
    url:"chat/mensagensEntre.php",
    async:true,
    method:"POST",
    data:{
      idOutroUsuario:idOutro
    },
    dataType:"html",
    success: function(result){
      $(document).find('#conversaChat').html(result);

      if (enviado == true) {
        $('#conversaChat').scrollTop($('.chat').height());
        enviado = false;
      }
    }
  });
}

function carregarConversas() {
  $.ajax({
    url:"chat/mensagens.php",
    async:true,
    dataType:"html",
    success: function(result){
      $(document).find('#conteudoMsg').html(result);
    }
  });

  window.clearInterval(interval);
  interval = window.setInterval(carregarConversasDoUsuario, 500);
}

function pesquisarChat() {
  window.clearInterval(interval);

  var nomePesquisado = $('#nomePesquisado').val();
  if (!ehVazia(nomePesquisado)) {
    $.ajax({
      url:"chat/pesquisarUsuarios.php",
      async:true,
      method:"POST",
      data:{
        nomePesquisado:nomePesquisado
      },
      dataType:"html",
      beforeSend:function(){
        $("container_conversas").html("Carregando...");
      },
      success: function(result){
        $(document).find('#conteudoMsg').html(result);
      }
    });
  }
}

function carregarConversasDoUsuario() {
  $.ajax({
    url:"chat/conversasDe.php",
    async:true,
    dataType:"html",
    success: function(result){
      $(document).find('#container_conversas').html(result);
    }
  });
}

function carregarCriarEvento() {
  $('#conteudoEvento').html("");

  $.ajax({
    url:"eventos/formEvento.php",
    async:true,
    dataType:"html",
    success: function(result){
      $(document).find('#conteudoEvento').html(result);
      $(document).find("#modalEvento").fadeIn(400);
    }, 
    error: function(result){
      var html = "<div class='modal' id='modalMsg'>\n<div class='modal-dialog'>\n\<div class='modal-content'>\n<div class='modal-header'>\n<button type='button' class='close' data-dismiss='modal' aria-hidden='true' onclick='fecharMsg()'>&times;</button>\n<h4 class='modal-title'>Erro</h4>\n</div>\n<div class='modal-body'>\n<center><h1>Erro: " + result.status + " " + result.statusText + "</h1></center>\n</div>\n</div>\n</div>\n</div>";
      $(document).find('#mensagens').html(html);
    }
  });
}

function carregarNotificacoes() {
  $.ajax({
    url:"listeners/notificacoes.php",
    dataType:"html",
    success: function(dados){
      $('#notif-itens').html(dados);
    }
  });
}

function carregarNumNotif() {
  $.ajax({
    url:"listeners/numNotif.php",
    dataType:"html",
    success: function(dados){
      $('#qnts_notif').html(dados);
    }
  });
}

function lerNotif() {
  $.ajax({
    url:"listeners/lerNotif.php"
  });
}

function pedirAmizade(idOutro) {
  $.ajax({
    url:"listeners/pedirAmigo.php",
    async:true,
    method:"POST",
    data:{
      idOutroUsuario:idOutro
    }
  });
}

function carregarAmigos() {
  $.ajax({
    url:"listeners/amigos.php",
    dataType:"html",
    success: function(dados){
      $('#div_amigos').html(dados);
    }
  });
}



function carregarMensagens() {
  $.ajax({
    url:"listeners/mensagens.php",
    dataType:"html",
    success: function(dados){
      $('#qnts_msg').html(dados);
    }
  });
}

function carregarConfiguracoes() {
  $.ajax({
    url:"configuracoes.php",
    dataType:"html",
    beforeSend:function() {
      $('#dadosCarregados').html("<br><center><h3>Carregando...</h3></center><br><br>");
    },
    success: function(dados){
      $('#dadosCarregados').html(dados);
    }
  });
}

function carregarPerfil() {
  $.ajax({
    url:"perfil.php",
    dataType:"html",
    beforeSend:function() {
      $('#dadosCarregados').html("<br><center><h3>Carregando...</h3></center><br><br>");
    },
    success: function(dados){
      $('#dadosCarregados').html(dados);
    }
  });
}

function carregarOutroPerfil(idOutro) {
  $.ajax({
    url:"perfil.php",
    method:"POST",
    data:{
      idOutro:idOutro
    },
    dataType:"html",
    beforeSend:function() {
      $('#dadosCarregados').html("<br><center><h3>Carregando...</h3></center><br><br>");
    },
    success: function(dados){
      $('#dadosCarregados').html(dados);
    }
  });
}

function carregarFeed() {
  $.ajax({
    url:"feed.php",
    dataType:"html",
    beforeSend:function() {
      $('#dadosCarregados').html("<br><center><h3>Carregando...</h3></center><br><br>");
    },
    success: function(dados){
      $('#dadosCarregados').html(dados);
    }
  });
}

function carregarEvento(idEvento) {
  $.ajax({
    url:"evento.php",
    method:"POST",
    data:{
      idEvento:idEvento
    },
    dataType:"html",
    beforeSend:function() {
      $('#dadosCarregados').html("<br><center><h3>Carregando...</h3></center><br><br>");
    },
    success: function(dados){
      $('#dadosCarregados').html(dados);
    }
  });
}

function carregarHome() {
  $.ajax({
    url:"home.php",
    dataType:"html",
    beforeSend:function() {
      $('#dadosCarregados').html("<br><center><h3>Carregando...</h3></center><br><br>");
    },
    success: function(dados){
      $('#dadosCarregados').html(dados);
    }
  });
}

function carregarListaDeAmigos(idEvento){
  $('#conteudoListaDeAmigos').html("");

  $.ajax({
    url:"eventos/formListaDeAmigos.php",
    async:true,
    method:"POST",
    data:{
      idEvento:idEvento
    },
    dataType:"html",
    success: function(result){
      $(document).find('#conteudoListaDeAmigos').html(result);
      $(document).find("#modalListaDeAmigos").fadeIn(400);
    }, 
    error: function(result){
      var html = "<div class='modal' id='modalMsg'>\n<div class='modal-dialog'>\n\<div class='modal-content'>\n<div class='modal-header'>\n<button type='button' class='close' data-dismiss='modal' aria-hidden='true' onclick='fecharMsg()'>&times;</button>\n<h4 class='modal-title'>Erro</h4>\n</div>\n<div class='modal-body'>\n<center><h1>Erro: " + result.status + " " + result.statusText + "</h1></center>\n</div>\n</div>\n</div>\n</div>";
      $(document).find('#modalListaDeAmigos').html(html);
    }
  });
}

function carregarAmigosNoForm(idEvento) {
  $.ajax({
    url:"eventos/listaDeAmigos.php",
    method:"POST",
    data:{
      idEvento:idEvento
    },
    dataType:"html",
    beforeSend:function() {
      $('#divAmigos').html("<br><center><h3>Carregando...</h3></center><br><br>");
    },
    success: function(dados){
      $('#divAmigos').html(dados);
    }
  });
}

function atualizarSession() {
  $.ajax({
    url:"listeners/atualizarSession.php"
  });
}

function carregarOrgs(idEvento){
  $('#conteudoOrgs').html("");

  $.ajax({
    url:"eventos/listaOrganizadores.php",
    async:true,
    method:"POST",
    data:{
      idEvento:idEvento
    },
    dataType:"html",
    success: function(result){
      $(document).find('#conteudoOrgs').html(result);
      $(document).find("#modalOrgs").fadeIn(400);
    }, 
    error: function(result){
      var html = "<div class='modal' id='modalMsg'>\n<div class='modal-dialog'>\n\<div class='modal-content'>\n<div class='modal-header'>\n<button type='button' class='close' data-dismiss='modal' aria-hidden='true' onclick='fecharMsg()'>&times;</button>\n<h4 class='modal-title'>Erro</h4>\n</div>\n<div class='modal-body'>\n<center><h1>Erro: " + result.status + " " + result.statusText + "</h1></center>\n</div>\n</div>\n</div>\n</div>";
      $(document).find('#modalOrgs').html(html);
    }
  });
}