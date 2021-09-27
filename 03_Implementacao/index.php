<?php
session_start();
if (!isset($_SESSION["userID"]))
    unset($_SESSION["userID"]);
?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <link rel="icon" href="img/Logos/Icon/IconVermelho.png">
        <title>Football Talent Manager - Login</title>

        <!-- Font special for pages-->
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">

        <!-- CSS -->
        <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">

        <link rel="stylesheet" href="css/login.css">
        <link rel="stylesheet" href="css/register.css">
        <link rel="stylesheet" href="css/footer.css">
    </head>
    <body>
        <main class="d-flex align-items-center min-vh-100 py-3 py-md-0" style="padding: 0px !important;">
            <div class="container" style="padding: 0 !important;">
                <div class="card login-card" style="border-radius: 27.5px !important;">
                    <div class="row no-gutters">
                        <div class="col-md-5">
                            <img src="img/ImageLogin.jpg" alt="login" class="login-card-img">
                        </div>
                        <div class="col-md-7">
                            <div class="card-body">
                                <div class="brand-wrapper">
                                    <img src="img/Logos/Logo/LogoAzul.png" alt="logo" class="logo">
                                </div>
                                <p class="login-card-description">Entrar</p>
                                <form method="post" action="validationLogin.php">
                                    <div class="input-group">
                                        <input type="text" autocomplete="off" name="inputUser" id="inputUser" class="input--style-2" placeholder="Email" required>
                                    </div>
                                    <div class="input-group">
                                        <input type="password" autocomplete="off" name="inputPassword" id="inputPassword" class="input--style-2" placeholder="Password" required>
                                    </div>
                                    <div class="input-group">
                                        <img src="captcha.php" alt="captcha" style="margin-bottom: 10px !important; height: 50px !important; box-shadow: 2px 2px 1px #62656780;"/>
                                        <input type="text" autocomplete="off" name="inputCaptcha" id="inputCaptcha" class="input--style-2" placeholder="Ex: 3638680af" required>
                                    </div>
                                    <input name="login" id="login" class="btn btn-block login-btn mb-4" type="submit" value="Login">
                                    <br>
                                </form>
                                <a href="forgotPassword.php" class="forgot-password-link">Recuperar Password!</a>
                                <p class="login-card-footer-text">Não tem Conta? <a href="register.php" class="text-reset">Registe-se aqui</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="footer-distributed">
            <div class="footer-left">
                <div class="col-md-5" style="padding: 0 !important">
                    <img src="img/Logos/Logo/LogoVermelho.png" alt="login" class="footer-img">
                </div>

                <p class="footer-links">
                    <a href="#" class="link-1">Login</a>
                    <a href="#">Registo</a>
                </p>
                <p class="footer-company-name">Football Talent Manager © 2021</p>
            </div>

            <div class="footer-center">
                <div>
                    <i class="fa fa-map-marker"></i>
                    <p> Lisboa, Portugal</p>
                </div>

                <div>
                    <i class="fa fa-phone"></i>
                    <p>+351 911 072 700 / 934 737 597</p>
                </div>

                <div>
                    <i class="fa fa-envelope"></i>
                    <p><a href="mailto:footballtalentmanager@gmail.com">footballtalentmanager@gmail.com</a></p>
                </div>
            </div>

            <div class="footer-right">
                <p class="footer-company-about">
                    <span>Sobre Nós</span>
                    Este projecto foi desenvolvido no âmbito da UC de Projecto Final, pelos alunos Rodrigo Abreu e Francisco Gomes, alunos da Licenciatura em Engenharia Informatica e Multimédia no Instituto Superior de Engenharia de Lisboa.
                </p>

                <div class="footer-icons">
                    <div class="row icon-row" >
                        <img src="img/Logos/Icon/IconAzul.png" alt="" style="width:15%; height: auto">
                        <img src="img/Logos/Icon/IconVermelho.png" alt="" style="width:15%; height: auto">
                        <img src="img/Logos/Icon/IconCinza.png" alt="" style="width:15%; height: auto"  >
                    </div>
                </div>
            </div>
        </footer>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    </body>
</html>