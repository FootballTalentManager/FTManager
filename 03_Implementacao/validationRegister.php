<?php
ob_start();
require_once("lib/emailConf.php");
require_once( "lib/db.php" );

// ===================================== Email Account =======================================
function sendEmail($inputEmail, $inputNome, $Token){
    $accountName = "Football Talent Manager - Support";
    $accountSmtpServer = "smtp.gmail.com";
    $accountPort = 465;
    $accountUseSSL = 1;
    $accountTimeOut = 30;
    $accountLoginName = "footballtalentmanager@gmail.com";
    $accountPassword = "project2021";
    $accountEmail = "footballtalentmanager@gmail.com";
    $accountDisplayNameEmailConfirmation = "Football Talent Manager Support - Confirmation Email";

    $Subject = "Football Talent Manager - Validação do email.";

    $Message = "Caro(a) {$inputNome}, desde já agradecemos o seu registo. \n" .
        "De forma a validar a sua conta, entre no link que se segue. \n" .
        "http://ftmanager.sytes.net/validationEmail.php/url?token={$Token}\n \n" .
        "Com os melhores cumprimentos, \n" .
        "Equipa de suporte DocoWiki. \n";

    $isSendEmail = sendAuthEmail($accountSmtpServer, $accountUseSSL, $accountPort, $accountTimeOut, $accountLoginName, $accountPassword, $accountEmail, $accountName,
        $inputNome . " <" . $inputEmail . ">", NULL, NULL, $Subject, $Message, FALSE, NULL);

    return $isSendEmail;
}
// ============================================================================================

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

$inputUserType = $_INPUT["inputUserType"];
if ($inputUserType === "treinador")
    $inputLicencaDesportiva = $_INPUT["inputCedulaDesportiva"];
$inputNome = $_INPUT["inputNome"];
$inputEmail = $_INPUT["inputEmail"];
$inputTelemovel = $_INPUT["inputTelemovel"];
$inputPassword = $_INPUT["inputPassword"];
$var = $_INPUT["inputBithdayDate"];
$date = str_replace('/', '-', $var);
$inputDataNascimento = date('Y-m-d', strtotime($date));
$inputGenero = $_INPUT["inputGenero"];

$regexPassword = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}/";
$regexNome = "/^(?=[a-zA-Z ]{3,125}$)/";

