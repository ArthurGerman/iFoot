document.querySelector("form").onsubmit = function(event) {
    let email = document.getElementById("EMAIL_JOG").value;
    let senha = document.getElementById("SENHA_JOG").value;
    
    let array = [email, senha,];
    
    
    for(let i = 0; i < array.length; i++){
        if(array[i] == ""){
            alert("Preencha todos os campos!")
            event.preventDefault();
            break
        }
    }
}