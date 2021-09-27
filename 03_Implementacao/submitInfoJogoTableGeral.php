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

$countTableGeral = $_INPUT['countTableGeral'];

$queryGetIDJogo = "SELECT `DadosXML` FROM `$dataBaseName`.`jogo` WHERE `IDJogo` = '$IDJogo'";
$queryResultGetIDJogo = mysqli_query($GLOBALS['ligacao'], $queryGetIDJogo);

$urlDadosXML = mysqli_fetch_row($queryResultGetIDJogo)[0];
$dadosJogoXML = simpledom_load_file($urlDadosXML);

$jogo = $dadosJogoXML;

$preInformacao = $jogo -> preInformacao;
$diretoInformacao = $jogo -> diretoInformacao;
$posInformacao = $jogo -> posInformacao;

$registosFinaisJogadores = $posInformacao -> registosFinaisJogadores;
$registoFinaiJogador = $registosFinaisJogadores -> registoFinaiJogador;
$cartoes = $diretoInformacao -> eventos -> cartoes;
$amarelos = $cartoes -> amarelos;
$amarelo = $amarelos -> amarelo;

$vermelhos = $cartoes -> vermelhos;
$vermelho = $vermelhos -> vermelho;

$fichaJogoJogadores = $preInformacao -> fichaJogoJogadores;
$Jogador = $fichaJogoJogadores -> jogador;

$count = count($registoFinaiJogador);
$countAmarelos = count($amarelos->amarelo);
$countVermelhos = count($vermelhos->vermelho);
$countJogadores = count($Jogador);

for ($nRegistoCartoes = $countAmarelos - 1; $nRegistoCartoes >= 0; $nRegistoCartoes--){
    if ($amarelo[$nRegistoCartoes]['idJogador'] != ""){
        unset($amarelo[$nRegistoCartoes]);
    }
}

for ($nRegistoFJ = $count - 1; $nRegistoFJ >= 0; $nRegistoFJ--){
    if ($registoFinaiJogador[$nRegistoFJ]['idJogador'] != ""){
        unset($registoFinaiJogador[$nRegistoFJ]);
    }
}

for ($nRegistoCartoes = $countVermelhos - 1; $nRegistoCartoes >= 0; $nRegistoCartoes--){
    if ($vermelho[$nRegistoCartoes]['idJogador'] != ""){
        unset($vermelho[$nRegistoCartoes]);
    }
}

for ($nJogadores = $countJogadores - 1; $nJogadores >= 0; $nJogadores--){
    if ($Jogador[$nJogadores]['idJogador'] != ""){
        unset($vermelho[$nJogadores]);
    }
}

for ($i = 1; $i <= $countTableGeral; $i++){
    $inputPlayer = $_INPUT['input-player' . $i];
    $inputClassificacao = $_INPUT['input-classificacao' . $i];
    $inputRemates = $_INPUT['input-remates' . $i];
    $inputPrimeroAmarelo = $_INPUT['input-primeroAmarelo' . $i];
    $inputSegundoAmarelo = $_INPUT['input-segundoAmarelo' . $i];
    $inputVermelho = $_INPUT['input-vermelho' . $i];
    $inputFSofridas = $_INPUT['input-fSofridas' . $i];
    $inputFCometidas = $_INPUT['input-fCometidas' . $i];

    if ($inputPrimeroAmarelo != ""){
        $new = $amarelos->appendChild($amarelo->cloneNode(true));
        $new -> setAttribute('idJogador', $inputPlayer);
        $new -> setAttribute('tempo', $inputPrimeroAmarelo);
    }

    if ($inputSegundoAmarelo != ""){
        $new = $amarelos->appendChild($amarelo->cloneNode(true));
        $new -> setAttribute('idJogador', $inputPlayer);
        $new -> setAttribute('tempo', $inputSegundoAmarelo);
    }

    if($inputVermelho != ""){
        $new = $vermelhos->appendChild($vermelho->cloneNode(true));
        $new -> setAttribute('idJogador', $inputPlayer);
        $new -> setAttribute('tempo', $inputVermelho);
    }

    $new = $fichaJogoJogadores->appendChild($Jogador->cloneNode(true));
    $new -> setAttribute('idJogador', $inputPlayer);

    $new = $registosFinaisJogadores->appendChild($registoFinaiJogador->cloneNode(true));
    $new -> setAttribute('idJogador', $inputPlayer);
    $new -> setAttribute('tempo', $inputSegundoAmarelo);
    $new -> setAttribute('idJogador', $inputPlayer);
    $new -> setAttribute('classificacao', $inputClassificacao);
    $new -> setAttribute('tempoJogado', 0);
    $new -> setAttribute('nFaltasSofridas', $inputFSofridas);
    $new -> setAttribute('nFaltasCometidas', $inputFCometidas);
    $new -> setAttribute('nRemates', $inputRemates);
}
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