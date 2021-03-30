function setTwoNumberDecimal() {
    this.value = parseFloat(this.value).toFixed(2);
    console.log(this.value);
}

function changeStatment()
{
    let select = document.getElementById('input_admin');
    let value = select.options[select.selectedIndex].value;
    for (i = 1; i < select.length; i = i + 1) 
    {
        document.getElementById(select.options[i].value).style.display = 'none';
    }
    document.getElementById(value).style.display = 'block';

    document.getElementById('form_entry_user').style.display = 'none';
    document.getElementById('new_entry_user').style.display = 'block';

    const id_select = value.substring(4); 
    document.getElementById('id_user').value = id_select; 
}

function changeTypeEntry() 
{
    const select = document.getElementById('id_entry_type');
    const text = select.options[select.selectedIndex].text;
    const inputDesc = document.getElementById('description');
    inputDesc.value = text.substring(12); 
}

function teste() {
    console.log("TESTE TESTE TESTE!!!");
}

function expandEntryUser() 
{
    document.getElementById('new_entry_user').style.display = 'none';
    document.getElementById('form_entry_user').style.display = 'flex';

    const descriptionType = document.getElementById('description');
    descriptionType.value = "Descrição";
    const selectIdType = document.getElementById('id_entry_type');
    selectIdType.selectedIndex = 0;
}


function showArea(area) {
    document.getElementById('statments_area').style.display = 'none';
    document.getElementById('list_validate').style.display = 'none';
    document.getElementById('bank_statment').style.display = 'none';
    //document.getElementById('post_entries').style.display = 'none';
    switch (area) {
        case 'statments_area':
            document.getElementById('statments_area').style.display = 'flex';
            break;
        case 'list_validate':
           document.getElementById('list_validate').style.display = 'flex';
            break;
        case 'bank_statement':
            document.getElementById('bank_statment').style.display = 'flex';
            break;
        case 'post_entries':
           // document.getElementById('post_entries').style.display = 'flex';
            break;
        default:
            break;
    }
}

function markAll()
{
    const checkAll = document.getElementById('check_all');
    const checkItens = document.getElementsByClassName('check_validable_entry1'); 
    for (let i=0; i<checkItens.length; i++) 
    {
        checkItens[i].checked = checkAll.checked ?  true : false;
    }
    checkAllChecks();
}

function checkAllChecks()
{
    const checkItens = document.getElementsByClassName('check_validable_entry1'); 
    const checkAll = document.getElementById('check_all');
    allChecked = true;
    oneChecked = false;
    // count = 0;
    // total = checkItens.length; 
    for (let i=0; i<checkItens.length; i++) 
    {
        if (!checkItens[i].checked)
            allChecked = false;
        else if (!oneChecked)
            oneChecked = true;
        console.log("item : " + checkItens[i] )
        // if (!checkItens[i].checked)
        //     allChecked = false;
        // else count = count + 1;
    }
    checkAll.checked = allChecked;

    // console.log("Total : " + total );
    // console.log("Checks: " + count );

    $change = document.getElementById('act_change');
    $validate = document.getElementById('act_validate');
    $delete = document.getElementById( 'act_delete');

    if (oneChecked || allChecked) 
    {
        console.log("HABILITAR!!")
        $change.disabled = false;
        $validate .disabled = false;
        $delete.disabled = false;
        $change.classList.remove('disabled');
        $validate.classList.remove('disabled');
        $delete.classList.remove('disabled');

    } 
    else
    {
        console.log("DESABILITAR!!")
        $change.disabled = true;
        $validate .disabled = true;
        $delete.disabled = true;
        $change.classList.add('disabled');
        $validate.classList.add('disabled');
        $delete.classList.add('disabled');
    }

}

function ask(msg="Confirma a operação?"){ 
    // retorna true se confirmado, ou false se cancelado
    return confirm(msg);
 }

