document.querySelector("form").onsubmit = function(event) {
    let endereco = document.getElementById("ENDERECO_QUAD").value;
    let cidade = document.getElementById("CIDADE_QUAD").value;
    let UF = document.getElementById("UF").value;
    let modalidade = document.getElementById("NOME_MODAL").value;
    let preco = document.getElementById("PRECO_HORA_QUAD").value;
    
    let array = [endereco, cidade, UF, modalidade, preco];
    
    
    for(let i = 0; i < array.length; i++){
        if(array[i] == ""){
            alert("Preencha todos os campos!")
            event.preventDefault();
            break
        }
    }
}