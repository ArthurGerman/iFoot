// Comandos para o menu lateral na tela de início do jogador(inicio.jog) funcionar

const btnMenu = document.getElementById('btnMenu');
const menu = document.getElementById('menuLateral');
const overlay = document.getElementById('menuOverlay');
const fechar = document.getElementById('fecharMenu');

function abrirMenu() {
    menu.classList.remove('translate-x-full');
    overlay.classList.remove('hidden');
}

function fecharMenu() {
    menu.classList.add('translate-x-full');
    overlay.classList.add('hidden');
}

btnMenu.addEventListener('click', abrirMenu);
fechar.addEventListener('click', fecharMenu);
overlay.addEventListener('click', fecharMenu);