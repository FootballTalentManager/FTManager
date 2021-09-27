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

$isValid = true;

$IDEquipa = $_INPUT['IDEquipa'];
$valConter = $_INPUT['valConterPlantel'];

for ($i = 1; $i <= $valConter; $i++) {
    $inputBI = $_INPUT["input-BI_" . $i];

    $queryGetIDPlayer = "SELECT * FROM `$dataBaseName`.`jogador` WHERE `BI` = '$inputBI'";
    $queryResult = mysqli_query($GLOBALS['ligacao'], $queryGetIDPlayer);

    if ($queryResult->num_rows > 0) {
        while ($row = mysqli_fetch_row($queryResult)) {
            $IDPlayer = $row[0];

            $queryVerifytreinoExist= "SELECT EXISTS(SELECT `IDEquipa`, `IDJogador` FROM `$dataBaseName`.`plantel` WHERE `IDEquipa` = '$IDEquipa' AND `IDJogador` = '$IDPlayer')";
            $queryResultVerifytreinoExist = mysqli_query($GLOBALS['ligacao'], $queryVerifytreinoExist);
            $exist = mysqli_fetch_array($queryResultVerifytreinoExist)[0];

            if (!$exist){
                // inserir na BD o utilizador
                $queryInsertPlantel = "INSERT INTO `$dataBaseName`.`plantel` (`IDEquipa`, `IDJogador`)" .
                    "VALUES ('$IDEquipa', '$IDPlayer');";
                $queryResultInsertPlantel = mysqli_query($GLOBALS['ligacao'], $queryInsertPlantel);

                if (!$queryResultInsertPlantel){
                    $isValid = false;
                }
            } else {
                $isValid = false;
            }
        }
    }
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
        header('Refresh: 3; http://ftmanager.sytes.net/asMinhasEquipas.php');
        ?>
    </div>
</div>
</body>
</html>
