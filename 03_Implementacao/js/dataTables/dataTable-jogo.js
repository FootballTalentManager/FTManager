let countPlayersTableGeral;
let countPlayersTableGolos;
let countPlayersTableSubs;

$(document).ready(function () {
    Table();
    document.getElementById("countTableGeral").value = getCountPlayersTableGeral();
    document.getElementById("countTableGolos").value = getCountPlayersTableGolos();
    document.getElementById("countTableSubs").value = getCountPlayersTableGolos();
    countPlayersTableSubs = parseInt(getCountPlayersTableSubs()) + 1;
    countPlayersTableGeral = parseInt(getCountPlayersTableGeral()) + 1;
    countPlayersTableGolos = parseInt(getCountPlayersTableGolos()) + 1;
})

async function creatTableGeral(){
    let table = $('#tableGeral').DataTable({
        "dom": "<'row'<'col-md-12'B>><'row'<'col-md-12't>><'row'<'col-md-6'l><'col-md-6'p>>",
        "scrollX": true,
        "searching": false,
        "autoWidth": true,
        "language": {
            "sInfo": "",
            "oPaginate": {
                "sFirst":    "<<",
                "sPrevious": "<",
                "sNext":     ">",
                "sLast":     ">>"
            },
            "sLengthMenu":   "Mostrar _MENU_ registos",
            "sZeroRecords":  "Não foram encontrados resultados",
            "sInfoEmpty":    ""
        },
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "columnDefs": [
            { "width": "200px", "targets": 1 }
        ],
        "buttons": [
            {
                text:      '<i class="fas fa-plus-circle"></i>',
                titleAttr: 'Adicionar Jogador',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id: 'addRowTableGeral'},
                action: function (e, dt, node, config) {
                    addRowTableGeral();
                }
            },
            $.extend(!0, {}, buttonCommon, {
                extend:    'excelHtml5',
                text:      '<i class="far fa-file-excel"></i>',
                attr: { class: 'skewBtn black', style: 'width: 55px; height: 30px;', id: 'btnExcelTableGeral'},
                titleAttr: 'Baixar Excel'
            }),
            $.extend(!0, {}, buttonCommon, {
                extend:    'pdfHtml5',
                text:      '<i class="far fa-file-pdf"></i>',
                attr: { class: 'skewBtn black', style: 'width: 55px; height: 30px;', id: 'btnPdfTableGeral'},
                titleAttr: 'Baixar PDF'
            }),
            {
                text:      '<i class="far fa-save"></i>',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id:'btnSavetableGeral'},
                titleAttr: 'Salvar Alterações',
                action: function (e, dt, node, config){
                    submitTableG();
                }
            }
        ]
    });
    return;
}

