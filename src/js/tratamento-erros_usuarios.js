// Este arquivo serve exclusivamente para tratamento de erros no momento da inserção dos dados por partes dos usuários. Ele atua no front-end não deixando que o formulário seja enviado sem estar preenchido completamente. Este tratamento de erro serve apenas para o cadastramento do jogador e do proprietário.

document.querySelector("form").onsubmit = function(event) {
    let nome = document.getElementById("nome").value;
    let email = document.getElementById("email").value;
    let CPF = document.getElementById("CPF").value;
    let cidade = document.getElementById("cidade").value;
    let UF = document.getElementById("UF").value;
    let endereco = document.getElementById("endereco").value;
    let telefone = document.getElementById("telefone").value;
    
    let array = [nome, email, CPF, cidade, UF, endereco, telefone];
    
    
    for(let i = 0; i < array.length; i++){
        if(array[i] == ""){
            alert("Preencha todos os campos!")
            event.preventDefault();
            break
        }
    }
}