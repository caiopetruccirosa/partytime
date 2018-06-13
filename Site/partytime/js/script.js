var interval;

function mudarForm(qualForm) {
	$(document).find('#divPrincipal').addClass('animated bounceOutUp').one('webkitAnimationEnd', function(){
		$(document).find('#divPrincipal').hide();
		$(document).find('#divPrincipal').removeClass('animated bounceOutUp');

		if (qualForm == "login") {
			$(document).find("#divPrincipal").css({ "height" : "390px" });
			$("#frmLogin").show();
			$("#frmCadastro").hide();
			$("#frmMudarSenha").hide();
		} else if (qualForm == "cadastro") {
			$(document).find("#divPrincipal").css({ "height" : "620px" });
			$("#frmLogin").hide();
			$("#frmCadastro").show();
			$("#frmMudarSenha").hide();
		} if (qualForm == "mudarSenha") {
			$(document).find("#divPrincipal").css({ "height" : "360px" });
			$("#frmLogin").hide();
			$("#frmCadastro").hide();
			$("#frmMudarSenha").show();
		}

		$(document).find('#divPrincipal').ready(function() {
			$(document).find('#divPrincipal').show();
			$(document).find('#divPrincipal').addClass('animated bounceInDown').one('webkitAnimationEnd', function() {
				$(document).find('#divPrincipal').removeClass('animated bounceInDown');
			});
		});
	});
}

function fecharAlert() {
	$(document).find('.alert').addClass('animated zoomOut').one('webkitAnimationEnd', function(){
		$(document).find('.alert').hide();
		$(document).find('.alert').removeClass('animated zoomOut');
	});				
}

function mudarMenu() {
	if ($(document).find('#itens').hasClass('collapse in')) {
		$(document).find('#itens').removeClass('collapse in');
		$(document).find('#itens').addClass('collapse');
	} else if ($(document).find('#itens').hasClass('collapse')) {
		$(document).find('#itens').removeClass('collapse');
		$(document).find('#itens').addClass('collapse in');
	}
}

function mudarSubmenu() {
	if ($(document).find('#sub-menu').hasClass('open')) 
		$(document).find('#sub-menu').removeClass('open');
	else if (!($(document).find('#sub-menu').hasClass('open')))
		$(document).find('#sub-menu').addClass('open');
}

function mudarNotif() {
	if ($(document).find('#notif').hasClass('open')) 
		$(document).find('#notif').removeClass('open');
	else if (!($(document).find('#notif').hasClass('open'))) {
		$(document).find('#notif').addClass('open');
		lerNotif();
	}
}

function mostrarEvento() {
	$(document).find("#criarevento").ready(function(){
        $(document).find("#criarevento").fadeIn(300);
        carregarCriarEvento();
	});
}

function mostrarMsg() {
	$(document).find("#mensagens").ready(function(){
        $(document).find("#mensagens").fadeIn(300);
		obterMensagens();
		carregarConversas();
	});
}

function fecharMsg() {
	window.clearInterval(interval);
	
	$(document).find("#modalMsg").ready(function(){
		$(document).find('#modalMsg').fadeOut(300);
		$('#conteudoMsg').html("");
		$(document).find("#mensagens").ready(function(){
			$(document).find("#mensagens").fadeOut(300);
		});
	});
}

function fecharEvento() {
	$(document).find("#modalEvento").ready(function(){
		$(document).find('#modalEvento').fadeOut(300);
		$('#conteudoEvento').html("");
		$(document).find("#criarevento").ready(function(){
			$(document).find("#criarevento").fadeOut(300);
		});
	});
}

function mostrarListaDeAmigos(idEvento) {
	$(document).find("#listaDeAmigos").ready(function(){
        $(document).find("#listaDeAmigos").fadeIn(300);
        carregarListaDeAmigos(idEvento);
	});
}

function fecharListaDeAmigos(){
	$(document).find("#modalListaDeAmigos").ready(function(){
		$(document).find("#modalListaDeAmigos").fadeOut(300);
		$('#conteudoListaDeAmigos').html("");
		$(document).find("#listaDeAmigos").ready(function(){
			$(document).find("#listaDeAmigos").fadeOut(300);
		});
	})
}


function mostrarOrgs(idEvento) {
	$(document).find("#listaOrgs").ready(function(){
        $(document).find("#listaOrgs").fadeIn(300);    
        carregarOrgs(idEvento);
	});
}

function fecharModalOrg(){
	$(document).find("#modalOrgs").ready(function(){
		$(document).find("#modalOrgs").fadeOut(300);
		$('#conteudoOrgs').html("");
		$(document).find("#listaOrgs").ready(function(){
			$(document).find("#listaOrgs").fadeOut(300);
		});
	})
}
