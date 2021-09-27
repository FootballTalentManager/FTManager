<?php
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

$IDJogo = $_INPUT['IDJogoSubmit'];
$jornadaSubmit = $_INPUT['jornadaSubmit'];
$compNameSubmit = $_INPUT['compNameSubmit'];
$equipaCasaSubmit = $_INPUT['equipaCasaSubmit'];

$queryGetIDJogo = "SELECT * FROM `$dataBaseName`.`jogo` WHERE IDJogo = '$IDJogo'";
$queryResult = mysqli_query($GLOBALS['ligacao'], $queryGetIDJogo);
if ($queryResult->num_rows > 0) {
    while ($row = mysqli_fetch_row($queryResult)) {
        $IDEquipa= $row[1];
        $IDComp = $row[2];
        $DataHora = explode(" ", $row[3]);
        $urlDadosXML = $row[4];

        $dadosJogoXML = simpledom_load_file($urlDadosXML);
        $jogo = $dadosJogoXML;

        $local = $jogo['local'];
        $esquipaFora = $jogo['esquipaFora'];
    }
}
$isTreinador = false;
$isAdministrador = false;
$IDUser = $_SESSION['IDUser'];

$queryVerifyExistTreinador = "SELECT EXISTS(SELECT * FROM `$dataBaseName`.`treinador` WHERE `IDTreinador` = '$IDUser')";
$queryResultVerifyExist = mysqli_query($GLOBALS['ligacao'], $queryVerifyExistTreinador);
$isTreinador = mysqli_fetch_array($queryResultVerifyExist)[0];

if (!$isTreinador){
    $queryVerifyExistAdministrador = "SELECT EXISTS(SELECT * FROM `$dataBaseName`.`administrador` WHERE `IDAdministrador` = '$IDUser')";
    $queryResultVerifyExist = mysqli_query($GLOBALS['ligacao'], $queryVerifyExistAdministrador);
    $isAdministrador = mysqli_fetch_array($queryResultVerifyExist)[0];
}

$queryGetNomesJogadores = "SELECT jogador.IDJogador, DadosXML FROM `$dataBaseName`.`plantel` JOIN `$dataBaseName`.`jogador` ON plantel.IDJogador = jogador.IDJogador Where `IDEquipa` = '$IDEquipa'";
$queryResultNomesJogadores = mysqli_query($GLOBALS['ligacao'], $queryGetNomesJogadores);

$playersArray = array();
array_push($playersArray, array(0, "- Selecione o Jogador -"));
if ($queryResultNomesJogadores->num_rows > 0) {
    while ($row = mysqli_fetch_row($queryResultNomesJogadores)) {
        $IDJogador= $row[0];
        $urlDadosXML= $row[1];
        $dadosJogadorXML = simpledom_load_file($urlDadosXML);
        $curriculo = $dadosJogadorXML;
        $nomeJogador = $curriculo['nome'];
        array_push($playersArray, array($IDJogador, (string)$nomeJogador));
    }
}

$queryGetNomesJogadores = "SELECT IDArbitro, Nome FROM `$dataBaseName`.`arbitro`";
$queryResultNomesJogadores = mysqli_query($GLOBALS['ligacao'], $queryGetNomesJogadores);

$refereeArray = array();
if ($queryResultNomesJogadores->num_rows > 0) {
    while ($row = mysqli_fetch_row($queryResultNomesJogadores)) {
        $IDArbitro = $row[0];
        $nomeArbitro = $row[1];
        array_push($refereeArray, array($IDArbitro, $nomeArbitro));
    }
}
$countPlayersTableGeral = 0;
$countTableGolos = 0;
$countTableSubs = 0;

$preInformacao = $jogo -> preInformacao;
$diretoInformacao = $jogo -> diretoInformacao;
$posInformacao = $jogo -> posInformacao;

$resultado = $diretoInformacao -> resultado;
$primeiraParte = $resultado -> primeiraParte;
$segundaParte = $resultado -> segundaParte;

$registosFinaisArbitros = $posInformacao -> registosFinaisArbitros;
$registoFinalArbitro = $registosFinaisArbitros -> registoFinalArbitro;


$formacaoTatica = $preInformacao -> formacaoTatica;

$resultTotalCasa = 0;
$resultTotalFora = 0;
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <link rel="icon" href="img/Logos/Icon/IconVermelho.png">
    <title>Football Talent Manager - Jogo</title>

    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/botoesTreino.css" rel="stylesheet" />
    <link href="css/mySelect.css" rel="stylesheet" />
    <link href="css/jogo.css" rel="stylesheet" />

    <!-- Icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://code.iconify.design/2/2.0.3/iconify.min.js"></script>

    <!-- data Table -->
    <link href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.datatables.net/1.11.0/css/dataTables.bootstrap5.min.css" rel="stylesheet"/>

    <link rel="stylesheet" href="css/footer-distributed-with-address-and-phones.css">
</head>

