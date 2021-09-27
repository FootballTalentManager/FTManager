<?php
session_start();
ob_start();
if (!isset($_SESSION['IDUser'])) {
    header('Location: index.php');
}
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
$errorMessage = null;

$NomeEpoca = $_INPUT["input-epoca"];
$escalao = $_INPUT["input-escalao"];
$nivelCompeticao = $_INPUT["input-nCompeticao"];

$queryVerifyTeamExist = "SELECT `NomeEpoca`, `Escalao`, `NivelCompeticao` FROM `$dataBaseName`.`equipa` " .
                        "WHERE `NomeEpoca`= '$NomeEpoca' AND `Escalao`='$escalao' AND `NivelCompeticao` = '$nivelCompeticao'";

$queryResult = mysqli_query($GLOBALS['ligacao'], $queryVerifyTeamExist);

if ($queryResult->num_rows > 0){
    $isValid = false;
    $errorMessage = "A equipa que tentou criar já existe";
} else{
    // inserir na BD o utilizador
    $queryInsertEquipa = "INSERT INTO `$dataBaseName`.`equipa` (`NomeEpoca`, `Escalao` ,`NivelCompeticao`)" .  "VALUES ('$NomeEpoca', '$escalao', '$nivelCompeticao');";
    $queryResult = mysqli_query($GLOBALS['ligacao'], $queryInsertEquipa);

    if (!$queryResult){
        $isValid = false;
        $errorMessage = "Erro ao tentar guardar a Equipa, tente novamente!";
    }
}
dbDisconnect();;
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
            <p><?php echo $errorMessage; ?>></p>
            <?php
        }
        header('Refresh: 3; http://ftmanager.sytes.net/gerirEquipas.php');
        ?>
    </div>
</div>
</body>
</html>
