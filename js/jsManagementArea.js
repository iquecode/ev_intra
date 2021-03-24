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
    //document.getElementById('bank_statement').style.display = 'none';
    //document.getElementById('post_entries').style.display = 'none';
    switch (area) {
        case 'statments_area':
            document.getElementById('statments_area').style.display = 'flex';
            break;
        case 'list_validate':
           document.getElementById('list_validate').style.display = 'flex';
            break;
        case 'bank_statement':
           // document.getElementById('bank_statement').style.display = 'flex';
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
    const checkItens = document.getElementsByClassName('check_validable_entry'); 
    for (let i=0; i<checkItens.length; i++) 
    {
        checkItens[i].checked = checkAll.checked ?  true : false;
    }
}

function checkAllChecks()
{
    const checkItens = document.getElementsByClassName('check_validable_entry'); 
    const checkAll = document.getElementById('check_all');
    allChecked = true;
    for (let i=0; i<checkItens.length; i++) 
    {
        if (!checkItens[i].checked)
        {
            allChecked = false;
        }  
    }
    checkAll.checked = allChecked;
}


function ask(msg="Confirma a operação?"){ 
    // retorna true se confirmado, ou false se cancelado
    return confirm(msg);
 }