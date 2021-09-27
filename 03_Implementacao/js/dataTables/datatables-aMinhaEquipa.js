let counterPlantel = 1;

$(document).ready(function () {
    creatTables();
});

$(window).resize(function () {
    columnsAdjustDT();
});

function columnsAdjustDT(){
    let delayInMilliseconds = 300; //1 second
    setTimeout(function() {
        $('#tablePlantel').DataTable().columns.adjust().draw();
        $('#tableCompeticao').DataTable().columns.adjust().draw();
    }, delayInMilliseconds);
}

async function tableComp(){
    let table = $('#tableCompeticao').DataTable({
        "dom": "<'row'<'col-md-12'B>><'row'<'col-md-12't>><'row'<'col-md-6'l><'col-md-6'p>>",
        "responsive": true,
        "scrollX": true,
        "searching": false,
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
                titleAttr: 'Adicionar Competição',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id: 'addRowComp'},
                action: function (e, dt, node, config){
                    addComp();
                    addOptionToNomeComp();
                }
            },
            {
                extend:    'excelHtml5',
                text:      '<i class="far fa-file-excel"></i>',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id: 'btnExcelComp'},
                titleAttr: 'Baixar Excel'
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="far fa-file-pdf"></i>',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id: 'btnPdfComp'},
                titleAttr: 'Baixar PDF'
            },
            {
                text:      '<i class="far fa-save"></i>',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id: 'saveChangesComp'},
                titleAttr: 'Salvar Alterações',
                action: function (e, dt, node, config){
                    if (formChangesCompeticao.checkValidity()) {
                        formChangesCompeticao.submit();
                    } else {
                        alert("Por favor, preencha todos os campos antes de submeter!!");
                    }
                }
            }
        ]
    });

    let nRows = table.data().count();

    let btnRemoveRow = table.buttons( ['#removeRowComp'] );
    let btnExcel = table.buttons( ['#btnExcelComp'] );
    let btnPdf = table.buttons( ['#btnPdfComp'] );
    let btnSave = table.buttons( ['#saveChangesComp'] );
    if (nRows === 0){
        btnRemoveRow.disable();
        btnExcel.disable();
        btnPdf.disable();
        btnSave.disable();
    } else {
        btnRemoveRow.disable();
        btnSave.disable();
    }
}

async function tablePlayers(){
    $.fn.dataTable.ext.type.order['position-grade-pre'] = function ( d ) {
        switch ( d ) {
            case 'GR':    return 1;
            case 'DD': return 2;
            case 'DE':   return 3;
            case 'DC':   return 4;
            case 'MCD':   return 5;
            case 'MC':   return 6;
            case 'MD':   return 7;
            case 'ME':   return 8;
            case 'MCO':   return 9;
            case 'EE':   return 10;
            case 'ED':   return 11;
            case 'PL':   return 12;
        }
        return 0;
    };

    $('#tablePlantel').DataTable({
        "dom": "<'row'<'col-md-12'B>><'row'<'col-md-12't>><'row'<'col-md-6'l><'col-md-6'p>>",
        "responsive": true,
        "scrollX": true,
        "searching": false,
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
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id: 'addRowPlant'},
                action: function (e, dt, node, config){
                    addPlayer();
                }
            },
            {
                extend:    'excelHtml5',
                text:      '<i class="far fa-file-excel"></i>',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id: 'btnExcelPlant'},
                titleAttr: 'Baixar Excel'
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="far fa-file-pdf"></i>',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id: 'btnPdfPlant'},
                titleAttr: 'Baixar PDF'
            },
            {
                text:      '<i class="far fa-save"></i>',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id: 'saveChangesPlant'},
                titleAttr: 'Salvar Alterações',
                action: function (e, dt, node, config){
                    if (formChanges.checkValidity()) {
                        formChanges.submit();
                    } else {
                        alert("Por favor, preencha todos os campos antes de submeter!!");
                    }
                }
            }
        ]
    });

    let table = $('#tablePlantel').DataTable();
    let nRows = table.data().count();

    let btnExcel = table.buttons( ['#btnExcelPlant'] );
    let btnPdf = table.buttons( ['#btnPdfPlant'] );
    let btnSave = table.buttons( ['#saveChangesPlant'] );
    if (nRows === 0){
        btnExcel.disable();
        btnPdf.disable();
        btnSave.disable();
    } else {
        btnSave.disable();
    }
}

async function creatTables(){
    await tableComp();
    await tablePlayers();
    eventTablePlantel();
}