<body class="sb-nav-fixed">
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark" style="padding-top: 2.2rem !important;
      padding-bottom: 2.2rem !important;">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="dashBoard.php"><img src="img/Logos/Logo/LogoCinza.png" alt="login" style="height: 45px !important; width: auto !important;"></a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" onclick="columnsAdjustDT()"><i class="fas fa-bars"></i></button>
    <!-- Navbar-->
    <div class="d-md-inline-block ms-auto me-0 me-md-3 my-2 my-md-0">
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading"><p><span class="iconify-inline" data-icon="bx:bx-menu-alt-left" data-width="15" data-height="15"></span> MENU</p></div>
                    <?php if ($isAdministrador){ ?>
                        <a class="nav-link" href="gerirEquipas.php">
                            <h6><span class="iconify-inline" data-icon="eos-icons:content-lifecycle-management"
                                      data-width="20" data-height="20"></span> Gerir Equipas</h6>
                        </a>
                        <a class="nav-link" href="gerirCompeticao.php">
                            <h6><span class="iconify-inline" data-icon="whh:managedhosting" data-width="20"
                                      data-height="20"></span> Gerir Competições</h6>
                        </a>
                        <a class="nav-link" href="treinos.php">
                            <h6><span class="iconify-inline" data-icon="ps:data-board" data-width="20" data-height="20"></span> Consultar Treinos</h6>
                        </a>
                        <a class="nav-link" href="calendario.php">
                            <h6><span class="iconify-inline" data-icon="akar-icons:calendar" data-width="20" data-height="20"></span> Consultar Calendário</h6>
                        </a>
                        <a class="nav-link" href="todosOsJogadores.php">
                            <h6><span class="iconify-inline" data-icon="fluent:people-audience-20-regular" data-width="20" data-height="20"></span>
                                Gerir Todos os Jogadores</h6>
                        </a>
                        <a class="nav-link" href="arbitros.php">
                            <h6><span class="iconify-inline" data-icon="bpmn:user" data-width="20" data-height="20"></span>
                                Gerir Árbitros</h6>
                        </a>
                    <?php } elseif ($isTreinador){?>
                        <a class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts" style="cursor: pointer;">
                            <h6 style="cursor: pointer;"><span class="iconify-inline" data-icon="bi:list-check" data-width="20" data-height="20"></span>   Equipas</h6>
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="todasAsEquipas.php">
                                    <h6><span class="iconify-inline" data-icon="fluent:mail-inbox-all-24-regular" data-width="20" data-height="20"></span>   Todas as Equipas</h6>
                                </a>
                                <a class="nav-link" href="asMinhasEquipas.php">
                                    <h6><span class="iconify-inline" data-icon="bi:shield-shaded" data-width="20" data-height="20"></span>   As minhas Equipas</h6>
                                </a>
                            </nav>
                        </div>
                        <a class="nav-link" href="treinos.php">
                            <h6><span class="iconify-inline" data-icon="ps:data-board" data-width="20" data-height="20"></span>   Treinos</h6>
                        </a>
                        <a class="nav-link" href="calendario.php">
                            <h6><span class="iconify-inline" data-icon="akar-icons:calendar" data-width="20" data-height="20"></span>   Calendário</h6>
                        </a>
                        <a class="nav-link" href="todosOsJogadores.php">
                            <h6><span class="iconify-inline" data-icon="fluent:people-audience-20-regular" data-width="20" data-height="20"></span>   Todos os Jogadores</h6>
                        </a>
                        <a class="nav-link" href="arbitros.php">
                            <h6><span class="iconify-inline" data-icon="bpmn:user" data-width="20" data-height="20"></span>   Árbitros</h6>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </nav>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <div class="row" style=" padding-bottom: 1rem;   border-bottom: 2px solid black;">
                    <div class="col-md-auto">
                        <h2 class="mt-4"><span class="iconify-inline" data-icon="feather:airplay" data-width="30" data-height="30"></span>  Ficha de Jogo</h2>
                    </div>

                    <?php  if ($isTreinador){?>
                    <div class="col-md-auto" style="margin-top: 2rem !important;">
                        <button type="button" class="skewBtn black" id="editarJogo" onclick="changeToEditMode()">Editar</button>
                    </div>
                    <div class="col-md-auto" style="margin-top: 2rem !important;">
                        <button type="button" class="skewBtn black" id="salvarJogo" disabled onclick="submitGeralPage()">Salvar</button>
                    </div>
                    <?php } elseif ($isAdministrador) { ?>
                        <div class="col-md-auto" style="margin-top: 2rem !important;">
                            <button type="button" class="skewBtn black" id="editarJogo" hidden>Editar</button>
                        </div>
                        <div class="col-md-auto" style="margin-top: 2rem !important;">
                            <button type="button" class="skewBtn black" id="salvarJogo" hidden>Salvar</button>
                        </div>
                    <?php } ?>

                </div>

                <div class="row center-info"  style="padding-top: 1rem">
                    <div class="col-md-8" id="resizeCar">
                        <div class="card">
                            <div class="card-header">
                                <h6><span class="iconify-inline" data-icon="fluent:book-information-24-filled" data-width="15" data-height="15"></span>  Informações do jogo</h6>
                            </div>
                            <div class="card-body">
                                <!-- Row informacoes  -->
                                <div class="row br-t" style="display: flex; align-items: center; border: 2px solid black;  color: white; background-color: #5D6D7E; padding: 0 !important">
                                    <!-- Nome/Jornada  -->
                                    <div class="row center-info" style="border-bottom: 2px solid black;">
                                        <!-- nome/  -->
                                        <div class="col-md-6 text-center" style="border-right: 2px solid black; color: white; background-color: #5D6D7E">
                                            <h6><?php echo $compNameSubmit; ?></h6>
                                        </div>
                                        <!-- jornada  -->
                                        <div class="col-md-6 text-center">
                                            <h6>Jornada: <?php echo $jornadaSubmit ?></h6>
                                        </div>
                                    </div>

                                    <!-- data/hora  -->
                                    <div class="row center-info" style="color: white; background-color: #5D6D7E">
                                        <!-- data  -->
                                        <div class="col-md-3 text-end text">
                                            <h6><span class="iconify-inline" data-icon="bi:calendar-event" data-width="15" data-height="15"></span> Data:</h6>
                                        </div>
                                        <div class="col-md-3">
                                            <h6><?php echo $DataHora[0]; ?></h6>
                                        </div>
                                        <!-- hora  -->
                                        <div class="col-md-3 text-end">
                                            <h6><span class="iconify-inline" data-icon="akar-icons:clock" data-width="15" data-height="15"></span> Hora:</h6>
                                        </div>
                                        <div class="col-md-3">
                                            <h6><?php echo $DataHora[1]; ?></h6>
                                        </div>
                                    </div>
                                </div>

                                <form method="post" action="submitInfoJogoGeralPage.php" name="formGeral">
                                    <input type="hidden" name="IDJogo" value="<?php echo $IDJogo ?>">
                                    <input type="hidden" name="jornadaSubmit" value="<?php echo $jornadaSubmit;?>">
                                    <input type="hidden" name="compNameSubmit" value="<?php echo $compNameSubmit;?>">
                                    <input type="hidden" name="equipaCasaSubmit" value="<?php echo $equipaCasaSubmit;?>">
                                    <!-- Row informacoes  -->
                                    <div class="row br-b" style="display: flex; align-items: center; border-bottom: 2px solid black; border-left: 2px solid black; border-right: 2px solid black;">
                                        <div class="col text-center">
                                            <div class="row">
                                                <div class="col-sm-2"><span class="iconify" data-icon="carbon:home" data-width="30" data-height="30"></span></div>
                                                <div class="col-sm-10"><h5><?php echo $equipaCasaSubmit ?></h5></div>
                                            </div>
                                        </div>

                                        <div class="col-md-3" style="border-right: 2px solid black; border-left: 2px solid black; background-color: #E5E8E8;">
                                            <div class="row text-center">
                                                <div class="col-md-7 text-center" style="padding: 0 !important;">
                                                    <h6>1ª Parte </h6>
                                                </div>
                                                <div class="col-md-2 text-center" style="padding: 0 !important; margin: 0">
                                                    <input type="number" id="input-totalGoalsHomeFirst" name="input-totalGoalsHomeFirst" value="<?php if($primeiraParte['casa'] != ""){ echo $primeiraParte['casa']; $resultTotalCasa+=(int)$primeiraParte['casa'];} else{ echo 0;} ?>" disabled style="width:100% !important; height: 20px; border: 0; font-weight: bold; text-align: center; background:#E5E8E8;">
                                                </div>
                                                <div class="col-md-1 text-center" style="padding: 0 !important; font-weight: bold;">
                                                    <h5> - </h5>
                                                </div>
                                                <div class="col-md-2" style="padding: 0 !important;">
                                                    <input type="number" id="input-totalGoalsAwayFirst" name="input-totalGoalsAwayFirst" value="<?php if($primeiraParte['fora'] != ""){ echo $primeiraParte['fora']; $resultTotalFora+=(int)$primeiraParte['fora'];} else{ echo 0;} ?>" min="0" disabled onblur="setScoreAway()" style="width:100% !important; height: 20px; border: 0; font-weight: bold; text-align: center; background:#E5E8E8;">
                                                </div>
                                            </div>

                                            <div class="row text-center">
                                                <div class="col-md-7 text-center" style="padding: 0 !important;">
                                                    <h6>2ª Parte </h6>
                                                </div>
                                                <div class="col-md-2 text-center" style="padding: 0 !important;">
                                                    <input type="number" id="input-totalGoalsHomeSecond" name="input-totalGoalsHomeSecond" value="<?php if($segundaParte['casa'] != ""){ echo $segundaParte['casa']; $resultTotalCasa+=(int)$segundaParte['casa'];} else{ echo 0;} ?>" disabled style="width:100% !important; height: 20px; border: 0; font-weight: bold; text-align: center;  background:#E5E8E8;">
                                                </div>
                                                <div class="col-md-1 text-center" style="padding: 0 !important;">
                                                    <h5> - </h5>
                                                </div>
                                                <div class="col-md-2 text-center" style="padding: 0 !important;">
                                                    <input type="number" id="input-totalGoalsAwaySecond"  name="input-totalGoalsAwaySecond" value="<?php if($segundaParte['fora'] != ""){ echo $segundaParte['fora']; $resultTotalFora+=(int)$segundaParte['fora'];} else{ echo 0;} ?>" min="0" disabled onblur="setScoreAway()" style="width:100% !important; height: 20px; border: 0; font-weight: bold; text-align: center; background:#E5E8E8;">
                                                </div>
                                            </div>

                                            <div class="row text-center">
                                                <div class="col-md-5 text-center" style="padding: 0 !important;">
                                                    <input type="number" id="input-totalGoalsHome" value="<?php echo $resultTotalCasa?>" disabled style="width:100% !important; height: 20px; border: 0; font-weight: bold; text-align: right; background:#E5E8E8;">
                                                </div>
                                                <div class="col-md-2" style="padding: 0 !important; font-weight: bold;">
                                                    <h5 style="text-align: right"> - </h5>
                                                </div>
                                                <div class="col-md-5 text-center" style="padding: 0 !important;">
                                                    <input type="number" id="input-totalGoalsAway" value="<?php echo $resultTotalFora?>" disabled style="width:100% !important; height: 20px; border: 0; font-weight: bold; text-align: center; background:#E5E8E8;">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col text-center">
                                            <div class="row">
                                                <div class="col-sm-2"><span class="iconify" data-icon="ant-design:home-filled" data-width="30" data-height="30"></span></div>
                                                <div class="col-sm-10"><h5><?php echo $esquipaFora ?></h5></div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row" style="margin-top: 1rem">
                                        <!-- Casa  -->
                                        <div class="col text-center" >
                                            <div class="row-cols-auto">
                                                <img id="imgFormationHome" name="imgFormationHome" src="img/campoInteiro.jpg" alt="" style="width:100%; height:100%">
                                            </div>
                                            <div class="row-cols-auto">
                                                <select name="selectFormationHome" id="selectFormationHome" disabled required onchange="changeFormtionHome(this)" style="width: 90%; height: 30px; opacity:100; z-index: 999; margin-top: 0.5rem !important; background:#F2F3F4;">
                                                    <option value="" selected disabled>-- Selecione a Formação --</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2 text-center">
                                            <div class="row text-start br-t" style="border: 2px solid black; background-color: #E5E8E8;">
                                                <h6>Árbitro:</h6>
                                            </div>
                                            <div class="row text-center" style="border-left: 2px solid black; border-right: 2px solid black">
                                                <div class="col-md-9" style="padding: 0">
                                                    <select class="form-select" id="input-ArbitroPrincipal" name="input-ArbitroPrincipal" size="1" disabled required style="width: 100%; height: 40px; opacity:100; position:relative; left:0; top:0; z-index: 999; border: 0; margin: 0; padding: 0; font-size:10px; font-weight: bold; text-align: center; background:white;"> ' +
                                                        <option value="" disabled selected>- Selecione Árbitro -</option>
                                                        <?php
                                                            for ($i = 0; $i < count($refereeArray); $i++){
                                                                ?>
                                                                <option value="<?php echo $refereeArray[$i][0]?>" <?php if(count($registoFinalArbitro) > 1 && (int)$refereeArray[$i][0] === (int)$registoFinalArbitro[1]['idArbitro']) {echo "selected";} ?>><?php echo $refereeArray[$i][1]?></option>
                                                                <?php
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3" style="padding: 0">
                                                    <input type="number" id="input-ArbitroPrincipalClass" name="input-ArbitroPrincipalClass" value="<?php echo (int)$registoFinalArbitro[1]['classificacao'];?>" min="0" max="10" disabled required style="width:100% !important; height: 100%; border: 0; font-weight: bold; font-size:12px; text-align: center; padding: 0; margin: 0; background:#E5E8E8;">
                                                </div>
                                            </div>
                                            <div class="row text-start" style="border: 2px solid black; background-color: #E5E8E8">
                                                <h6>Assistentes:</h6>
                                            </div>
                                            <div class="row text-center" style="border-left: 2px solid black; border-right: 2px solid black">
                                                <div class="col-md-9" style="padding: 0">
                                                    <select class="form-select" id="input-ArbitroAss1" name="input-ArbitroAss1" size="1" disabled required style="width: 100%; height: 40px; opacity:100; position:relative; left:0; top:0; z-index: 999; border: 0; margin: 0; padding: 0; font-size:10px; font-weight: bold; text-align: center; background:white;"> ' +
                                                        <option value="">- Selecione Árbitro -</option>
                                                        <?php
                                                        for ($i = 0; $i < count($refereeArray); $i++){
                                                            ?>
                                                            <option value="<?php echo $refereeArray[$i][0]?>" <?php if(count($registoFinalArbitro) > 1 && (int)$refereeArray[$i][0] === (int)$registoFinalArbitro[2]['idArbitro']) echo "selected"; ?>><?php echo $refereeArray[$i][1]?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3" style="padding: 0">
                                                    <input type="number" id="input-ArbitroAss1Class" name="input-ArbitroAss1Class" value="<?php echo (int)$registoFinalArbitro[2]['classificacao'];?>" min="0" max="10" disabled required style="width:100% !important; height: 100%; border: 0; font-weight: bold; font-size:12px; text-align: center; padding: 0; margin: 0; background:#E5E8E8;">
                                                </div>
                                            </div>
                                            <div class="row text-center" style="border-left: 2px solid black; border-right: 2px solid black">
                                                <div class="col-md-9" style="padding: 0">
                                                    <select class="form-select" id="input-ArbitroAss2" name="input-ArbitroAss2" size="1" disabled required style="width: 100%; height: 40px; opacity:100; position:relative; left:0; top:0; z-index: 999; border: 0; margin: 0; padding: 0; font-size:10px; font-weight: bold; text-align: center; background:white;"> ' +
                                                        <option value="">- Selecione Árbitro -</option>
                                                        <?php
                                                        for ($i = 0; $i < count($refereeArray); $i++){
                                                            ?>
                                                            <option value="<?php echo $refereeArray[$i][0]?>" <?php if(count($registoFinalArbitro) > 1 && (int)$refereeArray[$i][0] === (int)$registoFinalArbitro[3]['idArbitro']) echo "selected"; ?>><?php echo $refereeArray[$i][1]?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3" style="padding: 0">
                                                    <input type="number" id="input-ArbitroAss2Class" name="input-ArbitroAss2Class" value="<?php echo (int)$registoFinalArbitro[3]['classificacao'];?>" min="0" max="10" disabled required style="width:100% !important; height: 100%; border: 0; font-weight: bold; font-size:12px; text-align: center; padding: 0; margin: 0; background:#E5E8E8;">
                                                </div>
                                            </div>
                                            <div class="row text-start" style="border: 2px solid black; background-color: #E5E8E8">
                                                <h6>4º Arbitro:</h6>
                                            </div>
                                            <div class="row text-center br-b" style="border-bottom: 2px solid black; border-left: 2px solid black; border-right: 2px solid black;">
                                                <div class="col-md-9" style="padding: 0">
                                                    <select class="form-select" id="input-QuartoArbitro" name="input-QuartoArbitro" size="1" disabled required style="width: 100%; height: 40px; opacity:100; position:relative; left:0; top:0; z-index: 999; border: 0; margin: 0; padding: 0; font-size:10px; font-weight: bold; text-align: center; background:white;"> ' +
                                                        <option value="">- Selecione Árbitro -</option>
                                                        <?php
                                                        for ($i = 0; $i < count($refereeArray); $i++){
                                                            ?>
                                                            <option value="<?php echo $refereeArray[$i][0]?>" <?php if(count($registoFinalArbitro) > 1 && (int)$refereeArray[$i][0] === (int)$registoFinalArbitro[4]['idArbitro']) echo "selected"; ?>><?php echo $refereeArray[$i][1]?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3" style="padding: 0">
                                                    <input type="number" id="input-QuartoArbitroClass" name="input-QuartoArbitroClass" value="<?php echo (int)$registoFinalArbitro[4]['classificacao'];?>" min="0" max="10" disabled required style="width:100% !important; height: 100%; border: 0; font-weight: bold; font-size:12px; text-align: center; padding: 0; margin: 0; background:#E5E8E8;">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col text-center">
                                            <div class="row-cols-auto">
                                                <img id="imgFormationAway" name="imgFormationAway" src="img/campoInteiro.jpg" alt="" style="width:100%; height:100%">
                                            </div>
                                            <div class="row-cols-auto">
                                                <select name="selectFormationAway" id="selectFormationAway" disabled required onchange="changeFormtionAway(this)" style="width: 90%; height: 30px; opacity:100; z-index: 999; margin-top: 0.5rem !important; background:#F2F3F4;">
                                                    <option value="" selected disabled>-- Selecione a Formação --</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="row-cols-auto" style="margin-top: 2rem">
                                    <form method="post" action="submitInfoJogoTableGeral.php" name="submitTableGeral">
                                        <input type="hidden" name="IDJogo" value="<?php echo $IDJogo ?>">
                                        <input type="hidden" id="countTableGeral" name="countTableGeral">
                                        <input type="hidden" name="jornadaSubmit" value="<?php echo $jornadaSubmit;?>">
                                        <input type="hidden" name="compNameSubmit" value="<?php echo $compNameSubmit;?>">
                                        <input type="hidden" name="equipaCasaSubmit" value="<?php echo $equipaCasaSubmit;?>">

                                        <table id="tableGeral" class="table compact nowrap" style="width: 100% !important;">
                                            <thead style="color: white; background-color: #34495E">
                                            <tr style="color: white; background-color: #34495E">
                                            <tr>
                                                <th rowspan="2"> Nº</th>
                                                <th rowspan="2"> Jogador</th>
                                                <th rowspan="2"> Cla</th>
                                                <th rowspan="2"> Remates</th>
                                                <th colspan="3"> Tempo</th>
                                                <th colspan="2"> Nº Faltas </th>
                                            </tr>
                                            <tr>
                                                <th><span class="iconify-inline" data-icon="mdi:card" style="color: #ffe600;" data-width="20" data-height="20" data-rotate="90deg"></span></span></th>
                                                <th><span class="iconify-inline" data-icon="mdi:card" style="color: #ffe600;" data-width="20" data-height="20" data-rotate="90deg"></span></span></th>
                                                <th><span class="iconify-inline" data-icon="mdi:card" style="color: #ff0019;" data-width="20" data-height="20" data-rotate="90deg"></span></th>
                                                <th>Sof</th>
                                                <th>Com</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $registosFinaisJogadores = $posInformacao -> registosFinaisJogadores;
                                            $registoFinaiJogador = $registosFinaisJogadores -> registoFinaiJogador;
                                            $count = count($registoFinaiJogador);

                                            $cartoes = $diretoInformacao -> eventos -> cartoes;
                                            $amarelos = $cartoes -> amarelos;
                                            $amarelo = $amarelos -> amarelo;
                                            $countAmarelos = count($amarelo);
                                            $vermelhos = $cartoes -> vermelhos;
                                            $vermelho = $vermelhos -> vermelho;
                                            $countVermelhos = count($vermelho);

                                            for ($nRegistoFJ = 1; $nRegistoFJ < $count; $nRegistoFJ++){
                                                $idJogador = $registoFinaiJogador[$nRegistoFJ]['idJogador'];
                                                $classificacao = $registoFinaiJogador[$nRegistoFJ]['classificacao'];
                                                $tempoJogado = $registoFinaiJogador[$nRegistoFJ]['tempoJogado'];
                                                $nFaltasSofridas = $registoFinaiJogador[$nRegistoFJ]['nFaltasSofridas'];
                                                $nFaltasCometidas = $registoFinaiJogador[$nRegistoFJ]['nFaltasCometidas'];
                                                $nRemates = $registoFinaiJogador[$nRegistoFJ]['nRemates'];

                                                $yellowCardsPlayer = array();
                                                for ($nRegistoCartoes = 0; $nRegistoCartoes < $countAmarelos; $nRegistoCartoes++){
                                                    if ((int)$idJogador == (int)$amarelo[$nRegistoCartoes]['idJogador'])
                                                        array_push($yellowCardsPlayer, (int)$amarelo[$nRegistoCartoes]['tempo']);
                                                }
                                                asort($yellowCardsPlayer);

                                                $redCardPlayer = array();
                                                for ($nRegistoCartoes = 0; $nRegistoCartoes < $countVermelhos; $nRegistoCartoes++){
                                                    if ((int)$idJogador == (int)$vermelho[$nRegistoCartoes]['idJogador'])
                                                        array_push($redCardPlayer, (int)$vermelho[$nRegistoCartoes]['tempo']);
                                                }
                                                ?>
                                            <tr>
                                                <td><?php echo ++$countPlayersTableGeral?></td>
                                                <td>
                                                    <select class="form-select" id="input-player<?php echo $countPlayersTableGeral?>" name="input-player<?php echo $countPlayersTableGeral?>" size="1" required style="width:100% !important; height: 30px; border: 1.5px solid black; background:#F2F3F4; border-radius: 5px; padding-top: 0; padding-bottom: 0">
                                                        <option value="" disabled>- Selecione Jogador -</option>
                                                        <?php
                                                        for ($i = 1; $i < count($playersArray); $i++){
                                                            ?>
                                                            <option value="<?php echo $playersArray[$i][0] ?>" <?php if((int)$playersArray[$i][0] === (int)$idJogador) echo "selected"; ?>> <?php echo $playersArray[$i][1] ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td><input type="number" id="input-classificacao<?php echo $countPlayersTableGeral?>" name="input-classificacao<?php echo $countPlayersTableGeral?>" min="0" max="10" value="<?php echo $classificacao; ?>" style="width:100% !important;  height: 30px; border: 1.5px solid black; background:#F2F3F4; border-radius: 5px;"></td>
                                                <td><input type="number" id="input-remates<?php echo $countPlayersTableGeral?>" name="input-remates<?php echo $countPlayersTableGeral?>" min="0" value="<?php echo $nRemates; ?>" style="width:100% !important;  height: 30px; border: 1.5px solid black; background:#F2F3F4; border-radius: 5px;"></td>
                                                <td><input type="number" id="input-primeroAmarelo<?php echo $countPlayersTableGeral?>" name="input-primeroAmarelo<?php echo $countPlayersTableGeral?>" min="0" value="<?php if(count($yellowCardsPlayer) > 0){ echo array_values($yellowCardsPlayer)[0];} ?>" style="width:100% !important;  height: 30px; border: 1.5px solid black; background:#F2F3F4; border-radius: 5px;"></td>
                                                <td><input type="number" id="input-segundoAmarelo<?php echo $countPlayersTableGeral?>" name="input-segundoAmarelo<?php echo $countPlayersTableGeral?>" min="0" value="<?php if(count($yellowCardsPlayer) > 1){ echo array_values($yellowCardsPlayer)[1];} ?>" max="90" style="width:100% !important;  height: 30px; border: 1.5px solid black; background:#F2F3F4; border-radius: 5px;"></td>
                                                <td><input type="number" id="input-vermelho<?php echo $countPlayersTableGeral?>" name="input-vermelho<?php echo $countPlayersTableGeral?>" min="0" max="90" value="<?php if(count($redCardPlayer) > 0){ echo array_values($redCardPlayer)[0];} ?>" style="width:100% !important;  height: 30px; border: 1.5px solid black; background:#F2F3F4; border-radius: 5px;"></td>
                                                <td><input type="number" id="input-fSofridas<?php echo $countPlayersTableGeral?>" name="input-fSofridas<?php echo $countPlayersTableGeral?>" min="0" max="90" value="<?php echo $nFaltasSofridas; ?>" style="width:100% !important;  height: 30px; border: 1.5px solid black; background:#F2F3F4; border-radius: 5px;"></td>
                                                <td><input type="number" id="input-fCometidas<?php echo $countPlayersTableGeral?>" name="input-fCometidas<?php echo $countPlayersTableGeral?>" min="0" value="<?php echo $nFaltasCometidas; ?>" style="width:100% !important;  height: 30px; border: 1.5px solid black; background:#F2F3F4; border-radius: 5px;"></td>
                                            </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>

                                <div class="row-cols-auto" style="margin-top: 2rem">
                                    <form method="post" action="submitInfoJogoTableGolos.php" name="submitTableGolos">
                                        <input type="hidden" name="IDJogo" value="<?php echo $IDJogo ?>">
                                        <input type="hidden" id="countTableGolos" name="countTableGolos">
                                        <input type="hidden" name="jornadaSubmit" value="<?php echo $jornadaSubmit;?>">
                                        <input type="hidden" name="compNameSubmit" value="<?php echo $compNameSubmit;?>">
                                        <input type="hidden" name="equipaCasaSubmit" value="<?php echo $equipaCasaSubmit;?>">

                                        <table id="tableGolos" class="table compact nowrap" style="width: 100% !important">
                                            <thead style="color: white; background-color: #34495E">
                                            <tr style="color: white; background-color: #34495E">
                                            <tr>
                                                <th> Nº</th>
                                                <th> Jogador</th>
                                                <th> Tempo Golo</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $golos = $diretoInformacao -> eventos -> golos;
                                            $golo = $golos -> golo;
                                            $countGolos = count($golo);

                                            for ($nRegistoFJ = 1; $nRegistoFJ < $countGolos; $nRegistoFJ++){

                                                $idJogador = $golo[$nRegistoFJ]['idJogador'];
                                                $tempo = $golo[$nRegistoFJ]['tempo'];
                                                ?>
                                                <tr>
                                                    <td><?php echo ++$countTableGolos?></td>
                                                    <td>
                                                        <select class="form-select" id="input-player<?php echo $countTableGolos?>" name="input-player<?php echo $countTableGolos?>" size="1" required style="width:100% !important; height: 30px; border: 1.5px solid black; background:#F2F3F4; border-radius: 5px; padding-top: 0; padding-bottom: 0">
                                                            <option value="" disabled>- Selecione Jogador -</option>
                                                            <?php
                                                            for ($i = 1; $i < count($playersArray); $i++){
                                                                ?>
                                                                <option value="<?php echo $playersArray[$i][0] ?>" <?php if((int)$playersArray[$i][0] === (int)$idJogador) echo "selected"; ?>> <?php echo $playersArray[$i][1] ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td><input type="number" id="input-tempoGolo<?php echo $countTableGolos?>" name="input-tempoGolo<?php echo $countTableGolos?>" min="0" max="90" value="<?php echo $tempo?>" style="width:100% !important; height: 30px; border: 1.5px solid black; background:#F2F3F4; border-radius: 5px;"></td>
                                                    <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>

                                <div class="row-cols-auto" style="margin-top: 2rem">
                                    <form method="post" action="submitInfoJogoTableSub.php" name="submitTableSubs">
                                        <input type="hidden" name="IDJogo" value="<?php echo $IDJogo ?>">
                                        <input type="hidden" id="countTableSubs" name="countTableSubs">
                                        <input type="hidden" name="jornadaSubmit" value="<?php echo $jornadaSubmit;?>">
                                        <input type="hidden" name="compNameSubmit" value="<?php echo $compNameSubmit;?>">
                                        <input type="hidden" name="equipaCasaSubmit" value="<?php echo $equipaCasaSubmit;?>">
                                        <table id="tableSubs" class="table compact nowrap" style="width: 100% !important">
                                            <thead style="color: white; background-color: #34495E">
                                            <tr style="color: white; background-color: #34495E">
                                            <tr>
                                                <th> Nº</th>
                                                <th> Jogador Entrou</th>
                                                <th> Jogador Saiu</th>
                                                <th> Tempo</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $Subs = $diretoInformacao -> eventos -> substituicoes;
                                            $Sub = $Subs -> substituicao;
                                            $countSubs = count($Sub);

                                            for ($nRegistoFJ = 1; $nRegistoFJ < $countSubs; $nRegistoFJ++){
                                                $idJogadorEntrou = $Sub[$nRegistoFJ]['idJogadorEntrou'];
                                                $idJogadorSaiu = $Sub[$nRegistoFJ]['idJogadorSaiu'];
                                                $tempo = $Sub[$nRegistoFJ]['tempo'];
                                            ?>
                                            <tr>
                                                <td><?php echo ++$countTableSubs?></td>
                                                <td>
                                                    <select class="form-select" id="input-playerOut<?php echo $countTableSubs;?>" name="input-playerOut<?php echo $countTableSubs;?>" size="1" required style="width:100% !important; height: 30px; border: 1.5px solid black; background:#F2F3F4; border-radius: 5px; padding-top: 0; padding-bottom: 0">
                                                        <option value="" disabled>- Selecione Jogador -</option>
                                                        <?php
                                                        for ($i = 1; $i < count($playersArray); $i++){
                                                            echo (int)$playersArray[$i][0] === (int)$idJogadorSaiu;
                                                            ?>
                                                            <option value="<?php echo $playersArray[$i][0] ?>" <?php if((int)$playersArray[$i][0] === (int)$idJogadorSaiu) echo "selected"; ?>> <?php echo $playersArray[$i][1] ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-select" id="input-playerIn<?php echo $countTableSubs;?>" name="input-playerIn<?php echo $countTableSubs;?>" size="1" required style="width:100% !important; height: 30px; border: 1.5px solid black; background:#F2F3F4; border-radius: 5px; padding-top: 0; padding-bottom: 0">
                                                        <option value="" disabled>- Selecione Jogador -</option>
                                                        <?php
                                                        for ($i = 1; $i < count($playersArray); $i++){
                                                            echo (int)$playersArray[$i][0] === (int)$idJogadorEntrou;
                                                            ?>
                                                            <option value="<?php echo $playersArray[$i][0] ?>" <?php if((int)$playersArray[$i][0] === (int)$idJogadorEntrou) echo "selected"; ?>> <?php echo $playersArray[$i][1] ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td><input type="number" id="input-tempo<?php echo $countTableSubs;?>" name="input-tempo<?php echo $countTableSubs;?>" min="0" max="90" required value="<?php echo $tempo?>" style="width:100% !important; height: 30px; border: 1.5px solid black; background:#F2F3F4; border-radius: 5px;"></td>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <br>
        <!-- Footer -->
        <footer class="footer-distributed">
            <div class="footer-left" style="vertical-align: middle !important;">
                <img src="img/Logos/Logo/LogoVermelho.png" alt="login" style="width: 20%; height: auto;">
            </div>
            <div class="footer-center" style="vertical-align: middle !important;">
                <div>
                    <p style="color: black"><i class="fas fa-phone-square-alt"></i>  footballtalentmanager@gmail.com</span></a></p>
                </div>
            </div>
            <div class="footer-right" style="vertical-align: middle !important;">
                <div class="footer-icons">
                    <img class="iconSize" src="img/Logos/Icon/IconAzul.png">
                    <img class="iconSize" src="img/Logos/Icon/IconVermelho.png">
                    <img class="iconSize" src="img/Logos/Icon/IconCinza.png">
                </div>
                <p class="footer-company-name" style="padding-top: 10px !important;">FT Manager &copy; 2021</p>
            </div>
        </footer>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

<!-- data tables -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap5.min.js"></script>

<script src="js/scripts.js"></script>
<script src="js/jogo/jogo.js"></script>
<script src="js/dataTables/dataTable-jogo.js"></script>

<script>
    let width = $(window).width();
    let height = $(window).height();

    if ((width >= 1024) && (height >= 768) || (width >= 768) && (height >= 1024)) {
        document.getElementById("resizeCar").classList.remove("col-md-8");
        document.getElementById("resizeCar").classList.add("col-md-12");
    }

    function getJogadores(){
        let playersArray = [<?php echo json_encode($playersArray); ?>]
        return playersArray;
    }

    function getCountPlayersTableGeral(){
        return <?php echo json_encode($countPlayersTableGeral);?>;
    }

    function getCountPlayersTableGolos(){
        return <?php echo json_encode($countTableGolos);?>;
    }

    function getCountPlayersTableSubs(){
        return <?php echo json_encode($countTableSubs);?>;
    }

    function getFormationHome(){
        return <?php echo json_encode((string)$formacaoTatica['formacaoCasa']);?>;
    }

    function getFormationAway(){
        return <?php echo json_encode((string)$formacaoTatica['formacaoFora']);?>;
    }
</script>
</body>
</html>