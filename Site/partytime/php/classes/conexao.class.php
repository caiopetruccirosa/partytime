<?php
	class Conexao {

		// atributos/propriedades da classe
		public $con;
		protected $stmt; //recurso da conexão
		protected $host = 'regulus.cotuca.unicamp.br';
		protected $database = 'BD17197';
		protected $user = 'BD17197';
		protected $pass = '14060307';

		// metodo construtor
		public function __construct() {
			$this->con = new PDO("sqlsrv:server=$this->host; Database=$this->database; ConnectionPooling=0", "$this->user", "$this->pass");
			$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return true;
		}//fim __construct

		public function __destruct() {
			$this->con = null;
		}//fim __destruct

		public function validarHTMLInjection($str) {
			$ret = $str;
            $ret = str_replace("<", "&lt;", "$ret");
            $ret = str_replace(">", "&gt;", "$ret");
            return $ret;
		}

		//método para rodar um SELECT no BD
		public function selectUsuario($email) {
			$this->stmt = $this->con->prepare("SELECT * FROM Usuario WHERE email=:email");
			$this->stmt->bindParam(":email", $email);
			$this->stmt->execute();

			$dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			return $dados;
		}

		public function selectUsuarioPorId($id) {
			$this->stmt = $this->con->prepare("SELECT * FROM Usuario WHERE idUsuario=:id");
			$this->stmt->bindParam(":id", $id);
			$this->stmt->execute();

			$dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			return $dados;
		}

		public function selectUsuarioPorChave($chave) {
			$this->stmt = $this->con->prepare("SELECT * FROM Usuario WHERE chaveSenha=:chave");
			$this->stmt->bindParam(":chave", $chave);
			$this->stmt->execute();

			$dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			return $dados;
		}

		public function inserirUsuario($username, $email, $senhaCrip, $dataNasc, $sexo, $ehOrganizador) {
				$this->stmt = $this->con->prepare("INSERT INTO usuario(username, email, senha, dataNasc, sexo, ehOrganizador) VALUES(:username, :email, :senha, :dataNasc, :sexo, :ehOrganizador)");

				$username = $this->validarHTMLInjection($username);
				$email = $this->validarHTMLInjection($email);
				$dataNasc = $this->validarHTMLInjection($dataNasc);
				$sexo = $this->validarHTMLInjection($sexo);

				$this->stmt->bindParam(":username", $username);
				$this->stmt->bindParam(":email", $email);
				$this->stmt->bindParam(":senha", $senhaCrip);
				$this->stmt->bindParam(":dataNasc", $dataNasc);
				$this->stmt->bindParam(":sexo", $sexo);
				$this->stmt->bindParam(":ehOrganizador", $ehOrganizador);
				
				$this->stmt->execute();
		}

		public function inserirChave($id) {
			$this->stmt = $this->con->prepare("UPDATE usuario SET chaveSenha=:chave WHERE idUsuario=:id");

			$chave = hash($id, "tiger128,4", false);

			$this->stmt->bindParam(":id", $id);
			$this->stmt->bindParam(":chave", $chave);
			$this->stmt->execute();

			return $chave;
		}

		public function verificarChave($chave) {
			$this->stmt = $this->con->prepare("SELECT idUsuario FROM usuario WHERE chave=:chave");
			$this->stmt->bindParam(":chave", $chave);
			$this->stmt->execute();

			$dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			$this->stmt = $this->con->prepare("UPDATE usuario SET chave=null WHERE chave=:chave");
			$this->stmt->bindParam(":chave", $chave);
			$this->stmt->execute();

			if (count($dados) > 0)
				return $dados[0]['idUsuario'];

			return false;
		}

		public function atualizarSenha($id, $novaSenha) {
			$this->stmt = $this->con->prepare("UPDATE usuario SET senha=:senha WHERE idUsuario=:id");
			
			$this->stmt->bindParam(":senha", $novaSenha);
			$this->stmt->bindParam(":id", $id);
			
			$this->stmt->execute();
		}

		public function procurarUsuario($nome) {
			$this->stmt = $this->con->prepare("SELECT * FROM usuario WHERE username LIKE :nome");
			$param = "%$nome%";
			$this->stmt->bindParam(":nome", $param);
			$this->stmt->execute();

			$dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			return $dados;
		}

		public function criarEvento($idCriador, $nomeEvento, $capaEvento, $dataInicio, $dataFim, $localizacao, $descricao, $numConvidados, $maxConvidados, $convidadosPodemConvidar) {
			$this->stmt = $this->con->prepare("INSERT INTO evento(idCriador, nomeEvento, capaEvento, dataInicio, dataFim, localizacao, descricao, numConvidados, maxConvidados, convidadosPodemConvidar) VALUES(:idCriador, :nomeEvento, :capaEvento, :dataInicio, :dataFim, :localizacao, :descricao, :numConvidados, :maxConvidados, :convidadosPodemConvidar)");
			
			$nomeEvento = $this->validarHTMLInjection($nomeEvento);
			$dataInicio = $this->validarHTMLInjection($dataInicio);
			$dataFim = $this->validarHTMLInjection($dataFim);
			$localizacao = $this->validarHTMLInjection($localizacao);
			$descricao = $this->validarHTMLInjection($descricao);

			$this->stmt->bindParam(":idCriador", $idCriador);
			$this->stmt->bindParam(":nomeEvento", $nomeEvento);
			$this->stmt->bindParam(":capaEvento", $capaEvento);
			$this->stmt->bindParam(":dataInicio", $dataInicio);
			$this->stmt->bindParam(":dataFim", $dataFim);
			$this->stmt->bindParam(":localizacao", $localizacao);
			$this->stmt->bindParam(":descricao", $descricao);
			$this->stmt->bindParam(":numConvidados", $numConvidados);
            $this->stmt->bindParam(":maxConvidados", $maxConvidados);
			$this->stmt->bindParam(":convidadosPodemConvidar", $convidadosPodemConvidar);
			
			$this->stmt->execute();
		}

		public function selecionarAmigosOnline($idUsuario) {
			$sql = " 
			SELECT * FROM 
			usuario u,
			relacoes r
			WHERE
			(u.estaOnline = 1 and
			u.idUsuario = r.idUsuario2 and
			r.idUsuario1 = :id1 and 
			r.situacao = 1) or 
			(u.estaOnline = 1 and
			u.idUsuario = r.idUsuario1 and
			r.idUsuario2 = :id2 and
			r.situacao = 1)
			";

			$this->stmt = $this->con->prepare($sql);
			$this->stmt->bindParam(":id1", $idUsuario);
			$this->stmt->bindParam(":id2", $idUsuario);
			$this->stmt->execute();
            
            $dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			return $dados;
		}

		public function selecionarAmigosOffline($idUsuario) {
			$sql = " 
			SELECT * FROM 
			usuario u,
			relacoes r
			WHERE
			(u.estaOnline = 0 and
			u.idUsuario = r.idUsuario2 and
			r.idUsuario1 = :id1 and 
			r.situacao = 1) or 
			(u.estaOnline = 0 and
			u.idUsuario = r.idUsuario1 and
			r.idUsuario2 = :id2 and
			r.situacao = 1)
			";

			$this->stmt = $this->con->prepare($sql);
			$this->stmt->bindParam(":id1", $idUsuario);
			$this->stmt->bindParam(":id2", $idUsuario);
			$this->stmt->execute();
            
            $dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			return $dados;
		}

		public function selecionarNotificacoes($idUsuario) {
			$sql = "SELECT TOP 5 * FROM notificacoes WHERE idUsuarioRecebeu=:id ORDER BY dataNotif DESC";
			$this->stmt = $this->con->prepare($sql);
			$this->stmt->bindParam(":id", $idUsuario);
			$this->stmt->execute();

			$dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			return $dados;
		}

		public function quantasNotifNaoLidas($idUsuario) {
			$sql = "SELECT * FROM notificacoes WHERE idUsuarioRecebeu=:id AND foiLida=0";
			$this->stmt = $this->con->prepare($sql);
			$this->stmt->bindParam(":id", $idUsuario);
			$this->stmt->execute();

			$dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			return count($dados);
		}

		public function lerNotificacoes($idUsuario) {
			$sql = "UPDATE notificacoes SET foiLida=1 WHERE foiLida=0 AND idUsuarioRecebeu=:id";
			$this->stmt = $this->con->prepare($sql);
			$this->stmt->bindParam(":id", $idUsuario);
			$this->stmt->execute();
		}

		public function ficarOnline($id) {
			$this->stmt = $this->con->prepare("UPDATE usuario SET estaOnline=1 WHERE idUsuario=:id");
			$this->stmt->bindParam(":id", $id);
			$this->stmt->execute();
		}

		public function ficarOffline($id) {
			$this->stmt = $this->con->prepare("UPDATE usuario SET estaOnline=0 WHERE idUsuario=:id");	
			$this->stmt->bindParam(":id", $id);	
			$this->stmt->execute();
		}

		public function carregarConversas($id) {
			$this->stmt = $this->con->prepare("SET NOCOUNT ON; EXEC conversas_sp :id");
			$this->stmt->bindParam(":id", $id);	
			$this->stmt->execute();

			$dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			return $dados;
		}

		public function selecionarMensagensEntre($idLogado, $idOutro) {
			$this->stmt = $this->con->prepare("Select * from mensagens where idUsuario1=:id1 and idUsuario2=:id2 order by dataMensagem");

			if ($idLogado <= $idOutro) {
				$id1 = $idLogado;
				$id2 = $idOutro;
			} else {
				$id1 = $idOutro;
				$id2 = $idLogado;
			}

			$this->stmt->bindParam(":id1", $id1);	
			$this->stmt->bindParam(":id2", $id2);	
			$this->stmt->execute();

			$dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			return $dados;
		}

		public function enviarMensagem($idLogado, $idOutro, $conteudoMsg) {
			$this->stmt = $this->con->prepare("INSERT INTO mensagens VALUES (:id1, :id2, :idEnviou, :conteudoMsg, 0, GETDATE())");

			if ($idLogado <= $idOutro) {
				$id1 = $idLogado;
				$id2 = $idOutro;
			} else {
				$id1 = $idOutro;
				$id2 = $idLogado;
			}

			$conteudoMsg = $this->validarHTMLInjection($conteudoMsg);

			$this->stmt->bindParam(":id1", $id1);	
			$this->stmt->bindParam(":id2", $id2);
			$this->stmt->bindParam(":idEnviou", $idLogado);
			$this->stmt->bindParam(":conteudoMsg", $conteudoMsg);
			$this->stmt->execute();
		}

		public function visualizarMensagens($idLogado, $idOutro) {
			$this->stmt = $this->con->prepare("UPDATE mensagens SET visualizado=1 WHERE idUsuario1=:id1 AND idUsuario2=:id2 AND idEnviou=:idEnviou");

			if ($idLogado <= $idOutro) {
				$id1 = $idLogado;
				$id2 = $idOutro;
			} else {
				$id1 = $idOutro;
				$id2 = $idLogado;
			}

			$this->stmt->bindParam(":id1", $id1);	
			$this->stmt->bindParam(":id2", $id2);
			$this->stmt->bindParam(":idEnviou", $idOutro);
			$this->stmt->execute();
		}

		public function fazerPedidoAmizade($idLogado, $idOutro) {
			if ($this->temRelacao($idLogado, $idOutro))
				return false;
			else {
				if ($this->existeRelacao($idLogado, $idOutro)) {
					$this->stmt = $this->con->prepare("UPDATE relacoes SET situacao = 3, idUsuarioAcao=:idAcao WHERE idUsuario1=:id1 AND idUsuario2=:id2");

					if ($idLogado <= $idOutro) {
						$id1 = $idLogado;
						$id2 = $idOutro;
					} else {
						$id1 = $idOutro;
						$id2 = $idLogado;
					}

					$this->stmt->bindParam(":id1", $id1);	
					$this->stmt->bindParam(":id2", $id2);
					$this->stmt->bindParam(":idAcao", $idLogado);
					$this->stmt->execute();

					//////////////

					$dadosUsuario = $this->selectUsuarioPorId($idLogado)[0];

					$this->stmt = $this->con->prepare("
						INSERT INTO notificacoes VALUES (:idRecebeu, :idMandou, GETDATE(), :conteudoNotif, 0)
					");

					$conteudoNotif = $dadosUsuario['username'] . " enviou uma solicitação de amizade para você.";

					$this->stmt->bindParam(":idRecebeu", $idOutro);
					$this->stmt->bindParam(":idMandou", $idLogado);
					$this->stmt->bindParam(":conteudoNotif", $conteudoNotif);
					$this->stmt->execute();
				} else {
					$this->stmt = $this->con->prepare("INSERT INTO relacoes VALUES (:id1, :id2, :idAcao, 3)");

					if ($idLogado <= $idOutro) {
						$id1 = $idLogado;
						$id2 = $idOutro;
					} else {
						$id1 = $idOutro;
						$id2 = $idLogado;
					}

					$this->stmt->bindParam(":id1", $id1);	
					$this->stmt->bindParam(":id2", $id2);
					$this->stmt->bindParam(":idAcao", $idLogado);
					$this->stmt->execute();

					//////////////

					$dadosUsuario = $this->selectUsuarioPorId($idLogado)[0];

					$this->stmt = $this->con->prepare("
						INSERT INTO notificacoes VALUES (:idRecebeu, :idMandou, GETDATE(), :conteudoNotif, 0)
					");

					$conteudoNotif = $dadosUsuario['username'] . " enviou uma solicitação de amizade para você.";

					$this->stmt->bindParam(":idRecebeu", $idOutro);
					$this->stmt->bindParam(":idMandou", $idLogado);
					$this->stmt->bindParam(":conteudoNotif", $conteudoNotif);
					$this->stmt->execute();
				}
			}
		}

		public function temRelacao($idLogado, $idOutro) {
			$this->stmt = $this->con->prepare("SELECT * FROM relacoes WHERE idUsuario1= :id1 AND idUsuario2= :id2");

			if ($idLogado <= $idOutro) {
				$id1 = $idLogado;
				$id2 = $idOutro;
			} else {
				$id1 = $idOutro;
				$id2 = $idLogado;
			}

			$this->stmt->bindParam(":id1", $id1);	
			$this->stmt->bindParam(":id2", $id2);
			$this->stmt->execute();

			$dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			if (count($dados) > 0) {
				if ($dados[0]['situacao'] == 0)
					return false;
				else
					return true;
			}

			return false;
		}

		public function existeRelacao($idLogado, $idOutro) {
			$this->stmt = $this->con->prepare("SELECT * FROM relacoes WHERE idUsuario1= :id1 AND idUsuario2= :id2");

			if ($idLogado <= $idOutro) {
				$id1 = $idLogado;
				$id2 = $idOutro;
			} else {
				$id1 = $idOutro;
				$id2 = $idLogado;
			}

			$this->stmt->bindParam(":id1", $id1);	
			$this->stmt->bindParam(":id2", $id2);
			$this->stmt->execute();

			$dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			if (count($dados) > 0)
				return true;

			return false;
		}

		public function selecionarEventos($idUsuario) {
			$sql = "SELECT * FROM evento WHERE idCriador=:id ORDER BY dataCriacao DESC";
			$this->stmt = $this->con->prepare($sql);
			$this->stmt->bindParam(":id", $idUsuario);
			$this->stmt->execute();

			$dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			return $dados;
		}

		public function selecionarEventosAmigos($idUsuario)	{
			$sql = "
				SELECT 
				*
				from 
				evento ev,
				usuario u,
				relacoes re
				where
				(re.idUsuario1 = :id1 and
				re.situacao = 1 and 
				re.idUsuario2 = ev.idCriador and
				u.idUsuario = re.idUsuario2) or
				(re.idUsuario2 = :id2 and
				re.situacao = 1 and 
				re.idUsuario1 = ev.idCriador and
				u.idUsuario = re.idUsuario1)
				ORDER BY dataCriacao DESC
			";

			$this->stmt = $this->con->prepare($sql);
			$this->stmt->bindParam(":id1", $idUsuario);
			$this->stmt->bindParam(":id2", $idUsuario);
			$this->stmt->execute();

			$dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			return $dados;
		}

		public function recusarPedido($idLogado, $idOutro) {
			$this->stmt = $this->con->prepare("UPDATE relacoes SET situacao= 0 WHERE idUsuario1= :id1 AND idUsuario2= :id2");

			if ($idLogado <= $idOutro) {
				$id1 = $idLogado;
				$id2 = $idOutro;
			} else {
				$id1 = $idOutro;
				$id2 = $idLogado;
			}

			$this->stmt->bindParam(":id1", $id1);	
			$this->stmt->bindParam(":id2", $id2);
			$this->stmt->execute();
		}

		public function aceitarPedido($idLogado, $idOutro) {
			$this->stmt = $this->con->prepare("UPDATE relacoes SET situacao= 1 WHERE idUsuario1= :id1 AND idUsuario2= :id2");

			if ($idLogado <= $idOutro) {
				$id1 = $idLogado;
				$id2 = $idOutro;
			} else {
				$id1 = $idOutro;
				$id2 = $idLogado;
			}

			$this->stmt->bindParam(":id1", $id1);	
			$this->stmt->bindParam(":id2", $id2);
			$this->stmt->execute();
		}

		public function selecionarRelacao($idLogado, $idOutro) {
			$this->stmt = $this->con->prepare("SELECT * FROM relacoes WHERE idUsuario1= :id1 AND idUsuario2= :id2");

			if ($idLogado <= $idOutro) {
				$id1 = $idLogado;
				$id2 = $idOutro;
			} else {
				$id1 = $idOutro;
				$id2 = $idLogado;
			}

			$this->stmt->bindParam(":id1", $id1);	
			$this->stmt->bindParam(":id2", $id2);
			$this->stmt->execute();

			$dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			return $dados;
		}

		public function selecionarEvento($idEvento) {
			$this->stmt = $this->con->prepare("SELECT * FROM evento WHERE idEvento=:id");

			$this->stmt->bindParam(":id", $idEvento);	
			$this->stmt->execute();

			$dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			return $dados;
		}

		public function selecionarUltimoEventoDe($idLogado) {
			$this->stmt = $this->con->prepare("SELECT TOP 1 * FROM evento WHERE idCriador=:id ORDER BY dataCriacao DESC");

			$this->stmt->bindParam(":id", $idLogado);	
			$this->stmt->execute();

			$dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			return $dados[0];
		}

		public function mudarDiretorioDaCapa($idEvento, $diretorio) {
			$this->stmt = $this->con->prepare("UPDATE evento SET capaEvento = :diretorio WHERE idEvento = :id");
			$this->stmt->bindParam(":id", $idEvento);
			$this->stmt->bindParam(":diretorio", $diretorio);
			$this->stmt->execute();
		}

		public function selecionarConvidados($idEvento) {
			$this->stmt = $this->con->prepare("SELECT * FROM convidados WHERE idEvento=:id");

			$this->stmt->bindParam(":id", $idEvento);	
			$this->stmt->execute();

			$dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			return $dados;
		}

		public function selecionarOrganizadores($idEvento) {
			$this->stmt = $this->con->prepare("SELECT * FROM organizador WHERE idEvento=:id");

			$this->stmt->bindParam(":id", $idEvento);	
			$this->stmt->execute();

			$dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			return $dados;
		}

		public function procurarEvento($nome) {
			$this->stmt = $this->con->prepare("SELECT * FROM evento WHERE nomeEvento LIKE :nome");
			$param = "%$nome%";
			$this->stmt->bindParam(":nome", $param);
			$this->stmt->execute();

			$dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			return $dados;
		}
		public function selecionarAmigos($idUsuario) {
			$sql = " 
			SELECT * FROM 
			usuario u,
			relacoes r
			WHERE
			(u.idUsuario = r.idUsuario2 and
			r.idUsuario1 = :id1 and 
			r.situacao = 1) or 
			(u.idUsuario = r.idUsuario1 and
			r.idUsuario2 = :id2 and
			r.situacao = 1)
			";

			$this->stmt = $this->con->prepare($sql);
			$this->stmt->bindParam(":id1", $idUsuario);
			$this->stmt->bindParam(":id2", $idUsuario);
			$this->stmt->execute();
            
            $dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			return $dados;
		}

		public function ehConvidadoOuOrg($idUsuario, $idEvento) {
			$sql = "SELECT * FROM convidados WHERE idConvidado=:idUsuario AND idEvento=:idEvento";
			$this->stmt = $this->con->prepare($sql);
			$this->stmt->bindParam(":idUsuario", $idUsuario);
			$this->stmt->bindParam(":idEvento", $idEvento);
			$this->stmt->execute();
            
            $dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($dados) > 0)
            	return true;

			$sql = "SELECT * FROM organizador WHERE idOrganizador=:idUsuario AND idEvento=:idEvento";
			$this->stmt = $this->con->prepare($sql);
			$this->stmt->bindParam(":idUsuario", $idUsuario);
			$this->stmt->bindParam(":idEvento", $idEvento);
			$this->stmt->execute();
            
            $dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($dados) > 0)
            	return true;

			return false;
		}

		public function convidar($vConv, $idEvento, $idOrganizador) {
			for ($i = 0; $i < count($vConv); $i++) {
				$idConvidado = $vConv[$i];

				$this->enviarNotifEventoConv($idConvidado, $idOrganizador, $idEvento);

				$this->stmt = $this->con->prepare("INSERT INTO convidados(idConvidado, idEvento) VALUES(:idConvidado, :idEvento)");
				$this->stmt->bindParam(":idConvidado", $idConvidado);
				$this->stmt->bindParam(":idEvento", $idEvento);
				$this->stmt->execute();
			}
		}

		public function mudarDiretorioDaFotoPerfil($idUsuario, $diretorio) {
			$this->stmt = $this->con->prepare("UPDATE usuario SET fotoPerfil = :diretorio WHERE idUsuario = :id");
			$this->stmt->bindParam(":id", $idUsuario);
			$this->stmt->bindParam(":diretorio", $diretorio);
			$this->stmt->execute();
		}

		public function enviarNotifEventoConv($idConvidado, $idOrganizador, $idEvento) {
			$usuarioMandou = $this->selectUsuarioPorId($idMandou)[0];
			$evento = $this->selecionarEvento($idEvento)[0];

			$conteudo = $usuarioMandou['username'].
						" convidou você para o evento <b class='linkEvento' onclick=carregarEvento('".$evento['idEvento']."')><i>".
						$evento['nomeEvento']."</i></b>";

			$this->stmt = $this->con->prepare("INSERT INTO notificacoes VALUES(:idRecebeu, :idMandou, GETDATE(), :conteudo, 0)");
			$this->stmt->bindParam(":idRecebeu", $idConvidado);
			$this->stmt->bindParam(":idMandou", $idOrganizador);
			$this->stmt->bindParam(":conteudo", $conteudo);
			$this->stmt->execute();
		}

		public function ehOrganizador($idUsuario, $idEvento) {
			$sql = "SELECT * FROM organizador WHERE idOrganizador=:idUsuario AND idEvento=:idEvento";
			$this->stmt = $this->con->prepare($sql);
			$this->stmt->bindParam(":idUsuario", $idUsuario);
			$this->stmt->bindParam(":idEvento", $idEvento);
			$this->stmt->execute();
            
            $dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($dados) > 0)
            	return true;

            return false;
        }

        public function adicionarOrg($vConv, $idEvento, $idOrganizador1) {
			for ($i = 0; $i < count($vConv); $i++) {
				$idOrganizador2 = $vConv[$i];

				$this->enviarNotifEventoOrg($idOrganizador2, $idOrganizador1, $idEvento);

				$this->stmt = $this->con->prepare("INSERT INTO organizador(idOrganizador, idEvento) VALUES(:idOrganizador, :idEvento)");
				$this->stmt->bindParam(":idOrganizador", $idOrganizador2);
				$this->stmt->bindParam(":idEvento", $idEvento);
				$this->stmt->execute();

				$this->stmt = $this->con->prepare("DELETE FROM convidados WHERE idEvento=:idEvento AND idConvidado=:idConvidado");
				$this->stmt->bindParam(":idConvidado", $idOrganizador2);
				$this->stmt->bindParam(":idEvento", $idEvento);
				$this->stmt->execute();
			}
		}

		public function enviarNotifEventoOrg($idOrganizador1, $idOrganizador2, $idEvento) {
			$usuarioMandou = $this->selectUsuarioPorId($idMandou)[0];
			$evento = $this->selecionarEvento($idEvento)[0];

			$conteudo = $usuarioMandou['username'].
						" adicionou você como organizador no evento <b class='linkEvento' onclick=carregarEvento('".$evento['idEvento']."')><i>".
						$evento['nomeEvento']."</i></b>";

			$this->stmt = $this->con->prepare("INSERT INTO notificacoes VALUES(:idRecebeu, :idMandou, GETDATE(), :conteudo, 0)");
			$this->stmt->bindParam(":idRecebeu", $idOrganizador1);
			$this->stmt->bindParam(":idMandou", $idOrganizador2);
			$this->stmt->bindParam(":conteudo", $conteudo);
			$this->stmt->execute();
		}

		public function selecionarEventosPopulares() {
			$this->stmt = $this->con->prepare("SET NOCOUNT ON; EXEC eventosmaispop_sp");
			$this->stmt->execute();

			$dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			return $dados;
		}

		public function selecionarOrgsPopulares() {
			$this->stmt = $this->con->prepare("SET NOCOUNT ON; EXEC organizadormaispop_sp");
			$this->stmt->execute();

			$dados = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			return $dados;
		}
	}
?>