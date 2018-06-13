<?php
	session_start();

  include_once "../php/classes/conexao.class.php";

  $conexao = new Conexao();
  $conexao->ficarOnline($_SESSION['usuario']['id']);

	$username = $_SESSION['usuario']['username'];
	$email = $_SESSION['usuario']['email'];
	$dataNasc = $_SESSION['usuario']['dataNasc'];
  $sexo = $_SESSION['usuario']['sexo'];
  $fotoPerfil = $_SESSION['usuario']['fotoPerfil'];
	if (isset($_SESSION['usuario']['qnts_notif']))
	 $qnts_notif = $_SESSION['usuario']['qnts_notif'];
	if (isset($_SESSION['usuario']['qnts_msg']))
	 $qnts_msg = $_SESSION['usuario']['qnts_msg'];

	if (!isset($_SESSION['logado']) && $_SESSION['logado'] == false) {
		header("Location: ../");
		exit();
	}
?>

<!DOCTYPE html>
<html>
<head>
  <title>party time.</title>
  <meta charset="utf-8">

  <link rel="stylesheet" type="text/css" href="../css/animate.css">
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="../css/dashboard.css">
  <link rel="stylesheet" type="text/css" href="../css/chat.css">

  <link rel="icon" href="../imagens/icone.ico">

  <script type="text/javascript" src="../js/jquery.js"></script>
  <script type="text/javascript" src="../js/ajax.js"></script>
  <script type="text/javascript" src="../js/script.js"></script>
  <script type="text/javascript">
    
    $(document).ready(function(){
      carregarHome();
      carregarMensagens();
      carregarNotificacoes();
      carregarNumNotif();
      setInterval(carregarAmigos, 500);
      setInterval(carregarMensagens, 500);
      setInterval(carregarNotificacoes, 500);
      setInterval(carregarNumNotif, 500);
      setInterval(atualizarSession, 500);

      //history.pushState(null, "", "alo");
    });


    window.addEventListener("keydown", function(event) {
      if (event.keyCode === 13) {
        if ($("#textoMsg").is(":focus"))
          $('#btn-chat').click();
        else if ($("#nomePesquisado").is(":focus"))
          $('.btnPesquisar').click();
        else if ($("#nomeBusca").is(":focus"))
          $('#bbuscar').click();
      }
    });

    $(document).click(function(e){
      if(!($(e.target).is("#sub-menu") || $(e.target).closest("#sub-menu").length))
        if ($(document).find('#sub-menu').hasClass('open')) 
          $(document).find('#sub-menu').removeClass('open');
    });

    $(document).click(function(e){
      if(!($(e.target).is("#notif") || $(e.target).closest("#notif").length))
        if ($(document).find('#notif').hasClass('open')) 
          $(document).find('#notif').removeClass('open');
    });

    $(window).on("beforeunload", function(){
      $.ajax({
        url:"../php/logout/ficarOffline.php",
        method:"POST",
        data:{
          email:"<?php echo $_SESSION['usuario']['email'] ?>"
        }
      });
    });
    
    function ehVazia(string) {
      if (string == null || string.trim().length === 0)
        return true;
      
      return false;
    }
    $(document).ready(function(){
    $("#formEvento").submit(function (e) {
      alert("a");
      var nomeEvento    = $('#nomeEvento').val();
      var dataInicio    = $('#dataInicio').val();
      var dataFim       = $('#dataFim').val();
      var localizacao   = $('#localizacao').val();
      var descricao     = $('#descricao').val();
      var maxConvidados = $('#maxConvidados').val();

      if (ehVazia(nomeEvento) || ehVazia(dataInicio) || ehVazia(dataFim) || !ehVazia(localizacao) || !ehVazia(descricao) || !ehVazia(maxConvidados)){
        alert("Preencha todos os campos");
        stopEvent(e);
      }
      return true;  
    });
  });

  </script>
</head>

<body>
  <nav class="navbar navbar-inverse" id="menu">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#itens" onclick="mudarMenu()">
          <span class="sr-only">Mudar navegação</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="../dashboard/">Party Time</a>
      </div>

      <div class="collapse navbar-collapse" id="itens">
        <ul class="nav navbar-nav">
          <li><a onclick="carregarHome()"><span class="glyphicon glyphicon-home"></span> Home</a></li>
          <li><a onclick="carregarFeed()"><i class="fa fa-rss" aria-hidden="true"></i> Feed</a></li>
          <li onclick="mostrarEvento()"><a onclick=""><i class="fa fa-plus"></i> Criar evento</a></li>

          <li class="dropdown" id="notif">
            <a class="dropdown-toggle" data-toggle="dropdown" onclick="mudarNotif()"><i class="fa fa-bell"></i> Notificações <span class='badge' id="qnts_notif"></span></a>
            <ul class="dropdown-menu" id="notif-itens"></ul>
          </li>

          <li onclick="mostrarMsg()"><a><i class="fa fa-comments-o" aria-hidden="true"></i> Mensagens <span class='badge' id="qnts_msg"></span></a></li>
        </ul>

        <div class="navbar-form navbar-left">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Procurar usuário ou um evento..." id='nomeBusca'>
            <button class="btn btn-default" id='bbuscar' onclick="buscarUsuario()"><span class="glyphicon glyphicon-search"></span> Buscar</button>
          </div>
        </div>

        <ul class="nav navbar-nav navbar-right">
          <li><a onclick="carregarPerfil()">
          <?php 
            echo "<img src='fotos/usuario/$fotoPerfil' class='img-circle fotoMenu'> $username"; 
          ?>
          </a></li>

          <li class="dropdown" id="sub-menu">
            <a class="dropdown-toggle" data-toggle="dropdown" onclick="mudarSubmenu()"><span class="caret"></span></a>
            <ul class="dropdown-menu" id="sub-itens">
              <li onclick="carregarConfiguracoes()"><a><i class="fa fa-cog" aria-hidden="true"></i> Configurações</a></li>
              <li class="divider"></li>
              <li><a href="../php/logout/"><span class="glyphicon glyphicon-log-out"></span> Sair</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

<div id="main_container">
  <div id="div_amigos"></div>

  <div id='dadosCarregados'></div>
</div>

<div id="mensagens">
  <div class="modal" id="modalMsg">
    <div class="modal-dialog" id="conteudoMsg"></div>
  </div>
</div>
    
<div id="criarevento">
  <div class="modal" id="modalEvento">
    <div class="modal-dialog" id="conteudoEvento">
    </div>
  </div>
</div>

<div id='listaDeAmigos'>
  <div class='modal' id='modalListaDeAmigos'>
    <div class='modal-dialog' id='conteudoListaDeAmigos'></div>
  </div>
</div>

<div id='listaOrgs'>
  <div class='modal' id='modalOrgs'>
    <div class='modal-dialog' id='conteudoOrgs'></div>
  </div>
</div>

</body>
</html>