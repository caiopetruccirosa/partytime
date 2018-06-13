<?php
	session_start();

    if (isset($_POST['bmudarfoto'])) {
        include_once "../../php/classes/conexao.class.php";
        $conexao = new Conexao();

        $novaFoto = $_FILES["novaFoto"]["name"];

        if (!empty($novaFoto)) {
            $uploadOk = 1;

            $target_dir = "../fotos/usuario/".$_SESSION['usuario']['id']."/";

            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $target_file = $target_dir . basename($_FILES["novaFoto"]["name"]);                
            $check = getimagesize($_FILES["novaFoto"]["tmp_name"]);
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
            $diretorio = $_SESSION['usuario']['id']."/".basename($_FILES["novaFoto"]["name"]);
            $diretorioAntigo = "../fotos/usuario/".$_SESSION['usuario']['fotoPerfil'];

            $erro = "";

            if($check !== false) {
                $erro .= "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                $erro = "File is not an image.";
                $uploadOk = 0;
            }

            if ($_FILES["novaFoto"]["size"] > 5000000) {
                $erro .= "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                $erro .= "Sorry, your file was not uploaded.";
                echo $erro;
            // se estiver tudo certo ele tenta dar upload no arquivo
            } else {
                if (move_uploaded_file($_FILES["novaFoto"]["tmp_name"], $target_file)) {
                    if (file_exists($diretorioAntigo))
                        unlink($diretorioAntigo);
                    echo "The file ". basename( $_FILES["novaFoto"]["name"]). " has been uploaded.";
                    echo "deu certo"; 
                    $conexao->mudarDiretorioDaFotoPerfil($_SESSION['usuario']['id'], $diretorio);
                } else {
                    $erro .= "Sorry, there was an error uploading your file.";
                    echo $erro;
                }
            }
        }
    }

    header('Location: ../../');
    exit();
?>