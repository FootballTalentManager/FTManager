let counter = 1;

$(document).ready(function () {
    Table();
})

async function creatTable(){
    let table = $('#table').DataTable({
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
        "dom": 'Bfrtilp',
        "buttons": [
            {
                text:      '<i class="fas fa-plus-circle"></i>',
                titleAttr: 'Adicionar Competição',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id: 'addRow'},
                action: function (e, dt, node, config){
                    let table = $('#table').DataTable();
                    table.row.add([
                        '<select id="input-epoca_'+counter+'" name="input-epoca_'+counter+'" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">'+
                            '<option value="" selected disabled>'+' --- Selecione ---'+'</option>'+
                        '</select>',
                        '<select id="input-escalao_'+counter+'" name="input-escalao_'+counter+'" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">'+
                            '<option value="" selected disabled>'+' --- Selecione ---'+'</option>'+
                            '<option value="petizes">'+'Petizes'+'</option>'+
                            '<option value="traquinas">'+'Traquinas'+'</option>'+
                            '<option value="benjamins">'+'Benjamins'+'</option>'+
                            '<option value="infantis">'+'Infantis'+'</option>'+
                            '<option value="iniciados">'+'Iniciados'+'</option>'+
                            '<option value="juvenis">'+'Juvenis'+'</option>'+
                            '<option value="juniores">'+'Juniores'+'</option>'+
                            '<option value="seniores">'+'Seniores'+'</option>'+
                        '</select>',
                        '<input type="text" id="input-nomeCompeticao_'+counter+'" name="input-nomeCompeticao_'+counter+'" placeholder="Indique o nome da competição" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">'
                    ]).draw(false);
                    addOptionToEpocaSelect();
                    document.getElementById('valConter').value = counter;
                    counter++;
                }
            },
            {
                text:      '<i class="far fa-trash-alt"></i>',
                titleAttr: 'Eliminar Competição',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id: 'removeRow'},
                action: function (e, dt, node, config){
                    delRow();
                }
            },
            {
                extend:    'excelHtml5',
                text:      '<i class="far fa-file-excel"></i>',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id: 'btnExcel'},
                titleAttr: 'Baixar Excel'
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="far fa-file-pdf"></i>',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id: 'btnPdf'},
                titleAttr: 'Baixar PDF'
            },
            {
                text:      '<i class="far fa-save"></i>',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id:'btnSave'},
                titleAttr: 'Salvar Alterações',
                action: function (e, dt, node, config){
                    if (tableForm.checkValidity()) {
                        tableForm.submit();
                    } else {
                        alert("Por favor, preencha todos os campos antes de submeter!!");
                    }
                }
            }
        ]
    });
    return;
}

async function Table(){
    await creatTable();
    columnsAdjustDT();
    selectRow();

    let table = $('#table').DataTable();
    let nRows = table.data().count();

    let btnRemoveRow = table.buttons( ['#removeRow'] );
    let btnAddRow = table.buttons( ['#addRow'] );
    let btnExcel = table.buttons( ['#btnExcel'] );
    let btnPdf = table.buttons( ['#btnPdf'] );
    let btnSave = table.buttons( ['#btnSave'] );
    if (nRows === 0){
        btnRemoveRow.disable();
        btnExcel.disable();
        btnPdf.disable();
        btnSave.disable();
    } else {
        btnRemoveRow.disable();
        btnSave.disable();
    }

    console.log(nRows);
}

function selectRow(){
    let table = $('#table').DataTable();
    let btnAddRow = table.buttons( ['#addRow'] );
    let btnRemoveRow = table.buttons( ['#removeRow'] );
    let btnExcel = table.buttons( ['#btnExcel'] );
    let btnPdf = table.buttons( ['#btnPdf'] );

    $('#table tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected')) {
            $(this).removeClass('selected');
            btnRemoveRow.disable();
            btnAddRow.enable();
            btnExcel.enable();
            btnPdf.enable();
        }
        else if (!table.$('tr').hasClass('new')){
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            btnRemoveRow.enable();
            btnAddRow.disable();
            btnExcel.disable();
            btnPdf.disable();
        }
    } );
}

function columnsAdjustDT(){
    let delayInMilliseconds = 200; //1 second
    setTimeout(function() {
        $('#table').DataTable().columns.adjust().draw();
    }, delayInMilliseconds);
}

function addOptionToEpocaSelect(){
    let table = $('#table').DataTable();
    let btnSave = table.buttons( ['#btnSave'] );
    btnSave.enable();

    let arrayEpocas = getEpocas();
    let select = document.getElementById("input-epoca_" + counter);
    for (let i = 0; i < arrayEpocas[0].length; i++){
        let opt = document.createElement("option");
        opt.value = arrayEpocas[0][i];
        opt.innerHTML = arrayEpocas[0][i];
        select.appendChild(opt);
        console.log(arrayEpocas[0][i])
    }
}

function delRow(){
    let table = $('#table').DataTable();
    let idRow = table.$('tr.selected').attr('id');

    let args = "IDComp=" + idRow;

    xmlHttp = new GetXmlHttpObject();
    xmlHttp.open("GET", "DelSelectRowCompeticoes.php?" + args, true);
    xmlHttp.onreadystatechange = SelectEquipaHandleReply;
    xmlHttp.send(null);
}

function SelectEquipaHandleReply(){
    if (xmlHttp.readyState === 4){
        let isValid = JSON.parse(xmlHttp.responseText);

        if (isValid){
            document.getElementById("cardSuccess").removeAttribute("hidden");
        } else {
            document.getElementById("cardDanger").removeAttribute("hidden");
        }
        setTimeout(function() {
            location.reload();
        }, 1000);
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