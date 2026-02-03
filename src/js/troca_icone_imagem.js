// Script para trocar o ícone de avatar pela imagem de perfil que o usuário anexar

const inputImagem = document.getElementById('imagem');
const previewImagem = document.getElementById('preview-imagem');
const iconePerson = document.getElementById('icone-person');

inputImagem.addEventListener('change', () => {
    const file = inputImagem.files[0];

    if (!file) return;

    // valida se é imagem
    if (!file.type.startsWith('image/')) {
        alert('Selecione um arquivo de imagem válido');
        inputImagem.value = '';
        return;
    }

    const reader = new FileReader();

    reader.onload = () => {
        previewImagem.src = reader.result;
        previewImagem.classList.remove('hidden');
        iconePerson.classList.add('hidden');
    };

    reader.readAsDataURL(file);
});