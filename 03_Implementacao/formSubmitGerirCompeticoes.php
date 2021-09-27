<?php
session_start();
if (!isset($_SESSION['IDUser'])) {
    header('Location: index.php');
}
ob_start();
include 'SimpleDOM/SimpleDOM.php';
require_once( "lib/db.php" );

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

$isValid = true;
$errorMessage = "Lamentamos mas, ";
$competicaoExistArray = array();
$epocaExistArray = array();
$valConter = $_INPUT['valConter'];

for ($i = 1; $i <= $valConter; $i++) {
    $epoca = $_INPUT["input-epoca_" . $i];
    $escalao = $_INPUT["input-escalao_" . $i];
    $nomeCompeticao = $_INPUT["input-nomeCompeticao_" . $i];

    $queryGetIDEpoca = "SELECT `IDEpoca` FROM `$dataBaseName`.`epoca` WHERE `NomeEpoca` = '$epoca'";
    $queryResult = mysqli_query($GLOBALS['ligacao'], $queryGetIDEpoca);

    if ($queryResult->num_rows > 0) {
        while ($row = mysqli_fetch_row($queryResult)) {
            $IDEpoca = $row[0];

            $queryVerifytreinoExist= "SELECT EXISTS(SELECT `IDEpoca`, `Escalao`, `Nome` FROM `$dataBaseName`.`competicao` WHERE `IDEpoca` = '$IDEpoca' AND `Escalao` = '$escalao' AND `Nome` = '$nomeCompeticao')";
            $queryResultVerifytreinoExist = mysqli_query($GLOBALS['ligacao'], $queryVerifytreinoExist);
            $exist = mysqli_fetch_array($queryResultVerifytreinoExist)[0];

            if (!$exist){
                // inserir na BD o utilizador
                $queryInsertPlantel = "INSERT INTO `$dataBaseName`.`competicao` (`IDEpoca`, `Escalao`, `Nome`)" . "VALUES ('$IDEpoca', '$escalao', '$nomeCompeticao');";
                $queryResultInsertPlantel = mysqli_query($GLOBALS['ligacao'], $queryInsertPlantel);
            } else {
                array_push($competicaoExistArray, $nomeCompeticao);
            }
        }
    } else {
        array_push($competicaoExistArry, $epoca);
    }
}

if (count($competicaoExistArray) > 0 || count($epocaExistArray) > 0){
    $isValid = false;
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
            <p>UPS ocorreu um erro inesperado, tente novamente...</p>
            <?php
        }
        header('Refresh: 3; http://ftmanager.sytes.net/gerirCompeticao.php');
        ?>
    </div>
</div>
</body>
</html>