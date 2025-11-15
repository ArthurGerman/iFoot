// Este arquivo serve exclusivamente para tratamento de erros no momento da inserção dos dados por partes dos usuários. Ele atua no front-end não deixando que o formulário seja enviado sem estar preenchido completamente. Este tratamento de erro serve apenas para o cadastramento do jogador e do proprietário.

document.querySelector("form").onsubmit = function(event) {
    let nome = document.getElementById("NOME_JOG").value;
    let email = document.getElementById("EMAIL_JOG").value;
    let CPF = document.getElementById("CPF_JOG").value;
    let cidade = document.getElementById("CIDADE_JOG").value;
    let endereco = document.getElementById("ENDERECO_JOG").value;
    let telefone = document.getElementById("TEL_JOG").value;
    let senha = document.getElementById("SENHA_JOG").value
    let UF = document.getElementById("UF").value;
    
    let array = [nome, email, CPF, cidade, endereco, telefone, senha, UF];
    
    
    for(let i = 0; i < array.length; i++){
        if(array[i] == ""){
            alert("Preencha todos os campos!")
            event.preventDefault();
            break
        }
    }
}