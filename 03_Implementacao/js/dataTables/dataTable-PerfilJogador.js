$(document).ready(function () {
    creatTables();
})

$(window).resize(function () {
    columnsAdjustDT();
});

async function tableInformation(){
    $('#tableInformation').DataTable({
        "searching": false,
        "paging": false,
        "info": false,
        "ordering": false,
        "scrollX": true,
        "responsive": true,
        "columnDefs": [
            { "width": "100%", "targets": 2 }
        ],
        "dom": 'Bfrtilp',
        "buttons": [
            {
                text:      '<i class="fas fa-user-edit"></i>',
                titleAttr: 'Editar Dados do Jogador',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id: 'addRowTableInformation'},
                action: function (e, dt, node, config){
                    editTableInformation();
                }
            },
            {
                extend:    'excelHtml5',
                text:      '<i class="far fa-file-excel"></i>',
                attr: { class: 'skewBtn black', style: 'width: 55px; height: 30px;', id: 'btnExcelTableInformation'},
                titleAttr: 'Baixar Excel'
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="far fa-file-pdf"></i>',
                attr: { class: 'skewBtn black', style: 'width: 55px; height: 30px;', id: 'btnPdfTableInformation'},
                titleAttr: 'Baixar PDF'
            },
            {
                text:      '<i class="far fa-save"></i>',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id:'btnSaveTableInformation'},
                titleAttr: 'Salvar Alterações',
                action: function (e, dt, node, config){
                    formInformacoes.submit();
                }
            }
        ]
    });
}

async function tableIndividualRegister(){
    $('#tableIndividualRegister').DataTable({
        "searching": false,
        "info": false,
        "ordering": false,
        "scrollX": true,
        "scrollY": "200px",
        "scrollCollapse": true,
        "paging": false,
        "responsive": true,
        "dom": 'Bfrtilp',
        "buttons": [
            {
                text:      '<i class="fas fa-plus-circle"></i>',
                titleAttr: 'Adicionar Dados',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id: 'addRowTableIndividualRegister'},
                action: function (e, dt, node, config){
                    addRowtableIndividualRegister();

                }
            },
            {
                extend:    'excelHtml5',
                text:      '<i class="far fa-file-excel"></i>',
                attr: { class: 'skewBtn black', style: 'width: 55px; height: 30px;', id: 'btnExcelTableIndividualRegister'},
                titleAttr: 'Baixar Excel'
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="far fa-file-pdf"></i>',
                attr: { class: 'skewBtn black', style: 'width: 55px; height: 30px;', id: 'btnPdfTableIndividualRegister'},
                titleAttr: 'Baixar PDF'
            },
            {
                text:      '<i class="far fa-save"></i>',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id:'btnSaveTableIndividualRegister'},
                titleAttr: 'Salvar Alterações',
                action: function (e, dt, node, config){
                    if (formRegistoIndividual.checkValidity()) {
                        formRegistoIndividual.submit();
                    } else {
                        alert("Por favor, preencha todos os campos antes de submeter!!");
                    }
                }
            }
        ]
    });
}

async function tablePlayerProfile(){
    $('#tablePlayerProfile').DataTable({
        "searching": false,
        "paging": false,
        "info": false,
        "ordering": false,
        "scrollX": true,
        "responsive": true,
        "dom": 'Bfrtilp',
        "buttons": [
            {
                text:      '<i class="fas fa-user-edit"></i>',
                titleAttr: 'Editar Caracteristicas',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id: 'addRowTablePlayerProfile'},
                action: function (e, dt, node, config){
                    editTablePlayerProfile();
                }
            },
            {
                extend:    'excelHtml5',
                text:      '<i class="far fa-file-excel"></i>',
                attr: { class: 'skewBtn black', style: 'width: 55px; height: 30px;', id: 'btnExcelTableInformation'},
                titleAttr: 'Baixar Excel'
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="far fa-file-pdf"></i>',
                attr: { class: 'skewBtn black', style: 'width: 55px; height: 30px;', id: 'btnPdfTableInformation'},
                titleAttr: 'Baixar PDF'
            },
            {
                text:      '<i class="far fa-save"></i>',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id:'btnSaveTableInformation'},
                titleAttr: 'Salvar Alterações',
                action: function (e, dt, node, config){
                    formPerfilJogador.submit();
                }
            }
        ]
    });
}


