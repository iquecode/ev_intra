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
    count = 0;
    total = checkItens.length; 
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

    console.log("Total : " + total );
    console.log("Checks: " + count );

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


// function toEnable()
// {
//     $change = document.getElementById('act_change');
//     $validate = document.getElementById('act_validate');
//     $delete = document.getElementById( 'act_delete');
//     console.log("HABILITAR!!")
//     $change.disabled = false;
//     $validate .disabled = false;
//     $delete.disabled = false;
//     $change.classList.remove('disabled');
//     $validate.classList.remove('disabled');
//     $delete.classList.remove('disabled');
// }


// function toDisable()
// {
//     $change = document.getElementById('act_change');
//     $validate = document.getElementById('act_validate');
//     $delete = document.getElementById( 'act_delete');
//     console.log("DESABILITAR!!")
//     $change.disabled = true;
//     $validate .disabled = true;
//     $delete.disabled = true;
//     $change.classList.add('disabled');
//     $validate.classList.add('disabled');
//     $delete.classList.add('disabled');
// }


// function enable()
// {
//     const checkItens = document.getElementsByClassName('check_validable_entry'); 
//     const checkAll = document.getElementById('check_all');
//     allChecked = true;
//     oneChecked = false;
//     for (let i=0; i<checkItens.length; i++) 
//     {
//         if (!checkItens[i].checked)
//             allChecked = false;
//         else if (!oneChecked)
//             oneChecked = true;
//     }
//     checkAll.checked = allChecked;

//     return oneChecked;
// }


// function checkAllChecks()
// {
    
//     if (enable()) 
//     {
//         toEnable();
//     } 
//     else
//     {
//         toDisable();
//     }
// }



// function checkAble()
// {
//     console.log("CHECK ABLE!!!");
//     const checkItens = document.getElementsByClassName('check_validable_entry'); 
//     const checkAll = document.getElementById('check_all');
//     oneChecked = false;
//     for (let i=0; i<checkItens.length; i++) 
//     {
//         if (checkItens[i].checked && !oneChecked)
//             oneChecked = true;
//     }

//     if (oneChecked) 
//     {
//         document.getElementById('act_change').disabled = false;
//         document.getElementById('act_validate').disabled = false;
//         document.getElementById( 'act_delete').disabled = false;
//     } 
//     else
//     {
//         document.getElementById('act_change').disabled = true;
//         document.getElementById('act_validate').disabled = true;
//         document.getElementById( 'act_delete').disabled = true;
//     }
// }



function ask(msg="Confirma a operação?"){ 
    // retorna true se confirmado, ou false se cancelado
    return confirm(msg);
 }

function showToChangeValidable(type = '')
{
    list = document.getElementById('list_validate');
    change = document.getElementById('list_validate2');

    listDisplay = 'none';
    changeDisplay = 'flex';

    if (type == 'back')
    {
        listDisplay = 'flex';
        changeDisplay = 'none';
    }

    list.style.display = listDisplay;
    change.style.display = changeDisplay;

    // console.log(a);
    // a.classList.add('hide');
    
    //document.getElementById('list_validate2').classList.remove('hide');
}

