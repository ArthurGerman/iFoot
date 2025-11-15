document.querySelector("form").onsubmit = function(event) {
    let nome = document.getElementById("NOME_PROP").value;
    let email = document.getElementById("EMAIL_PROP").value;
    let CPF = document.getElementById("CPF_PROP").value;
    let telefone = document.getElementById("TEL_PROP").value;
    let senha = document.getElementById("SENHA_PROP").value;
    
    let array = [nome, email, CPF, telefone, senha];
    
    
    for(let i = 0; i < array.length; i++){
        if(array[i] == ""){
            alert("Preencha todos os campos!")
            event.preventDefault();
            break
        }
    }
}