if (!preg_match($regexNome, $inputNome)){
    $isValid = false;
    $errorMessage = "Erro na validação do nome, por favor tente novamente!";
} else if (!preg_match($regexPassword, $inputPassword)){
    $isValid = false;
    $errorMessage = "Erro na validação da password, por favor tente novamente!";
} else {
    // ================================== Ligação BD ====================================
    // Ligação BD
    dbConnect(ConfigFile);
    $dataBaseName = $GLOBALS['configDataBase']->db;
    mysqli_select_db($GLOBALS['ligacao'], $dataBaseName);

    // inserir na BD o utilizador
    $queryInsertUser = "INSERT INTO `$dataBaseName`.`utilizador` (`Email`, `Telefone`, `DataNascimento`, `Nome` ,`Genero`, `Password` ,`Active`)" .
        "VALUES ('$inputEmail', '$inputTelemovel', '$inputDataNascimento', '$inputNome', '$inputGenero', '$inputPassword', false);";

    $queryResult = mysqli_query($GLOBALS['ligacao'], $queryInsertUser);

    if ($queryResult){
        $queryidUser = "SELECT `IDUtilizador` FROM `$dataBaseName`.`utilizador` " . "WHERE `Email`='$inputEmail'";
        $queryResult = mysqli_query($GLOBALS['ligacao'], $queryidUser);
        $idUser = mysqli_fetch_array($queryResult)[0];
        if ($idUser != null && $inputUserType !== null &&  $inputUserType === "treinador"){
            // inserir na BD o utilizador
            $queryInsertTreinador = "INSERT INTO `$dataBaseName`.`treinador` (`IDTreinador`, `LicencaDesportiva`)" .
                "VALUES ('$idUser', '$inputLicencaDesportiva');";
            $queryResult = mysqli_query($GLOBALS['ligacao'], $queryInsertTreinador);

            if ($queryResult){
                $lenToken = 10 - strlen((string)$idUser);
                $Token = $idUser . str_replace(" ", "*", getRandomWord($lenToken));

                // inserir na BD o utilizador
                $queryInsertChalleng = "INSERT INTO `$dataBaseName`.`challenge` (`IDUtilizador`, `challenge`)" . "VALUES ('$idUser', '$Token');";
                $queryResult = mysqli_query($GLOBALS['ligacao'], $queryInsertChalleng);

                if ($queryResult){
                    $isSendEmail = sendEmail($inputEmail, $inputNome, $Token);
                    if($isSendEmail){
                        if(file_exists("C:\\FootballTalentManager")){
                            mkdir("C:\\FootballTalentManager\\Treinadores\\" . $idUser . "_" . $inputNome);
                        }
                    } else {
                        $isValid = false;
                        $errorMessage = "Erro ao enviar email de confimação, por favor tente novamente!";
                    }
                } else {
                    $isValid = false;
                    $errorMessage = "Erro no Utilizador, por favor tente novamente!";
                }
            } else {
                $isValid = false;
                $errorMessage = "Erro ao registar o treinador, por favor tente novamente!";
            }
        } else if ($idUser != null && $inputUserType !== null && $inputUserType === "administrador"){
            // inserir na BD o utilizador
            $queryInsertAdministrador = "INSERT INTO `$dataBaseName`.`administrador` (`IDAdministrador`)" . "VALUES ('$idUser');";
            $queryResult = mysqli_query($GLOBALS['ligacao'], $queryInsertAdministrador);

            if ($queryResult){
                $lenToken = 10 - strlen((string)$idUser);
                $Token = $idUser . str_replace(" ", "*", getRandomWord($lenToken));

                // inserir na BD o utilizador
                $queryInsertChalleng = "INSERT INTO `$dataBaseName`.`challenge` (`IDUtilizador`, `challenge`)" . "VALUES ('$idUser', '$Token');";
                $queryResult = mysqli_query($GLOBALS['ligacao'], $queryInsertChalleng);

                if ($queryResult){
                    $isSendEmail = sendEmail($inputEmail, $inputNome, $Token);
                    if(!$isSendEmail){
                        $isValid = false;
                        $errorMessage = "Erro ao enviar email de confimação, por favor tente novamente!";
                    }
                } else {
                    $isValid = false;
                    $errorMessage = "Erro no registo, por favor tente novamente!";
                }

            } else {
                $isValid = false;
                $errorMessage = "Erro no registo, por favor tente novamente!";
            }
        } else {
            $isValid = false;
            $errorMessage = "Erro no registo, por favor tente novamente!";
        }
    } else {
        $isValid = false;
        $errorMessage = "Erro no registo, por favor tente novamente!";
    }
}
dbDisconnect();
function getRandomWord($len = 10) {
    $word = array_merge(range('a', 'z'), range('A', 'Z'));
    shuffle($word);
    return substr(implode($word), 0, $len);
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
                    <p>Foi-lhe enviado um email para validar o seu registo.</p>
                    <?php
                } else {
                    ?>
                    <div class="notfound-404">
                        <div></div>
                        <h1><i class="fas fa-exclamation-triangle" style = "position: absolute !important; bottom: -0.2em !important; left: -0.55em !important;"></i></h1>
                    </div>
                    <h2>Perigo!!</h2>
                    <p>Algo aconteceu de errado, por faver tente novamente</p>
                    <?php
                }
                header('Refresh: 3; http://ftmanager.sytes.net');
                ?>
            </div>
        </div>
    </body>
</html>