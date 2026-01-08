// Script específico para o update do jogador, onde o usuário pode não digitar uma nova senha, ou seja, a senha nova é opcional


document.querySelector("form").onsubmit = function(event) {
    let nome = document.getElementById("NOME_JOG").value;
    let email = document.getElementById("EMAIL_JOG").value;
    let CPF = document.getElementById("CPF_JOG").value;
    let cidade = document.getElementById("CIDADE_JOG").value;
    let endereco = document.getElementById("ENDERECO_JOG").value;
    let telefone = document.getElementById("TEL_JOG").value;
    let UF = document.getElementById("UF").value;
    
    let array = [nome, email, CPF, cidade, endereco, telefone, UF];
    
    
    for(let i = 0; i < array.length; i++){
        if(array[i] == ""){
            alert("Preencha todos os campos obrigatórios!")
            event.preventDefault();
            break
        }
    }
}