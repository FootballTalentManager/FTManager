let dict = []; // create an empty array

$(document).ready(function () {
    push(dict, "3-2-3-2", "img/Taticas/3-2-3-2.jpg");
    push(dict, "3-2-5", "img/Taticas/3-2-5.jpg");
    push(dict, "3-3-2-2", "img/Taticas/3-3-2-2.jpg");
    push(dict, "3-3-4A", "img/Taticas/3-3-4A.jpg");
    push(dict, "3-3-4B", "img/Taticas/3-3-4B.jpg");

    push(dict, "3-4-3A", "img/Taticas/3-4-3A.jpg");
    push(dict, "3-4-3B", "img/Taticas/3-4-3B.jpg");
    push(dict, "3-5-2", "img/Taticas/3-5-2.jpg");
    push(dict, "4-2-3-1", "img/Taticas/4-2-3-1.jpg");
    push(dict, "4-2-4A", "img/Taticas/4-2-4A.jpg");
    push(dict, "4-2-4B", "img/Taticas/4-2-4B.jpg");
    push(dict, "4-3-3A", "img/Taticas/4-3-3A.jpg");
    push(dict, "4-3-3B", "img/Taticas/4-3-3B.jpg");
    push(dict, "4-4-2A", "img/Taticas/4-4-2A.jpg");
    push(dict, "4-4-2B", "img/Taticas/4-4-2B.jpg");
    push(dict, "4-5-1", "img/Taticas/4-5-1.jpg");
    push(dict, "5-2-3A", "img/Taticas/5-2-3A.jpg");
    push(dict, "5-2-3B", "img/Taticas/5-2-3B.jpg");

    push(dict, "5-3-1-1", "img/Taticas/5-3-1-1.jpg");
    push(dict, "5-3-2", "img/Taticas/5-3-2.jpg");
    push(dict, "5-4-1A", "img/Taticas/5-4-1A.jpg");
    push(dict, "5-4-1B", "img/Taticas/5-4-1B.jpg");
    push(dict, "6-3-1A", "img/Taticas/6-3-1A.jpg");

    push(dict, "6-3-1B", "img/Taticas/6-3-1B.jpg");

    addOptionsToSelect();

    document.getElementById("salvarJogo").disabled = true;
})

function push(dict, key, value){
    dict.push({
        key: key,
        value: value
    });
}

function changeFormtionHome(select){
    let img = document.getElementById("imgFormationHome");
    img.src = select.value;
}

function changeFormtionAway(select){
    let img = document.getElementById("imgFormationAway");
    img.src = select.value;
}

function addOptionsToSelect(){
    let dictSize = dict.length;

    let formationHome = getFormationHome();
    let formationAway = getFormationAway();

    let selectHomeTeam = document.getElementById('selectFormationHome');
    let selectAwayTeam = document.getElementById('selectFormationAway');

    for (let i = 0; i < dictSize; i++){
        let optAway = document.createElement("option");
        optAway.value = dict[i].value;
        optAway.innerHTML = dict[i].key;
        if (formationAway === dict[i].key){
            optAway.setAttribute("selected", true);
        }

        let optHome = document.createElement("option");
        optHome.value = dict[i].value;
        optHome.innerHTML = dict[i].key;
        if (formationHome === dict[i].key){
            optHome.setAttribute("selected", true);
        }

        selectHomeTeam.appendChild(optHome);
        selectAwayTeam.appendChild(optAway);
    }

    if (formationHome !== ""){
        changeFormtionHome(selectHomeTeam);
    }

    if (formationAway !== ""){
        changeFormtionAway(selectAwayTeam);
    }
}

function changeToEditMode(){
    document.getElementById("editarJogo").disabled = true;
    document.getElementById("salvarJogo").disabled = false;

    let tableGeral = $('#tableGeral').DataTable();
    let tableGolos = $('#tableGolos').DataTable();
    let tableSubs = $('#tableSubs').DataTable();

    let addRowTableGeral = tableGeral.buttons( ['#addRowTableGeral'] );
    let addRowTableGolos = tableGolos.buttons( ['#addRowTableGolos'] );
    let addRowTableSubs = tableSubs.buttons( ['#addRowTableSubs'] );
    addRowTableGeral.enable();
    addRowTableGolos.enable();
    addRowTableSubs.enable();

    let nRowsTableGeral = tableGeral.data().count();
    if (nRowsTableGeral > 0){
        let btnSaveTableGeral = tableGeral.buttons( ['#btnSavetableGeral'] );
        btnSaveTableGeral.enable();
    }

    let nRowsTableGolos = tableGolos.data().count();
    if(nRowsTableGolos > 0){
        let btnSaveTableGolos = tableGolos.buttons( ['#btnSaveTableGolos'] );
        btnSaveTableGolos.enable();
    }

    document.getElementById("input-totalGoalsAwayFirst").disabled = false;
    document.getElementById("input-totalGoalsAwaySecond").disabled = false;
    document.getElementById("selectFormationHome").disabled = false;
    document.getElementById("input-ArbitroPrincipal").disabled = false;
    document.getElementById("input-ArbitroPrincipalClass").disabled = false;
    document.getElementById("input-ArbitroAss1").disabled = false;
    document.getElementById("input-ArbitroAss1Class").disabled = false;
    document.getElementById("input-ArbitroAss2").disabled = false;
    document.getElementById("input-ArbitroAss2Class").disabled = false;
    document.getElementById("input-QuartoArbitro").disabled = false;
    document.getElementById("input-QuartoArbitroClass").disabled = false;
    document.getElementById("selectFormationAway").disabled = false;
}