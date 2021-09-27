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

$IDEquipa = $_INPUT['IDEquipa'];
$IDComp = $_INPUT['IDComp'];

$queryString = "SELECT * FROM `$dataBaseName`.`jogo` WHERE `IDEquipa` = '$IDEquipa' AND `IDCompeticao` = '$IDComp' ORDER BY `DataHora` ASC";
$queryResult = mysqli_query($GLOBALS['ligacao'], $queryString);

if ($queryResult->num_rows > 0) {
    while ($row = mysqli_fetch_row($queryResult)) {
        $IDJogo = $row[0];
        $IDEquipa= $row[1];
        $IDComp = $row[2];
        $DataHora = $row[3];
        $urlDadosXML = $row[4];

        $dadosJogoXML = simpledom_load_file($urlDadosXML);
        // Vamos buscar o nó geral;
        $jogo = $dadosJogoXML;

        $local = $jogo['local'];
        $esquipaFora = $jogo['esquipaFora'];

        $result[] = array("IDJogo" => $IDJogo,
                          "DataHora" => $DataHora,
                          "local" => (string)$local,
                          "equipaFora" => (string)$esquipaFora);
    }
}

dbDisconnect();

echo json_encode($result);