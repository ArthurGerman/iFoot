// Script para fazer as mensagens de erro ou sucesso desaparecerem após 3 segundos. Essas mensagens são usadas no cadastramento e edição dos proprietários, jogadores, partidas e quadras.

const msg = document.getElementById('msg');

if (msg) {
    setTimeout(() => {
        msg.style.transition = 'opacity 0.5s ease';
        msg.style.opacity = '0';

        setTimeout(() => {
            msg.remove();
        }, 500);
    }, 6000); // 6 segundos
}