function addRowTableGeral(){
    let table = $('#tableGeral').DataTable();
    table.row.add([
        countPlayersTableGeral,
        '<select class="form-select" id="input-player'+countPlayersTableGeral+'" name="input-player'+countPlayersTableGeral+'" size="1" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">' +
            '<option value="">- Selecione Jogador -</option>' +
        '</select>',
        '<input type="number" id="input-classificacao'+countPlayersTableGeral+'" name="input-classificacao'+countPlayersTableGeral+'" min="0" max="10" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">',
        '<input type="number" id="input-remates'+countPlayersTableGeral+'" name="input-remates'+countPlayersTableGeral+'" min="0" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">',
        '<input type="number" id="input-primeroAmarelo'+countPlayersTableGeral+'" name="input-primeroAmarelo'+countPlayersTableGeral+'" min="0" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">',
        '<input type="number" id="input-segundoAmarelo'+countPlayersTableGeral+'" name="input-segundoAmarelo'+countPlayersTableGeral+'" min="0" max="90" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">',
        '<input type="number" id="input-vermelho'+countPlayersTableGeral+'" name="input-vermelho'+countPlayersTableGeral+'" min="0" max="90" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">',
        '<input type="number" id="input-fSofridas'+countPlayersTableGeral+'" name="input-fSofridas'+countPlayersTableGeral+'" min="0" max="90" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">',
        '<input type="number" id="input-fCometidas'+countPlayersTableGeral+'" name="input-fCometidas'+countPlayersTableGeral+'" min="0" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">'
    ]).node().id = 'new';
    table.draw(false);
    columnsAdjustDT();
    let btnSaveTableGeral = table.buttons( ['#btnSavetableGeral'] );
    //if (countPlayersTableGeral >= 11){
    btnSaveTableGeral.enable();
    //}
    //document.getElementById('valConter').value = counter;

    let selectInputTreino = document.getElementById("input-player"+countPlayersTableGeral);
    selectInputTreino.options.length = 0;

    let arrayPlayers = getJogadores()[0];

    for (let i = 0; i < arrayPlayers.length; i++){
        let currentPlayer  = arrayPlayers[i];
        let value = currentPlayer[0];
        let option = currentPlayer[1];

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
    document.getElementById("countTableGeral").value = countPlayersTableGeral;
    countPlayersTableGeral++;
}

async function creatTableGolos(){
    let table = $('#tableGolos').DataTable({
        "dom": "<'row'<'col-md-12'B>><'row'<'col-md-12't>><'row'<'col-md-6'l><'col-md-6'p>>",
        "scrollX": true,
        "searching": false,
        "autoWidth": true,
        "language": {
            "sInfo": "",
            "oPaginate": {
                "sFirst":    "<<",
                "sPrevious": "<",
                "sNext":     ">",
                "sLast":     ">>"
            },
            "sLengthMenu":   "Mostrar _MENU_ registos",
            "sZeroRecords":  "Não foram encontrados resultados",
            "sInfoEmpty":    ""
        },
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "columnDefs": [ {
            "type": "position-grade",
            "targets": 2,
        } ],
        "buttons": [
            {
                text:      '<i class="fas fa-plus-circle"></i>',
                titleAttr: 'Adicionar Jogador',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id: 'addRowTableGolos'},
                action: function (e, dt, node, config) {
                    addRowTableGolos();
                }
            },
            $.extend(!0, {}, buttonCommon, {
                extend:    'excelHtml5',
                text:      '<i class="far fa-file-excel"></i>',
                attr: { class: 'skewBtn black', style: 'width: 55px; height: 30px;', id: 'btnExcelTableGolos'},
                titleAttr: 'Baixar Excel'
            }),
            $.extend(!0, {}, buttonCommon, {
                extend:    'pdfHtml5',
                text:      '<i class="far fa-file-pdf"></i>',
                attr: { class: 'skewBtn black', style: 'width: 55px; height: 30px;', id: 'btnPdfTableGolos'},
                titleAttr: 'Baixar PDF'
            }),
            {
                text:      '<i class="far fa-save"></i>',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id:'btnSaveTableGolos'},
                titleAttr: 'Salvar Alterações',
                action: function (e, dt, node, config){
                    submitTableGoal();
                }
            }
        ]
    });
    return;
}

function addRowTableGolos(){
    let table = $('#tableGolos').DataTable();
    table.row.add([
        countPlayersTableGolos,
        '<select class="form-select" id="input-playerGolos'+countPlayersTableGolos+'" name="input-player'+countPlayersTableGolos+'" size="1" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">' +
            '<option value="">- Selecione Jogador -</option>' +
        '</select>',
        '<input type="number" id="input-tempoGolo'+countPlayersTableGolos+'" name="input-tempoGolo'+countPlayersTableGolos+'" min="0" max="90" onblur="setScoreHome(this)" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">',
    ]).node().id = 'new';
    table.draw(false);
    columnsAdjustDT();
    let btnSaveTableGolos = table.buttons( ['#btnSaveTableGolos'] );
    btnSaveTableGolos.enable();

    let selectInputTreino = document.getElementById("input-playerGolos" + countPlayersTableGolos);
    selectInputTreino.options.length = 0;

    let arrayPlayers = getJogadores()[0];

    for (let i = 0; i < arrayPlayers.length; i++){
        let currentPlayer  = arrayPlayers[i];
        let value = currentPlayer[0];
        let option = currentPlayer[1];

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
    document.getElementById("countTableGolos").value = countPlayersTableGolos;
    countPlayersTableGolos++;
}

async function creatTableSubs(){
    let table = $('#tableSubs').DataTable({
        "dom": "<'row'<'col-md-12'B>><'row'<'col-md-12't>><'row'<'col-md-6'l><'col-md-6'p>>",
        "scrollX": true,
        "searching": false,
        "autoWidth": true,
        "language": {
            "sInfo": "",
            "oPaginate": {
                "sFirst":    "<<",
                "sPrevious": "<",
                "sNext":     ">",
                "sLast":     ">>"
            },
            "sLengthMenu":   "Mostrar _MENU_ registos",
            "sZeroRecords":  "Não foram encontrados resultados",
            "sInfoEmpty":    ""
        },
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "columnDefs": [ {
            "type": "position-grade",
            "targets": 2,
        } ],
        "buttons": [
            {
                text:      '<i class="fas fa-plus-circle"></i>',
                titleAttr: 'Adicionar Arbitro',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id: 'addRowTableSubs'},
                action: function (e, dt, node, config) {
                    addRowTableSubs();
                }
            },
            $.extend(!0, {}, buttonCommon, {
                extend:    'excelHtml5',
                text:      '<i class="far fa-file-excel"></i>',
                attr: { class: 'skewBtn black', style: 'width: 55px; height: 30px;', id: 'btnExcelTableSubs'},
                titleAttr: 'Baixar Excel'
            }),
            $.extend(!0, {}, buttonCommon, {
                extend:    'pdfHtml5',
                text:      '<i class="far fa-file-pdf"></i>',
                attr: { class: 'skewBtn black', style: 'width: 55px; height: 30px;', id: 'btnPdfTableSubs'},
                titleAttr: 'Baixar PDF'
            }),
            {
                text:      '<i class="far fa-save"></i>',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id:'btnSavetableSubs'},
                titleAttr: 'Salvar Alterações',
                action: function (e, dt, node, config){
                    submitTableS();
                }
            }
        ]
    });
    return;
}