async function creatTables(){
    await tableInformation();
    await tableIndividualRegister();
    await tablePlayerProfile();
    columnsAdjustDT();
    let TableInformation = $('#tableInformation').DataTable()
    let TableIndividualRegister = $('#tableIndividualRegister').DataTable()
    let TablePlayerProfile = $('#tablePlayerProfile').DataTable()
    let btnSaveTableInformation = TableInformation.buttons( ['#btnSaveTableInformation'] );
    let btnSaveTableIndividualRegister= TableIndividualRegister.buttons( ['#btnSaveTableIndividualRegister'] );
    let btnSaveTablePlayerProfile = TablePlayerProfile.buttons( ['#btnSaveTableInformation'] );

    let enableEditBtns = getEnableBtns();

    if (enableEditBtns == 0){
        // btnAdd
        let addRowTableInformation = TableInformation.buttons( ['#addRowTableInformation'] );
        let addRowTableIndividualRegister= TableIndividualRegister.buttons( ['#addRowTableIndividualRegister'] );
        let addRowTablePlayerProfile = TablePlayerProfile.buttons( ['#addRowTablePlayerProfile'] );

        addRowTableInformation.disable();
        addRowTableIndividualRegister.disable();
        addRowTablePlayerProfile.disable();
    }

    btnSaveTableInformation.disable();
    btnSaveTableIndividualRegister.disable();
    btnSaveTablePlayerProfile.disable();
}

function columnsAdjustDT(){
    let delayInMilliseconds = 300; //1 second
    setTimeout(function() {
        $('#tableInformation').DataTable().columns.adjust().draw();
        $('#tableIndividualRegister').DataTable().columns.adjust().draw();
        $('#tablePlayerProfile').DataTable().columns.adjust().draw();
    }, delayInMilliseconds);
}

function editTableInformation(){
    let TableInformation = $('#tableInformation').DataTable()
    let TableIndividualRegister = $('#tableIndividualRegister').DataTable()
    let TablePlayerProfile = $('#tablePlayerProfile').DataTable()

    // btnSave
    let btnSaveTableInformation = TableInformation.buttons( ['#btnSaveTableInformation'] );
    let btnSaveTableIndividualRegister= TableIndividualRegister.buttons( ['#btnSaveTableIndividualRegister'] );
    let btnSaveTablePlayerProfile = TablePlayerProfile.buttons( ['#btnSaveTableInformation'] );

    btnSaveTableInformation.enable();
    btnSaveTableIndividualRegister.disable();
    btnSaveTablePlayerProfile.disable();

    // btnAdd
    let addRowTableInformation = TableInformation.buttons( ['#addRowTableInformation'] );
    let addRowTableIndividualRegister= TableIndividualRegister.buttons( ['#addRowTableIndividualRegister'] );
    let addRowTablePlayerProfile = TablePlayerProfile.buttons( ['#addRowTablePlayerProfile'] );

    addRowTableInformation.disable();
    addRowTableIndividualRegister.disable();
    addRowTablePlayerProfile.disable();

    let Morada = TableInformation.row(3).data()[2];
    let Telemovel = TableInformation.row(4).data()[2];
    let Numero = TableInformation.row(5).data()[2];

    TableInformation.row(3).data([
        '<i class="fas fa-map-marked-alt"></i>',
        '<h6>Morada</h6>',
        '<input type="text" id="input-morada" name="input-morada" value="' + Morada + '" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">'
    ]);
    TableInformation.row(4).data([
        '<i class="fas fa-mobile-alt"></i>',
        '<h6>Telefone</h6>',
        '<input type="tel" pattern="9[1236][0-9]{7}|2[1-9]{1,2}[0-9]{7}" id="input-telemovel" name="input-telemovel" value="' + Telemovel + '" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">'
    ]);
    TableInformation.row(5).data([
        '<span class="iconify" data-icon="icon-park-outline:basketball-clothes"></span>',
        '<h6>Número</h6>',
        '<input type="number" id="input-numero" name="input-numero" min="0" max="99" value="' + Numero + '" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">'
    ]);
}

