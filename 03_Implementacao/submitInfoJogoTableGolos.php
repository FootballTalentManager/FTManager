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

$IDJogo = $_INPUT['IDJogo'];
$jornadaSubmit = $_INPUT['jornadaSubmit'];
$compNameSubmit = $_INPUT['compNameSubmit'];
$equipaCasaSubmit = $_INPUT['equipaCasaSubmit'];

$countTableGolos = $_INPUT['countTableGolos'];

$queryGetIDJogo = "SELECT `DadosXML` FROM `$dataBaseName`.`jogo` WHERE `IDJogo` = '$IDJogo'";
$queryResultGetIDJogo = mysqli_query($GLOBALS['ligacao'], $queryGetIDJogo);

$urlDadosXML = mysqli_fetch_row($queryResultGetIDJogo)[0];
$dadosJogoXML = simpledom_load_file($urlDadosXML);

$jogo = $dadosJogoXML;
$diretoInformacao = $jogo -> diretoInformacao;
$golos = $diretoInformacao -> eventos -> golos;
$golo = $golos -> golo;
$resultado = $diretoInformacao -> resultado;
$primeiraParte = $resultado -> primeiraParte;
$segundaParte = $resultado -> segundaParte;

$countGolos = count($golo);

for ($nRegistoGolos = $countGolos - 1; $nRegistoGolos >= 0; $nRegistoGolos--){
    if ($golo[$nRegistoGolos]['idJogador'] != ""){
        unset($golo[$nRegistoGolos]);
    }
}

$RPrimeiraParte = 0;
$RSegundaParte = 0;

for ($i = 1; $i <= $countTableGolos; $i++){
    $inputPlayerGolos = $_INPUT['input-player' . $i];
    $inputTempoGolo = $_INPUT['input-tempoGolo' . $i];

    if ((int)$inputTempoGolo <= 45 ){
        $RPrimeiraParte++;
    } else {
        $RSegundaParte++;
    }

    $new = $golos->appendChild($golo->cloneNode(true));
    $new -> setAttribute('idJogador', $inputPlayerGolos);
    $new -> setAttribute('tempo', $inputTempoGolo);
    $new -> setAttribute('autoGolo', 0);
}
$primeiraParte -> setAttribute('casa', $RPrimeiraParte);
$segundaParte -> setAttribute('casa', $RSegundaParte);

$isValid = $jogo ->asXML($urlDadosXML);
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
        $url = 'Refresh: 3; http://ftmanager.sytes.net/jogo.php?IDJogoSubmit=' . $IDJogo . '&jornadaSubmit=' . $jornadaSubmit . '&compNameSubmit=' . $compNameSubmit . '&equipaCasaSubmit=' . $equipaCasaSubmit;

        header($url);
        ?>
    </div>
</div>
</body>
</html>
