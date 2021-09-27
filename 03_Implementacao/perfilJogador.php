<?php
session_start();
if (!isset($_SESSION['IDUser'])) {
    header('Location: index.php');
}
include 'SimpleDOM/SimpleDOM.php';
require_once( "lib/db.php" );

$flags[] = FILTER_NULL_ON_FAILURE;
$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING, $flags);

$urlPlayersPath = "C:\\FootballTalentManager\\Jogadores\\";

if ($method == 'POST') {
    $_INPUT_METHOD = INPUT_POST;
    $_INPUT = $_POST;
} elseif ($method == 'GET') {
    $_INPUT_METHOD = INPUT_GET;
    $_INPUT = $_GET;
    $isTreinador = $_INPUT['isTreinador'];
} else {
    echo "Invalid HTTP method (" . $method . ")";
    exit();
}

$BI = $_INPUT['hiddenField'];

dbConnect(ConfigFile);
$dataBaseName = $GLOBALS['configDataBase']->db;
mysqli_select_db($GLOBALS['ligacao'], $dataBaseName);

$queryIsBIExist = "SELECT EXISTS (SELECT `BI` FROM `$dataBaseName`.`jogador` WHERE `BI` = '$BI')";
$queryResultIsBIExist = mysqli_query($GLOBALS['ligacao'], $queryIsBIExist);

$isPlayerExist = null;
$inputNomeCompleto = null;
$inputNacionalidade = null;
$inputDataNascimento = null;
$inputMorada = null;
$inputTelefone = null;
$inputNumero = null;
$inputAlcunha = null;
$inputFoto = null;
$inputDadosXML = null;
$IDJogador = null;