function addRowtableIndividualRegister(){
    let TableInformation = $('#tableInformation').DataTable()
    let TableIndividualRegister = $('#tableIndividualRegister').DataTable()
    let TablePlayerProfile = $('#tablePlayerProfile').DataTable()

    // btnSave
    let btnSaveTableInformation = TableInformation.buttons( ['#btnSaveTableInformation'] );
    let btnSaveTableIndividualRegister= TableIndividualRegister.buttons( ['#btnSaveTableIndividualRegister'] );
    let btnSaveTablePlayerProfile = TablePlayerProfile.buttons( ['#btnSaveTableInformation'] );

    btnSaveTableInformation.disable();
    btnSaveTableIndividualRegister.enable();
    btnSaveTablePlayerProfile.disable();

    // btnAdd
    let addRowTableInformation = TableInformation.buttons( ['#addRowTableInformation'] );
    let addRowTableIndividualRegister= TableIndividualRegister.buttons( ['#addRowTableIndividualRegister'] );
    let addRowTablePlayerProfile = TablePlayerProfile.buttons( ['#addRowTablePlayerProfile'] );

    addRowTableInformation.disable();
    addRowTableIndividualRegister.disable();
    addRowTablePlayerProfile.disable();

    let date = new Date();
    let currentDate = date.toISOString().substring(0,10);

    TableIndividualRegister.row.add( [
        '<input type="date" id="input-data" name="input-data" value="' + currentDate +'" readonly style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<input type="number" id="input-peso" name="input-peso" value="0" min="0" max="150" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<input type="number" id="input-altura" name="input-altura" value="100" min="100" max="220" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<input type="number" id="input-IMC" name="input-IMC" value="10" min="10" max="50" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<select id="input-peDominante" name="input-peDominante" size="1" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"> ' +
            '<option value="" selected> ---- Selecione --- </option> ' +
            '<option value="Esquerdo">Esquerdo</option> ' +
            '<option value="Direito">Direito</option> ' +
        '</select>',
        '<select id="input-posicao" name="input-posicao" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">'+
            '<option value="" selected="selected"> ---- Selecione --- </option>'+
            '<option value="GR">GR</option>'+
            '<option value="DD">DD</option>'+
            '<option value="DE">DE</option>'+
            '<option value="DC">DC</option>'+
            '<option value="MCD">MCD</option>'+
            '<option value="MC">MC</option>'+
            '<option value="MD">MD</option>'+
            '<option value="ME">ME</option>'+
            '<option value="EE">EE</option>'+
            '<option value="ED">ED</option>'+
            '<option value="PL">PL</option>'+
        '</select>',
        '<select class="form-select" id="input-tamanhoCamisola" name="input-tamanhoCamisola" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"> ' +
            '<option value="" selected> ---- Selecione --- </option> ' +
            '<option value="9">9 anos</option> ' +
            '<option value="10">10 anos</option> ' +
            '<option value="11">11 anos</option> ' +
            '<option value="12">12 anos</option> ' +
            '<option value="13">13 anos</option> ' +
            '<option value="14">14 anos</option> ' +
            '<option value="15">15 anos</option> ' +
            '<option value="xs">XS</option> ' +
            '<option value="s">S</option> ' +
            '<option value="m">M</option> ' +
            '<option value="l">L</option> ' +
            '<option value="xl">XL</option> ' +
            '<option value="xxl">XXL</option> ' +
        '</select>',
        '<select class="form-select" id="input-tamanhoCalcoes" name="input-tamanhoCalcoes" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"> ' +
            '<option value="" selected> ---- Selecione --- </option> ' +
            '<option value="9">9 anos</option> ' +
            '<option value="10">10 anos</option> ' +
            '<option value="11">11 anos</option> ' +
            '<option value="12">12 anos</option> ' +
            '<option value="13">13 anos</option> ' +
            '<option value="14">14 anos</option> ' +
            '<option value="15">15 anos</option> ' +
            '<option value="xs">XS</option> ' +
            '<option value="s">S</option> ' +
            '<option value="m">M</option> ' +
            '<option value="l">L</option> ' +
            '<option value="xl">XL</option> ' +
            '<option value="xxl">XXL</option> ' +
        '</select>',
    ]).draw(false);
}