function addRowTableSubs(){
    let table = $('#tableSubs').DataTable();
    table.row.add([
        countPlayersTableSubs,
        '<select class="form-select" id="input-playerOut'+countPlayersTableSubs+'" name="input-playerOut'+countPlayersTableSubs+'" size="1" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">' +
            '<option value="">- Selecione Jogador -</option>' +
        '</select>',
        '<select class="form-select" id="input-playerIn'+countPlayersTableSubs+'" name="input-playerIn'+countPlayersTableSubs+'" size="1" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">' +
            '<option value="">- Selecione Jogador -</option>' +
        '</select>',
        '<input type="number" id="input-tempo'+countPlayersTableSubs+'" name="input-tempo'+countPlayersTableSubs+'" min="0" max="90" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">',
    ]).node().id = 'new';
    table.draw(false);
    columnsAdjustDT();

    let selectPlayerOut = document.getElementById("input-playerOut" + countPlayersTableSubs);
    let selectPlayerIn = document.getElementById("input-playerIn" + countPlayersTableSubs);
    selectPlayerOut.options.length = 0;
    selectPlayerIn.options.length = 0;

    let arrayPlayers = getJogadores()[0];

    for (let i = 0; i < arrayPlayers.length; i++){
        let currentPlayer  = arrayPlayers[i];
        let value = currentPlayer[0];
        let option = currentPlayer[1];

        if (value !== ""){
            try{
                selectPlayerOut.add(new Option("", value), null);
                selectPlayerIn.add(new Option("", value), null);
            } catch (e) {
                selectPlayerOut.add(new Option("", value));
                selectPlayerIn.add(new Option("", value));
            }
            selectPlayerOut.options[i].innerHTML = option;
            selectPlayerIn.options[i].innerHTML = option;
            if (i === 0){
                selectPlayerOut.options[i].selected = true;
                selectPlayerIn.options[i].selected = true;
                selectPlayerOut.options[i].disabled = true;
                selectPlayerIn.options[i].disabled = true;
            }
        }
    }

    let btnSaveTableSubs = table.buttons( ['#btnSavetableSubs'] );
    //if (countPlayersTableGeral >= 11){
    btnSaveTableSubs.enable();
    document.getElementById("countTableSubs").value = countPlayersTableSubs;
    countPlayersTableSubs++;
}