function loadValidablesToChange(type='')
{
    const checkItens = document.getElementsByClassName('check_validable_entry1'); 
    for (let i=0; i<checkItens.length; i++) 
    {
        //pegar os Ids dos elementos dos templates HTML que já estão na DOM
        idPure = checkItens[i].id.substring(5);
        idRow = 'id_row_chg' + idPure; 
        idCheck = 'id_inp_chk' + idPure;
        idDate = 'id_inp_dte' + idPure;
        idNum = 'id_inp_num' + idPure;
         // console.log('idRow: ' + idRow); 
        // console.log('idCheck: ' + idCheck);
        // console.log('idDate: ' + idDate);
        // console.log('idNum: ' + idNum);

        //set display e abilitação do input como se fosse voltar... normal
        typeDisplay = 'table-row';
        inpDisabled = false;
        
        if (type!='back' && !checkItens[i].checked)
        {
            typeDisplay = 'none';
            inpDisabled = true;
        }

        
        itemRow = document.getElementById(idRow);
        itemCheck = document.getElementById(idCheck);
        itemDate = document.getElementById(idDate);
        itemNum = document.getElementById(idNum);

        if (itemRow != null) itemRow.style.display = typeDisplay; 
        if (itemCheck != null) itemCheck.disabled = inpDisabled;
        if (itemDate != null) itemDate.disabled = inpDisabled;
        if (itemNum != null) itemNum.disabled = inpDisabled;
        
        
        // console.log('idRow: ' + idRow);
        // console.log(document.getElementById(idRow));
        // console.log('type displat: ' + typeDisplay);
        // console.log( document.querySelector('#'+idRow) );

        //document.getElementById(idRow).style.display=typeDisplay; 
        // document.getElementById(idCheck).disabled = inpDisabled;
        // document.getElementById(idDate).disabled = inpDisabled;
        // document.getElementById(idNum).disabled = inpDisabled;
    }
}



function OLDonlySelectedsChecks()
{
    const checkItens = document.getElementsByClassName('check_validable_entry1'); 
    for (let i=0; i<checkItens.length; i++) 
    {
        if (!checkItens[i].checked)
        {
            //desabilitar e display none  inputs com esse ID do change
            idPure = checkItens[i].id.substring(5);
            idRow = 'id_row_chg' + idPure; 
            idCheck = 'id_inp_chk' + idPure;
            idDate = 'id_inp_dte' + idPure;
            idNum = 'id_inp_num' + idPure;
            document.getElementById(idRow).style.display='none'; 
            document.getElementById(idCheck).disabled = true;
            document.getElementById(idDate).disabled = true;
            document.getElementById(idNum).disabled = true;

            // console.log('idRow: ' + idRow); 
            // console.log('idCheck: ' + idCheck);
            // console.log('idDate: ' + idDate);
            // console.log('idNum: ' + idNum);
        }
        //console.log(checkItens[i].id);
    }
}


function showToChangeValidable(type = '')
{
    list = document.getElementById('list_validate');
    change = document.getElementById('list_validate2');

    listDisplay = 'none';
    changeDisplay = 'flex';
    loadValidablesToChange();

    if (type == 'back')
    {
        loadValidablesToChange('back');
        listDisplay = 'flex';
        changeDisplay = 'none';
    }
    

    list.style.display = listDisplay;
    change.style.display = changeDisplay;
    
}








