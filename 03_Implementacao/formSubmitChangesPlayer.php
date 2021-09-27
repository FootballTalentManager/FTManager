<?php
session_start();
ob_start();
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

$IDUser = $_SESSION['IDUser'];

$queryVerifyExistTreinador = "SELECT EXISTS(SELECT * FROM `$dataBaseName`.`treinador` WHERE `IDTreinador` = '$IDUser')";
$queryResultVerifyExist = mysqli_query($GLOBALS['ligacao'], $queryVerifyExistTreinador);
$isTreinador = mysqli_fetch_array($queryResultVerifyExist)[0];

if (!$isTreinador){
    $queryVerifyExistAdministrador = "SELECT EXISTS(SELECT * FROM `$dataBaseName`.`administrador` WHERE `IDAdministrador` = '$IDUser')";
    $queryResultVerifyExist = mysqli_query($GLOBALS['ligacao'], $queryVerifyExistAdministrador);
    $isAdministrador = mysqli_fetch_array($queryResultVerifyExist)[0];
}

$formName = $_INPUT['frmName'];
$BI = $_INPUT['BI'];

$queryGetIDEquipa = "SELECT `DadosXML` FROM `$dataBaseName`.`jogador` WHERE BI = '$BI'";
$queryResult = mysqli_query($GLOBALS['ligacao'], $queryGetIDEquipa);

$urlDadosXML = null;

$isValid = true;

if ($queryResult->num_rows > 0) {
    while ($row = mysqli_fetch_row($queryResult)) {
        $urlDadosXML = $row[0];
    }
}

