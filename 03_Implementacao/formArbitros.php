<?php
session_start();
if (!isset($_SESSION['IDUser'])) {
    header('Location: index.php');
}
require_once( "lib/db.php" );

dbConnect(ConfigFile);
$dataBaseName = $GLOBALS['configDataBase']->db;
mysqli_select_db($GLOBALS['ligacao'], $dataBaseName);

$flags[] = FILTER_NULL_ON_FAILURE;
$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING, $flags);

$urlRefereesPath = "C:\\FootballTalentManager\\Arbitros\\";

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

$arrayOfRefereeFail = array();
$valConter = $_INPUT['valConter'];

for ($i = 1; $i <= $valConter; $i++){
    $inputFoto = $_FILES['input-foto_' . strval($i)]['tmp_name'];
    $nome = $_INPUT['input-nome_' . $i];
    $cedula = $_INPUT['input-cedula_' . $i];
    $associacao = $_INPUT['input-associacao_' . $i];

    $fotoFileName = $cedula . "_" . $nome . ".png";

    $dst = $urlRefereesPath . DIRECTORY_SEPARATOR . $fotoFileName;
    $dst = addslashes($dst);
    $isCoped = copy($inputFoto, $dst);

    if ($isCoped){
        // inserir na BD o utilizador
        $queryInsertReferee = "INSERT INTO `$dataBaseName`.`arbitro` (`Cedula`, `Nome`, `Foto`, `Associacao`, `Classificacao`)" .
                              "VALUES ('$cedula', '$nome', '$dst', '$associacao', 0);";
        $queryResult = mysqli_query($GLOBALS['ligacao'], $queryInsertReferee);

        if (!$queryResult){
            array_push($arrayOfRefereeFail, $nome);
        }
    } else {
        array_push($arrayOfRefereeFail, $nome);
    }
}

$isValid = count($arrayOfRefereeFail) === 0;
?>

