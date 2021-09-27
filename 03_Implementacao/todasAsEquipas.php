<?php
session_start();
if (!isset($_SESSION['IDUser'])) {
    header('Location: index.php');
}
require_once( "lib/db.php" );
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
    $queryResultVerifyExist = mysqli_query($GLOBALS['ligacao'], $queryVerifyExistTreinador);
    $isAdministrador = mysqli_fetch_array($queryResultVerifyExist)[0];
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <link rel="icon" href="img/Logos/Icon/IconVermelho.png">
    <title>Football Talent Manager - Todas as equipas</title>

    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/botoesTreino.css" rel="stylesheet" />

    <!-- Icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://code.iconify.design/2/2.0.3/iconify.min.js"></script>
    <!-- data Table -->
    <link href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.11.0/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

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
                        <h2 class="mt-4"><span class="iconify-inline" data-icon="fluent:mail-inbox-all-24-regular" data-width="30" data-height="30"></span>  Todas as Equipas</h2>
                    </div>
                </div>
                <div class="row" style="margin-top: 2rem !important">
                    <div class="card mb-4" style="padding-right: 0; padding-left: 0;">
                        <div class="card-header">
                            <h6><span class="iconify-inline" data-icon="ri:team-fill" data-width="20" data-height="20"></span>  Equipas</h6>
                        </div>
                        <div class="card-body">
                            <table id="table" class="table table-hover" style="width:100% !important;">
                                <thead style="color: white; background-color: #34495E">
                                    <tr>
                                        <th>Epoca</th>
                                        <th>Escalão</th>
                                        <th>Nivel de Competição</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $queryGetEquipas = "SELECT * FROM `$dataBaseName`.`equipa`";
                                $queryResult = mysqli_query($GLOBALS['ligacao'], $queryGetEquipas);

                                if ($queryResult->num_rows > 0){
                                    while($row = mysqli_fetch_row($queryResult)){
                                        $idEquipa = $row[0];
                                        ?>
                                        <tr style="cursor: pointer;">
                                            <td><?php echo $row[1] ?></td>
                                            <td><?php echo strtoupper($row[2]) ?></td>
                                            <td><?php echo strtoupper($row[3]) ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                            <form method="post" name="formPlayerPage" action="EquipaTecnica.php">
                                <input type="hidden" value="" id="hiddenField" name="hiddenField[]"/>
                            </form>
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

<!-- Data Table -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap5.min.js"></script>
<script src="js/dataTables/dataTable-todasAsEquipas.js"></script>
</body>
</html>
