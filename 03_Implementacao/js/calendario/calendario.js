let counter = 1
let teamNameSelected = null;

function addJornada(){
    console.log("TeamNameSelected: " + teamNameSelected);
    let jornada = $('<div class="col c-mg-bt" > ' +
                        '<div class="card"> ' +
                            '<div class="card-header">' +
                                '<span>Jornada<input type="text" placeholder="Nº" class="n-jor"></span> ' +
                            '</div> ' +
                            '<div class="row center-content card-body"> ' +
                                '<div class="row center-content row-padding r-style-dark"> ' +
                                    '<div class="col-sm-auto c-padding" > ' +
                                        '<input type="datetime-local" class="input-style-1" name="input-dateTime_'+ counter +'" required title="Data e Hora do jogo"/> ' +
                                    '</div> ' +
                                    '<div class="col-sm-auto c-padding" > ' +
                                        '<input type="text" class="input-style-1" name="input-local_'+ counter +'" required title="Local do jogo" placeholder="Local do Jogo"/> ' +
                                    '</div> ' +
                                '</div> ' +
                                '<div class="row vert-center center-content row-padding r-style"> ' +
                                    '<div class="col c-padding"> ' +
                                        '<div class="row inner-row"> ' +
                                            '<input type="text" value="'+ teamNameSelected +'" class="input-style-2" readonly title="Equipa da casa"> ' +
                                        '</div> ' +
                                        '<div class="row "> ' +
                                            '<input type="number" placeholder="0" class="input-style-2" readonly title="Resultado casa"> ' +
                                        '</div> ' +
                                    '</div> ' +
                                    '<div class="col c-padding fullHeight"> ' +
                                        '<h6 class="text-center">VS</h6> ' +
                                    '</div> ' +
                                    '<div class="col-sm c-padding" > ' +
                                        '<div class="row inner-row"> ' +
                                            '<input type="text" placeholder="Fora" class="input-style-2" name="input-NameAway_'+ counter +'" required title="Equipa de fora"> ' +
                                        '</div> ' +
                                        '<div class="row"> ' +
                                            '<input type="number" placeholder="0" class="input-style-2" readonly title="Resultado fora"> ' +
                                        '</div> ' +
                                    '</div> ' +
                                '</div> ' +
                            '</div> ' +
                        '</div> ' +
                    '</div> ');
    let btnSaveJornada = document.getElementById("saveJornada");
    btnSaveJornada.removeAttribute("disabled");
    document.getElementById('valConter').value = counter;
    counter++;
    $('#jornadas').append(jornada);
}

function submitChanges(){
    if (formJornada.checkValidity()) {
        formJornada.submit();
    } else {
        alert("Existem dados por preencher!!");
    }
}

function changeTeamAndGetComp(select){
    let selectedEquipa = select.value;
    document.getElementById("equipaCasaSubmit").value = $("#input-equipa :selected").text();
    document.getElementById("IDTeam").value = selectedEquipa;
    teamNameSelected = $(':selected', select).attr('id');

    let args = "IDEquipa="+selectedEquipa;

    xmlHttp = new GetXmlHttpObject();
    xmlHttp.open("GET", "getCompByEquipaFromDB.php?"+args, true);
    xmlHttp.onreadystatechange = SelectEquipaHandleReply;
    xmlHttp.send(null);
}

function SelectEquipaHandleReply(){
    if (xmlHttp.readyState === 4){
        let CompName = JSON.parse(xmlHttp.responseText);

        console.log(CompName);

        let selectInputTreino = document.getElementById("input-competicao");
        selectInputTreino.removeAttribute("hidden");
        selectInputTreino.options.length = 0;

        for (let i = 0; i < CompName.length; i++){
            let currentTreino  = CompName[i];
            let value = currentTreino.CompID;
            let option = currentTreino.CompName;

            if (value !== ""){
                try{
                    selectInputTreino.add(new Option("", value), null);
                } catch (e) {
                    selectInputTreino.add(new Option("", value));
                }
                selectInputTreino.options[i].innerHTML = option;
                if (i === 0){
                    selectInputTreino.options[i].selected = true;
                    selectInputTreino.options[i].disabled = true;
                }
            }
        }
    }
}

