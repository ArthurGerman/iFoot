<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastramento de quadras</title>
</head>
<body>
    <h2>
        Cadastramento de quadras
    </h2>

    <a href="./inicio_prop.php">Voltar</a>

    <form action="" method="post">

        <label for="CPF_PROP">Seu CPF: </label>
        <input type="text" name="CPF_PROP" id="CPF_PROP"><br>


        <label for="PRECO_HORA_QUAD">Preço da hora: </label>
        <input type="number" name="PRECO_HORA_QUAD" id="PRECO_HORA_QUAD"><br>

        <label for="UF">UF: </label>
        <select name="UF" id="UF">
            <option value="">Selecione</option>
            <option value="AC">Acre</option>
            <option value="AL">Alagoas</option>
            <option value="AP">Amapá</option>
            <option value="AM">Amazonas</option>
            <option value="BA">Bahia</option>
            <option value="CE">Ceará</option>
            <option value="DF">Distrito Federal</option>
            <option value="ES">Espírito Santo</option>
            <option value="GO">Goiás</option>
            <option value="MA">Maranhão</option>
            <option value="MT">Mato Grosso</option>
            <option value="MS">Mato Grosso do Sul</option>
            <option value="MG">Minas Gerais</option>
            <option value="PA">Pará</option>
            <option value="PB">Paraíba</option>
            <option value="PR">Paraná</option>
            <option value="PE">Pernambuco</option>
            <option value="PI">Piauí</option>
            <option value="RJ">Rio de Janeiro</option>
            <option value="RN">Rio Grande do Norte</option>
            <option value="RS">Rio Grande do Sul</option>
            <option value="RO">Rondônia</option>
            <option value="RR">Roraima</option>
            <option value="SC">Santa Catarina</option>
            <option value="SP">São Paulo</option>
            <option value="SE">Sergipe</option>
            <option value="TO">Tocantins</option>
        </select><br>

        <label for="CIDADE_QUAD">Cidade: </label>
        <input type="text" name="CIDADE_QUAD" id="CIDADE_QUAD"><br>

        <label for="ENDERECO_QUAD">Endereço: </label>
        <input type="text" name="ENDERECO_QUAD" id="ENDERECO_QUAD"><br>

        <label for="NOME_MODAL">Modalidade: </label>
        <select name="NOME_MODAL" id="NOME_MODAL">
            <option value="">Selecione</option>
            <option value="CAMPO">Campo</option>
            <option value="FUTSAL">Futsal</option>
            <option value="SOCIETY">Society</option>
        </select><br>


        <input type="submit">
    </form>
</body>
</html>