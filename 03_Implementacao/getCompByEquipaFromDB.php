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

$queryCompName = "SELECT competicao.IDCompeticao, Nome FROM `$dataBaseName`.competicao JOIN `$dataBaseName`.equipascompeticao ON competicao.IDCompeticao = equipascompeticao.IDCompeticao WHERE equipascompeticao.IDEquipa = '$IDEquipa'";
$queryResult = mysqli_query($GLOBALS['ligacao'], $queryCompName);

if ($queryResult->num_rows > 0){
    $result[] = array( 'CompName'=> "-- Selecione o Competição --" , 'CompID'=> 0);
    while($row = mysqli_fetch_row($queryResult)){
        $name = $row[1];
        $ID = $row[0];
        $result[] = array('CompName' => (string)$name, 'CompID' => (string)$ID);
    }
} else {
    $result[] = array('CompName' => "",
                      'CompID' => "");
}

dbDisconnect();
echo json_encode($result);