function addPlayer(){
    let table = $('#tablePlantel').DataTable();
    table.row.add( [
        '<input type="number" id="input-BI_'+counterPlantel+'" name="input-BI_'+counterPlantel+'" min="10000000" max="99999999" placeholder="BI" onkeyup="fillTable(this)" onpaste="pasted(this)" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '',
        '',
        '',
        '',
        ''
    ] ).node().id = 'new';
    table.draw(false);
    document.getElementById('valConterPlantel').value = counterPlantel;
    let btnSave = table.buttons( ['#saveChangesPlant'] );
    btnSave.enable();
    counterPlantel++;
}

function addComp(){
    let table = $('#tableCompeticao').DataTable();
    let btnSave = table.buttons( ['#saveChangesComp'] );
    let btnadd = table.buttons( ['#addRowComp'] );
    btnSave.enable();
    btnadd.disable();

    table.row.add( [
        '<input type="text" id="input-epocaComp" name="input-epocaComp" value="'+ getEpoca()+'" readonly style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">',
        '<input id="input-escalaoComp" name="input-escalaoComp" value="'+ getEscalao().toLowerCase()+'" readonly style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">',
        '<select id="input-nomeComp" name="input-nomeComp" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">'+
            '<option value="" selected disabled>'+' --- Selecione ---'+'</option>'+
        '</select>'
    ] ).node().id = 'new';
    table.draw(false);
}

function eventTablePlantel(){
    let table = $('#tablePlantel').DataTable();

    let touchTime = 0;
    $("#tablePlantel tbody").on("click", 'tr', function() {
        if (touchTime === 0) {
            // set first click
            touchTime = new Date().getTime();
        } else {
            // compare first click to this click and see if they occurred within double click threshold
            if (((new Date().getTime()) - touchTime) < 200) {
                // double click occurred
                let id = table.row( this ).node().id;
                if (id !== "new"){
                    document.getElementById('hiddenField').value = table.row(this).data()[0];
                    formPlayerPage.submit();
                }
                touchTime = 0;
            } else {
                // not a double click so set as a new first click
                touchTime = new Date().getTime();
                let id = table.row( this ).node().id;
                if (id !== "new"){
                    if ( $(this).hasClass('selected') ) {
                        $(this).removeClass('selected');
                    }
                    else {
                        table.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }
                }
            }
        }
    });
}

function fillTable(input){
    let biValue = input.value;

    if (biValue.toString().length === 8){
        let args = "BIPlayer=" + biValue;
        inputTR = input.parentNode.parentNode;

        xmlHttp = new GetXmlHttpObject();
        xmlHttp.open("GET", "getPlayerEquipaFromDB.php?"+args, true);
        xmlHttp.onreadystatechange = fillTableHandleReply;
        xmlHttp.send(null);
    }
}

function pasted(element) {
    setTimeout(function(){
        console.log(element.value);
    }, 0);
}

function fillTableHandleReply(){
    if (xmlHttp.readyState === 4){
        let infoPlayer = JSON.parse(xmlHttp.responseText)[0];

        if (infoPlayer != null){
            let table = $('#tablePlantel').DataTable();
            let nRows = table.rows().count();

            let BI = infoPlayer.BI;
            let telemovel = infoPlayer.telemovel;
            let nome = infoPlayer.nome;
            let foto = infoPlayer.foto;
            let alcunha = infoPlayer.alcunha;
            let posicaoHabitual = infoPlayer.posicaoHabitual;


            let rowInputName = $(table.row(inputTR).node()).find('input').attr("name");
            let indexRow = table.row(inputTR).index();

            for (let i = 0; i < nRows; i++){
                let auxRowBI = $(table.cell(i, 0).node()).find('input').val();

                if (auxRowBI === undefined){
                    auxRowBI = table.row(i).data()[0];
                }

                if (auxRowBI === BI && indexRow !== i){
                    alert("Já existe um elemento na tabela com este BI");
                    break;
                } else if (i === nRows - 1){
                    table.row( inputTR ).data([
                        '<input type="number" id="'+rowInputName+'" name="'+rowInputName+'" min="10000000" max="99999999" placeholder="BI" value="' + BI + '" onkeyup="fillTable(this)" onpaste="pasted(this)" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
                        '<img src="' + foto +'" style="width: 80px; height: auto" alt=""/>',
                        '<h6>' + posicaoHabitual + '</h6>',
                        '<h6>' + nome + '</h6>',
                        '<h6>' + alcunha + '</h6>',
                        '<h6>' + telemovel + '</h6>'
                    ]);
                    columnsAdjustDT();
                }
            }
        } else {
            alert("O número de BI introduzido, está incorrecto ou não existe!")
        }
    }
}

function addOptionToNomeComp(){
    let select = document.getElementById("input-nomeComp");
    let NamesComps = getNomeComp()[0];

    for (let i = 0; i < NamesComps.length; i++){
        let opt = document.createElement("option");
        opt.value = NamesComps[i][0];
        opt.innerHTML = NamesComps[i][1];
        select.appendChild(opt);
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