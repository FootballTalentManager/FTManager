<?php
ob_start();
require_once( "lib/db.php" );
$isValid = false;
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

// Vamos buscar o token ao URL
$token = $_INPUT["token"];

// Establecimento da ligação à base de dados
dbConnect(ConfigFile);
$dataBaseName = $GLOBALS['configDataBase']->db;
mysqli_select_db($GLOBALS['ligacao'], $dataBaseName);

// Vamos buscar o idUser com o token recebido
$queryGetChallenge = "SELECT `IDUtilizador` FROM `$dataBaseName`.`challenge` " . "WHERE `challenge`='$token'";
$queryResult = mysqli_query($GLOBALS['ligacao'], $queryGetChallenge);

if ($queryResult) {
    $idUser = mysqli_fetch_array($queryResult)[0];

    // Fazemos Update na BD
    $queryUpdateUserActive = "UPDATE `$dataBaseName`.`utilizador` SET `Active` = '1' WHERE `IDUtilizador` = '$idUser'";
    $queryResult = mysqli_query($GLOBALS['ligacao'], $queryUpdateUserActive);

    if ($queryResult) {
        $queryDeleteChallenge = "DELETE FROM `$dataBaseName`.`challenge` WHERE `challenge`='$token'";
        $queryResult = mysqli_query($GLOBALS['ligacao'], $queryDeleteChallenge);

        if (mysqli_affected_rows($GLOBALS['ligacao'])) {
            $isValid = true;
        } else {
            $isValid = false;
        }
    }
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
                    <p>Validação realizada com sucesso. Será reencaminhado para a pagina principal, para puder fazer login.</p>
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
                header('Refresh: 3; http://ftmanager.sytes.net');
                ?>
            </div>
        </div>
    </body>
</html>

