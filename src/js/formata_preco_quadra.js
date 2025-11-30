// Script destinado exclusivamente ao tramento do valor do preço da hora da quadra na tabela de quadra

function formatarMoeda(campo) {
        let valor = campo.value.replace(/\D/g, ""); // Remove tudo que não for número
        valor = (valor / 100).toFixed(2) + ""; // Divide por 100 e fixa duas casas decimais
        valor = valor.replace(".", ","); // Substitui o ponto por vírgula
        valor = valor.replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Adiciona os pontos de milhar
        campo.value = valor;
    }