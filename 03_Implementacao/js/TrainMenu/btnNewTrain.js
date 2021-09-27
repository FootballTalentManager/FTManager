let counter = 0;
//document.getElementById('valConter').value = counter;

function addCardPlanoTreino() {
    document.getElementsByName("btnNewTrain")[0].setAttribute("disabled", "true");
    document.getElementsByName("btnNewExercice")[0].removeAttribute("hidden");
    document.getElementsByName("btnSalvarTreino")[0].removeAttribute("hidden");
    let backgroundAnimation = document.getElementById("background-animation");
    if (backgroundAnimation)
        backgroundAnimation.remove();
    let rowHeader = document.getElementById("rowHeader");
    if (rowHeader)
        rowHeader.remove();
    let cardBodyTrains = document.getElementsByName("cardBodyTrains");
    if (cardBodyTrains.length > 0){
        let len = cardBodyTrains.length;
        let parentNode = cardBodyTrains[0].parentNode;
        for(let i=0; i<len; i++)
        {
            parentNode.removeChild(cardBodyTrains[0]);
        }
    }
    let newDiv = $('<div class="row" id="rowHeader" style="margin-top: 1rem !important; padding-bottom: 1rem !important;"> ' +
        '<div class="col-xxl-12">' +
            '<div class="card mb-auto">' +
                '<div class="card-header">' +
                    '<h6><span class="iconify-inline" data-icon="bi:pencil-square" data-width="20" data-height="20"></span>  Plano de treino</h6>' +
                '</div>' +
                '<div class="card-body">' +
                    '<table id="table" class="" style="width:100% !important; "> ' +
                        '<thead>' +
                            '<tr>' +
                                '<th></th>' +
                                '<th></th>' +
                                '<th></th>' +
                                '<th></th>' +
                                '<th></th>' +
                            '</tr>' +
                        '</thead>' +
                        '<tbody>' +
                            '<tr>' +
                                '<th>' +
                                    '<h6>Microciclo: </h6>' +
                                    '<input type="number" name="input-Microciclos" id="input-Microciclos" min="0" max="30" value="" required style="width: 67px; height: 40px; opacity:100; position:relative; left:0; top:0; margin-left: 5px; border: 1px solid #85C1E9; background:#F2F3F4;"/> ' +
                                '</th>' +
                                '<th>' +
                                    '<h6>Mesociclos: </h6>' +
                                    '<input type="number" name="input-Mesociclos" id="input-Mesociclos" min="0" max="30" value="" required style="width: 67px; height: 40px; opacity:100; position:relative; left:0; top:0; margin-left: 5px; border: 1px solid #85C1E9; background:#F2F3F4;"/> ' +
                                '</th>' +
                                '<th>' +
                                    '<h6>Período: </h6> ' +
                                    '<select class="form-select" name="input-periodo" size="1" value="" required style="width: auto; height: 40px; opacity:100; position:relative; left:0; top:0; margin-left: 5px; border: 1px solid #85C1E9; background:#F2F3F4;"> ' +
                                        '<option value="">-- Selecione --</option> ' +
                                        '<option value="competitivo">Competitivo</option> ' +
                                        '<option value="não Competitivo">Não Competitivo</option> ' +
                                    '</select>'+
                                '</th>' +
                                '<th>' +
                                    '<h6>Data e Hora: </h6>' +
                                    '<input type="datetime-local" name="input-DataHora" id="input-Data" value="" required style="width: auto; height: 40px; opacity:100; position:relative; left:0; top:0; margin-left: 5px; border: 1px solid #85C1E9; background:#F2F3F4;"/> ' +
                                '</th>' +
                                '<th>' +
                                    '<h6>Volume: </h6>' +
                                    '<input type="number" name="input-Volume" id="input-Volume" value="" required style="width: 67px; height: 40px; opacity:100; position:relative; left:0; top:0; margin-left: 5px; border: 1px solid #85C1E9; background:#F2F3F4;"/> ' +
                                '</th>' +
                            '</tr>' +
                        '</tbody>' +
                    '</table>' +
                '</div>' +
            '</div>' +
        '</div>');
    $('#cardPlanoTreino').append(newDiv);
    return;
}

async function loadTable(){
    await addCardPlanoTreino();
    $('#table').DataTable( {
        "scrollX": true,
        "searching": false,
        "paging": false,
        "info": false,
        "ordering": false
    } );
    loadCanvas();
}