function changeComp(select){
    let selectedComp = select.value;
    document.getElementById("compNameSubmit").value = $("#input-competicao :selected").text();
    let btnAddJornada = document.getElementById("addJornada");
    btnAddJornada.removeAttribute("disabled");
    document.getElementById("IDComp").value = selectedComp;

    let teamSelected = document.getElementById("input-equipa");
    let selectedEquipa = teamSelected.value;

    let args = "IDEquipa="+selectedEquipa + "&" + "IDComp=" + selectedComp;

    console.log(args);

    xmlHttpGetJogos = new GetXmlHttpObject();
    xmlHttpGetJogos.open("GET", "getJogosCompFromDB.php?"+args, true);
    xmlHttpGetJogos.onreadystatechange = changeCompHandleReply;
    xmlHttpGetJogos.send(null);

}

function changeCompHandleReply(){
    if (xmlHttpGetJogos.readyState === 4) {
        let CompName = JSON.parse(xmlHttpGetJogos.responseText);
        console.log(CompName);

        for (let i = 0; i < CompName.length; i++) {
            let IDJogo = CompName[i].IDJogo;
            let DataHora = CompName[i].DataHora.replace(" ", "T");
            let equipaFora = CompName[i].equipaFora;
            let local = CompName[i].local;

            let jornada = $('<div class="col c-mg-bt" > ' +
                                '<div class="card dblclick" style="cursor: pointer;" id="'+IDJogo+'"> ' +
                                    '<div class="card-header">' +
                                        '<span>Jornada<input type="number" value="'+(i + 1)+'" readonly class="n-jor"></span> ' +
                                    '</div> ' +
                                    '<div class="row center-content card-body"> ' +
                                        '<div class="row center-content row-padding r-style-dark"> ' +
                                            '<div class="col-sm-auto c-padding" > ' +
                                                '<input type="datetime-local" class="input-style-1" value="'+ DataHora +'" readonly title="Data e Hora do jogo"/> ' +
                                            '</div> ' +
                                            '<div class="col-sm-auto c-padding" > ' +
                                                '<input type="text" class="input-style-1" value="'+ local +'" readonly title="Local do jogo" placeholder="Local do Jogo"/> ' +
                                            '</div> ' +
                                        '</div> ' +
                                        '<div class="row vert-center center-content row-padding r-style"> ' +
                                            '<div class="col c-padding"> ' +
                                                '<div class="row inner-row"> ' +
                                                    '<input type="text" class="input-style-2" value="'+ teamNameSelected +'" readonly title="Equipa da casa"> ' +
                                                '</div> ' +
                                                '<div class="row "> ' +
                                                    '<input type="number" placeholder="0" class="input-style-2" readonly title="Resultado casa"> ' +
                                                '</div> ' +
                                            '</div> ' +
                                            '<div class="col c-padding fullHeight"> ' +
                                                '<h6 class="text-center">VS</h6> ' +
                                            '</div> ' +
                                            '<div class="col-sm c-padding" > ' +
                                                '<div class="row inner-row"> ' +
                                                    '<input type="text" placeholder="Fora" class="input-style-2" value="'+equipaFora+'" readonly title="Equipa de fora"> ' +
                                                '</div> ' +
                                                '<div class="row"> ' +
                                                    '<input type="number" placeholder="0" class="input-style-2" readonly title="Resultado fora"> ' +
                                                '</div> ' +
                                            '</div> ' +
                                        '</div> ' +
                                    '</div> ' +
                                '</div> ' +
                            '</div> ');
            $('#jornadas').append(jornada);
        }

        let touchTime = 0;
        $(".dblclick").on("click", function(event) {
            if (touchTime === 0) {
                // set first click
                touchTime = new Date().getTime();
            } else {
                // compare first click to this click and see if they occurred within double click threshold
                if (((new Date().getTime()) - touchTime) < 200) {
                    // double click occurred
                    document.getElementById('IDJogoSubmit').value = this.id;
                    document.getElementById('jornadaSubmit').value = this.childNodes[1].childNodes[0].childNodes[1].value;
                    formSubmitJogo.submit();
                    touchTime = 0;
                } else {
                    // not a double click so set as a new first click
                    touchTime = new Date().getTime();
                }
            }
        });
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