function OLDloadViewDeposits(deposits, allEntries)
{

PendValidablesIndex = [];
//i=0;
entries = allEntries;
//validable_assoc = [];
depositsToRecord = [];
depositsNoRecord = [];
loopValidables = false;
validables = [];

deposits.map((item, index)=>{
    let depositItem = document.querySelector('.models .deposit_load_bank').cloneNode(true);
    depositItem.querySelector('.date_bkst').innerHTML = item.date;
    depositItem.querySelector('.quota_bkst').innerHTML = `${item.quota} - ${item.name} - ${item.nickname}`;
    depositItem.querySelector('.value_bkst').innerHTML = item.value;

    countSimilarEntries = 0;
    for (i=0; i < entries.length; i++)
    {
        similar = entries[i].date == item.date && entries[i].value == item.value && 
                  entries[i].user_quota == item.quota && entries[i].status == '1';

        // similar2 = entries[i].date == item.date;
        // similar3 = entries[i].value == item.value; 
        // similar4 = entries[i].user_quota == item.quota; 
        // similar5 = entries[i].status == '1';
        
        if (similar) 
        {
            countSimilarEntries++;
            console.log("CCCCCCCCCCCCCccc");
        }
        // se a entrada for um lançamento a validar, popula o array validables
        if (entries[i].status == '0' && !loopValidables)
        {
            validables.push(entries[i]);
        }
    }
    loopValidables = true;

    if (countSimilarEntries > 0)
    {
        countSimilarToRecord = 0
        for (i=0; i < depositsToRecord.length; i++)
        {
            similar = depositsToRecord[i].date == item.date && depositsToRecord[i].value == item.value
            && depositsToRecord[i].quota == item.quota;
            if (similar) countSimilarToRecord++;
        }
        countSimilarDeposits = 0
        for (i=0; i < deposits.length; i++)
        {
            similar = deposits[i].date == item.date && deposits[i].value == item.value
            && deposits[i].quota == item.quota;
            if (similar) countSimilarDeposits++;
        }

        if ( countSimilarDeposits > (countSimilarEntries+countSimilarToRecord) ) 
        {
            depositsToRecord.push(item);
        }
        else
        {
            depositsNoRecord.push(item); 
            //console.log("bbbbbbbbbbbbbbbbb!!!");
        }  
    }
    else
    {
        depositsToRecord.push(item);
        //console.log("aaaaaaaaaaaaaaaaaaaaa!!!");
    }
    // console.log(validables[0].user_info);
    // console.log(validables[0].id);
    // console.log(validables[0].date);
    // console.log(validables[0].value);
    // console.log(validables[0].user_id);
    // console.log(validables[0].user_quota);
    // console.log(validables[0].status);
    document.querySelector('#list_load_bank_st').append( depositItem );
});







console.log(depositsToRecord);



}