async function addCardExercicio() {
    counter++;
    document.getElementById("btnSalvarTreino").disabled = true;
    let newDiv = $(
            '<!-- CARD --> ' +
            '<div class="col-xl-4" name="cardBodyExercice"> ' +
                '<div class="card mb-4"> ' +
                    '<div class="card-header"> ' +
                        '<i class="fas fa-futbol me-1"></i> <input type="text" name="ex_title_'+ counter +'" value="" required placeholder="Inserir o Título" style="width: 50%; height: 35px; opacity:100; position:relative; left:0; top:0; border: 1px solid #85C1E9; background:#F2F3F4;">' +
                    '</div> ' +
                    '<div class="card-body"> ' +
                        '<!--canvas --> ' +
                        '<div class="row" id="backgroundCanvas-row_' + counter +'" style="background-image: url('+"../../img/campoInteiro.jpg"+'); background-size: contain">' +
                            '<canvas id="canvas" style="padding: 0 !important;"><canvas> ' +
                        '</div>' +
                        '<div class="row" id="inputs-canvas-row" style="padding-top: 5px; horiz-align: center">' +
                            '<div class="col-auto" style="padding-top: 7px; padding-left: 0 !important; padding-right: 0px !important;">' +
                                '<button type="button" class="skewBtn black" style="width: 35px; height: 30px;" onclick="newObjectFromButtonCircle()" title="Desenhar Jogador"><i class="far fa-circle"></i></button> ' +
                            '</div>' +
                            '<div class="col-auto" style="width: 55px; position: relative; padding-left: 0 !important; padding-right: 0px !important;"> ' +
                                '<input class="skewBtn black" type="color" id="color-picker" value="#ff0000" title="Escolher Cor" style=" width: 50px; height: 15px; opacity:100; position:relative; left:0; top:0px;"> ' +
                                '<input class="skewBtn black" type="range" id="color-picker-alpha" min="1" max="100" value="100" title="Transparencia" style=" width: 50px; height: 15px; opacity:100; position:relative; left:0; top:0px;"> ' +
                            '</div>' +
                            '<div class="col-auto" style="padding-top: 7px; padding-left: 0 !important; padding-right: 0px !important;" > ' +
                                '<button type="button" class="skewBtn black" style="width: 35px; height: 30px;" onclick="remove()" title="Apagar"><i class="fas fa-eraser"></i></button> ' +
                            '</div>' +
                            '<div class="col-auto" style="padding-top: 7px; padding-left: 0 !important; padding-right: 0px !important;" > ' +
                                '<button type="button" class="skewBtn black" style="width: 35px; height: 30px;" onclick="setArea()" title="Desenhar Area"><span class="iconify" data-icon="pixelarticons:drop-area"></span></button> ' +
                            '</div>' +
                            '<div class="col-auto" style="padding-top: 7px; width: auto; padding-left: 0 !important; padding-right: 0px !important;">' +
                                '<select class="skewSelect blackSelect"  name="cones" id="cones" style=" height: 30px;">' +
                                    '<option value="red">Cone Vermelho</option>' +
                                    '<option value="yellow">Cone Amarelo</option>' +
                                    '<option value="orange">Cone Laranja</option>' +
                                    '<option value="blue">Cone Azul</option>' +
                                '</select>' +
                                '<button type="button" class="skewBtn black" style="width: 35px; height: 30px;" onclick="insertImage()" title="Desenhar Cone"><span class="iconify" data-icon="bx:bx-traffic-cone"></span></i></button> ' +
                            '</div>' +
                            '<div class="col-auto" style="padding-top: 7px; padding-left: 0 !important; padding-right: 0px !important;" > ' +
                                '<button type="button" class="skewBtn black" style="width: 35px; height: 30px;" onclick="insertGoal()" title="Desenhar Baliza"><span class="iconify" data-icon="emojione-monotone:goal-net"></span></button> ' +
                            '</div>' +
                            '<div class="col-auto" style="padding-top: 7px; padding-left: 0 !important; padding-right: 0px !important;" > ' +
                                '<button type="button" class="skewBtn black" style="width: 35px; height: 30px;" onclick="setArrow()" title="Desenhar Seta"><span class="iconify" data-icon="akar-icons:arrow-up-right"></span></button> ' +
                            '</div>' +
                        '</div>' +
                        '<div class="row" id="inputs-slider-row" style="margin-top:1rem;">' +
                            '<div class="col-xxl-auto" style="padding-top: 0; padding-left: 0 !important; padding-right: 0 !important; width: 100%" > ' +
                                '<div class="slideContainer">' +
                                    '<input class="slider" type="range" id="input-Rotation" name="input-Rotation" min="0" max="360" value="0" step="90" title="Rotação" style="width: 100%; height: 15px; opacity:100; position:relative; left:0; top:0;"> ' +
                                '</div>' +
                            '<div style="background-color: black; color: white;">' +
                                '<p>Ângulo: <span id="sliderValue"></span></p>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<!-- Tempo, jogadores, espaco -->' +
                    '<div class="row" style="margin-top:1rem;">' +
                        '<div class="col-md-3" style="margin-bottom: 0.2rem;">' +
                            '<table style="width:100%">' +
                                '<thead style="background:#B3B6B7;">' +
                                    '<tr>' +
                                        '<th  class="text-center"> <span class="iconify-inline" data-icon="akar-icons:clock" data-width="26" data-height="26"></span></th>' +
                                    '</tr>' +
                                '</thead>' +
                                '<tbody>' +
                                    '<tr>' +
                                        '<td class="text-center" style="background: background:#F2F3F4">' +
                                            '<input class="selector" type="number" id="tempo_' + counter +'" name="tempo_' + counter + '" min="1" max="120" style="opacity:100 !important; position:relative !important; left:0 !important; width:100% !important; height: 38px; border: 1px solid #85C1E9; background:#F2F3F4;"> ' +
                                        '</td>' +
                                    '</tr>' +
                                '</tbody>' +
                            '</table>' +
                        '</div>' +
                        '<div class="col-md-3" style="margin-bottom: 0.2rem;">' +
                            '<table style="width:100%">' +
                                '<thead style="background:#B3B6B7;">' +
                                    '<tr>' +
                                        '<th class="text-center"><span class="iconify-inline" data-icon="ph:users-four-light" data-width="24" data-height="24"></span></th> ' +
                                    '</tr>' +
                                '</thead>' +
                                '<tbody>' +
                                    '<tr style="background: #F2F3F4">' +
                                        '<td class="text-center">' +
                                            '<input class="selector" type="number" id="n-jogadores_' + counter +'" name="n-jogadores_' + counter +'" value="" min="1" max="20" required style="opacity:100 !important; position:relative !important; left:0 !important; width:100% !important; height: 38px; border: 1px solid #85C1E9; background:#F2F3F4;"> ' +
                                        '</td>' +
                                    '</tr>' +
                                '</tbody>' +
                            '</table>' +
                        '</div>' +
                        '<div class="col-md-6" style="margin-bottom: 0.2rem;">' +
                            '<table style="width:100%;">' +
                                '<thead style="background:#B3B6B7;">' +
                                    '<tr>' +
                                        '<th  class="text-center"><span class="iconify-inline" data-icon="mdi:soccer-field" data-width="26" data-height="26"></span></th> ' +
                                    '</tr>' +
                                '</thead>' +
                                '<tbody>' +
                                    '<tr>' +
                                        '<td class="text-center">' +
                                            '<select class="form-select" id="input-CanvasHalf_' + counter +'" name="input-CanvasHalf_' + counter +'" size="1" onchange="changeCanvasHalf(this)" style="width: 100%; height: 40px; border: 0; background:#F2F3F4;"> ' +
                                                '<option value="meio-campo-direita">Meio Campo (D)</option> ' +
                                                '<option value="meio-campo-esquerda">Meio Campo (E)</option> ' +
                                                '<option value="campo-inteiro" selected>Campo Inteiro</option> ' +
                                            '</select>'+
                                        '</td>' +
                                    '</tr>' +
                                '</tbody>' +
                            '</table>' +
                        '</div>' +
                    '</div>' +
                    '<!-- objectivos especificos -->' +
                    '<div class="row" style="margin-top:1rem">' +
                        '<div class="col">' +
                        '<h6  class="text-center">Objectivo(s) especifíco(s)</h6>' +
                            '<div class="row-cols-1">' +
                                '<label>' +
                                '<textarea class="textarea-field" id="objectivosEspecificos_' + counter +'" name="objectivosEspecificos_' + counter +'" required></textarea>' +
                                '</label >' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<!-- Descricao e Organizacao -->' +
                    '<div class="row" style="margin-top:1rem">' +
                        '<div class="col">' +
                            '<h6 class="text-center">Descrição e Organização Metodologica</h6>' +
                                '<div class="row-cols-1">' +
                                    '<label>' +
                                    '<textarea class="textarea-field" id="descricao_'+ counter +'" name="descricao_'+ counter +'" required></textarea>' +
                                    '</label>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<button type="button" id="btnSalvarExer_' + counter +'" name="btnSalvarExer_' + counter +'" style="margin-top:0.5rem; float: right;" class="skewBtn black" onclick="salvarExercicio()">Salvar Exercício</button>' +
                    '</div>' +
                '</div>' +
                '<!-- END CARD -->');
    $('#cardExercice').append(newDiv);
    document.getElementsByName("btnNewExercice")[0].setAttribute("disabled", "true");
    return;
}

