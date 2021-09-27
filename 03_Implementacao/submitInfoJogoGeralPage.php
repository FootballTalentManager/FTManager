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

$inputTotalGoalsAwayFirst = $_INPUT['input-totalGoalsAwayFirst'];
$inputTotalGoalsAwaySecond = $_INPUT['input-totalGoalsAwaySecond'];
$selectFormationHome = $_INPUT['selectFormationHome'];
$selectFormationAway = $_INPUT['selectFormationAway'];

$inputArbitroPrincipal = $_INPUT['input-ArbitroPrincipal'];
$inputArbitroAss1 = $_INPUT['input-ArbitroAss1'];
$inputArbitroAss2 = $_INPUT['input-ArbitroAss2'];
$inputQuartoArbitro = $_INPUT['input-QuartoArbitro'];

$inputArbitroPrincipalClass = $_INPUT['input-ArbitroPrincipalClass'];
$inputArbitroAss1Class = $_INPUT['input-ArbitroAss1Class'];
$inputArbitroAss2Class = $_INPUT['input-ArbitroAss2Class'];
$inputQuartoArbitroClass = $_INPUT['input-QuartoArbitroClass'];

$queryGetIDJogo = "SELECT `DadosXML` FROM `$dataBaseName`.`jogo` WHERE `IDJogo` = '$IDJogo'";
$queryResultGetIDJogo = mysqli_query($GLOBALS['ligacao'], $queryGetIDJogo);

$urlDadosXML = mysqli_fetch_row($queryResultGetIDJogo)[0];
$dadosJogoXML = simpledom_load_file($urlDadosXML);

$jogo = $dadosJogoXML;

$preInformacao = $jogo -> preInformacao;
$diretoInformacao = $jogo -> diretoInformacao;
$posInformacao = $jogo -> posInformacao;

$registosFinaisArbitros = $posInformacao -> registosFinaisArbitros;
$registoFinalArbitro = $registosFinaisArbitros -> registoFinalArbitro;
$countFA = count($registoFinalArbitro);

$resultado = $diretoInformacao -> resultado;
$primeiraParte = $resultado -> primeiraParte;
$segundaParte = $resultado -> segundaParte;

$formacaoTatica = $preInformacao -> formacaoTatica;
$equipaArbitragem = $preInformacao -> equipaArbitragem;

$equipaArbitragem = $preInformacao -> equipaArbitragem;

for($nRegistoSubs = $countFA - 1; $nRegistoSubs >= 0; $nRegistoSubs--){
    if ($registoFinalArbitro[$nRegistoSubs]['idArbitro'] != ""){
        unset($registoFinalArbitro[$nRegistoSubs]);
    }
}

$equipaArbitragem -> setAttribute('idArbitroPrincipal', $inputArbitroPrincipal);
$equipaArbitragem -> setAttribute('idArbitroAss1', $inputArbitroAss1);
$equipaArbitragem -> setAttribute('idArbitroAss2', $inputArbitroAss2);
$equipaArbitragem -> setAttribute('idQuartoArbitro', $inputQuartoArbitro);

if ($inputArbitroPrincipal != ""){
    $new = $registosFinaisArbitros->appendChild($registoFinalArbitro->cloneNode(true));
    $new -> setAttribute('idArbitro', $inputArbitroPrincipal);
    $new -> setAttribute('classificacao', $inputArbitroPrincipalClass);
}

if ($inputArbitroAss1 != "") {
    $new = $registosFinaisArbitros->appendChild($registoFinalArbitro->cloneNode(true));
    $new->setAttribute('idArbitro', $inputArbitroAss1);
    $new->setAttribute('classificacao', $inputArbitroAss1Class);
}
if ($inputArbitroAss2 != "") {
    $new = $registosFinaisArbitros->appendChild($registoFinalArbitro->cloneNode(true));
    $new->setAttribute('idArbitro', $inputArbitroAss2);
    $new->setAttribute('classificacao', $inputArbitroAss2Class);
}
if ($inputQuartoArbitro != "") {
    $new = $registosFinaisArbitros->appendChild($registoFinalArbitro->cloneNode(true));
    $new->setAttribute('idArbitro', $inputQuartoArbitro);
    $new->setAttribute('classificacao', $inputQuartoArbitroClass);
}

$primeiraParte -> setAttribute('fora', $inputTotalGoalsAwayFirst);
$segundaParte -> setAttribute('fora', $inputTotalGoalsAwaySecond);

$selectFormationHome = explode("/", $selectFormationHome);
$selectFormationHome = $selectFormationHome[2];
$selectFormationHome = explode(".", $selectFormationHome);
$formacaoTatica -> setAttribute('formacaoCasa', $selectFormationHome[0]);

$selectFormationAway = explode("/", $selectFormationAway);
$selectFormationAway = $selectFormationAway[2];
$selectFormationAway = explode(".", $selectFormationAway);
$formacaoTatica -> setAttribute('formacaoFora', $selectFormationAway[0]);

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