function loadViewDeposits(deposits, allEntries)
{

PendValidablesIndex = [];
//i=0;
entries = allEntries;
//validable_assoc = [];
depositsToRecord = [];
depositsNoRecord = [];
loopValidables = false;
validables = [];

deposits.map((item, index)=>{
    //let depositItem = document.querySelector('.models .deposit_load_bank').cloneNode(true);
    //depositItem.querySelector('.date_bkst').innerHTML = item.date;
    //depositItem.querySelector('.quota_bkst').innerHTML = `${item.quota} - ${item.name} - ${item.nickname}`;
    //depositItem.querySelector('.value_bkst').innerHTML = item.value;

    countSimilarEntries = 0;
    SimilarEntrie = '';
    for (i=0; i < entries.length; i++)
    {
        similar = entries[i].date == item.date && entries[i].value == item.value && 
                  entries[i].user_quota == item.quota && entries[i].status == '1';
        if (similar) 
        {
            countSimilarEntries++;
            SimilarEntrie = entries[i];
        }
        // se a entrada for um lançamento a validar, popula o array validables
        if (entries[i].status == '0' && !loopValidables)
        {
            validables.push(entries[i]);
        }
    }
    loopValidables = true;

    if (countSimilarEntries > 0)
    {
        countSimilarToRecord = 0
        for (i=0; i < depositsToRecord.length; i++)
        {
            similar = depositsToRecord[i].date == item.date && depositsToRecord[i].value == item.value
            && depositsToRecord[i].quota == item.quota;
            if (similar) countSimilarToRecord++;
        }
        countSimilarDeposits = 0
        for (i=0; i < deposits.length; i++)
        {
            similar = deposits[i].date == item.date && deposits[i].value == item.value
            && deposits[i].quota == item.quota;
            if (similar) countSimilarDeposits++;
        }

        if ( countSimilarDeposits > (countSimilarEntries+countSimilarToRecord) ) 
        {
            depositsToRecord.push(item);
        }
        else
        {
            depositsNoRecord.push([item, SimilarEntrie]); 
            //console.log("bbbbbbbbbbbbbbbbb!!!");
        }  
    }
    else
    {
        depositsToRecord.push(item);
        //console.log("aaaaaaaaaaaaaaaaaaaaa!!!");
    }
    // console.log(validables[0].user_info);
    // console.log(validables[0].id);
    // console.log(validables[0].date);
    // console.log(validables[0].value);
    // console.log(validables[0].user_id);
    // console.log(validables[0].user_quota);
    // console.log(validables[0].status);
    //document.querySelector('#list_load_bank_st').append( depositItem );
});


//matchDepositWithValidable = false;
for (i=0; i < depositsToRecord.length; i++)
{
    matchDepositWithValidable = false;
    for (j=0; j < validables.length; j++)
    {
        matchDepositWithValidable = validables[j].date == depositsToRecord[i].date && validables[j].value == depositsToRecord[i].value &&
                validables[j].user_quota == depositsToRecord[i].quota;
        if (matchDepositWithValidable)
        {
            depositsToRecord[i] = [ depositsToRecord[i], validables[j] ];
            validables.splice(j, 1);
            break;
        }        
    }

    //se passou sem dar 'match'
    if (!matchDepositWithValidable)
    {
        depositsToRecord[i] = [ depositsToRecord[i], '' ];
    }
}


//console.log(depositsToRecord);   //[0=> deposito identificado no extrato    1=> Lançamento a validar com este depósito ou '']
//console.log(validables);   // validables que sobraram pendentes de validação
//console.log(depositsNoRecord);  // 0=> deposito identificado no extrato    1=> Lançamento identificado 





depositsToRecord.map((item, index)=>{
    let depositToRecordItem = document.querySelector('.models .deposit_load_bank').cloneNode(true);
    let inputUserId = document.querySelector('.models .input_toRecord_userId').cloneNode(true); 
    let inputDate = document.querySelector('.models .input_toRecord_date').cloneNode(true);
    let inputValue = document.querySelector('.models .input_toRecord_value').cloneNode(true); 
    let inputToValidable = document.querySelector('.models .input_toRecord_validable').cloneNode(true);   
    console.log(inputToValidable);                     
    toRecord = item[0];
    toValidate = item[1];
    depositToRecordItem.querySelector('.date_bkst').innerHTML = toRecord.date;
    depositToRecordItem.querySelector('.quota_bkst').innerHTML = `${toRecord.quota} - ${toRecord.name} - ${toRecord.nickname}`;
    depositToRecordItem.querySelector('.value_bkst').innerHTML = toRecord.value;

    inputUserId.setAttribute('id', 'toRecord_userId' + index);
    inputUserId.setAttribute('name', 'toRecord_userId' + index);
    inputUserId.setAttribute('value', toRecord.id_user);

    inputDate.setAttribute('id', 'toRecord_date' + index);
    inputDate.setAttribute('name', 'toRecord_date' + index);
    inputDate.setAttribute('value', toRecord.date);

    inputValue.setAttribute('id', 'toRecord_value' + index);
    inputValue.setAttribute('name', 'toRecord_value' + index);
    inputValue.setAttribute('value', toRecord.value);

    
    let foundToValidabe = false;
    if (toValidate == '') {
        depositToRecordItem.querySelector('.msg_toValidate').innerHTML = 'Sem lançamento a validar com os mesmos parametros do depósito identificado no extrato!';
    }
    else
    {
        depositToRecordItem.querySelector('.msg_toValidate').innerHTML = 'O seguinte lançamento será validado pelo depósito identificado no extrato!';
        depositToRecordItem.querySelector('.date_toValidate').innerHTML = toValidate.date;
        depositToRecordItem.querySelector('.quota_toValidate').innerHTML = toValidate.user_info;
        depositToRecordItem.querySelector('.value_toValidate').innerHTML = toValidate.value;

        console.log("AAAAAAAA!!!");
        foundToValidabe = true;
        //let inputToValidable = document.querySelector('.models .input_toRecord_validable').cloneNode(true);
        inputToValidable.setAttribute('id', 'toValidable_id' + index);
        inputToValidable.setAttribute('name', 'toValidable_id' + index);
        inputToValidable.setAttribute('value', toValidate.id);


    }
    document.querySelector('#toRecord_deposits').append( depositToRecordItem );

    document.querySelector('#form_recordLoadBankSt').append( inputUserId );
    document.querySelector('#form_recordLoadBankSt').append( inputDate );
    document.querySelector('#form_recordLoadBankSt').append( inputValue );
    
    if (foundToValidabe) document.querySelector('#form_recordLoadBankSt').append( inputToValidable );
});



depositsNoRecord.map((item, index)=>{
    let depositNoRecordItem = document.querySelector('.models .noRecord_deposit_item').cloneNode(true);
    noRecord = item[0];
    entrySimilar = item[1];
    depositNoRecordItem.querySelector('.noRecord_date_deposit').innerHTML = noRecord.date;
    depositNoRecordItem.querySelector('.noRecord_quota_deposit').innerHTML = `${noRecord.quota} - ${noRecord.name} - ${noRecord.nickname}`;
    depositNoRecordItem.querySelector('.noRecord_value_deposit').innerHTML = noRecord.value;
    if ( entrySimilar == '') {
        depositToRecordItem.querySelector('.msg_entry_similar').innerHTML = 'Não identificado motivo exato para não gravação do depósito!';
    }
    else
    {
        depositNoRecordItem.querySelector('.msg_entry_similar').innerHTML = 'Identificado o seguinte lançamento já validado similar ao identificado extrato bançario:';
        depositNoRecordItem.querySelector('.date_entry_similar').innerHTML = entrySimilar.date;
        depositNoRecordItem.querySelector('.quota_entry_similar').innerHTML = entrySimilar.user_info;
        depositNoRecordItem.querySelector('.value_entry_similar').innerHTML = entrySimilar.value;
    }
    document.querySelector('#noRecord_deposits').append( depositNoRecordItem );
});



validables.map((item, index)=>{
    let validableItem = document.querySelector('.models .pending_validable_model').cloneNode(true);
    validableItem.querySelector('.pendValidable_date').innerHTML = item.date;
    validableItem.querySelector('.pendValidable_quota').innerHTML = item.user_info;
    validableItem.querySelector('.pendValidable_value').innerHTML = item.value;
    document.querySelector('#pending_validables').append( validableItem );
});


displayArea = 'none';
if(depositsToRecord.length > 0)
{
    displayArea = 'flex';
    document.querySelector('#group_title_models_toRecord').style.display='flex';
}
if(depositsNoRecord.length > 0)
{
    displayArea = 'flex';
    document.querySelector('#group_title_models_noRecord').style.display='flex';
}
if(validables.length > 0)
{   
    displayArea = 'flex';
    document.querySelector('#group_title_models_validables').style.display='flex';
}

document.querySelector('#list_load_bank_st').style.display=displayArea;



}












function loadFile()
{
    const list = document.querySelector('#list');

    const formData = new FormData();
    const fileField = document.querySelector('input[type="file"]');

    formData.append('username', 'abc123');
    formData.append('file', fileField.files[0]);

    console.log(formData.get('file'));

    fetch('api/backApi.php', {
        method: 'POST',
        body: formData
    })
    .then((response) => response.json())
    .then((result) => {
        // list.innerHTML =  '<a href="'+ result['file'] + '">Fluxo de Caixa em formato CSV para importar na planilha</a>';
        // console.log('Success:', result['file']);
        console.log('Success:', result);
        loadViewDeposits(result['deposits'], result['validable_entries']);
    })
    .catch((error) => {
        console.log('Error:', error);
    });
}