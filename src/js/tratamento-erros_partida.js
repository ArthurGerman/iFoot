// Este arquivo serve exclusivamente para tratamento de erros no momento da inserção dos dados por partes dos usuários. Ele atua no front-end não deixando que o formulário seja enviado sem estar preenchido completamente. Este tratamento de erro serve apenas para o cadastramento do jogador e do proprietário.

document.querySelector("form").onsubmit = function(event) {
    let data = document.getElementById("DATA_PTD").value;
    let inicio = document.getElementById("HORARIO_INICIO_PTD").value;
    let fim = document.getElementById("HORARIO_FIM_PTD").value;
    
    let array = [data, inicio, fim];
    
    
    for(let i = 0; i < array.length; i++){
        if(array[i] == ""){
            alert("Preencha todos os campos!")
            event.preventDefault();
            break
        }
    }
}