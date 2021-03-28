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










function loadViewDeposits(deposits)
{
deposits.map((item, index)=>{
    let depositItem = document.querySelector('.models .deposit_load_bank').cloneNode(true);
    depositItem.querySelector('.date_bkst').innerHTML = item.date;
    depositItem.querySelector('.quota_bkst').innerHTML = `${item.quota} - ${item.name} - ${item.nickname}`;
    depositItem.querySelector('.value_bkst').innerHTML = item.value;

    document.querySelector('#list_load_bank_st').append( depositItem );
});



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
        loadViewDeposits(result);
    })
    .catch((error) => {
        console.log('Error:', error);
    });
}