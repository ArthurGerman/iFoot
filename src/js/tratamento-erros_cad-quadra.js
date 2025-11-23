document.querySelector("form").onsubmit = function(event) {
    let preco = document.getElementById("PRECO_HORA_QUAD").value;
    let UF = document.getElementById("UF").value;
    let endereco = document.getElementById("ENDERECO_QUAD").value;
    let cidade = document.getElementById("CIDADE_QUAD").value;
    let modalidade = document.getElementById("NOME_MODAL").value;
    
    let array = [preco, UF, endereco, cidade, modalidade];
    
    
    for(let i = 0; i < array.length; i++){
        if(array[i] == ""){
            alert("Preencha todos os campos!")
            event.preventDefault();
            break
        }
    }
}