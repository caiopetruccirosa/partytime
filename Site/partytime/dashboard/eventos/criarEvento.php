<?php 

    session_start();
   
    include_once "../../php/classes/conexao.class.php";
    $conexao = new Conexao();

    $idCriador = $_SESSION['usuario']['id'];
    $nomeEvento = $_POST['nomeEvento'];
    $capaEvento = $_FILES["capaEvento"]["name"];
    $objDataInicio = new Datetime($_POST['dataInicio']);
    $objDataFim = new Datetime($_POST['dataFim']);
    $localizacao = $_POST['localizacao'];
    $numConvidados = 0;
    $maxConvidados = $_POST['maxConvidados'];
    $descricao = $_POST['descricao'];
    $convidadosPodemConvidar = $_POST['convidadosPodemConvidar'];

    $dataInicio = $objDataInicio->format('Y-m-d H:i:s');
    $dataFim = $objDataFim->format('Y-m-d H:i:s');  

    if (!empty($idCriador) && !empty($nomeEvento) && !empty($maxConvidados) && !empty($dataInicio) && !empty($dataFim) && !empty($localizacao) && !empty($descricao) && ($convidadosPodemConvidar == 0 || $convidadosPodemConvidar == 1)) {
        if (!($objDataInicio > $objDataFim) || !(new DateTime() > $objDataInicio)) {
            $conexao->criarEvento($idCriador, $nomeEvento, null, $dataInicio, $dataFim, $localizacao, $descricao, $numConvidados, $maxConvidados, $convidadosPodemConvidar); 

            if (!empty($capaEvento)) {
                $evento = $conexao->selecionarUltimoEventoDe($_SESSION['usuario']['id']);

                $uploadOk = 1;

                $target_dir = "../fotos/eventos/capas/".$evento['idEvento']."/";

                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                $target_file = $target_dir . basename($_FILES["capaEvento"]["name"]);                
                $check = getimagesize($_FILES["capaEvento"]["tmp_name"]);
                $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                $diretorio = $evento['idEvento']."/".basename($_FILES["capaEvento"]["name"]);

                $erro = "";

                if($check !== false) {
                    $erro .= "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    $erro = "File is not an image.";
                    $uploadOk = 0;
                }

                if ($_FILES["capaEvento"]["size"] > 500000) {
                    $erro .= "Sorry, your file is too large.";
                    $uploadOk = 0;
                }

                if ($uploadOk == 0) {
                    $erro .= "Sorry, your file was not uploaded.";
                // se estiver tudo certo ele tenta dar upload no arquivo
                } else {
                    if (move_uploaded_file($_FILES["capaEvento"]["tmp_name"], $target_file)) {
                        echo "The file ". basename( $_FILES["capaEvento"]["name"]). " has been uploaded.";
                        echo "deu certo"; 
                        $conexao->mudarDiretorioDaCapa($evento['idEvento'], $diretorio);
                    } else {
                        $erro .= "Sorry, there was an error uploading your file.";
                    }
                }
            } 
        }
    }

    header('Location: ../../');
    exit();
?>