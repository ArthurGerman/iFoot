// Faz a ação da caixa do filtro

const btnFiltro = document.getElementById('btnFiltro');
const caixaFiltro = document.getElementById('caixaFiltro');
const fecharFiltro = document.getElementById('fecharFiltro');
const overlayFiltro = document.getElementById('overlayFiltro');

btnFiltro.addEventListener('click', () => {
    caixaFiltro.classList.toggle('hidden');
    overlayFiltro.classList.toggle('hidden');
});

fecharFiltro.addEventListener('click', () => {
    caixaFiltro.classList.add('hidden');
    overlayFiltro.classList.add('hidden');
});

overlayFiltro.addEventListener('click', () => {
    caixaFiltro.classList.add('hidden');
    overlayFiltro.classList.add('hidden');
});



// Limpa os filtros
document.getElementById('limparFiltro').addEventListener('click', () => {
    document.querySelectorAll('#caixaFiltro input').forEach(input => {
        if (input.type === 'checkbox' || input.type === 'radio') {
            input.checked = false;
        } else {
            input.value = '';
        }
    });

    document.querySelectorAll('#caixaFiltro select').forEach(select => {
        select.selectedIndex = 0;
    });
});