if ($queryResultIsBIExist->num_rows > 0) {
    while ($row = mysqli_fetch_row($queryResultIsBIExist)) {
        $isPlayerExist = $row[0];
    }

    if ($isPlayerExist){
        $queryGetPlayerData = "SELECT * FROM `$dataBaseName`.`jogador` WHERE BI = '$BI'";
        $queryResultGetPlayerData = mysqli_query($GLOBALS['ligacao'], $queryGetPlayerData);

        while ($row = mysqli_fetch_row($queryResultGetPlayerData)) {
            $IDJogador = $row[0];
            $inputTelefone = $row[1];
            $inputBI = $row[2];
            $inputFoto = $row[3];
            $inputDadosXML = $row[4];

            $dadosJogadorXML = simpledom_load_file($inputDadosXML);
            $curriculo = $dadosJogadorXML;

            $inputNomeCompleto = $curriculo['nome'];
            $inputAlcunha = $curriculo['alcunha'];
            $inputMorada = $curriculo['morada'];
            $inputNacionalidade = $curriculo['nacionalidade'];
            $inputDataNascimento = $curriculo['dataNascimento'];
            $inputNumero = $curriculo['numero'];
        }
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


$dadosJogadorXML = simpledom_load_file($inputDadosXML);
$curriculo = $dadosJogadorXML;

$registoCompetencias = $curriculo -> competencias -> registoCompetencias;

$passe = $registoCompetencias['passe'];
$recepcao = $registoCompetencias['recepcao'];
$finalizacao = $registoCompetencias['finalizacao'];
$jogoCabeca = $registoCompetencias['jogoCabeca'];
$cruzamentos= $registoCompetencias['cruzamentos'];
$marcacao= $registoCompetencias['marcacao'];
$cobrancaDeLivres = $registoCompetencias['cobrancaDeLivres'];
$umParaUmDefensivo = $registoCompetencias['umParaUmDefensivo'];
$umParaUmOfensivo = $registoCompetencias['umParaUmOfensivo'];
$posicionamento = $registoCompetencias['posicionamento'];
$tomadaDecisao = $registoCompetencias['tomadaDecisao'];
$velocidadeExecucao = $registoCompetencias['velocidadeExecucao'];
$capacidadeDeTrabalho = $registoCompetencias['capacidadeDeTrabalho'];
$agressividade = $registoCompetencias['agressividade'];
$autoConfianca = $registoCompetencias['autoConfianca'];
$inteligenciaEmJogo = $registoCompetencias['inteligenciaEmJogo'];
$autoControlo = $registoCompetencias['autoControlo'];
$condicaoFisica = $registoCompetencias['condicaoFisica'];
$resistenciaLesoes = $registoCompetencias['resistenciaLesoes'];

$avaliacaoGeral = ((int)$passe + (int)$recepcao + (int)$finalizacao + (int)$jogoCabeca + (int)$cruzamentos + (int)$marcacao + (int)$cobrancaDeLivres +
                   (int)$umParaUmDefensivo + (int)$umParaUmOfensivo + (int)$posicionamento + (int)$tomadaDecisao + (int)$velocidadeExecucao +
                   (int)$capacidadeDeTrabalho + (int)$agressividade + (int)$autoConfianca + (int)$inteligenciaEmJogo + (int)$autoControlo +
                   (int)$condicaoFisica + (int)$resistenciaLesoes)/19;


$nTotalDeJogos = 0;
$nGolosMarcados = 0;
$nConvocatorias = 0;
$classificacaoJogador = 0;
$cartoesAmarelos = 0;
$cartoesVermelhos = 0;
$faltasCometidasPorJogo = 0;
$nTotalDeRemates = 0;

$queryGetJogos = "SELECT DadosXML FROM `$dataBaseName`.`jogo`";
$queryResultJogo = mysqli_query($GLOBALS['ligacao'], $queryGetJogos);
if ($queryResultJogo->num_rows > 0) {
    while ($jogo = mysqli_fetch_row($queryResultJogo)) {
        $urlDadosXML = $jogo[0];
        $dadosJogoXML = simpledom_load_file($urlDadosXML);
        $jogo = $dadosJogoXML;

        $result = $jogo->xpath("//jogo/preInformacao/fichaJogoJogadores/jogador[@idJogador='" . $IDJogador ."']");

        if(count($result) > 0){
            $nTotalDeJogos++;

            $result = $jogo->xpath("//jogo/diretoInformacao/eventos/golos/golo[@idJogador='". $IDJogador ."']");
            $nGolosMarcados += count($result);

            $result = $jogo->xpath("//jogo/diretoInformacao/eventos/cartoes/amarelos/amarelo[@idJogador='". $IDJogador ."']");
            $cartoesAmarelos += count($result);

            $result = $jogo->xpath("//jogo/diretoInformacao/eventos/cartoes/amarelos/vermelho[@idJogador='". $IDJogador ."']");
            $cartoesVermelhos += count($result);

            $result = $jogo->xpath("//posInformacao/registosFinaisJogadores/registoFinaiJogador[@idJogador='". $IDJogador ."']");
            $classificacaoJogador += (int)$result[0]['classificacao'];
            $nTotalDeRemates += (int)$result[0]['nRemates'];
            $faltasCometidasPorJogo += (int)$result[0]['nFaltasCometidas'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <link rel="icon" href="img/Logos/Icon/IconVermelho.png">
    <title>Football Talent Manager - Perfil Jogador</title>

    <link href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet"/>
    <link href="css/botoesTreino.css" rel="stylesheet"/>
    <link href="css/perfilJogador.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>

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
                            <li><a class="dropdown-item" href="logout.php"><span class="iconify-inline" data-icon="mdi-light:logout"></span>  Logout</a></li>
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
                        <div class="row" style="border-bottom: 2px solid black; padding-bottom: 1rem;">
                            <div class="col-md-auto">
                                <h2 class="mt-4"><span class="iconify-inline" data-icon="icomoon-free:profile" data-width="30" data-height="30"></span> Perfil do Jogador</h2>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 2rem !important;">

                            <div class="col-sm-4" style="margin-bottom: 2rem">
                                <!-- Foto/alcunha-->
                                <div class="card">
                                    <div class="card-header">
                                        <span><h6><span class="iconify" data-icon="bx:bx-photo-album" data-width="20" data-height="20"></span>  Foto</h6></span>
                                    </div>
                                    <div class="card-body ">
                                        <!-- ROW-1 -->
                                        <div class="row center-content vert-center" >
                                            <img src="showImage.php?imageURL=<?php echo $inputFoto; ?>" style="width: auto; height: 210px" alt=""/>
                                            <h3 class="text-center"><?php echo $inputAlcunha; ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-8" style="margin-bottom: 2rem">
                                <!-- Ficha Individual-->
                                <div class="card">
                                    <div class="card-header">
                                        <span><h6><span class="iconify" data-icon="carbon:information" data-width="20" data-height="20"></span>  Informações</h6></span>
                                    </div>
                                    <div class="card-body vert-center">
                                        <div class="row center-content">
                                            <form method="post" action="formSubmitChangesPlayer.php" name="formInformacoes">
                                                <input type="hidden" name="frmName" value="formInformacoes">
                                                <input type="hidden" name="BI" value="<?php echo $BI; ?>">
                                                <table id="tableInformation" class="display nowrap compact" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td><i class="fas fa-signature"></i></td>
                                                        <td><h6>Nome Completo</h6></td>
                                                        <td> <?php echo $inputNomeCompleto?> <Nome Completo do Jogador Nome Completo do Jogador></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="far fa-flag"></i></td>
                                                        <td><h6>Nacionalidade</h6></td>
                                                        <td><?php echo $inputNacionalidade?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="far fa-calendar"></i></td>
                                                        <td><h6>Data de Nascimento</h6></td>
                                                        <td><?php echo $inputDataNascimento?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fas fa-map-marked-alt"></i></td>
                                                        <td><h6>Morada</h6></td>
                                                        <td><?php echo $inputMorada?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="fas fa-mobile-alt"></i></td>
                                                        <td><h6>Telefone</h6></td>
                                                        <td><?php echo $inputTelefone?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="iconify" data-icon="icon-park-outline:basketball-clothes"></span></td>
                                                        <td><h6>Número Dorsal</h6></td>
                                                        <td><?php echo $inputNumero?></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12" style="margin-bottom: 2rem">
                                <div class="row fullHeight" >
                                    <div class="col">
                                        <!-- Dados Fisicos-->
                                        <div class="card fullHeight">
                                            <div class="card-header">
                                                <span><h6><span class="iconify" data-icon="healthicons:register-book" data-width="25" data-height="25"></span>  Registo Individual</h6></span>
                                            </div>
                                            <div class="card-body vert-center">
                                                <div class="row center-content">
                                                    <form method="post" action="formSubmitChangesPlayer.php" name="formRegistoIndividual">
                                                        <input type="hidden" name="frmName" value="formRegistoIndividual">
                                                        <input type="hidden" name="BI" value="<?php echo $BI; ?>">
                                                        <table data-scroll-y="300" data-scroll-x="300" id="tableIndividualRegister" class="display compact nowrap" style="width: 100% !important">
                                                            <thead>
                                                                <tr>
                                                                    <th rowspan="2"> <i class="far fa-calendar"></i> Data</th>
                                                                    <th colspan="3"> <i class="fas fa-child"></i> Dados Físicos</th>
                                                                    <th colspan="2"> <span class="iconify" data-icon="bi:card-list"></span> Caracteristicas</th>
                                                                    <th colspan="2"> <span class="iconify" data-icon="ion:resize-sharp"></span> Tamanho Equipamentos</th>
                                                                </tr>
                                                                <tr>
                                                                    <th> <i class="fas fa-weight"></i> Peso (kg)</th>
                                                                    <th> <i class="fas fa-text-height"></i> Altura (cm)</th>
                                                                    <th> <i class="fas fa-percent"></i> IMC (%)</th>
                                                                    <th> <i class="fas fa-shoe-prints"></i> Pé Dominante</th>
                                                                    <th> <i class="fas fa-crosshairs"></i> Posição Habitual</th>
                                                                    <th> <span class="iconify" data-icon="emojione-monotone:running-shirt"></span> Camisola</th>
                                                                    <th> <span class="iconify" data-icon="icon-park-outline:shorts"></span> Calções</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                                if($isPlayerExist){
                                                                    $dadosJogadorXML = simpledom_load_file($inputDadosXML);
                                                                    $curriculo = $dadosJogadorXML;

                                                                    $registoIndividual = $curriculo -> fichaIndividual -> registoIndividual;
                                                                    $numRegistoIndividual = $registoIndividual->count();

                                                                    for ($i = 0; $i < $numRegistoIndividual; $i++){
                                                                        $dataRegistoFichaIndividual = $registoIndividual[$i]['dataRegistoFichaIndividual'];
                                                                        $peDominante = $registoIndividual[$i]['peDominante'];
                                                                        $posicaoHabitual = $registoIndividual[$i]['posicaoHabitual'];
                                                                        $peso = $registoIndividual[$i]['peso'];
                                                                        $altura = $registoIndividual[$i]['altura'];
                                                                        $camisola = $registoIndividual[$i]['camisola'];
                                                                        $calcoes = $registoIndividual[$i]['calcoes'];
                                                                        $imc = $registoIndividual[$i]['imc'];

                                                                        if ($dataRegistoFichaIndividual == ""){
                                                                            break;
                                                                        } else {
                                                                            ?>
                                                                            <tr>
                                                                                <td><?php echo $dataRegistoFichaIndividual ?></td>
                                                                                <td><?php echo $peso ?></td>
                                                                                <td><?php echo $altura ?></td>
                                                                                <td><?php echo $imc ?></td>
                                                                                <td><?php echo $peDominante ?></td>
                                                                                <td><?php echo $posicaoHabitual ?></td>
                                                                                <td><?php echo $camisola ?></td>
                                                                                <td><?php echo $calcoes ?></td>
                                                                            </tr>
                                                                            <?php
                                                                        }
                                                                    }
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
                        </div>

                        <div class="row">
                            <div class="col " style="margin-bottom: 2rem">
                                <div class="row fullHeight">
                                    <div class="col">
                                        <!-- Av. geral-->
                                        <div class="card mb-4 halfHeight" >
                                            <div class="card-header">
                                                <span><h6><span class="iconify" data-icon="ant-design:star-outlined" data-width="25" data-height="25"></span>  Avaliação Geral</h6></span>
                                            </div>
                                            <div class="card-body vert-center">
                                                <div class="row center-content">
                                                    <table class="">
                                                        <tbody>
                                                        <tr>
                                                            <td class="text-center cl-td-style-value br-t"><?php echo round($avaliacaoGeral, 2);?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center ag-td-style-text br-b">(1-10)</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Classificacoes-->
                                        <div class="card halfHeight" >
                                            <div class="card-header">
                                                <span><h6><span class="iconify" data-icon="icon-park-outline:ranking" data-width="20" data-height="20"></span>  Classificações</h6></span>
                                            </div>
                                            <div class="card-body vert-center">
                                                <div class="row center-content">
                                                    <table class="">
                                                        <tbody>
                                                        <tr>
                                                            <td class="text-center cl-td-style-value br-tl" ><?php if($classificacaoJogador === 0 || (int)$nTotalDeJogos === 0){ echo 0;} else{ echo round(($classificacaoJogador / $nTotalDeJogos), 2);}?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center cl-td-style-text br-bl">Média por jogo</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col" style="margin-bottom: 2rem">
                                <div class="row fullHeight">
                                    <div class="col">
                                        <!-- Golos-->
                                        <div class="card mb-4 halfHeight" >
                                            <div class="card-header">
                                                <span><h6><span class="iconify" data-icon="iconoir:soccer-ball" data-width="20" data-height="20"></span>  Golos</h6></span>
                                            </div>
                                            <div class="card-body vert-center">
                                                <div class="row center-content">
                                                    <table class="">
                                                        <tbody>
                                                        <tr>
                                                            <td class="text-center cl-td-style-value br-tl" style="width: 25%" ><?php echo $nGolosMarcados; ?></td>
                                                            <td class="text-center cl-td-style-value reverse-colors" style="width: 25%" ><?php if($nGolosMarcados === 0 || $nTotalDeJogos === 0){ echo 0;} else{ echo round(((int)$nGolosMarcados/(int)$nTotalDeJogos), 2);}?></td>
                                                            <td class="text-center cl-td-style-value br-tr reverse-colors" style="width: 25%" ><?php if($nGolosMarcados === 0 || $nTotalDeRemates === 0){ echo 0;} else{ echo round(((int)$nGolosMarcados/(int)$nTotalDeRemates), 3); } ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center cl-td-style-text br-bl" style="width: 25%">Marcados</td>
                                                            <td class="text-center cl-td-style-text reverse-colors" style="width: 25%">Média por jogo</td>
                                                            <td class="text-center cl-td-style-text br-br reverse-colors" style="width: 25%">Precisão(0 - 1)</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Cartoes-->
                                        <div class="card halfHeight" >
                                            <div class="card-header">
                                                <span><h6><span class="iconify" data-icon="mdi:card-outline" data-width="20" data-height="20" data-rotate="90deg"></span>  Cartões</h6></span>
                                            </div>
                                            <div class="card-body vert-center">
                                                <div class="row center-content">
                                                    <table class="">
                                                        <tbody>
                                                        <tr>
                                                            <td class="text-center cl-td-style-value br-tl amarelo" > <?php echo $cartoesAmarelos?></td>
                                                            <td class="text-center cl-td-style-value br-tr vermelho" > <?php echo $cartoesVermelhos?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center cl-td-style-text br-bl amarelo">Amarelos</td>
                                                            <td class="text-center cl-td-style-text br-br vermelho">Vermelhos</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col" style="margin-bottom: 1.8rem">
                                <div class="row fullHeight">
                                    <div class="col">
                                        <!-- Convocatorias-->
                                        <div class="card mb-4 halfHeight" >
                                            <div class="card-header">
                                                <span><h6><span class="iconify" data-icon="akar-icons:people-group" data-width="25" data-height="25"></span>  Convocatórias</h6></span>
                                            </div>
                                            <div class="card-body vert-center">
                                                <div class="row center-content">
                                                    <table class="">
                                                        <tbody>
                                                        <tr>
                                                            <td class="text-center cl-td-style-value br-tl" > <?php echo $nTotalDeJogos;?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center cl-td-style-text br-bl">Totais</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Tempo Jogado-->
                                        <div class="card halfHeight" >
                                            <div class="card-header">
                                                <span><h6><span class="iconify" data-icon="foundation:foot" data-width="25" data-height="25"></span>  Faltas Cometidas</h6></span>
                                            </div>
                                            <div class="card-body vert-center">
                                                <div class="row center-content">
                                                    <table class="">
                                                        <tbody>
                                                        <tr>
                                                            <td class="text-center cl-td-style-value br-tl" ><?php if($faltasCometidasPorJogo === 0 || $nTotalDeJogos === 0){ echo 0;} else{ echo round(($faltasCometidasPorJogo/$nTotalDeJogos), 2);}?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center cl-td-style-text br-bl">Média por jogo</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col" style="margin-bottom: 2rem">
                                <!-- perfil do jogador -->
                                <div class="card">
                                    <div class="card-header">
                                        <span><h6><span class="iconify" data-icon="carbon:user-profile" data-width="20" data-height="20"></span>  Perfil do Jogador  <label style="color: red; font-size:9px;">*Valores entre (1-10)</label></h6></span>
                                    </div>
                                    <div class="card-body">
                                        <div class="row center-content">
                                            <form method="post" action="formSubmitChangesPlayer.php" name="formPerfilJogador">
                                                <input type="hidden" name="frmName" value="formPerfilJogador">
                                                <input type="hidden" name="BI" value="<?php echo $BI; ?>">
                                                <table id="tablePlayerProfile" class="display compact" style="width: 100% !important">
                                                    <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    if($isPlayerExist){
                                                        ?>
                                                        <tr>
                                                            <td><h6>Agressividade</h6></td>
                                                            <td> <?php echo $agressividade ?> </td>

                                                            <td><h6>Condição Física</h6></td>
                                                            <td> <?php echo $condicaoFisica ?> </td>

                                                            <td><h6>Marcação</h6></td>
                                                            <td> <?php echo $marcacao ?> </td>

                                                            <td><h6>Tomada de Decisão</h6></td>
                                                            <td> <?php echo $tomadaDecisao ?> </td>
                                                        </tr>

                                                        <tr>
                                                            <td><h6>Auto Confiança</h6></td>
                                                            <td> <?php echo $autoConfianca ?> </td>

                                                            <td><h6>Cruzamentos</h6></td>
                                                            <td> <?php echo $cruzamentos ?> </td>

                                                            <td><h6>Passe</h6></td>
                                                            <td> <?php echo $passe ?> </td>

                                                            <td><h6>Velocidade de Execução</h6></td>
                                                            <td> <?php echo $velocidadeExecucao ?> </td>
                                                        </tr>

                                                        <tr>
                                                            <td><h6>Auto Controlo</h6></td>
                                                            <td> <?php echo $autoControlo ?> </td>

                                                            <td><h6>Finalização</h6></td>
                                                            <td> <?php echo $finalizacao ?> </td>

                                                            <td><h6>Posicionameto</h6></td>
                                                            <td> <?php echo $posicionamento ?> </td>

                                                            <td><h6>1x1 Defensivo</h6></td>
                                                            <td> <?php echo $umParaUmDefensivo ?> </td>
                                                        </tr>

                                                        <tr>
                                                            <td><h6>Capacidade de Trabalho</h6></td>
                                                            <td> <?php echo $capacidadeDeTrabalho ?> </td>

                                                            <td><h6>Inteligencia no Jogo</h6></td>
                                                            <td> <?php echo $inteligenciaEmJogo ?> </td>

                                                            <td><h6>Recepção</h6></td>
                                                            <td> <?php echo $recepcao ?> </td>

                                                            <td><h6>1x1 Ofensivo</h6></td>
                                                            <td> <?php echo $umParaUmOfensivo ?> </td>

                                                        </tr>

                                                        <tr>
                                                            <td><h6>Cobrança de Livres</h6></td>
                                                            <td> <?php echo $cobrancaDeLivres ?> </td>

                                                            <td><h6>Jogo de Cabeça</h6></td>
                                                            <td> <?php echo $jogoCabeca ?> </td>

                                                            <td><h6>Resistencia</h6></td>
                                                            <td> <?php echo $resistenciaLesoes ?> </td>

                                                            <td></td>
                                                            <td></td>
                                                        </tr>
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
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>

        <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
        <script src="js/global.js"></script>
        <script src="https://code.iconify.design/2/2.0.3/iconify.min.js"></script>

        <!-- data tables -->
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>

        <script src='js/dataTables/dataTable-PerfilJogador.js'></script>

        <script>
            function getEnableBtns(){
                let enableBtns = <?php echo json_encode($isTreinador)?>;
                console.log(enableBtns);
                return enableBtns;
            }
        </script>
    </body>
</html>