function editTablePlayerProfile(){
    let TableInformation = $('#tableInformation').DataTable()
    let TableIndividualRegister = $('#tableIndividualRegister').DataTable()
    let TablePlayerProfile = $('#tablePlayerProfile').DataTable()

    // btnSave
    let btnSaveTableInformation = TableInformation.buttons( ['#btnSaveTableInformation'] );
    let btnSaveTableIndividualRegister= TableIndividualRegister.buttons( ['#btnSaveTableIndividualRegister'] );
    let btnSaveTablePlayerProfile = TablePlayerProfile.buttons( ['#btnSaveTableInformation'] );

    btnSaveTableInformation.disable();
    btnSaveTableIndividualRegister.disable();
    btnSaveTablePlayerProfile.enable();

    // btnAdd
    let addRowTableInformation = TableInformation.buttons( ['#addRowTableInformation'] );
    let addRowTableIndividualRegister= TableIndividualRegister.buttons( ['#addRowTableIndividualRegister'] );
    let addRowTablePlayerProfile = TablePlayerProfile.buttons( ['#addRowTablePlayerProfile'] );

    addRowTableInformation.disable();
    addRowTableIndividualRegister.disable();
    addRowTablePlayerProfile.disable();

    // row 1
    let Agressividade = TablePlayerProfile.row(0).data()[1];
    let CondicaoFisica = TablePlayerProfile.row(0).data()[3];
    let Marcacao = TablePlayerProfile.row(0).data()[5];
    let TomadaDeDecisao = TablePlayerProfile.row(0).data()[7];

    TablePlayerProfile.row(0).data([
        '<h6>Agressividade</h6>',
        '<input type="number" id="input-Agressividade" name="input-Agressividade" value="' + Agressividade +'" min="1" max="10" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<h6>Condição Física</h6>',
        '<input type="number" id="input-CondicaoFisica" name="input-CondicaoFisica" value="' + CondicaoFisica +'" min="1" max="10" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<h6>Marcação</h6>',
        '<input type="number" id="input-Marcacao" name="input-Marcacao" value="' + Marcacao +'" min="1" max="10" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<h6>Tomada de Decisão</h6>',
        '<input type="number" id="input-TomadaDeDecisao" name="input-TomadaDeDecisao" value="' + TomadaDeDecisao +'" min="1" max="10" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
    ]);

    //row 2
    let AutoConfianca = TablePlayerProfile.row(1).data()[1];
    let Cruzamentos = TablePlayerProfile.row(1).data()[3];
    let Passe = TablePlayerProfile.row(1).data()[5];
    let VelocicadeExecucao = TablePlayerProfile.row(1).data()[7];

    TablePlayerProfile.row(1).data([
        '<h6>Auto Confiança</h6>',
        '<input type="number" id="input-AutoConfianca" name="input-AutoConfianca" value="' + AutoConfianca +'" min="1" max="10" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<h6>Cruzamentos</h6>',
        '<input type="number" id="input-Cruzamentos" name="input-Cruzamentos" value="' + Cruzamentos +'" min="1" max="10" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<h6>Passe</h6>',
        '<input type="number" id="input-Passe" name="input-Passe" value="' + Passe +'" min="1" max="10" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<h6>Velocidade de Execução</h6>',
        '<input type="number" id="input-VelocicadeExecucao" name="input-VelocicadeExecucao" value="' + VelocicadeExecucao +'" min="1" max="10" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
    ]);

    //row 3
    let AutoControlo = TablePlayerProfile.row(2).data()[1];
    let Finalizacao = TablePlayerProfile.row(2).data()[3];
    let Posicionamento = TablePlayerProfile.row(2).data()[5];
    let oneXoneDefensivo = TablePlayerProfile.row(2).data()[7];

    TablePlayerProfile.row(2).data([
        '<h6>Auto Controlo</h6>',
        '<input type="number" id="input-AutoControlo" name="input-AutoControlo" value="' + AutoControlo +'" min="1" max="10" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<h6>Finalização</h6>',
        '<input type="number" id="input-Finalizacao" name="input-Finalizacao" value="' + Finalizacao +'" min="1" max="10" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<h6>Posicionameto</h6>',
        '<input type="number" id="input-Posicionamento" name="input-Posicionamento" value="' + Posicionamento +'" min="1" max="10" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<h6>1x1 Defensivo</h6>',
        '<input type="number" id="input-oneXoneDefensivo" name="input-oneXoneDefensivo" value="' + oneXoneDefensivo +'" min="1" max="10" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
    ]);

    //row 4
    let CapacidadeTrabalho = TablePlayerProfile.row(3).data()[1];
    let InteligenciaJogo = TablePlayerProfile.row(3).data()[3];
    let Recepcao = TablePlayerProfile.row(3).data()[5];
    let oneXoneOfensivo = TablePlayerProfile.row(3).data()[7];

    TablePlayerProfile.row(3).data([
        '<h6>Capacidade de Trabalho</h6>',
        '<input type="number" id="input-CapacidadeTrabalho" name="input-CapacidadeTrabalho" value="' + CapacidadeTrabalho +'" min="1" max="10" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<h6>Inteligencia no Jogo</h6>',
        '<input type="number" id="input-InteligenciaJogo" name="input-InteligenciaJogo" value="' + InteligenciaJogo +'" min="1" max="10" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<h6>Recepção</h6>',
        '<input type="number" id="input-Recepcao" name="input-Recepcao" value="' + Recepcao +'" min="1" max="10" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<h6>1x1 Ofensivo</h6>',
        '<input type="number" id="input-oneXoneOfensivo" name="input-oneXoneOfensivo" value="' + oneXoneOfensivo +'" min="1" max="10" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
    ]);

    //row 5
    let CobrancaDeLivres = TablePlayerProfile.row(4).data()[1];
    let JogoCabeca = TablePlayerProfile.row(4).data()[3];
    let Resistencia = TablePlayerProfile.row(4).data()[5];

    TablePlayerProfile.row(4).data([
        '<h6>Cobrança de Livres</h6>',
        '<input type="number" id="input-CobrancaDeLivres" name="input-CobrancaDeLivres" value="' + CobrancaDeLivres +'" min="1" max="10" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<h6>Jogo de Cabeça</h6>',
        '<input type="number" id="input-JogoCabeca" name="input-JogoCabeca" value="' + JogoCabeca +'" min="1" max="10" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<h6>Resistencia</h6>',
        '<input type="number" id="input-Resistencia" name="input-Resistencia" value="' + Resistencia +'" min="1" max="10" style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '',
        ''
    ]);
}