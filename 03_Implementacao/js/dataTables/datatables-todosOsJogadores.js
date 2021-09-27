let counter = 1;

$(document).ready(function () {
    creatTables();
})

$(window).resize(function () {
    columnsAdjustDT();
});

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

    $('#table').DataTable({
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
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id: 'addRow'},
                action: function (e, dt, node, config){
                    addPlayer();
                }
            },
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
            {
                text:      '<i class="far fa-save"></i>',
                attr: { class: 'skewBtn black', style: 'width: 35px; height: 30px;', id: 'saveChanges'},
                titleAttr: 'Salvar Alterações',
                action: function (e, dt, node, config){
                    if (formChanges.checkValidity()) {
                        console.log('valid');
                        formChanges.submit();
                    } else {
                        alert("Por favor, preencha todos os campos antes de submeter!!");
                    }
                }
            }
        ]
    });
}



async function creatTables(){
    await tablePlayers();
    let table = $('#table').DataTable();

    let nRows = table.data().count();

    let btnExcel = table.buttons( ['#btnExcel'] );
    let btnPdf = table.buttons( ['#btnPdf'] );
    let btnSave = table.buttons( ['#saveChanges'] );
    let btnAdd = table.buttons( ['#addRow'] );

    if (isTreinador()[0] != 0){
        document.getElementById('addRow').setAttribute("hidden", true);
        document.getElementById('saveChanges').setAttribute("hidden", true);
    }

    if (nRows === 0){
        btnExcel.disable();
        btnPdf.disable();
        btnSave.disable();
    } else {
        btnSave.disable();
    }

    let touchTime = 0;
    $("#table tbody").on("click", 'tr', function() {
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

function columnsAdjustDT(){
    let delayInMilliseconds = 200;
    setTimeout(function() {
        $('#table').DataTable().columns.adjust().draw();
    }, delayInMilliseconds);
}

function addPlayer(){
    let table = $('#table').DataTable();
    table.row.add( [
        '<input type="number" id="input-BI_'+counter+'" name="input-BI_'+counter+'" min="10000000" max="99999999" placeholder="BI" onkeyup="setTitleInput(this);" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<input type="file" id="input-foto_'+counter+'" name="input-foto_'+counter+'" placeholder="Alcunha" onkeyup="setTitleInput(this);" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '',
        '<input type="text" id="input-nome_'+counter+'" name="input-nome_'+counter+'" placeholder="Nome" onkeyup="setTitleInput(this);" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<input type="text" id="input-alcunha_'+counter+'" name="input-alcunha_'+counter+'" placeholder="Alcunha" onkeyup="setTitleInput(this);" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<select id="input-country_'+counter+'" name="input-country_'+counter+'" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;">'+
            '<option value="Afghanistan">Afghanistan</option>'+
            '<option value="Åland Islands">Åland Islands</option>'+
            '<option value="Albania">Albania</option>'+
            '<option value="Algeria">Algeria</option>'+
            '<option value="American Samoa">American Samoa</option>'+
            '<option value="Andorra">Andorra</option>'+
            '<option value="Angola">Angola</option>'+
            '<option value="Anguilla">Anguilla</option>'+
            '<option value="Antarctica">Antarctica</option>'+
            '<option value="Antigua and Barbuda">Antigua and Barbuda</option>'+
            '<option value="Argentina">Argentina</option>'+
            '<option value="Armenia">Armenia</option>'+
            '<option value="Aruba">Aruba</option>'+
            '<option value="Australia">Australia</option>'+
            '<option value="Austria">Austria</option>'+
            '<option value="Azerbaijan">Azerbaijan</option>'+
            '<option value="Bahamas">Bahamas</option>'+
            '<option value="Bahrain">Bahrain</option>'+
            '<option value="Bangladesh">Bangladesh</option>'+
            '<option value="Barbados">Barbados</option>'+
            '<option value="Belarus">Belarus</option>'+
            '<option value="Belgium">Belgium</option>'+
            '<option value="Belize">Belize</option>'+
            '<option value="Benin">Benin</option>'+
            '<option value="Bermuda">Bermuda</option>'+
            '<option value="Bhutan">Bhutan</option>'+
            '<option value="Bolivia">Bolivia</option>'+
            '<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>'+
            '<option value="Botswana">Botswana</option>'+
            '<option value="Bouvet Island">Bouvet Island</option>'+
            '<option value="Brazil">Brazil</option>'+
            '<option value="Brunei Darussalam">Brunei Darussalam</option>'+
            '<option value="Bulgaria">Bulgaria</option>'+
            '<option value="Burkina Faso">Burkina Faso</option>'+
            '<option value="Burundi">Burundi</option>'+
            '<option value="Cambodia">Cambodia</option>'+
            '<option value="Cameroon">Cameroon</option>'+
            '<option value="Canada">Canada</option>'+
            '<option value="Cape Verde">Cape Verde</option>'+
            '<option value="Cayman Islands">Cayman Islands</option>'+
            '<option value="Central African Republic">Central African Republic</option>'+
            '<option value="Chad">Chad</option>'+
            '<option value="Chile">Chile</option>'+
            '<option value="China">China</option>'+
            '<option value="Christmas Island">Christmas Island</option>'+
            '<option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>'+
            '<option value="Colombia">Colombia</option>'+
            '<option value="Comoros">Comoros</option>'+
            '<option value="Congo">Congo</option>'+
            '<option value="Cook Islands">Cook Islands</option>'+
            '<option value="Costa Rica">Costa Rica</option>'+
            '<option value="Croatia">Croatia</option>'+
            '<option value="Cuba">Cuba</option>'+
            '<option value="Cyprus">Cyprus</option>'+
            '<option value="Czech Republic">Czech Republic</option>'+
            '<option value="Denmark">Denmark</option>'+
            '<option value="Djibouti">Djibouti</option>'+
            '<option value="Dominica">Dominica</option>'+
            '<option value="Dominican Republic">Dominican Republic</option>'+
            '<option value="Ecuador">Ecuador</option>'+
            '<option value="Egypt">Egypt</option>'+
            '<option value="El Salvador">El Salvador</option>'+
            '<option value="Equatorial Guinea">Equatorial Guinea</option>'+
            '<option value="Eritrea">Eritrea</option>'+
            '<option value="Estonia">Estonia</option>'+
            '<option value="Ethiopia">Ethiopia</option>'+
            '<option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>'+
            '<option value="Faroe Islands">Faroe Islands</option>'+
            '<option value="Fiji">Fiji</option>'+
            '<option value="Finland">Finland</option>'+
            '<option value="France">France</option>'+
            '<option value="Gabon">Gabon</option>'+
            '<option value="Gambia">Gambia</option>'+
            '<option value="Georgia">Georgia</option>'+
            '<option value="Germany">Germany</option>'+
            '<option value="Ghana">Ghana</option>'+
            '<option value="Gibraltar">Gibraltar</option>'+
            '<option value="Greece">Greece</option>'+
            '<option value="Greenland">Greenland</option>'+
            '<option value="Grenada">Grenada</option>'+
            '<option value="Guadeloupe">Guadeloupe</option>'+
            '<option value="Guam">Guam</option>'+
            '<option value="Guatemala">Guatemala</option>'+
            '<option value="Guernsey">Guernsey</option>'+
            '<option value="Guinea">Guinea</option>'+
            '<option value="Guinea-bissau">Guinea-bissau</option>'+
            '<option value="Guyana">Guyana</option>'+
            '<option value="Haiti">Haiti</option>'+
            '<option value="Honduras">Honduras</option>'+
            '<option value="Hong Kong">Hong Kong</option>'+
            '<option value="Hungary">Hungary</option>'+
            '<option value="Iceland">Iceland</option>'+
            '<option value="India">India</option>'+
            '<option value="Indonesia">Indonesia</option>'+
            '<option value="Iran">Iran</option>'+
            '<option value="Iraq">Iraq</option>'+
            '<option value="Ireland">Ireland</option>'+
            '<option value="Isle of Man">Isle of Man</option>'+
            '<option value="Israel">Israel</option>'+
            '<option value="Italy">Italy</option>'+
            '<option value="Jamaica">Jamaica</option>'+
            '<option value="Japan">Japan</option>'+
            '<option value="Jersey">Jersey</option>'+
            '<option value="Jordan">Jordan</option>'+
            '<option value="Kazakhstan">Kazakhstan</option>'+
            '<option value="Kenya">Kenya</option>'+
            '<option value="Kiribati">Kiribati</option>'+
            '<option value="Korea">Korea</option>'+
            '<option value="Korea, Republic of">Korea, Republic of</option>'+
            '<option value="Kuwait">Kuwait</option>'+
            '<option value="Kyrgyzstan">Kyrgyzstan</option>'+
            '<option value="Latvia">Latvia</option>'+
            '<option value="Lebanon">Lebanon</option>'+
            '<option value="Lesotho">Lesotho</option>'+
            '<option value="Liberia">Liberia</option>'+
            '<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>'+
            '<option value="Liechtenstein">Liechtenstein</option>'+
            '<option value="Lithuania">Lithuania</option>'+
            '<option value="Luxembourg">Luxembourg</option>'+
            '<option value="Macao">Macao</option>'+
            '<option value="Macedonia">Macedonia</option>'+
            '<option value="Madagascar">Madagascar</option>'+
            '<option value="Malawi">Malawi</option>'+
            '<option value="Malaysia">Malaysia</option>'+
            '<option value="Maldives">Maldives</option>'+
            '<option value="Mali">Mali</option>'+
            '<option value="Malta">Malta</option>'+
            '<option value="Marshall Islands">Marshall Islands</option>'+
            '<option value="Martinique">Martinique</option>'+
            '<option value="Mauritania">Mauritania</option>'+
            '<option value="Mauritius">Mauritius</option>'+
            '<option value="Mayotte">Mayotte</option>'+
            '<option value="Mexico">Mexico</option>'+
            '<option value="Micronesia, Federated States of">Micronesia</option>'+
            '<option value="Moldova, Republic of">Moldova, Republic of</option>'+
            '<option value="Monaco">Monaco</option>'+
            '<option value="Mongolia">Mongolia</option>'+
            '<option value="Montenegro">Montenegro</option>'+
            '<option value="Montserrat">Montserrat</option>'+
            '<option value="Morocco">Morocco</option>'+
            '<option value="Mozambique">Mozambique</option>'+
            '<option value="Myanmar">Myanmar</option>'+
            '<option value="Namibia">Namibia</option>'+
            '<option value="Nauru">Nauru</option>'+
            '<option value="Nepal">Nepal</option>'+
            '<option value="Netherlands">Netherlands</option>'+
            '<option value="Netherlands Antilles">Netherlands Antilles</option>'+
            '<option value="New Caledonia">New Caledonia</option>'+
            '<option value="New Zealand">New Zealand</option>'+
            '<option value="Nicaragua">Nicaragua</option>'+
            '<option value="Niger">Niger</option>'+
            '<option value="Nigeria">Nigeria</option>'+
            '<option value="Niue">Niue</option>'+
            '<option value="Norfolk Island">Norfolk Island</option>'+
            '<option value="Northern Mariana Islands">Northern Mariana Islands</option>'+
            '<option value="Norway">Norway</option>'+
            '<option value="Oman">Oman</option>'+
            '<option value="Pakistan">Pakistan</option>'+
            '<option value="Palau">Palau</option>'+
            '<option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>'+
            '<option value="Panama">Panama</option>'+
            '<option value="Papua New Guinea">Papua New Guinea</option>'+
            '<option value="Paraguay">Paraguay</option>'+
            '<option value="Peru">Peru</option>'+
            '<option value="Philippines">Philippines</option>'+
            '<option value="Pitcairn">Pitcairn</option>'+
            '<option value="Poland">Poland</option>'+
            '<option value="Portugal" selected="selected">Portugal</option>'+
            '<option value="Puerto Rico">Puerto Rico</option>'+
            '<option value="Qatar">Qatar</option>'+
            '<option value="Reunion">Reunion</option>'+
            '<option value="Romania">Romania</option>'+
            '<option value="Russian Federation">Russian Federation</option>'+
            '<option value="Rwanda">Rwanda</option>'+
            '<option value="Saint Helena">Saint Helena</option>'+
            '<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>'+
            '<option value="Saint Lucia">Saint Lucia</option>'+
            '<option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>'+
            '<option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>'+
            '<option value="Samoa">Samoa</option>'+
            '<option value="San Marino">San Marino</option>'+
            '<option value="Sao Tome and Principe">Sao Tome and Principe</option>'+
            '<option value="Saudi Arabia">Saudi Arabia</option>'+
            '<option value="Senegal">Senegal</option>'+
            '<option value="Serbia">Serbia</option>'+
            '<option value="Seychelles">Seychelles</option>'+
            '<option value="Sierra Leone">Sierra Leone</option>'+
            '<option value="Singapore">Singapore</option>'+
            '<option value="Slovakia">Slovakia</option>'+
            '<option value="Slovenia">Slovenia</option>'+
            '<option value="Solomon Islands">Solomon Islands</option>'+
            '<option value="Somalia">Somalia</option>'+
            '<option value="South Africa">South Africa</option>'+
            '<option value="Spain">Spain</option>'+
            '<option value="Sri Lanka">Sri Lanka</option>'+
            '<option value="Sudan">Sudan</option>'+
            '<option value="Suriname">Suriname</option>'+
            '<option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>'+
            '<option value="Swaziland">Swaziland</option>'+
            '<option value="Sweden">Sweden</option>'+
            '<option value="Switzerland">Switzerland</option>'+
            '<option value="Syrian Arab Republic">Syrian Arab Republic</option>'+
            '<option value="Taiwan">Taiwan</option>'+
            '<option value="Tajikistan">Tajikistan</option>'+
            '<option value="Tanzania, United Republic of">Tanzania, United Republic of</option>'+
            '<option value="Thailand">Thailand</option>'+
            '<option value="Timor-leste">Timor-leste</option>'+
            '<option value="Togo">Togo</option>'+
            '<option value="Tokelau">Tokelau</option>'+
            '<option value="Tonga">Tonga</option>'+
            '<option value="Trinidad and Tobago">Trinidad and Tobago</option>'+
            '<option value="Tunisia">Tunisia</option>'+
            '<option value="Turkey">Turkey</option>'+
            '<option value="Turkmenistan">Turkmenistan</option>'+
            '<option value="Turks and Caicos Islands">Turks and Caicos Islands</option>'+
            '<option value="Tuvalu">Tuvalu</option>'+
            '<option value="Uganda">Uganda</option>'+
            '<option value="Ukraine">Ukraine</option>'+
            '<option value="United Arab Emirates">United Arab Emirates</option>'+
            '<option value="United Kingdom">United Kingdom</option>'+
            '<option value="United States">United States</option>'+
            '<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>'+
            '<option value="Uruguay">Uruguay</option>'+
            '<option value="Uzbekistan">Uzbekistan</option>'+
            '<option value="Vanuatu">Vanuatu</option>'+
            '<option value="Venezuela">Venezuela</option>'+
            '<option value="Viet Nam">Viet Nam</option>'+
            '<option value="Virgin Islands, British">Virgin Islands, British</option>'+
            '<option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>'+
            '<option value="Wallis and Futuna">Wallis and Futuna</option>'+
            '<option value="Western Sahara">Western Sahara</option>'+
            '<option value="Yemen">Yemen</option>'+
            '<option value="Zambia">Zambia</option>'+
            '<option value="Zimbabwe">Zimbabwe</option>'+
        '</select>',
        '<input type="date" id="input-dataNascimento_'+counter+'" name="input-dataNascimento_'+counter+'" placeholder="Data-Nascimento" onkeyup="setTitleInput(this);" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<input type="text" id="input-morada_'+counter+'" name="input-morada_'+counter+'" placeholder="Morada" onkeyup="setTitleInput(this);" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<input type="tel" pattern="9[1236][0-9]{7}|2[1-9]{1,2}[0-9]{7}" name="input-phone_'+counter+'" id="input-phone_'+counter+'" placeholder="Telemovel" onkeyup="setTitleInput(this);" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>',
        '<input type="number" name="input-nCamisola_'+counter+'" id="input-nCamisola_'+counter+'" placeholder="Nº Camisola" onkeyup="setTitleInput(this);" required style="width:100% !important; height: 40px; border: 1px solid #85C1E9; background:#F2F3F4;"/>'
    ] ).node().id = 'new';
    table.draw(false);
    document.getElementById('valConter').value = counter;
    let btnSave = table.buttons( ['#saveChanges'] );
    btnSave.enable();
    counter++;
}

function setTitleInput(input){
    let txt = input.value;
    input.title = txt;
}