async function loadCanvas(){
    await addCardExercicio();
    initCanvasFull(counter);
    slider();
}

function salvarExercicio(){
    let canvas = document.getElementById("canvas");
    let url = "backgroundCanvas-row_" + counter.toString();
    let backgroundCanvasImg = document.getElementById(url);
    let btnCanvas = document.getElementById("inputs-canvas-row");
    let sliderCanvas = document.getElementById("inputs-slider-row");
    let img = backgroundCanvasImg.style.backgroundImage.slice(4, -1).replace(/"/g, "");

    setBackgroundCanvas(canvas, img, counter);

    canvas.remove();
    sliderCanvas.remove();
    btnCanvas.remove();
    document.getElementById("btnNewExercice").disabled = false;
    document.getElementById("btnSalvarTreino").disabled = false;
    document.getElementById("tempo_" + counter.toString()).readOnly = true;
    document.getElementById("n-jogadores_" + counter.toString()).readOnly = true;
    document.getElementById("input-CanvasHalf_" + counter.toString()).disabled = true;
    document.getElementById("btnSalvarExer_" + counter.toString()).hidden = true;
}

function slider(){
    let slider = document.getElementById("input-Rotation");
    let output = document.getElementById("sliderValue");
    output.innerHTML = slider.value;

    slider.oninput = function() {
        output.innerHTML = this.value;
        sliderUpdate();
    }
}

function CanvasHalfRight(){
    initCanvasHalfRight(counter);
}

function CanvasHalfLeft(){
    initCanvasHalfLeft(counter);
}

function CanvasFull(){
    initCanvasFull(counter);
}

function submitTreino(){
    // Get first form element
    let $form = $('form')[0];

    // Check if valid using HTML5 checkValidity() builtin function
    if ($form.checkValidity()) {
        console.log('valid');
        $form.submit();
    } else {
        console.log('not valid');
    }
    return false;
}

function changeCanvasHalf(select){
    switch (select.value){
        case "meio-campo-direita":
            CanvasHalfRight();
            break;
        case"meio-campo-esquerda":
            CanvasHalfLeft()
            break;
        case "campo-inteiro":
            CanvasFull()
            break;
    }
}

function changeTeam(select){
    let selectedEquipa = select.value;

    if (select.value !== ""){
        document.getElementById("btnNewTrain").disabled = false;
    } else {
        document.getElementById("btnNewTrain").disabled = true;
    }

    let args = "IDEquipa="+selectedEquipa;

    xmlHttp = new GetXmlHttpObject();
    xmlHttp.open("GET", "getSelectTreinosEquipa.php?"+args, true);
    xmlHttp.onreadystatechange = SelectEquipaHandleReply;
    xmlHttp.send(null);
}

function SelectEquipaHandleReply(){
    if (xmlHttp.readyState === 4){
        let selectInputTreino = document.getElementById("input-treino");
        selectInputTreino.removeAttribute("hidden");
        selectInputTreino.options.length = 0;

        let treinos = JSON.parse(xmlHttp.responseText);

        for (i = 0; i < treinos.length; i++){
            let currentTreino  = treinos[i];
            let value = currentTreino.idPlano;
            let option = currentTreino.dataHora;

            try{
                selectInputTreino.add(new Option("", value), null);
            } catch (e) {
                selectInputTreino.add(new Option("", value));
            }

            selectInputTreino.options[i].innerHTML = option;
        }
    }
}

function changeTreino(select){
    let selectedtreino = select.value;

    let args = "IDPlano="+selectedtreino;

    xmlHttp = new GetXmlHttpObject();
    xmlHttp.open("GET", "getTreinoEquipaFromDB.php?"+args, true);
    xmlHttp.onreadystatechange = changeTreinoHandleReply;
    xmlHttp.send(null);
}

function changeTreinoHandleReply(){
    if (xmlHttp.readyState === 4){
        let treinos = JSON.parse(xmlHttp.responseText);

        let volume = treinos[0].volume;
        let periodo = treinos[1].periodo;
        let mesaciclos = treinos[2].mesaciclos;
        let microciclos = treinos[3].microciclos;

        loadHeadTrain(volume, periodo, mesaciclos, microciclos)

        for (i = 4; i < treinos.length; i++){
            let nJogadores = treinos[i].nJogadores;
            let tempo = treinos[i].tempo;
            let titulo = treinos[i].titulo;
            let urlImg = treinos[i].urlImg;
            let objectivoEspecifico = treinos[i].objectivoEspecifico;
            let descricaoOrganizacaoMetodologica = treinos[i].descricaoOrganizacaoMetodologica;

            addCard(titulo, nJogadores, tempo, urlImg, objectivoEspecifico, descricaoOrganizacaoMetodologica);
        }
    }
}

function GetXmlHttpObject() {
    try {
        return new ActiveXObject("Msxml2.XMLHTTP");
    } catch(e) {} // Internet Explorer
    try {
        return new ActiveXObject("Microsoft.XMLHTTP");
    } catch(e) {} // Internet Explorer
    try {
        return new XMLHttpRequest();
    } catch(e) {} // Firefox, Opera 8.0+, Safari
    alert("XMLHttpRequest not supported");
    return null;
}

async function showHeaderreino(volume, periodo, mesaciclos, microciclos){
    let backgroundAnimation = document.getElementById("background-animation");
    if (backgroundAnimation)
        backgroundAnimation.remove();
    let rowHeader = document.getElementById("rowHeader");
    if (rowHeader)
        rowHeader.remove();
    let cardBodyTrains = document.getElementsByName("cardBodyTrains");
    if (cardBodyTrains.length > 0){
        let len = cardBodyTrains.length;
        let parentNode = cardBodyTrains[0].parentNode;
        for(let i=0; i<len; i++)
        {
            parentNode.removeChild(cardBodyTrains[0]);
        }
    }
    let cardBodyExercice = document.getElementsByName("cardBodyExercice");
    if (cardBodyExercice.length > 0){
        let len = cardBodyExercice.length;
        let parentNode = cardBodyExercice[0].parentNode;
        for(let i=0; i<len; i++)
        {
            parentNode.removeChild(cardBodyExercice[0]);
        }
    }

    document.getElementsByName("btnNewTrain")[0].removeAttribute("disabled");
    document.getElementsByName("btnNewExercice")[0].setAttribute("hidden", "true");
    document.getElementsByName("btnSalvarTreino")[0].setAttribute("hidden", "true");

    let newDiv = $('<div class="row" id="rowHeader" style="margin-top: 1rem !important; padding-bottom: 1rem !important;"> ' +
        '<div class="col-xxl-12">' +
            '<div class="card mb-auto">' +
                '<div class="card-header">' +
                    '<h6><span class="iconify-inline" data-icon="bi:pencil-square" data-width="20" data-height="20"></span>  Plano de treino</h6>' +
                '</div>' +
                '<div class="card-body">' +
                    '<table id="table" class="" style="width:100% !important; "> ' +
                        '<thead>' +
                            '<tr>' +
                                '<th></th>' +
                                '<th></th>' +
                                '<th></th>' +
                                '<th></th>' +
                            '</tr>' +
                        '</thead>' +
                        '<tbody>' +
                            '<tr>' +
                                '<th>' +
                                    '<h6>Microciclo: </h6>' +
                                    '<h6 style="display: flex; justify-content: center; align-items: center;  width: auto; height: 40px; opacity:100; margin-left: 5px; border: 0; background:#F2F3F4;">' + microciclos + '</h6> ' +
                                '</th>' +
                                '<th>' +
                                    '<h6>Mesociclos: </h6>' +
                                    '<h6 style="display: flex; justify-content: center; align-items: center;  width: auto; height: 40px; opacity:100; margin-left: 5px; border: 0; background:#F2F3F4;">' + mesaciclos + '</h6> ' +
                                '</th>' +
                                '<th>' +
                                    '<h6>Período: </h6> ' +
                                    '<h6 style="resize: none; display: flex; justify-content: center; align-items: center;  width: auto; height: 40px; opacity:100; margin-left: 5px; border: 0; background:#F2F3F4;">' + periodo.toUpperCase()  + '</h6> ' +
                                '</th>' +
                                '<th>' +
                                    '<h6>Volume: </h6>' +
                                    '<h6 style="display: flex; justify-content: center; align-items: center;  width: auto; height: 40px; opacity:100; margin-left: 5px; border: 0; background:#F2F3F4;">' + volume + '</h6> ' +
                                '</th>' +
                            '</tr>' +
                        '</tbody>' +
                    '</table>' +
                '</div>' +
            '</div>' +
        '</div>');
    $('#cardPlanoTreino').append(newDiv);
}

async function loadHeadTrain(volume, periodo, mesaciclos, microciclos){
    await showHeaderreino(volume, periodo, mesaciclos, microciclos);
    $('#table').DataTable( {
        "scrollX": true,
        "searching": false,
        "paging": false,
        "info": false,
        "ordering": false
    } );
}

function addCard(titulo, nJogadores, tempo, urlImg, objectivoEspecifico, descricaoOrganizacaoMetodologica){
    let newDiv = $(
        '<!-- CARD --> ' +
        '<div class="col-xl-4" name="cardBodyTrains"> ' +
            '<div class="card mb-4"> ' +
                '<div class="card-header"> ' +
                    '<h6> <i class="fas fa-futbol me-1"></i> ' + titulo + '</h6>' +
                '</div> ' +
                '<div class="card-body"> ' +
                '<!--canvas --> ' +
                '<div class="row">' +
                    '<img src="' + urlImg +'" alt=""/>'+
                '</div>' +
                '<!-- Tempo, jogadores, espaco -->' +
                '<div class="row" style="margin-top:1rem;">' +
                    '<div class="col-xxl-6" style="margin-bottom: 0.2rem;">' +
                        '<table style="width:100%">' +
                            '<thead style="background:#B3B6B7;">' +
                                '<tr>' +
                                    '<th  class="text-center"> <span class="iconify-inline" data-icon="akar-icons:clock" data-width="26" data-height="26"></span></th>' +
                                '</tr>' +
                            '</thead>' +
                            '<tbody>' +
                                '<tr>' +
                                    '<td class="text-center" style="background: background:#F2F3F4">' +
                                        '<h6 style="display: flex; justify-content: center; align-items: center;  width: auto; height: 40px; opacity:100; margin-left: 5px; border: 0; background:#F2F3F4;">' + tempo + '</h6>' +
                                    '</td>' +
                                '</tr>' +
                            '</tbody>' +
                        '</table>' +
                    '</div>' +
                    '<div class="col-xxl-6" style="margin-bottom: 0.2rem;">' +
                        '<table style="width:100%">' +
                            '<thead style="background:#B3B6B7;">' +
                                '<tr>' +
                                    '<th class="text-center"><span class="iconify-inline" data-icon="ph:users-four-light" data-width="24" data-height="24"></span></th> ' +
                                '</tr>' +
                            '</thead>' +
                            '<tbody>' +
                                '<tr style="background: #F2F3F4">' +
                                    '<td class="text-center">' +
                                        '<h6 style="display: flex; justify-content: center; align-items: center;  width: auto; height: 40px; opacity:100; margin-left: 5px; border: 0; background:#F2F3F4;"> ' + nJogadores + ' </h6>' +
                                    '</td>' +
                                '</tr>' +
                            '</tbody>' +
                        '</table>' +
                    '</div>' +
                '</div>' +
                '<!-- objectivos especificos -->' +
                '<div class="row" style="margin-top:1rem">' +
                    '<div class="col">' +
                        '<h6  class="text-center">Objectivo(s) especifíco(s)</h6>' +
                        '<div class="row-cols-1">' +
                        '<label>' +
                            '<textarea class="textarea-field" disabled>' + objectivoEspecifico + '</textarea>' +
                        '</label >' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<!-- Descricao e Organizacao -->' +
                '<div class="row" style="margin-top:1rem">' +
                    '<div class="col">' +
                        '<h6 class="text-center">Descrição e Organização Metodologica</h6>' +
                        '<div class="row-cols-1">' +
                            '<label>' +
                                '<textarea class="textarea-field" disabled> ' + descricaoOrganizacaoMetodologica + ' </textarea>' +
                            '</label>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>' +
        '<!-- END CARD -->');
    $('#cardExercice').append(newDiv);
}