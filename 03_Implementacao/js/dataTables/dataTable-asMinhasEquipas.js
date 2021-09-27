$(document).ready(function () {
    Table();
})

async function creatTable(){
    let table = $('#table').DataTable({
        "dom": '<"top">rt<"bottom"lp><"clear"i>',
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
                extend:    'excelHtml5',
                text:      '<i class="far fa-file-excel"></i>',
                attr: { class: 'skewBtn black', style: 'width: 55px; height: 30px;', id: 'btnExcel'},
                titleAttr: 'Baixar Excel'
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="far fa-file-pdf"></i>',
                attr: { class: 'skewBtn black', style: 'width: 55px; height: 30px;', id: 'btnPdf'},
                titleAttr: 'Baixar PDF'
            }
        ]
    });
    return;
}

async function Table(){
    await creatTable();
    columnsAdjustDT();
    let table = $('#table').DataTable();
    let nRows = table.data().count();

    let btnExcel = table.buttons( ['#btnExcel'] );
    let btnPdf = table.buttons( ['#btnPdf'] );
    if (nRows === 0){
        btnExcel.disable();
        btnPdf.disable();
    }

    let touchTime = 0;
    $("#table tbody").on("click", 'tr', function() {
        idR =  table.row( this ).id();
        if (touchTime === 0) {
            // set first click
            touchTime = new Date().getTime();
        } else {
            // compare first click to this click and see if they occurred within double click threshold
            if (((new Date().getTime()) - touchTime) < 200) {
                // double click occurred
                document.getElementById('input-epoca').value = table.row(this).data()[0];
                document.getElementById('input-escalao').value = table.row(this).data()[1];
                document.getElementById('input-nivelCompeticao').value = table.row(this).data()[2];
                document.getElementById('IDEquipa').value = idR;
                tableForm.submit();

                touchTime = 0;
            } else {
                // not a double click so set as a new first click
                touchTime = new Date().getTime();
            }
        }
    });
}

function columnsAdjustDT(){
    let delayInMilliseconds = 200; //1 second
    setTimeout(function() {
        $('#table').DataTable().columns.adjust().draw();
    }, delayInMilliseconds);
}