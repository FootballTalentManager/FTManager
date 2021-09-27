<?php
session_start();
ob_start();
if (!isset($_SESSION['IDUser'])) {
    header('Location: index.php');
}
require_once( "lib/db.php" );
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

    // Verifica se o utilizador é treinador
    $queryVerifyTreinador = "SELECT EXISTS(SELECT `IDTreinador` FROM `$dataBaseName`.`treinador` WHERE `IDTreinador` = '$IDTreinador')";
    $queryResult = mysqli_query($GLOBALS['ligacao'], $queryVerifyTreinador);

    if ($queryResult->num_rows > 0) {
        $isTreinador = null;
        while ($row = mysqli_fetch_row($queryResult)) {
            $isTreinador =  $row[0];
        }
        if ($isTreinador){
            $date = date("Y-m-d");
            // Verifica se o utilizador é treinador
            $queryInsertTreinadorET = "INSERT INTO `$dataBaseName`.`equipatecnica` (`IDEquipa`, `IDTreinador` ,`DataInicio` ,`DataFim`)" .
                                    " VALUES ('$IDEquipa', '$IDTreinador', '$date', null);";
            $queryResult = mysqli_query($GLOBALS['ligacao'], $queryInsertTreinadorET);

            if (!$queryResult) {
                $isValid = false;
                $errorMessage = "Lamentamos, algo aconteceu de errado. Por favor tente novamente.";
            }
        } else {
            $isValid = false;
            $errorMessage = "Lamentamos, mas não é um treinador válido.";
        }
    }
} else {
    $isValid = false;
    $errorMessage = "Lamentamos, algo aconteceu de errado. Por favor tente novamente Geral.";
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
    <link type="text/css" rel="stylesheet" href="css/faileSucessPage.css" />

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
            <p>Validação realizada com sucesso.</p>
            <?php
            header('Refresh: 2; http://ftmanager.sytes.net/todasAsEquipas.php');
        } else {
            ?>
            <div class="notfound-404">
                <div></div>
                <h1><i class="fas fa-exclamation-triangle" style = "position: absolute !important; bottom: -0.2em !important; left: -0.55em !important;"></i></h1>
            </div>
            <h2>Perigo!!</h2>
            <p><?php echo $errorMessage; ?></p>
            <?php
            header('Refresh: 2; http://ftmanager.sytes.net/asMinhasEquipas.php');
        }
        ?>
    </div>
</div>
</body>
</html>