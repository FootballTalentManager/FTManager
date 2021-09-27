<?php
session_start();
ob_start();
if (!isset($_SESSION['IDUser'])) {
    header('Location: index.php');
}
require_once( "lib/db.php" );
session_start();
$Inputs = $_POST["hiddenField"];

$Inputs_arr = explode(",", $Inputs);

$NomeEpoca = $Inputs_arr[0];
$escalao = $Inputs_arr[1];
$nivelCompeticao = $Inputs_arr[2];
$IDTreinador = $_SESSION['IDUser'];

$isValid = true;
$errorMessage = null;

dbConnect(ConfigFile);
$dataBaseName = $GLOBALS['configDataBase']->db;
mysqli_select_db($GLOBALS['ligacao'], $dataBaseName);

$queryGetIDEquipa = "SELECT `IDEquipa` FROM `$dataBaseName`.`equipa` " . "WHERE `NomeEpoca`='$NomeEpoca' AND `Escalao`='$escalao' AND `NivelCompeticao`='$nivelCompeticao'";
$queryResult = mysqli_query($GLOBALS['ligacao'], $queryGetIDEquipa);

if ($queryResult->num_rows > 0){
    $IDEquipa = null;
    while($row = mysqli_fetch_row($queryResult)){
        $IDEquipa = $row[0];
    }

    $date = date("Y-m-d");
    $queryUpdateDataFim  = "UPDATE `$dataBaseName`.`equipatecnica` SET `DataFim` = '$date' WHERE `IDTreinador` = '$IDTreinador' AND `IDEquipa` = '$IDEquipa'";
    $queryResult = mysqli_query($GLOBALS['ligacao'], $queryUpdateDataFim);

    if (!$queryResult){
        $isValid = false;
        $errorMessage = "Lamentamos, algo aconteceu de errado. Por favor tente novamente.";
    }
} else {
    $isValid = false;
    $errorMessage = "Lamentamos, algo aconteceu de errado. Por favor tente novamente.";
}
dbDisconnect();
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
            <p>Validação realizada com sucesso</p>
            <?php

        } else {
            ?>
            <div class="notfound-404">
                <div></div>
                <h1><i class="fas fa-exclamation-triangle" style = "position: absolute !important; bottom: -0.2em !important; left: -0.55em !important;"></i></h1>
            </div>
            <h2>Perigo!!</h2>
            <p><?php echo $errorMessage; ?></p>
            <?php
        }
        header('Refresh: 3; http://ftmanager.sytes.net/asMinhasEquipas.php');
        ?>
    </div>
</div>
</body>
</html>