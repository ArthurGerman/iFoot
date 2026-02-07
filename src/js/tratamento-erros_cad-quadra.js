document.querySelector("#form_cadastro_quadra").onsubmit = function(event) {
    let endereco = document.getElementById("ENDERECO_QUAD").value.trim();
    let cidade = document.getElementById("CIDADE_QUAD").value.trim();
    let UF = document.getElementById("UF").value;
    let modalidade = document.getElementById("NOME_MODAL").value;
    let preco = document.getElementById("PRECO_HORA_QUAD").value.trim();
    let imagem = document.getElementById("imagem").files.length;
    
    let array = [endereco, cidade, UF, modalidade, preco];
    
    
    for(let i = 0; i < array.length; i++){
        if(array[i] == ""){
            alert("Preencha todos os campos!");
            event.preventDefault();
            return
        }
    }

    if (imagem === 0) {
        alert("Você deve adicionar uma imagem da quadra!");
        event.preventDefault();
        return;
    }
}