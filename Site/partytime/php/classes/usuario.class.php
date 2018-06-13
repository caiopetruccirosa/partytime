<?php 

	include_once "conexao.class.php";
	// Import PHPMailer classes into the global namespace
	// These must be at the top of your script, not inside a function
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	//Load composer's autoloader
	//require 'vendor/autoload.php';

	class Usuario extends Conexao {
		private $id;
		private $username;
		private $email;
		private $senha;
		private $dataNasc;
		private $sexo;
		private $ehOrganizador;
		private $fotoPerfil;

		private $senhaConf;
		private $senhaCrip;

		private $erro = "";
		private $forcaSenha;

		const HASH = PASSWORD_DEFAULT;
		const COST = 14;

		public function __construct($email, $senha) {
			$this->email = $email;
			$this->senha = $senha;
			$this->con = new PDO("sqlsrv:server=$this->host; Database=$this->database; ConnectionPooling=0", "$this->user", "$this->pass");
			$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

//private function smtpmailer() {
//	
//try{
//	$mail = new PHPMailer();
//    $mail->IsSMTP(); // enable SMTP
//    $mail->SMTPDebug = 2;  // debugging: 1 = errors and messages, 2 = messages only
//    $mail->SMTPAuth = true;  // authentication enabled
//    $mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
//    $mail->Host = 'smtp.gmail.com';
//    $mail->Port = 587;
//    $mail->Username = 'projetopartytime@gmail.com';
//    $ma-
//    il->Password = 'caio2002';
//
//    $mail->SetFrom('projetopartytime@gmail.com', 'Equipe Party Time');
//    $mail->Subject = "Mudançca de senha do usuario ". $this->username;
//    $mail->Body = "Olá $this->username! \r\n Para mudar sua senha clique em: www2/u17167/aa/mudarSenha/?usuario=".$chaveUsuario;;
//    $mail->AddAddress($this->email);
//
//    $mail->Send()
//     }catch(Exception $e){
//        $error = 'Mail error: '.$mail->ErrorInfo; 
//        return false;
//    }
//
//    return true;
//}
//
//public function enviarEmail() {
//	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//		$this->erro = "E-mail inválido!";
//		return false;
//	}
//
//	if ($this->usuarioExiste() == true) {
//		$this->setInfo();
//		$assunto = "Troca de senha ". $this->user;
//		$conteudo = "Nao esta funcionando";
//		$chaveUsuario = $this->inserirChave($this->id);
//
//		if ($this->smtpmailer()) {
//			return true;
//		}
//	}
//
//	$this->erro = "Usuário não existe";
//	return false;
//}

public function mudarSenha($chave, $novaSenha, $novaSenhaConf) {
	if (!($novaSenha === $novaSenhaConf)) {
		$this->erro = "Senhas diferentes";
		return false;
	}

	$this->senha = $senha;

	$id = $this->con->verificarChave($chave);
	if ($id != false) {
		$this->senha = $senha;
		$this->criptografarSenha();
		$this->atualizarSenha($id, $this->senhaCrip);

		return true;
	}

	$this->erro = "Não foi possível alterar a senha!";
	return false;
	}

		public function setInfo() {
			$dados = $this->selectUsuario($this->email)[0];

			$this->id = $dados['idUsuario'];
			$this->username = $dados['username'];
			$this->dataNasc = new DateTime($dados['dataNasc']);
			$this->sexo = $dados['sexo'];
			$this->ehOrganizador = $dados['ehOrganizador'];
			$this->fotoPerfil = $dados['fotoPerfil'];
		}
 
		public function setUsuarioInfo($username, $dataNasc, $sexo, $senhaConf) {
			$this->username = $username;
			$this->senhaConf = $senhaConf;
			$this->dataNasc = new DateTime($dataNasc);
			$this->sexo = $sexo;
			$this->ehOrganizador = 0;
		}

		private function usuarioExiste() {
			$dados = $this->selectUsuario($this->email);
			
			if (count($dados) > 0) {
				$this->erro = "E-mail já usado";
				return true;
			}

			$this->erro = "Usuário não existe";
			return false;
		}

		private function senhaEhCorreta() {
			$dados = $this->selectUsuario($this->email)[0];
			$this->senhaCrip = $dados['senha'];

			$senhaCripVerif = password_verify($this->senha, $this->senhaCrip);

			if  ($senhaCripVerif == true)
				return true;

			$this->erro = "Senha incorreta";
			return false;
		}

		private function verificarForcaSenha($senha) {
			if (strlen($this->senha) < 8) {
				$this->forcaSenha = 0;
				return "Digite uma senha com pelo menos 8 caracteres";
			}

			if (!preg_match('/[a-zA-Z]/', $senha)) {
				$this->forcaSenha = 0;
				return "Senha fraca! Coloque letras maiúsculas e minúsculas";
			}

			if (!preg_match('/[0-9]/', $senha)) {
				$this->forcaSenha = 0;
				return "Senha fraca! Coloque números";
			}

			if (!preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\]]/', $senha))
				$this->forcaSenha = 1;

			$this->forcaSenha = 2;
			return true;
		}

		private function criptografarSenha() {
			$this->senha = $this->validarHTMLInjection($this->senha);
			$this->senhaCrip = password_hash($this->senha, self::HASH, ['cost' => self::COST]);
		}

		private function verifInfoCadastro() {
			$this->username = trim($this->username);
			$this->email = trim($this->email);

			$this->username = strtolower($this->username);
			$this->email = strtolower($this->email);

			$this->username = ucwords($this->username);

			if (strlen($this->username) < 2 || strlen($this->username) > 20)
				return "Digite um nome entre 2 e 50 caracteres";

			// Verifica se o usuário digitou o e-mail corretamente (xx@xx.xx.xx)
			if (strlen($this->email) > 50)
				return "Digite um email com menos de 50 caracteres";
				
			// Verifica se o usuário colocou uma senha válida
			if (strlen($this->senha) < 8)
				return "Digite uma senha com pelo menos 8 caracteres";

			if (!($this->senha === $this->senhaConf))
				return "Senhas diferentes";

			if (!filter_var($this->email, FILTER_VALIDATE_EMAIL))
      			return "E-mail inválido!";

      		if (!$this->verificarForcaSenha($this->senha))
	  			return $this->verificarForcaSenha($this->senha);
				
			// Verifica se o usuário selecionou algum sexo
			if (!($this->sexo === "M") && !($this->sexo === "F") && !($this->sexo === "O"))
				return "Selecione algum sexo";

			$data = date("d-m-Y");
		    $dataAtual = new DateTime($data);
		    $intervalo = $dataAtual->diff($this->dataNasc);
		    $diferenca = $intervalo->y;
				
		    if ($this->dataNasc->format("Y") < 1930)
		    	return "Insira uma data válida";

		    if ($diferenca < 14)
		    	return "Você precisa ter no mínimo 14 anos para se cadastrar!";

			return true;
		}

		public function getErro() {
			return $this->erro;
		}

		public function getInfo() {
	        $info = array('id' => $this->id,
	        			'username' => $this->username, 
			        	'email' => $this->email, 
			        	'dataNasc' => $this->dataNasc, 
			        	'sexo' => $this->sexo, 
			        	'ehOrganizador' => $this->ehOrganizador,
			        	'fotoPerfil' => $this->fotoPerfil);

	        return $info;
	    }

		public function logar() {
			if ($this->usuarioExiste() === true) {
				if ($this->senhaEhCorreta() === true) {
					$this->setInfo();
					if (password_needs_rehash($this->senhaCrip, self::HASH, ['cost' => self::COST])) {
		            	$this->criptografarSenha();
		            	$this->atualizarSenha($this->id, $this->senhaCrip);	
					}
					return true;
				}
			}

			return false;
		}

		public function cadastrar() {
			$this->erro = $this->verifInfoCadastro();

			if (!($this->erro === true)) 
				return false;

			if ($this->usuarioExiste() === false) {
				$this->criptografarSenha();

				$data = $this->dataNasc->format("d/m/Y");
				$this->inserirUsuario($this->username, $this->email, $this->senhaCrip, $data, $this->sexo, $this->ehOrganizador);

				return true;
			}
			return false;
		}
	}
?>