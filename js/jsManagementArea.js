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
    for (let i=0; i<checkItens.length; i++) 
    {
        if (!checkItens[i].checked)
            allChecked = false;
        else if (!oneChecked)
            oneChecked = true;
        console.log("item : " + checkItens[i] )
    }
    checkAll.checked = allChecked;
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

function removeAllChildren(parentId)
{
    const e = document.getElementById(parentId);
    while (e.firstChild) {
        e.removeChild(e.firstChild);
    }
}

function loadViewDeposits(deposits, allEntries, cashFlow='')
{
    PendValidablesIndex = [];
    entries = allEntries;
    depositsToRecord = [];
    depositsNoRecord = [];
    loopValidables = false;
    validables = [];
    
    document.querySelector('#linkCashFlow').setAttribute('href', cashFlow);
    document.querySelector('#divLinkCashFlow').style.display = 'flex'; 

    removeAllChildren("toRecord_deposits");
    removeAllChildren("noRecord_deposits");
    removeAllChildren("pending_validables");
    removeAllChildren("removedToRecorder");
    const areaRemoved = document.querySelector('#areaRemoved');
    areaRemoved.style.display = 'none';
    

    deposits.map((item, index)=>{
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
        }
    });

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

    depositsToRecord.map((item, index)=>{
        let depositToRecordItem = document.querySelector('.models .deposit_load_bank').cloneNode(true);
        let inputUserId = document.querySelector('.models .input_toRecord_userId').cloneNode(true); 
        let inputDate = document.querySelector('.models .input_toRecord_date').cloneNode(true);
        let inputValue = document.querySelector('.models .input_toRecord_value').cloneNode(true); 
        let inputToValidable = document.querySelector('.models .input_toRecord_validable').cloneNode(true);   
        let confirmRecord = document.querySelector('.models .input_confirmRecord').cloneNode(true); 
        let confirmValidable = document.querySelector('.models .input_confirmValidable').cloneNode(true);
        let remover = document.querySelector('.models .remove_toRecord').cloneNode(true);
        // let msgToVaidate = document.querySelector('. models .msg_toValidate');
        

        //console.log(inputToValidable);                     
        toRecord = item[0];
        toValidate = item[1];
        depositToRecordItem.querySelector('.date_bkst').innerHTML = toRecord.date;
        depositToRecordItem.querySelector('.quota_bkst').innerHTML = `${toRecord.quota} - ${toRecord.name} - ${toRecord.nickname}`;
        depositToRecordItem.querySelector('.value_bkst').innerHTML = toRecord.value;

        depositToRecordItem.setAttribute('id', 'deposit_load_bank' + index);

        inputUserId.setAttribute('id', 'toRecord_userId' + index);
        inputUserId.setAttribute('name', 'toRecord_userId' + index);
        inputUserId.setAttribute('value', toRecord.id_user);

        inputDate.setAttribute('id', 'toRecord_date' + index);
        inputDate.setAttribute('name', 'toRecord_date' + index);
        inputDate.setAttribute('value', toRecord.date);

        inputValue.setAttribute('id', 'toRecord_value' + index);
        inputValue.setAttribute('name', 'toRecord_value' + index);
        inputValue.setAttribute('value', toRecord.value);

        confirmRecord.setAttribute('id', 'toRecord_confirm' + index);
        confirmRecord.setAttribute('name', 'toRecord_confirm' + index);
        confirmRecord.setAttribute('value', 1);

        remover.setAttribute('id', 'remover'+index);


        depositToRecordItem.querySelector('.msg_toValidate').setAttribute('id', 'msg_toValidate' + index );



        let foundToValidabe = false;
        if (toValidate == '') {
            depositToRecordItem.querySelector('.msg_toValidate').innerHTML = 'Gravação do lançamento';
        }
        else
        {
            depositToRecordItem.querySelector('.msg_toValidate').innerHTML = 'Gravação e baixa do lançamento a validar abaixo:';
            depositToRecordItem.querySelector('.date_toValidate').innerHTML = toValidate.date;
            depositToRecordItem.querySelector('.quota_toValidate').innerHTML = toValidate.user_info;
            depositToRecordItem.querySelector('.value_toValidate').innerHTML = toValidate.value;
            foundToValidabe = true;
            inputToValidable.setAttribute('id', 'toValidable_id' + index);
            inputToValidable.setAttribute('name', 'toValidable_id' + index);
            inputToValidable.setAttribute('value', toValidate.id);
            confirmValidable.setAttribute('id', 'toValidable_confirm' + index);
            confirmValidable.setAttribute('name', 'toValidable_confirm' + index);
            confirmValidable.setAttribute('value', 1);
        }


        //depositToRecordItem.append(remover);
        depositToRecordItem.prepend(remover);
        depositToRecordItem.prepend(remover);

        document.querySelector('#toRecord_deposits').append( depositToRecordItem );
        document.querySelector('#form_recordLoadBankSt').append( inputUserId );
        document.querySelector('#form_recordLoadBankSt').append( inputDate );
        document.querySelector('#form_recordLoadBankSt').append( inputValue );
        document.querySelector('#form_recordLoadBankSt').append( confirmRecord );
        document.querySelector('#form_recordLoadBankSt').append( confirmValidable );
        
        
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
            depositToRecordItem.querySelector('.msg_entry_similar').innerHTML = '';
        }
        else
        {
            depositNoRecordItem.querySelector('.msg_entry_similar').innerHTML = 'lançamento já validado similar:';
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

function removeToRecord(e)
{
    //console.log("REMOVE TO RECORD!!!...." + e.classList);

    const idRemover = e.getAttribute('id').substring(7);

    const form = document.getElementById('form_recordLoadBankSt');

    let elements = [];
    for(let i = 0; i < form.children.length; i++)
    {
        elements[i] = form.children[i];
    }


    for(let i = 0; i < elements.length; i++)
    {
        const input = elements[i];
        id = input.getAttribute('id');
        //console.log("INPUT ID: " + id);

        remove= false;
        if ( id != null) 
        {
            if (id.substring(0,13) == 'toRecord_date')
            {
                if (idRemover == id.substring(13)) {
                    remove = true;
                    id = id.substring(13);
                }       
                //console.log('Achou toRecord_date ID :' + id.substring(13));
                //input.remove();
            }
            if (id.substring(0,15) == 'toRecord_userId')
            {
                if (idRemover == id.substring(15)) {
                    remove = true;
                    id = id.substring(15);
                }                
                //console.log('Achou toRecord_user ID :' + id.substring(15));
                //id.substring(13);
                //input.remove();
            }
            if (id.substring(0,14) == 'toRecord_value')
            {
                if (idRemover == id.substring(14)) {
                    remove = true;
                    id = id.substring(14);
                } 
                //console.log('Achou toRecord_value ID :' + id.substring(14));
                //input.remove();
            }
            if (id.substring(0,14) == 'toValidable_id')
            {
                if (idRemover == id.substring(14)) {
                    remove = true;
                    id = id.substring(14);
                }   
                //console.log('Achou toValidable_id ID :' + id.substring(14));
                //input.remove();
            }
        }

        if ( remove ) 
        {
            form.removeChild(input);   // uma outra alternativa é deixar desabilidato e não remover... assim fica mais fácil implementar a volta no front end
            //deposit_load_bank
            //01234567890123456
            const depositsItens = document.getElementsByClassName("deposit_load_bank");
            //console.log(depositsItens);
            for(let i = 0; i < depositsItens.length; i++)
            {
                idItem = depositsItens[i].getAttribute('id');
                if (idItem != null)
                {
                    //console.log('idItem : ' + idItem.substring(17) + '.....  id: ' + id);
                    if ( idItem.substring(17) == id ) 
                    {
                        console.log('ACHOU PARA REMOVER DA TELA');
                        document.querySelector('.msg_toValidate').style.display = 'none';
                        
                        const areaRemoved = document.querySelector('#areaRemoved');
                        const divRemoved = document.querySelector('#removedToRecorder');
                        divRemoved.append( depositsItens[i] );
                        areaRemoved.style.display = 'flex';
                        
                        document.querySelector('#msg_toValidate' + id).innerHTML = 'Depósito a Validar Vinculado:';
                        
                        document.querySelector('#remover' + id).innerHTML = '';
                        
                    }
                }
                //console.log('ID : ' + depositsItens[i].getAttribute('id'));
                //console.log(depositsItens[i]);
            }
            //document.querySelector('#pending_validables').append(  );
            //console.log('Removido: ' + id);
        }
        
        //toValidable_id
        //01234567890123 14

        //toRecord_user
        //0123456789012 13
        
        //toRecord_date
        //0123456789012 13

        //toRecord_value
        //01234567890123 14

        //toValidable_id
        //01234567890123 14



        //toRecord_confirm // não precisa --  tirar
        //toValidable_confirm // não precisa -- tirar

    }
    



    // inputUserId.setAttribute('id', 'toRecord_userId' + index);
    //     inputUserId.setAttribute('name', 'toRecord_userId' + index);
    //     inputUserId.setAttribute('value', toRecord.id_user);

    //     inputDate.setAttribute('id', 'toRecord_date' + index);
    //     inputDate.setAttribute('name', 'toRecord_date' + index);
    //     inputDate.setAttribute('value', toRecord.date);

    //     inputValue.setAttribute('id', 'toRecord_value' + index);
    //     inputValue.setAttribute('name', 'toRecord_value' + index);
    //     inputValue.setAttribute('value', toRecord.value);

    //     confirmRecord.setAttribute('id', 'toRecord_confirm' + index);
    //     confirmRecord.setAttribute('name', 'toRecord_confirm' + index);
    //     confirmRecord.setAttribute('value', 1);



}

function loadFile()
{
    const list = document.querySelector('#list');
    const formData = new FormData();
    const fileField = document.querySelector('input[type="file"]');
    formData.append('username', 'abc123');
    formData.append('file', fileField.files[0]);

    fetch('api/backApi.php', {
        method: 'POST',
        body: formData
    })
    .then((response) => response.json())
    .then((result) => {
        console.log('Success:', result);
        loadViewDeposits(result['deposits'], result['validable_entries'], result['cf_file']);
    })
    .catch((error) => {
        console.log('Error:', error);
    });
}