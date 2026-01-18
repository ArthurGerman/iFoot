document.querySelector("form").onsubmit = function(event) {
    let email = document.getElementById("EMAIL_PROP").value;
    let senha = document.getElementById("SENHA_PROP").value;
    
    let array = [email, senha];
    
    
    for(let i = 0; i < array.length; i++){
        if(array[i] == ""){
            alert("Preencha todos os campos!")
            event.preventDefault();
            break
        }
    }
}