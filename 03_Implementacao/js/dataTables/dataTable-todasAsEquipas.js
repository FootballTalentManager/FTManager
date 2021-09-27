$(document).ready(function () {
    Table();

    let table = $('#table').DataTable();

    //double click player
    $('#table tbody').on('dblclick', 'tr', function () {
        document.getElementById('hiddenField').value = table.row( this ).data();
        formPlayerPage.submit();
    } );
})

async function creatTable(){
    $('#table').DataTable({
        "dom": '<"top">rt<"bottom"lp><"clear"i>',
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
        "dom": 'Bfrtilp',
        "buttons": [
            {
                extend:    'excelHtml5',
                text:      '<i class="far fa-file-excel"></i>',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;'},
                titleAttr: 'Baixar Excel'
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="far fa-file-pdf"></i>',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;'},
                titleAttr: 'Baixar PDF'
            },
        ]
    });
}

async function Table(){
    await creatTable();
}

function columnsAdjustDT(){
    let delayInMilliseconds = 500; //1 second
    setTimeout(function() {
        $('#table').DataTable().columns.adjust().draw();
    }, delayInMilliseconds);
}