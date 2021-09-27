<?php
session_start();
ob_start();
include 'SimpleDOM/SimpleDOM.php';
require_once( "lib/db.php" );

dbConnect(ConfigFile);
$dataBaseName = $GLOBALS['configDataBase']->db;
mysqli_select_db($GLOBALS['ligacao'], $dataBaseName);

if (!isset($_SESSION['IDUser'])) {
    header('Location: index.php');
}

$IDUser = $_SESSION['IDUser'];
$userFolder = $_SESSION['userFolder'];

$urlUserFolder = "C:\\FootballTalentManager\\Treinadores\\" . $userFolder;
$urlTreinos = "C:\\FootballTalentManager\\Treinadores\\" . $userFolder . "\\Treinos";
if(file_exists($urlUserFolder)){
    if(!file_exists($urlTreinos)){
        mkdir($urlTreinos);
    }
} else {
    mkdir($urlUserFolder);
    if(!file_exists($urlTreinos)){
        mkdir($urlTreinos);
    }
}

$flags[] = FILTER_NULL_ON_FAILURE;

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING, $flags);

if ($method == 'POST'){
    $_INPUT_METHOD = INPUT_POST;
    $_INPUT = $_POST;
} elseif ($method == 'GET') {
    $_INPUT_METHOD = INPUT_GET;
    $_INPUT = $_GET;
} else {
    echo "Invalid HTTP method (" . $method . ")";
    exit();
}

$isValid = true;
$errorMessage = "";

$IDEquipa = $_INPUT["input-equipa"];
$inputMicrociclos = $_INPUT["input-Microciclos"];
$inputMesociclos = $_INPUT["input-Mesociclos"];
$inputPeriodo = $_INPUT["input-periodo"];
$inputDataHora = date("Y-m-d H:i:s", strtotime($_INPUT["input-DataHora"]));
$inputVolume = $_INPUT["input-Volume"];
$inputCounter = $_INPUT["inputCounter"];

$queryVerifytreinoExist= "SELECT EXISTS(SELECT `IDTreinador`, `IDEquipa`, `DataHora` FROM `$dataBaseName`.`planotreino` WHERE `IDTreinador` = '$IDUser' AND `IDEquipa` = '$IDEquipa' AND DataHora = '$inputDataHora')";
$queryResult = mysqli_query($GLOBALS['ligacao'], $queryVerifytreinoExist);
$exist = mysqli_fetch_array($queryResult)[0];

if (!$exist){
    $queryCreatTreino = "INSERT INTO `$dataBaseName`.`planotreino` (`IDTreinador`, `IDEquipa`, `DataHora`, `DadosXML`) VALUES ('$IDUser', '$IDEquipa', '$inputDataHora', null)";
    $create = mysqli_query($GLOBALS['ligacao'], $queryCreatTreino);

    if($create){
        $queryGetIDPlano = "SELECT `IDPlano` FROM `$dataBaseName`.`planotreino` WHERE `IDTreinador` = '$IDUser' AND `IDEquipa` = '$IDEquipa' AND DataHora = '$inputDataHora'";
        $queryResult = mysqli_query($GLOBALS['ligacao'], $queryGetIDPlano);

        if ($queryResult->num_rows > 0) {
            $IDPlanoTreino = null;
            while ($row = mysqli_fetch_row($queryResult)) {
                $IDPlanoTreino = $row[0];
            }

            $urlPlanoTreino = $urlTreinos . DIRECTORY_SEPARATOR . $IDPlanoTreino;

            if(!file_exists($urlPlanoTreino)){
                mkdir($urlPlanoTreino);
            }

            // =================== XML ===================
            $planotreino = simpledom_load_file("DadosXML/Treinos.xml");

            // Vamos buscar o nó geral;
            $general = $planotreino->geral;
            // set Attribute
            $general->setAttribute('microciclos', $inputMicrociclos); // microciclos
            $general->setAttribute('mesaciclos', $inputMesociclos); // mesociclos
            $general->setAttribute('periodo', $inputPeriodo); // periodo
            $general->setAttribute('volume', $inputVolume); // volume

            // Vamos buscar o nó geral;
            $treinos = $planotreino->treinos;
            $treinosEspecifico = $treinos->treinoEspecifio;

            for ($n = 0; $n < $inputCounter; $n++){
                if ($n !== 0 && $n !== $inputCounter)
                    $new = $treinos->appendChild($treinosEspecifico->cloneNode(true));

                $exTitle = $_INPUT["ex_title_" . ($n+1)];
                $treinosEspecifico[$n]->setAttribute('titulo', $exTitle);

                $tempo = $_INPUT["tempo_" . ($n+1)];
                $treinosEspecifico[$n]->setAttribute('tempo', $tempo);

                $nJogadores = $_INPUT["n-jogadores_" . ($n+1)];
                $treinosEspecifico[$n]->setAttribute('nJogadores', $nJogadores);

                // Save Img
                $canvasImg = $_INPUT["inputCanvasImag_" . ($n+1)];
                $img = str_replace('data:image/png;base64,', '', $canvasImg);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $dst = $urlPlanoTreino . DIRECTORY_SEPARATOR . ($n+1) . ".jpg";
                $success = file_put_contents($dst, $data);
                $treinosEspecifico[$n]->imagem->setAttribute('url', $dst);

                $objetivo = $_INPUT["objectivosEspecificos_" . ($n+1)];
                $treinosEspecifico[$n]->objetivoEspecifico->setAttribute('descricao', $objetivo);

                $descricao = $_INPUT["descricao_" . ($n+1)];
                $treinosEspecifico[$n]->descricaoOrganizacaoMetodologica->setAttribute('descricao', $descricao);
            }

            $dadosFileName = addslashes($urlPlanoTreino . DIRECTORY_SEPARATOR . "xmlDados.xml");
            // save
            $planotreino ->asXML($dadosFileName);

            $queryUpdate = "UPDATE `$dataBaseName`.`planotreino` SET `DadosXML` = '$dadosFileName' WHERE `IDPlano` = '$IDPlanoTreino'";
            $update = mysqli_query($GLOBALS['ligacao'], $queryUpdate);

            if (!$update){
                $isValid = false;
                $errorMessage = "Algo aconteceu de errado, por favor tente novamente.";
            }
        }
    }
} else {
    $isValid = false;
    $errorMessage = "Lamentamos, mas já existe um treino para a data/hora indicada";
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Cabin:400,700" rel="stylesheet">

    <!-- Custom stlylesheet -->
    <link rel="stylesheet" href="css/faileSucessPage.css">
    <style>
        <?php include("css/faileSucessPage.css")?>
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body>
<div id="notfound">
    <div class="notfound">
        <?php
        if ($isValid) {
            ?>
            <div class="success">
                <div></div>
                <h1><i class="far fa-thumbs-up" style = "position: absolute; bottom: -0.2em; left: -0.4em;"></i></h1>
            </div>
            <h2>Sucesso!!</h2>
            <p>Registo Realizado com Sucesso</p>
            <?php
        } else {
            ?>
            <div class="notfound-404">
                <div></div>
                <h1><i class="fas fa-exclamation-triangle" style = "position: absolute !important; bottom: -0.2em !important; left: -0.55em !important;"></i></h1>
            </div>
            <h2>Perigo!!</h2>
            <p>UPS ocorreu um erro inesperado.  <?php echo $errorMessage?></p>
            <?php
        }
        header('Refresh: 3; http://ftmanager.sytes.net/treinos.php');
        ?>
    </div>
</div>
</body>
</html>