if ($formName === "formInformacoes"){

    $inputMorada = $_INPUT['input-morada'];
    $inputTelemovel = $_INPUT['input-telemovel'];
    $inputNCamisola = $_INPUT['input-numero'];

    // inserir na BD o utilizador
    $queryUpdatePlayer = "UPDATE `$dataBaseName`.`jogador` SET Telemovel = '$inputTelemovel' WHERE BI = '$BI';";
    $queryResult = mysqli_query($GLOBALS['ligacao'], $queryUpdatePlayer);

    $dadosJogadorXML = simpledom_load_file($urlDadosXML);
    // Vamos buscar o nó geral;
    $curriculo = $dadosJogadorXML;

    $curriculo->setAttribute('morada', $inputMorada); // morada
    $curriculo->setAttribute('numero', $inputNCamisola); // numero

    $isValid = $dadosJogadorXML ->asXML($urlDadosXML);


} elseif ($formName === "formRegistoIndividual"){
    $inputData = $_INPUT['input-data'];
    $inputPeso = $_INPUT['input-peso'];
    $inputAltura = $_INPUT['input-altura'];
    $inputIMC = $_INPUT['input-IMC'];
    $inputPeDominante = $_INPUT['input-peDominante'];
    $inputPosicao = $_INPUT['input-posicao'];
    $inputTamanhoCamisola = $_INPUT['input-tamanhoCamisola'];
    $inputTamanhoCalcoes = $_INPUT['input-tamanhoCalcoes'];

    $dadosJogadorXML = simpledom_load_file($urlDadosXML);
    // Vamos buscar o nó geral;
    $curriculo = $dadosJogadorXML;
    $fichaIndividual = $curriculo -> fichaIndividual;
    $registoIndividual = $curriculo -> fichaIndividual -> registoIndividual;

    $Nregisto = $registoIndividual -> count();

    $dataRegisto = $registoIndividual[$Nregisto - 1]['dataRegistoFichaIndividual'];

    if($Nregisto >= 1 && $dataRegisto != "" ){
        $fichaIndividual->appendChild($registoIndividual->cloneNode(true));
        $Nregisto = $registoIndividual -> count();
    }

    $registoIndividual[$Nregisto - 1]->setAttribute('dataRegistoFichaIndividual', $inputData); //dataRegistoFichaIndividual
    $registoIndividual[$Nregisto - 1]->setAttribute('peDominante', $inputPeDominante); //peDominante
    $registoIndividual[$Nregisto - 1]->setAttribute('posicaoHabitual', $inputPosicao); //posicaoHabitual
    $registoIndividual[$Nregisto - 1]->setAttribute('peso', $inputPeso); //peso
    $registoIndividual[$Nregisto - 1]->setAttribute('altura', $inputAltura); //altura
    $registoIndividual[$Nregisto - 1]->setAttribute('imc', $inputIMC); //imc
    $registoIndividual[$Nregisto - 1]->setAttribute('camisola', $inputTamanhoCamisola); //camisola
    $registoIndividual[$Nregisto - 1]->setAttribute('calcoes', $inputTamanhoCalcoes); //calcoes

    $isValid = $dadosJogadorXML ->asXML($urlDadosXML);

} elseif ($formName === "formPerfilJogador"){

    $inputAgressividade = $_INPUT['input-Agressividade'];
    $inputCondicaoFisica = $_INPUT['input-CondicaoFisica'];
    $inputMarcacao = $_INPUT['input-Marcacao'];
    $inputTomadaDeDecisao = $_INPUT['input-TomadaDeDecisao'];
    $inputAutoConfianca = $_INPUT['input-AutoConfianca'];
    $inputCruzamentos = $_INPUT['input-Cruzamentos'];
    $inputPasse = $_INPUT['input-Passe'];
    $inputVelocicadeExecucao = $_INPUT['input-VelocicadeExecucao'];

    $inputAutoControlo = $_INPUT['input-AutoControlo'];
    $inputFinalizacao = $_INPUT['input-Finalizacao'];
    $inputPosicionamento = $_INPUT['input-Posicionamento'];
    $inputOneXoneDefensivo = $_INPUT['input-oneXoneDefensivo'];
    $inputCapacidadeTrabalho = $_INPUT['input-CapacidadeTrabalho'];
    $inputInteligenciaJogo = $_INPUT['input-InteligenciaJogo'];
    $inputRecepcao = $_INPUT['input-Recepcao'];
    $inputOneXoneOfensivo = $_INPUT['input-oneXoneOfensivo'];

    $inputCobrancaDeLivres = $_INPUT['input-CobrancaDeLivres'];
    $inputJogoCabeca = $_INPUT['input-JogoCabeca'];
    $inputResistencia = $_INPUT['input-Resistencia'];

    $dadosJogadorXML = simpledom_load_file($urlDadosXML);
    // Vamos buscar o nó geral;
    $curriculo = $dadosJogadorXML;
    $competencias = $curriculo -> competencias;
    $registoCompetencias = $competencias -> registoCompetencias;

    $registoCompetencias->setAttribute('passe', $inputPasse); //dataRegistoFichaIndividual
    $registoCompetencias->setAttribute('recepcao', $inputRecepcao); //dataRegistoFichaIndividual
    $registoCompetencias->setAttribute('finalizacao', $inputFinalizacao); //dataRegistoFichaIndividual
    $registoCompetencias->setAttribute('jogoCabeca', $inputJogoCabeca); //dataRegistoFichaIndividual
    $registoCompetencias->setAttribute('cruzamentos', $inputCruzamentos); //dataRegistoFichaIndividual
    $registoCompetencias->setAttribute('marcacao', $inputMarcacao); //dataRegistoFichaIndividual
    $registoCompetencias->setAttribute('cobrancaDeLivres', $inputCobrancaDeLivres); //dataRegistoFichaIndividual
    $registoCompetencias->setAttribute('umParaUmDefensivo', $inputOneXoneDefensivo); //dataRegistoFichaIndividual
    $registoCompetencias->setAttribute('umParaUmOfensivo', $inputOneXoneOfensivo); //dataRegistoFichaIndividual
    $registoCompetencias->setAttribute('posicionamento', $inputPosicionamento); //dataRegistoFichaIndividual
    $registoCompetencias->setAttribute('tomadaDecisao', $inputTomadaDeDecisao); //dataRegistoFichaIndividual
    $registoCompetencias->setAttribute('velocidadeExecucao', $inputVelocicadeExecucao); //dataRegistoFichaIndividual
    $registoCompetencias->setAttribute('capacidadeDeTrabalho', $inputCapacidadeTrabalho); //dataRegistoFichaIndividual
    $registoCompetencias->setAttribute('agressividade', $inputAgressividade); //dataRegistoFichaIndividual
    $registoCompetencias->setAttribute('autoConfianca', $inputAutoConfianca); //dataRegistoFichaIndividual
    $registoCompetencias->setAttribute('inteligenciaEmJogo', $inputInteligenciaJogo); //dataRegistoFichaIndividual
    $registoCompetencias->setAttribute('autoControlo', $inputAutoControlo); //dataRegistoFichaIndividual
    $registoCompetencias->setAttribute('condicaoFisica', $inputCondicaoFisica); //dataRegistoFichaIndividual
    $registoCompetencias->setAttribute('resistenciaLesoes', $inputResistencia); //dataRegistoFichaIndividual

    $isValid = $dadosJogadorXML ->asXML($urlDadosXML);
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
            <p>Alterações realizadas com sucesso!</p>
            <?php
        } else {
            ?>
            <div class="notfound-404">
                <div></div>
                <h1><i class="fas fa-exclamation-triangle" style = "position: absolute !important; bottom: -0.2em !important; left: -0.55em !important;"></i></h1>
            </div>
            <h2>Perigo!!</h2>
            <p>Algo aconteceu de errado, por faver tente novamente!</p>
            <?php
        }
        $arg = 'hiddenField=' . $BI . '&isTreinador=' . $isTreinador;
        header('Refresh: 3; http://ftmanager.sytes.net/perfilJogador.php?' . $arg);
        ?>
    </div>
</div>
</body>
</html>