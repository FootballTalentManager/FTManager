<?php
session_start();
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

$isValid = true;

$IDEquipa = $_INPUT['IDEquipa'];

$queryDeleteplanotreino = "DELETE FROM `$dataBaseName`.`planotreino` WHERE `IDEquipa`='$IDEquipa'";
$queryResult = mysqli_query($GLOBALS['ligacao'], $queryDeleteplanotreino);

$queryDeleteequipascompeticao = "DELETE FROM `$dataBaseName`.`equipascompeticao` WHERE `IDEquipa`='$IDEquipa'";
$queryResult = mysqli_query($GLOBALS['ligacao'], $queryDeleteequipascompeticao);

$queryDeleteequipatecnica = "DELETE FROM `$dataBaseName`.`equipatecnica` WHERE `IDEquipa`='$IDEquipa'";
$queryResult = mysqli_query($GLOBALS['ligacao'], $queryDeleteequipatecnica);

$queryDeletejogo = "DELETE FROM `$dataBaseName`.`jogo` WHERE `IDEquipa`='$IDEquipa'";
$queryResult = mysqli_query($GLOBALS['ligacao'], $queryDeletejogo);

$queryDeleteplantel = "DELETE FROM `$dataBaseName`.`plantel` WHERE `IDEquipa`='$IDEquipa'";
$queryResult = mysqli_query($GLOBALS['ligacao'], $queryDeleteplantel);

$queryDeleteequipa = "DELETE FROM `$dataBaseName`.`equipa` WHERE `IDEquipa`='$IDEquipa'";
$queryResult = mysqli_query($GLOBALS['ligacao'], $queryDeleteequipa);

if (mysqli_affected_rows($GLOBALS['ligacao'])) {
    $isValid = true;
} else {
    $isValid = false;
}

dbDisconnect();
echo json_encode($isValid);