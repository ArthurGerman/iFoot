// Este arquivo serve exclusivamente para tratamento de erros no momento da inserção dos dados por partes dos usuários. Ele atua no front-end não deixando que o formulário seja enviado sem estar preenchido completamente. Este tratamento de erro serve apenas para o cadastramento do jogador e do proprietário.

document.querySelectorAll(".form_partida").forEach(form => {

    form.addEventListener("submit", function(event){

        const data   = form.querySelector("[name='DATA_PTD']").value.trim();
        const inicio = form.querySelector("[name='HORARIO_INICIO_PTD']").value.trim();
        const fim    = form.querySelector("[name='HORARIO_FIM_PTD']").value.trim();

        if (!data || !inicio || !fim) {
            alert("Preencha todos os campos!");
            event.preventDefault();
            return;
        }

    });

});