async function Table(){
    await creatTableGeral();
    await creatTableGolos();
    await creatTableSubs();
    columnsAdjustDT();
    let tableGeral = $('#tableGeral').DataTable();
    let nRowsTableGeral = tableGeral.data().count();

    let btnExcelTableGeral = tableGeral.buttons( ['#btnExcelTableGeral'] );
    let btnPdfTableGeral = tableGeral.buttons( ['#btnPdfTableGeral'] );

    let tableGolos = $('#tableGolos').DataTable();
    let nRowsTableGolos = tableGolos.data().count();

    let btnExcelTableGolos = tableGolos.buttons( ['#btnExcelTableGolos'] );
    let btnPdfTableGolos = tableGolos.buttons( ['#btnPdfTableGolos'] );

    let tableSubs = $('#tableSubs').DataTable();
    let nRowsTableSubs = tableSubs.data().count();

    let btnExcelTableSubs = tableSubs.buttons( ['#btnExcelTableSubs'] );
    let btnPdfTableSubs = tableSubs.buttons( ['#btnPdfTableSubs'] );

    if (nRowsTableGeral === 0){
        btnExcelTableGeral.disable();
        btnPdfTableGeral.disable();
    }
    if (nRowsTableGolos === 0){
        btnExcelTableGolos.disable();
        btnPdfTableGolos.disable();
    }
    if (nRowsTableSubs === 0){
        btnExcelTableSubs.disable();
        btnPdfTableSubs.disable();
    }

    let btnSaveTableGeral = tableGeral.buttons( ['#btnSavetableGeral'] );
    let btnSaveTableGolos = tableGolos.buttons( ['#btnSaveTableGolos'] );
    let btnSaveTableSubs = tableSubs.buttons( ['#btnSavetableSubs'] );
    btnSaveTableGeral.disable();
    btnSaveTableGolos.disable();
    btnSaveTableSubs.disable();

    let addRowTableGeral = tableGeral.buttons( ['#addRowTableGeral'] );
    let addRowTableGolos = tableGolos.buttons( ['#addRowTableGolos'] );
    let addRowTableSubs = tableSubs.buttons( ['#addRowTableSubs'] );
    addRowTableGeral.disable();
    addRowTableGolos.disable();
    addRowTableSubs.disable();
}

function columnsAdjustDT(){
    let delayInMilliseconds = 200; //1 second
    setTimeout(function() {
        $('#tableGeral').DataTable().columns.adjust().draw();
        $('#tableGolos').DataTable().columns.adjust().draw();
        $('#tableSubs').DataTable().columns.adjust().draw();
    }, delayInMilliseconds);
}

let buttonCommon = {
    exportOptions: {
        format: {
            body: function(data, column, row, node) {
                if ($(data).is("select")){
                    return $(data)[0].options[$(data)[0].selectedIndex].text;
                }  else if ($(data).is("input")) {
                    return $(data).val();
                } else {
                    return data;
                }
            }
        }
    }
};

function setScoreAway(){
   let scoreAwayFirst = document.getElementById("input-totalGoalsAwayFirst");
   let scoreAwaySecond = document.getElementById("input-totalGoalsAwaySecond");
   let scoreTotal = document.getElementById("input-totalGoalsAway");

   scoreTotal.value = parseInt(scoreAwayFirst.value) + parseInt(scoreAwaySecond.value);
}

function setScoreHome(input){
    let valInput = input.value;

    let scoreHomeFirst = document.getElementById("input-totalGoalsHomeFirst");
    let scoreHomeSecond = document.getElementById("input-totalGoalsHomeSecond");

    let scoreTotalHome = document.getElementById("input-totalGoalsHome");

    if (parseInt(valInput) <= 45){
        scoreHomeFirst.value = parseInt(scoreHomeFirst.value) + 1
    } else {
        scoreHomeSecond.value = parseInt(scoreHomeSecond.value) + 1
    }

    scoreTotalHome.value = parseInt(scoreHomeFirst.value) + parseInt(scoreHomeSecond.value);
}

function submitTableG(){
    // Check if valid using HTML5 checkValidity() builtin function
    if (submitTableGeral.checkValidity()) {
        submitTableGeral.submit();
    } else {
        alert("Ainda existem dados por preencher!")
    }
    return false;
}

function submitTableGoal(){
    // Check if valid using HTML5 checkValidity() builtin function
    if (submitTableGolos.checkValidity()) {
        submitTableGolos.submit();
    } else {
        alert("Ainda existem dados por preencher!")
    }
    return false;
}

function submitTableS(){
    // Check if valid using HTML5 checkValidity() builtin function
    if (submitTableSubs.checkValidity()) {
        submitTableSubs.submit();
    } else {
        alert("Ainda existem dados por preencher!")
    }
    return false;
}

function submitGeralPage(){
    // Check if valid using HTML5 checkValidity() builtin function
    if (formGeral.checkValidity()) {
        formGeral.submit();
    } else {
        alert("Ainda existem dados por preencher!")
    }
    return false;
}
