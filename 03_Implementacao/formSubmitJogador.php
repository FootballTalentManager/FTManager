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

$urlPlayersPath = "C:\\FootballTalentManager\\Jogadores\\";

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

$valConter = $_INPUT['valConter'];
$isValid = true;
$errorMessage = "";
$errorPlayersArray = array();

for ($i = 1; $i <= $valConter; $i++){
    $inputBI = $_INPUT['input-BI_' . $i];
    $inputFoto = $_FILES['input-foto_' . $i]['tmp_name'];
    $inputNome = $_INPUT['input-nome_' . $i];
    $inputAlcunha = $_INPUT['input-alcunha_' . $i];
    $inputNacionalidade = $_INPUT['input-country_' . $i];
    $inputDataNascimento = $_INPUT['input-dataNascimento_' . $i];
    $inputMorada = $_INPUT['input-morada_' . $i];
    $inputTelemovel = $_INPUT['input-phone_' . $i];
    $inputNCamisola = $_INPUT['input-nCamisola_' . $i];

    $urlPlayerPath = $urlPlayersPath . $inputBI . "_" . $inputNome;

    if(!file_exists($urlPlayerPath)){
        mkdir($urlPlayerPath);

        $fotoFileName = $inputBI . "_" . $inputNome . ".png";
        $dstFoto = $urlPlayerPath . DIRECTORY_SEPARATOR . $fotoFileName;
        $dstFoto = addslashes($dstFoto);
        $isCoped = copy($inputFoto, $dstFoto);

        // =================== XML ===================
        $dadosJogadorXML = simpledom_load_file("DadosXML/Jogador.xml");

        // Vamos buscar o nó geral;
        $curriculo = $dadosJogadorXML;
        // set Attribute
        $curriculo->setAttribute('bilheteIdentidade', $inputBI); // bilheteIdentidade
        $curriculo->setAttribute('nome', $inputNome); // nome
        $curriculo->setAttribute('alcunha', $inputAlcunha); // alcunha
        $curriculo->setAttribute('morada', $inputMorada); // morada
        $curriculo->setAttribute('nacionalidade', $inputNacionalidade); // nacionalidade
        $curriculo->setAttribute('dataNascimento', $inputDataNascimento); // dataNascimento
        $curriculo->setAttribute('numero', $inputNCamisola); // numero

        $dadosFileName = addslashes($urlPlayerPath . DIRECTORY_SEPARATOR . "xmlDados.xml");
        $dadosJogadorXML ->asXML($dadosFileName);

        // inserir na BD o utilizador
        $queryInsertReferee = "INSERT INTO `$dataBaseName`.`jogador` (`Telemovel`, `BI`, `Foto`, `DadosXML`)" .
            "VALUES ('$inputTelemovel', '$inputBI', '$dstFoto', '$dadosFileName');";
        $queryResult = mysqli_query($GLOBALS['ligacao'], $queryInsertReferee);

        if (!$queryResult){
            array_push($errorPlayersArray, $inputNome);
        }
    } else {
        array_push($errorPlayersArray, $inputNome);
    }
}

if (count($errorPlayersArray) > 0){
    $isValid = false;
    $errorMessage = "Lamentamos, mas o jogador(es) ";

    for ($i = 0; $i < count($errorPlayersArray); $i++){
        $errorMessage = $errorMessage . $errorPlayersArray[$i] . ", ";
    }

    $errorMessage = $errorMessage . "já existe(em)";
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
        header('Refresh: 3; http://ftmanager.sytes.net/todosOsJogadores.php');
        ?>
    </div>
</div>
</body>
</html>
