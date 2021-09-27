<?php
ob_start();
session_start();
if (!isset($_SESSION['IDUser'])) {
    header('Location: index.php');
}
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

$IDConp = $_INPUT['input-nomeComp'];
$IDEquipa = $_INPUT['IDEquipa'];
$isValid = true;
$errorMessage = "";

$queryVerifyExist= "SELECT EXISTS(SELECT `IDCompeticao`, `IDEquipa` FROM `$dataBaseName`.`equipascompeticao` WHERE `IDCompeticao` = '$IDConp' AND `IDEquipa` = '$IDEquipa')";
$queryResultVerifyExist = mysqli_query($GLOBALS['ligacao'], $queryVerifyExist);
$exist = mysqli_fetch_array($queryResultVerifyExist)[0];

if (!$exist){
    $queryInsert = "INSERT INTO `$dataBaseName`.`equipascompeticao` (`IDCompeticao`, `IDEquipa` )" . "VALUES ('$IDConp', '$IDEquipa');";
    $queryResultInsert = mysqli_query($GLOBALS['ligacao'], $queryInsert);

    if(!$queryResultInsert){
        $isValid = false;
        $errorMessage = "Lamentamos, aconteceu algo de errado. Tente novamente.";
    }

} else {
    $isValid = false;
    $errorMessage = "Lamentamos, a equipa que pretende associa já existe na competição.";
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
        header('Refresh: 3; http://ftmanager.sytes.net/asMinhasEquipas.php');
        ?>
    </div>
</div>
</body>
</html>