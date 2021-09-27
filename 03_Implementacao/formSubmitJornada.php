<?php
session_start();
ob_start();
include 'SimpleDOM/SimpleDOM.php';
require_once( "lib/db.php" );

if (!isset($_SESSION['IDUser'])) {
    header('Location: index.php');
}

dbConnect(ConfigFile);
$dataBaseName = $GLOBALS['configDataBase']->db;
mysqli_select_db($GLOBALS['ligacao'], $dataBaseName);

$flags[] = FILTER_NULL_ON_FAILURE;

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING, $flags);

if ($method == 'POST') {
    $_INPUT_METHOD = INPUT_POST;
    $_INPUT = $_POST;
} elseif ($method == 'GET') {
    $_INPUT_METHOD = INPUT_GET;
    $_INPUT = $_GET;
} else {
    echo "Invalid HTTP method (" . $method . ")";
    exit();
}

$IDTeam = $_INPUT["IDTeam"];
$IDComp = $_INPUT["IDComp"];
$valConter = $_INPUT["valConter"];

$isValid = true;
$errorMessage = "";

for ($i = 1; $i <= $valConter; $i++){
    $inputDateTime = date("Y-m-d H:i:s", strtotime($_INPUT["input-dateTime_" . $i]));
    $inputNameAway = $_INPUT["input-NameAway_" . $i];
    $inputLocal = $_INPUT["input-local_" . $i];

    $queryVerifytreinoExist= "SELECT EXISTS(SELECT * FROM `$dataBaseName`.`jogo` WHERE `IDCompeticao` = '$IDComp' AND `IDEquipa` = '$IDTeam' AND DataHora = '$inputDateTime')";
    $queryResult = mysqli_query($GLOBALS['ligacao'], $queryVerifytreinoExist);
    $exist = mysqli_fetch_array($queryResult)[0];

    if (!$exist){
        $queryInsertJogo = "INSERT INTO `$dataBaseName`.`jogo` (`IDEquipa`, `IDCompeticao`, `DataHora`, `DadosXML`) VALUES ('$IDTeam', '$IDComp', '$inputDateTime', 'null')";
        $create = mysqli_query($GLOBALS['ligacao'], $queryInsertJogo);

        if ($create){
            $queryGetIDJogo = "SELECT `IDJogo` FROM `$dataBaseName`.`jogo` WHERE `IDCompeticao` = '$IDComp' AND `IDEquipa` = '$IDTeam' AND DataHora = '$inputDateTime'";
            $queryResultGetIDJogo = mysqli_query($GLOBALS['ligacao'], $queryGetIDJogo);

            if ($queryResultGetIDJogo->num_rows > 0) {
                while ($row = mysqli_fetch_row($queryResultGetIDJogo)) {
                    $IDJogo = $row[0];
                    $urlUserFolder = "C:\\FootballTalentManager\\Jogos\\" . $IDJogo;
                    if(!file_exists($urlUserFolder)){
                        mkdir($urlUserFolder);

                        $dadosFileName = addslashes($urlUserFolder . DIRECTORY_SEPARATOR . "xmlDados.xml");

                        $dadosJogoXML = simpledom_load_file("DadosXML/Jogo.xml");
                        // Vamos buscar o nó geral;
                        $jogo = $dadosJogoXML;

                        $jogo->setAttribute('local', $inputLocal); // Local do Jogo
                        $jogo->setAttribute('equipaCasa', $IDTeam); // ID equipa da Casa
                        $jogo->setAttribute('esquipaFora', $inputNameAway); // Nome equipa de fora

                        $jogo ->asXML($dadosFileName);

                        $queryUpdate = "UPDATE `$dataBaseName`.`jogo` SET `DadosXML` = '$dadosFileName' WHERE `IDJogo` = '$IDJogo'";
                        $update = mysqli_query($GLOBALS['ligacao'], $queryUpdate);
                    }else{
                        $isValid = false;
                        $errorMessage = "lamentamos, ocorreu um erro, tente novamente";
                    }
                }
            } else{
                $isValid = false;
                $errorMessage = "lamentamos, ocorreu um erro, tente novamente";
            }
        } else{
            $isValid = false;
            $errorMessage = "lamentamos, ocorreu um erro, tente novamente";
        }
    } else {
        $isValid = false;
        $errorMessage = "lamentamos, mas a jornada já existe";
    }
} ?>

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
        header('Refresh: 3; http://ftmanager.sytes.net/calendario.php');
        ?>
    </div>
</div>
</body>
</html>


