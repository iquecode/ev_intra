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
    // #form_entry_user {
    //     display: none;
    // }
    // #new_entry_user {
    //     display: none;
    // }
}

function teste() {
    console.log("TESTE TESTE TESTE!!!");
}

function expandEntryUser() 
{
    document.getElementById('new_entry_user').style.display = 'none';
    document.getElementById('form_entry_user').style.display = 'flex';
}

function saveNewEntryUser() 
{


    
	




    console.log("Salvar novo Lançamento");

    const select = document.getElementById('input_admin');
	const id_user = select.options[select.selectedIndex].value.substring(4);
    const entry_date = document.getElementById('entry_date').value;
    const description = document.getElementById('description').value;
    const value = document.getElementById('value').value;
    const id_entry_type = document.getElementById('id_entry_type').value;

    console.log(`ID USER ${id_user}`);
    console.log(`DESCRIÇÃO ${description}`);
    //console.log(id_user);

    

    //  id_user = 1; 
    //  record_user = 32; 
    //  status = 0;

    // let _data = {
    //     id_user: document.getElementById('id_user'),
    //     entry_date: document.getElementById('entry_date'),
    //     description: document.getElementById('description'),
    //     value: document.getElementById('value'),
    //     id_entry_type: document.getElementById('id_entry_type'),
    //     id_user: document.getElementById('id_user')
    // }

    // $id_user = filter_input(INPUT_POST, 'id_user');
    // $entry_date = filter_input(INPUT_POST, 'entry_date');
    // $description = filter_input(INPUT_POST, 'description');
    // $value = filter_input(INPUT_POST, 'value');
    // $id_entry_type = filter_input(INPUT_POST, 'id_entry_type');
    // $record_user = filter_input(INPUT_POST, 'record_user');
    // $status = filter_input(INPUT_POST, 'status');


//     let _data = {
//         id_user: 1,
//         entry_date: 2021-03-18,
//         description: 'teste teste teste',
//         value: 1.11,
//         id_entry_type: 1,
//         record_user: 32,
//         status = 1
//     }

//     let _options = {
//         method: "POST",
//         body: JSON.stringify(_data),
//         headers: {"Content-type": "application/json; charset=UTF-8"}
//     }

//     fetch('../api/saveEntryUser.php', _options)
//     .then(response =>{ response.json()
//         .then( data => console.lof(data))
//     })
//     .catch(e => console.log('Deu Erro: '+ e,message));
     

}