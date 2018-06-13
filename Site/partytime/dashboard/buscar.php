<?php
	session_start();
	include_once "../php/classes/conexao.class.php";

	$conexao = new Conexao();
	$nomeProcurado = $_POST['nomeProcurado'];
?>

<?php
	if (!empty($nomeProcurado)) {
		$dadosEncontrados  = $conexao->procurarUsuario($nomeProcurado);

		echo "<h2>Usuários</h2><hr>";

		if (count($dadosEncontrados) < 1)
			echo "<h3>Nenhum usuário foi encontrado.</h3>";
		else {
			echo "<ul class='usuariosProcurados'>";
			for ($i = 0; $i < count($dadosEncontrados); $i++) {
				echo "<li>";
					echo "<div class='row'>";

					echo "<div class='col-md-11 nomePerfil' onclick='carregarOutroPerfil(".$dadosEncontrados[$i]['idUsuario'].")'>";
						echo "<label>".$dadosEncontrados[$i]["username"]."</label>";
					echo "</div>";
					
					if ($conexao->temRelacao($_SESSION['usuario']['id'], $dadosEncontrados[$i]['idUsuario']) == false && $_SESSION['usuario']['id'] != $dadosEncontrados[$i]['idUsuario']) {
						echo "<div class='col-md-1 iconeAdicionar'>";
							echo "<div onclick='pedirAmizade(".$dadosEncontrados[$i]['idUsuario'].")'>
									+<span class='glyphicon glyphicon-user'>
								  </div>";
						echo "</div>";
					}
					echo "</div>";
				echo "</li>";	
			}
			echo "</ul>";
		}

		echo "<br>";

		$dadosEncontrados  = $conexao->procurarEvento($nomeProcurado);

		echo "<h2>Eventos</h2><hr>";

		if (count($dadosEncontrados) < 1)
			echo "<h3>Nenhum evento foi encontrado.</h3>";
		else {
			echo "<ul class='usuariosProcurados'>";
			for ($i = 0; $i < count($dadosEncontrados); $i++) {
				echo "<li>";
					echo "<div class='row'>";
						echo "<div class='col-md-11 nomePerfil' onclick='carregarEvento(".$dadosEncontrados[$i]['idEvento'].")'>";
							echo "<label>".$dadosEncontrados[$i]["nomeEvento"]."</label>";
						echo "</div>";
					echo "</div>";
				echo "</li>";	
			}
			echo "</ul>";
		}
	} else
		echo "<h3>Nenhum evento foi encontrado</h3>";
	echo "<br>";
?>