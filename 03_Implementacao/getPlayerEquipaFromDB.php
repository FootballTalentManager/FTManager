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
$BIPlayer = $_INPUT['BIPlayer'];

$queryGetPlayer = "SELECT * FROM `$dataBaseName`.`jogador` WHERE `BI` = '$BIPlayer'";
$queryResult = mysqli_query($GLOBALS['ligacao'], $queryGetPlayer);

if ($queryResult->num_rows > 0){
    while($row = mysqli_fetch_row($queryResult)){
        $telemovel = $row[1];
        $urlFoto = $row[3];
        $urlDadosXML = $row[4];

        $dadosJogadorXML = simpledom_load_file($urlDadosXML);
        $curriculo = $dadosJogadorXML;

        $nomeXml = $curriculo['nome'];
        $alcunhaXml = $curriculo['alcunha'];
        $registoIndividual = $curriculo -> fichaIndividual -> registoIndividual;

        // vamos buscar a ultima posição registada do jogador, caso não exista essa informação, não aparece nada
        $posicaoHabitual = $registoIndividual[$registoIndividual->count() - 1]['posicaoHabitual'];

        $result[] = array('telemovel' => (string)$telemovel,
            'nome' => (string)$nomeXml,
            'foto' => jpeg_to_base64($urlFoto),
            'alcunha' => (string)$alcunhaXml,
            'posicaoHabitual' => (string)$posicaoHabitual,
            'BI' => (string)$BIPlayer);
    }
}

function jpeg_to_base64($url) {
    $type = pathinfo($url, PATHINFO_EXTENSION);
    $data = file_get_contents($url);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    return $base64;
}

dbDisconnect();

echo json_encode($result);