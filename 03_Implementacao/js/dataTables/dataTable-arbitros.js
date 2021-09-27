let counter = 1;

$(document).ready(function () {
    Table();
})

async function creatTable(){
    // Construção da tabela
    let table = $('#table').DataTable({
        "dom": "<'row'<'col-md-12'B>><'row'<'col-md-12't>><'row'<'col-md-6'l><'col-md-6'p>>",
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
                    titleAttr: 'Adicionar Arbitro',
                    attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id: 'addRow'},
                    action: function (e, dt, node, config) {
                        let table = $('#table').DataTable();
                        table.row.add([
                            '<input type="file" id="input-foto_' + counter + '" name="input-foto_' + counter + '" placeholder="foto" required style="opacity:100 !important; position:relative !important; left:0 !important; width:100% !important; border: 0; background:#F2F3F4;height: 40px;"/>',
                            '<input type="text" id="input-nome_' + counter + '" name="input-nome_' + counter + '" placeholder="Nome" required style="opacity:100 !important; position:relative !important; left:0 !important; width:100% !important; border: 0; background:#F2F3F4;height: 40px;"/>',
                            '<input type="number" id="input-cedula_' + counter + '" name="input-cedula_' + counter + '" placeholder="Cedula" required style="opacity:100 !important; position:relative !important; left:0 !important; width:100% !important; border: 0; background:#F2F3F4;height: 40px;"/>',
                            '<input type="text" id="input-associacao_' + counter + '" name="input-associacao_' + counter + '" placeholder="Associaçao" required style="opacity:100 !important; position:relative !important; left:0 !important; width:100% !important; border: 0; background:#F2F3F4;height: 40px;"/>',
                            0
                        ]).node().id = 'new';
                        table.draw(false);
                        columnsAdjustDT();
                        btnSaveEnable.enable();
                        document.getElementById('valConter').value = counter;
                        counter++;
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
                        submitTreino();
                    }
                }
            ]
        });
    let btnSaveEnable = table.buttons( ['#btnSave'] );
    let btnAddEnable = table.buttons( ['#addRow'] );
    btnSaveEnable.disable();
}

async function Table(){
    await creatTable();

    let table = $('#table').DataTable();
    let nRows = table.data().count();

    let btnExcel = table.buttons( ['#btnExcel'] );
    let btnPdf = table.buttons( ['#btnPdf'] );
    let btnSave = table.buttons( ['#btnSave'] );

    console.log(isTreinador()[0]);

    if (isTreinador()[0] != 0){
        document.getElementById('addRow').setAttribute("hidden", true);
        document.getElementById('btnSave').setAttribute("hidden", true);
    }

    if (nRows === 0){
        btnExcel.disable();
        btnPdf.disable();
        btnSave.disable();
    } else {
        btnSave.disable();
    }
}

function columnsAdjustDT(){
    let delayInMilliseconds = 200;
    setTimeout(function() {
        $('#table').DataTable().columns.adjust().draw();
    }, delayInMilliseconds);
}

function submitTreino(){
    let $form = $('form')[0];
    if ($form.checkValidity()) {
        $form.submit();
    } else {
        alert("Existem dados por preencher!!");
    }
    return false;
}