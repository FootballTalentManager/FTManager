<?php
session_start();
ob_start();
require_once( "lib/db.php" );
dbConnect(ConfigFile);
$dataBaseName = $GLOBALS['configDataBase']->db;
mysqli_select_db($GLOBALS['ligacao'], $dataBaseName);

$captcha = $_SESSION['captchaValue'];
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

$inputUser = $_INPUT["inputUser"];
$inputPassword = $_INPUT["inputPassword"];
$inputCaptcha = $_INPUT["inputCaptcha"];

$isValid = false;
$messageError = null;
if ($inputCaptcha === $captcha){
    $queryGetEquipas = "SELECT `IDUtilizador`, `Nome` FROM `$dataBaseName`.`utilizador` WHERE `Email` = '$inputUser' AND  `Password` = '$inputPassword' AND `Active` = '1' ";
    $queryResult = mysqli_query($GLOBALS['ligacao'], $queryGetEquipas);
    if ($queryResult->num_rows > 0){
        while($row = mysqli_fetch_row($queryResult)){
            $_SESSION['IDUser'] = $row[0];
            $_SESSION['userFolder'] = $row[0] . "_" . $row[1];
            $isValid = true;
        }
    } else{
        $isValid = false;
        $messageError = "Algo de errado aconteceu, tente novamente.";
    }
} else {
    $isValid = false;
    $messageError = "O captcha introduzido está incorrecto, por favor tente novamente.";
}?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Cabin:400,700" rel="stylesheet">

    <!-- Custom stlylesheet -->
    <link type="text/css" rel="stylesheet" href="css/faileSucessPage.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body>
<div id="notfound">
    <div class="notfound">
        <?php
        if ($isValid) {
            header('Refresh: 0; dashBoard.php');
        } else {
            ?>
            <div class="notfound-404">
                <div></div>
                <h1><i class="fas fa-exclamation-triangle" style = "position: absolute !important; bottom: -0.2em !important; left: -0.55em !important;"></i></h1>
            </div>
            <h2>Perigo!!</h2>
            <p><?php echo $messageError;?></p>
            <?php
            header('Refresh: 2; index.php');
        }
        ?>
    </div>
</div>
</body>
</html>