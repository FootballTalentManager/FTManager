<?php
session_start();
if (!isset($_SESSION['IDUser'])) {
    header('Location: index.php');
}
require_once( "lib/db.php" );

$isTreinador = false;
$isAdministrador = false;

dbConnect(ConfigFile);
$dataBaseName = $GLOBALS['configDataBase']->db;
mysqli_select_db($GLOBALS['ligacao'], $dataBaseName);

$IDUser = $_SESSION['IDUser'];

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
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

    <link rel="icon" href="img/Logos/Icon/IconVermelho.png">
    <title>Football Talent Manager</title>

    <link href="css/styles.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://code.iconify.design/2/2.0.3/iconify.min.js"></script>

    <link rel="stylesheet" href="css/footer-distributed-with-address-and-phones.css">

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;1,600&display=swap");
        :root {
            --hero-gap: 60px;
        }

        h1 {
            font-size: calc(0.5rem + 8vmin);
            font-weight: 600;
            font-style: italic;
        }

        h2 {
            font-size: calc(0.8rem + 4vmin);
            font-weight: 600;
            font-style: italic;
            line-height: 1.2;
        }

        .text-parallax {
            font-size: calc(0.8rem + 1.25vmin);
            line-height: 1.65;
        }

        .caption {
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            color: white;
            z-index: 2;
            font-size: 0.6rem;
        }

        .parallax-wrapper {
            height: 100vh;
            overflow-x: hidden;
            overflow-y: scroll;
            perspective: 10px;
        }

        .parallax-content {
            position: relative;
            width: 100%;
            height: calc(101vh - var(--hero-gap));
        }

        .hero {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .hero img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 110%;
            -o-object-fit: cover;
            object-fit: cover;
            z-index: 1;
            transform: translateZ(1px);
        }
        .hero .hero__title {
            color: white;
            z-index: 2;
            text-align: center;
            transform: translateZ(-2px) scale(1.2);
        }
        .hero .hero__title p {
            margin-top: 0.5rem;
            font-size: calc(0.6rem + 0.75vmin);
        }
        .hero .hero__title a {
            color: white;
        }
        .hero::after {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 100%;
            transform-origin: 0 100%;
            transform: translateZ(8px);
            pointer-events: none;
            background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.013) 8%, rgba(0, 0, 0, 0.049) 14.8%, rgba(0, 0, 0, 0.104) 20.8%, rgba(0, 0, 0, 0.175) 26%, rgba(0, 0, 0, 0.259) 30.8%, rgba(0, 0, 0, 0.352) 35.3%, rgba(0, 0, 0, 0.45) 39.8%, rgba(0, 0, 0, 0.55) 44.5%, rgba(0, 0, 0, 0.648) 49.5%, rgba(0, 0, 0, 0.741) 55.2%, rgba(0, 0, 0, 0.825) 61.7%, rgba(0, 0, 0, 0.896) 69.2%, rgba(0, 0, 0, 0.951) 77.9%, rgba(0, 0, 0, 0.987) 88.1%, black 100%);
            z-index: 3;
        }

        .main-content {
            position: relative;
            margin: 0 auto;
            padding: var(--hero-gap) 2rem;
            max-width: 725px;
            background-color: white;
        }
        .main-content > * + * {
            margin-top: 2rem;
        }

        .scroll-icon-container {
            --size: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            overflow: hidden;
            top: calc(var(--size) * -5);
            left: 0;
            right: 0;
            margin: 0 auto;
            width: calc(var(--size) * 2);
            height: calc(var(--size) * 2);
            border-radius: 0.15rem;
            background-color: inherit;
            box-shadow: 0 6px 12px -3px rgba(0, 0, 0, 0.1);
            z-index: 4;
        }
        .scroll-icon-container .icon--down-arrow {
            width: var(--size);
            height: var(--size);
        }

        .vert-center{
            height:100%;
            width:100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* width */
        ::-webkit-scrollbar {
            width: 0;
        }
    </style>

</head>
    <body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="dashBoard.php"><img src="img/Logos/Logo/LogoCinza.png" alt="login" style="height: 45px !important; width: auto !important;"></a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
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
                <div class="row-cols-sm-1 parallax-wrapper">
                    <div class="hero parallax-content"><img src="img/parallax_Soccer.png">
                        <div class="hero__title">
                            <h1>Desenvolve o teu sonho</h1>
                            <p class="text-parallax"><span><img src="img/Logos/Icon/IconCinza.png" style="height: 20px !important; width: auto; position: relative" alt=""></span>Football Tallent Manager</p>
                        </div>
                    </div>
                    <div class="main-content">
                        <div class="scroll-icon-container">
                            <svg class="icon--down-arrow" viewBox="0 0 24 24">
                                <path d="M11,4H13V16L18.5,10.5L19.92,11.92L12,19.84L4.08,11.92L5.5,10.5L11,16V4Z"></path>
                            </svg>
                        </div>
                        <h2><span><img src="img/Logos/Icon/IconAzul.png" style="height: 60px !important; width: auto; position: relative" alt=""></span>       Football Talent Manager</h2>
                        <p class="text-parallax" style="text-align: justify; text-justify: inter-word;">
                            A gestão de clubes de futebol exige um esforço contínuo por parte das equipas técnicas e administração para gerir os planteis, planear treinos e jogos ao
                            longo da temporada, gerindo os recursos afectos às actividades de formação e de competição. A gestão das várias equipas que compõem um clube deve ser um
                            prazer, e não uma dor de cabeça motivada, pela desadequação da tecnologia resultando num esforço e tempo desperdiçados com pormenores tecnológicos que
                            deviam o foco do objectivo principal que é ganhar. Tendo como base informação recolhida junto de pessoas com experiência na área e analise de algumas
                            plataformas com um objectivo semelhante, consta-se efectivamente que as ferramentas disponíveis apresentam lacunas que comprometem a produtividade desportiva
                            dos clubes.
                        </p>
                        <p class="text-parallax" style="text-align: justify; text-justify: inter-word;">
                            Neste âmbito, é relevante facilitar a gestão de equipas de futebol salvaguardando aspectos relacionados com a gestão das características e resultados
                            desportivos dos atletas através da manutenção de dados individuais e de indicadores estatísticos agregados reconhecidos por especialistas na área.
                        </p>
                        <p class="text-parallax" style="text-align: justify; text-justify: inter-word;">
                            Sendo assim, é oportuno conceber e implementar uma solução informática que incorpore boas práticas de acessibilidade e usabilidade para facilitar a
                            gestão de talentos nos clubes de futebol facilitando aos técnicos e administradores a gestão da informação necessária, nos locais próprios, em tempo
                            real e através de dispositivos computacionais de uso quotidiano privilegiando a versatilidade e o baixo custo de manutenção.
                        </p>
                    </div>
                    <!-- Footer -->
                    <div class="footer-distributed">
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
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    </body>
</html>
