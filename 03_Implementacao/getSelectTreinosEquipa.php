<?php
session_start();
require_once( "lib/db.php" );
if (!isset($_SESSION['IDUser'])) {
    header('Location: index.php');
}

dbConnect( ConfigFile );
$dataBaseName = $GLOBALS['configDataBase']->db;
mysqli_select_db( $GLOBALS['ligacao'], $dataBaseName );

$flags[] = FILTER_NULL_ON_FAILURE;
$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING, $flags);

if ($method == 'POST'){
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
$IDEquipaSelected = $_INPUT['IDEquipa'];

$isTreinador = false;
$isAdministrador = false;

$queryVerifyExistTreinador = "SELECT EXISTS(SELECT * FROM `$dataBaseName`.`treinador` WHERE `IDTreinador` = '$IDUser')";
$queryResultVerifyExist = mysqli_query($GLOBALS['ligacao'], $queryVerifyExistTreinador);
$isTreinador = mysqli_fetch_array($queryResultVerifyExist)[0];

if (!$isTreinador){
    $queryVerifyExistAdministrador = "SELECT EXISTS(SELECT * FROM `$dataBaseName`.`administrador` WHERE `IDAdministrador` = '$IDUser')";
    $queryResultVerifyExist = mysqli_query($GLOBALS['ligacao'], $queryVerifyExistAdministrador);
    $isAdministrador = mysqli_fetch_array($queryResultVerifyExist)[0];
}

if ($isTreinador){
    $queryString = "SELECT `IDPlano`, `DataHora` FROM `$dataBaseName`.`planotreino` WHERE `IDEquipa` = '$IDEquipaSelected' AND `IDTreinador` = '$IDUser'";
} elseif ($isAdministrador){
    $queryString = "SELECT `IDPlano`, `DataHora` FROM `$dataBaseName`.`planotreino` WHERE `IDEquipa` = '$IDEquipaSelected'";
}

$queryResult = mysqli_query( $GLOBALS['ligacao'], $queryString );

if ( $queryResult ) {
    $result[] = array( 'idPlano'=>0, 'dataHora'=>"-- Selecione o treino --" );

    while ($registo = mysqli_fetch_array($queryResult)) {
        $result[] = array(
            'idPlano'=>$registo[0],
            'dataHora'=>$registo[1]);
    }
} else {
    $errDesc = mysqli_error( $GLOBALS['ligacao'] );
    $errNumber = mysqli_errno( $GLOBALS['ligacao']  );

    $result[] = array(
        'idPlano'=>-1,
        'dataHora'=>"No Counties Available" );
    $result[] = array(
        'idPlano'=>-$errNumber,
        'dataHora'=>$errDesc );
}

dbDisconnect();
echo json_encode( $result );

