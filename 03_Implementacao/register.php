<!DOCTYPE html>
<html lang="pt">
    <head>
        <!-- Required meta tags-->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Title Page-->
        <link rel="icon" href="img/Logos/Icon/IconVermelho.png">
        <title>Football Talent Manager - Registo</title>

        <!-- Icons font CSS-->
        <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
        <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
        <!-- Font special for pages-->
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">

        <!-- Vendor CSS-->
        <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
        <link href="vendor/datepicker/daterangepicker.css" rel="stylesheet" media="all">

        <!-- Main CSS-->
        <link href="css/register.css" rel="stylesheet" media="all">
        <link href="css/footer.css" rel="stylesheet" media="all">

        <!-- intlTelInput-->
        <link rel="stylesheet" href="css/intlTelInput.css">
    </head>

    <body>
        <div class="page-wrapper bg-black p-t-20 p-b-20 font-robo">
            <div class="wrapper wrapper--w960">
                <div class="card card-2">
                    <div class="card-heading"></div>
                    <div class="card-body">
                        <h2 class="title">Informação do Registo</h2>
                        <form method="post" onsubmit="return verifySubmit()" action="validationRegister.php">
                            <div class="row row-space">
                                <div class="col-2">
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="inputUserType" onchange="changeUserType(this)" required>
                                            <option value="" disabled selected>Tipo de Utilizador</option>
                                            <option value="treinador">Treinador</option>
                                            <option value="administrador">Administrador</option>
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group">
                                        <input class="input--style-2" type="text" placeholder="Cedula Desportiva" name="inputCedulaDesportiva" id="inputCedulaDesportiva" required disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group">
                                <input class="input--style-2" type="text" placeholder="Nome" name="inputNome" id="inputNome" required/>
                            </div>
                            <div class="input-group">
                                <input class="input--style-2" type="email" placeholder="Email" name="inputEmail" id="inputEmail" required/>
                            </div>
                            <div class="row row-space">
                                <div class="col-2">
                                    <div class="input-group">
                                        <label> </label>
                                        <input class="input--style-2" type="tel" id="phone" placeholder="Telemóvel" name="inputTelemovel" id="inputTelemovel" required>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group">
                                        <label style="color: red; font-size:9px;">*Minimo 8 carateres, pelos menos 1 letra e 1 numero</label>
                                        <input class="input--style-2" type="password" placeholder="Password" name="inputPassword" id="inputPassword" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row row-space">
                                <div class="col-2">
                                    <div class="input-group">
                                        <input class="input--style-2 js-datepicker" type="text" placeholder="Data-Nascimento" name="inputBithdayDate" autocomplete="off" required>
                                        <i class="zmdi zmdi-calendar-note input-icon js-btn-calendar"></i>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group">
                                        <div class="rs-select2 js-select-simple select--no-search">
                                            <select name="inputGenero" required>
                                                <option value="" disabled="disabled" selected="selected">Género</option>
                                                <option value="M">Masculino</option>
                                                <option value="F">Feminino</option>
                                            </select>
                                            <div class="select-dropdown"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-t-30">
                                <button class="btn btn--radius btn--black" type="submit">Registar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

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
                    <p>+351 911 072 700 / 934 737 597 </p>
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

        <!-- Jquery JS-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <!-- Vendor JS-->
        <script src="vendor/select2/select2.min.js"></script>
        <script src="vendor/datepicker/moment.min.js"></script>
        <script src="vendor/datepicker/daterangepicker.js"></script>

        <!-- Main JS-->
        <script src="js/global.js"></script>

        <!-- intlTelInput-->
        <script src="js/intlTell/intlTelInput.js"></script>
        <script>
            const phoneInputField = document.querySelector("#phone");
            const phoneInput = window.intlTelInput(phoneInputField, {utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js", });

            function verifySubmit(){
                let password = document.getElementById("inputPassword").value;
                let name = document.getElementById("inputNome").value;
                let phoneNumber = phoneInput.getNumber();

                var regexPassword = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
                var regexNome = /^(?=[a-zA-Z ]{3,125}$)/;

                if (!regexNome.test(name)) {
                    alert("Nome incorrecto!");
                    return false;
                } else if (!phoneInput.isValidNumber()) {
                    alert("Telemóvel incorrecto!");
                    return false;
                } else if (!regexPassword.test(password)) {
                    alert("Password incorrecta!");
                    return false;
                } else if (!verifyDate()){
                    alert("Data de Nascimento incorrecta!");
                    return false;
                } else
                    return true;
            }

            function verifyDate() {
                let dataNascimento = document.getElementById("bithdayDate").value;
                var today = new Date();
                var d2 = new Date(dataNascimento);
                return (d2 < today);
            }
        </script>
        <script>
            function changeUserType(select){
                switch (select.value){
                    case "":
                        break;
                    case"treinador":
                        document.getElementById("inputCedulaDesportiva").removeAttribute("disabled");
                        break;
                    case "administrador":
                        document.getElementsByName("inputCedulaDesportiva")[0].setAttribute("disabled", "true");
                        break;
                }
            }
        </script>
    </body>
</html>
<!-- end document-->