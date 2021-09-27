<?php
session_start();
include 'SimpleDOM/SimpleDOM.php';
require_once("lib/db.php");
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

$IDUser = $_SESSION['IDUser'];
$IDPlano = $_INPUT['IDPlano'];

$queryString = "SELECT `DadosXML` FROM `$dataBaseName`.`planotreino` WHERE `IDPlano` = '$IDPlano'";
$queryResult = mysqli_query($GLOBALS['ligacao'], $queryString);

if ($queryResult) {
    while ($plano = mysqli_fetch_array($queryResult)) {
        $linkDadosXML = $plano[0];
        $planoTreinoXML = simpledom_load_file($linkDadosXML);

        $general = $planoTreinoXML->geral;

        $result[] = array('volume' => (string)$general['volume']);
        $result[] = array('periodo' => (string)$general['periodo']);
        $result[] = array('mesaciclos' => (string)$general['mesaciclos']);
        $result[] = array('microciclos' => (string)$general['microciclos']);


        $treinos = $planoTreinoXML->treinos;
        $treinosEspecificos = $treinos->treinoEspecifio;

        foreach ($treinosEspecificos as $treinoEspecifico) {
            $nJogadores = $treinoEspecifico['nJogadores'];
            $tempo = $treinoEspecifico['tempo'];
            $titulo = $treinoEspecifico['titulo'];

            $urlImg = $treinoEspecifico->imagem['url'];

            $objectivoEspecifico = $treinoEspecifico->objetivoEspecifico['descricao'];

            $descricaoOrganizacaoMetodologica = $treinoEspecifico->descricaoOrganizacaoMetodologica['descricao'];

            $result[] = array('nJogadores' => (string)$nJogadores,
                              'tempo' => (string)$tempo,
                              'titulo' => (string)$titulo,
                              'urlImg' => jpeg_to_base64($urlImg),
                              'objectivoEspecifico' => (string)$objectivoEspecifico,
                              'descricaoOrganizacaoMetodologica' => (string)$descricaoOrganizacaoMetodologica);
        }
    }
} else {
    $errDesc = mysqli_error($GLOBALS['ligacao']);
    $errNumber = mysqli_errno($GLOBALS['ligacao']);

    $result[] =  array('dadosXML' =>  "b");
}

dbDisconnect();

echo json_encode($result);

function jpeg_to_base64($url) {
    $type = pathinfo($url, PATHINFO_EXTENSION);
    $data = file_get_contents($url);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    return $base64;
}