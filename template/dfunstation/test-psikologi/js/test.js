function updatedJawaban(){

    const getKlik  = event.target.name;
    const getElemen = document.getElementById(getKlik);
    getElemen.classList.add("bg-isi");

    console.log(getKlik);
    console.log(getElemen);

}

function removeREquired(idsoal){
    const getKlik  = event.target.id;
    const getElemen = document.getElementById(getKlik);
    getElemen.classList.add("bg-isi");

    const checkboxes = document.querySelectorAll(`#${getKlik}`);

    for(let i = 0; i < checkboxes.length; i++){
        checkboxes[i].removeAttribute("required");
    }

    const lainhal = document.querySelector(`#lainhal-${idsoal}`);
    if(lainhal){
        lainhal.setAttribute("disabled", "true");
    }else {
        console.log("Soal ini tidak memiliki lain hal");
    }

    const catatan = document.querySelector(`#catatan-${idsoal}`);
    if(catatan){
        catatan.setAttribute("disabled", "true");
    }else {
        console.log("Soal ini tidak memiliki catatan");
    }

}

function pilihDataKedua(soal){
    const getKlik  = event.target.id;
    const getElemen = document.getElementById(getKlik);
    const getId = document.getElementById(`jawab-${getKlik}`);
    getId.classList.add("bg-isi");

    const removeReq = document.querySelectorAll(`.soal-${soal}`);

    for(let i = 0; i < removeReq.length; i++){
        removeReq[i].removeAttribute("required");
    }

    console.log(getKlik);
    console.log(`cetak ${soal}`);
    console.log(getId);
    console.log(removeReq);

}

function removeRequiredKedua(){
    const getKlik  = event.target.id;
    const getId = document.getElementById(`soal-${getKlik}`);
    getId.classList.add("bg-isi");

    const removeReq = document.querySelectorAll(`.soal-${getKlik}`);

    for(let i = 0; i < removeReq.length; i++){
        removeReq[i].removeAttribute("required");
    }

    console.log(getKlik);
    console.log(getId);
    console.log(removeReq);

}

function lainhal(idsoal){
    
    let idbg = document.querySelector(`#soal-${idsoal}`);
    idbg.classList.add("bg-isi");

    const checkboxes = document.querySelectorAll(`#soal-${idsoal}`);

    for(let i = 0; i < checkboxes.length; i++){
        checkboxes[i].setAttribute("disabled", "true");
    }

    console.log(idbg);
}

function createElement(idjawaban){

    // hapus onclik
    const tessoal = document.querySelector(`#tulissoal-${idjawaban}`);
    tessoal.removeAttribute("onclick");
    
    // Buat form element
    const form = document.createElement('form');
    form.setAttribute('id', `form-${idjawaban}`);
    
    // Buat input element untuk idsoaljawaban
    const inputIdSoalJawaban = document.createElement('input');
    inputIdSoalJawaban.setAttribute('type', 'hidden');
    inputIdSoalJawaban.setAttribute('name', 'idsoaljawaban');
    inputIdSoalJawaban.setAttribute('value', idjawaban);
    inputIdSoalJawaban.setAttribute('id', `idsoaljawaban-${idjawaban}`);
    
    // buat div
    const divForm = document.createElement('div');
    divForm.setAttribute('class', 'input-group mb-3 mt-2');
    
    // Buat input element untuk soaljawaban
    const inputSoalJawaban = document.createElement('input');
    inputSoalJawaban.setAttribute('type', 'text');
    inputSoalJawaban.setAttribute('name', 'soaljawaban');
    inputSoalJawaban.setAttribute('class', 'form-control');
    inputSoalJawaban.setAttribute('placeholder', 'Tulis soal disini');
    inputSoalJawaban.setAttribute('aria-label', 'Tulis soal');
    inputSoalJawaban.setAttribute('aria-describedby', 'button-addon2');
    inputSoalJawaban.setAttribute('id', `soaljawaban-${idjawaban}`);
    
    // Buat button element untuk submit
    const buttonSubmit = document.createElement('button');
    buttonSubmit.setAttribute('class', 'btn btn-primary');
    buttonSubmit.setAttribute('type', 'submit');
    buttonSubmit.setAttribute('id', 'button-addon');
    buttonSubmit.innerText = 'Simpan';
    
    // masukan element
    divForm.appendChild(inputSoalJawaban);
    divForm.appendChild(buttonSubmit);
    
    form.appendChild(inputIdSoalJawaban);
    form.appendChild(divForm);
    
    // bikin note
    const span = document.createElement('span');
    span.setAttribute('class', 'text-light');
    span.innerText = "Note! jika tidak jadi mengisi keterangan, tolong untuk menekan tombol batal";
    
    // bikin button
    const buttonClose = document.createElement('button');
    buttonClose.setAttribute('class', 'btn btn-danger');
    buttonClose.setAttribute("onclick", `closeButton(${idjawaban})`);
    buttonClose.innerText = 'Batal';
    
    // create div
    const createDiv = document.createElement('div');
    createDiv.setAttribute('id', `formdiv-${idjawaban}` );
    createDiv.setAttribute('class', 'bg-secondary p-3 rounded mb-2');
    
    createDiv.appendChild(span);
    createDiv.appendChild(form);
    createDiv.appendChild(buttonClose);
    
    const myDiv = document.querySelector(`#formsoal-${idjawaban}`);
    
    myDiv.appendChild(createDiv);
    
    // bikin session
    sessionStorage.setItem('dataform', `form-${idjawaban}`);
    
    
    }

function addForm(idjawaban){

    createElement(idjawaban);
    
      const dataform = sessionStorage.getItem('dataform');

      document.getElementById(dataform).addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent form from submitting normally
        var form = event.target;
        var data = new FormData(form);
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "https://www.dfunstation.com/tes-psikologi/soaljawaban", true);
        xhr.onload = function () {
          if (xhr.status === 200) {
            form.reset(); // Reset the form after successful submission
          }
        };
        xhr.send(data); // Send the form data to the server asynchronously
        
        const div = document.querySelector(`#formdiv-${idjawaban}`);
        div.remove();

        sessionStorage.removeItem('dataform');

        const tulisSoal = document.querySelector(`#tulissoal-${idjawaban}`);
        tulisSoal.removeAttribute('class');
        tulisSoal.innerHTML = `| ${data.get("soaljawaban")}`;
        console.log(data.get("soaljawaban"));

      });



}


function closeButton(idjawaban){
    const div = document.querySelector(`#formdiv-${idjawaban}`);
    div.remove();

    const soaltulist = document.querySelector(`#tulissoal-${idjawaban}`)
    soaltulist.setAttribute("onclick", `addForm(${idjawaban})`);

    sessionStorage.removeItem('dataform');
}

