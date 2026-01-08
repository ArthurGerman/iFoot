// Script específico para o update do proprietário, onde o usuário pode não digitar uma nova senha, ou seja, a senha nova é opcional

document.querySelector("form").onsubmit = function(event) {
    let nome = document.getElementById("NOME_PROP").value;
    let email = document.getElementById("EMAIL_PROP").value;
    let CPF = document.getElementById("CPF_PROP").value;
    let telefone = document.getElementById("TEL_PROP").value;

    let array = [nome, email, CPF, telefone];
    
    
    for(let i = 0; i < array.length; i++){
        if(array[i] == ""){
            alert("Preencha todos os campos obrigatórios!")
            event.preventDefault();
            break
        }
    }
}