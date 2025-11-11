<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastramento</title>
</head>
<body>
    <h1>
        Cadastramento de usuario
    </h1>
    
    <form action="cadastra_proprietario_action.php" method="post">

        <label for="NOME_PROP">Nome: </label>
        <input type="text" name="NOME_PROP" id="NOME_PROP"><br>
        
        <label for="EMAIL_PROP">E-mail: </label>
        <input type="email" name="EMAIL_PROP" id="EMAIL_PROP"><br>

        <label for="SENHA_PROP">Senha: </label>
        <input type="password" name="SENHA_PROP" id="SENHA_PROP"><br>

        <label for="CPF_PROP">CPF: </label>
        <input type="text" name="CPF_PROP" id="CPF_PROP" maxlength="11"><br>

        <!-- <label for="UF">UF: </label>
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
        </select><br> -->
        
        <label for="TEL_PROP">Telefone: </label>
        <input type="telefone" name="TEL_PROP" id="TEL_PROP" maxlength="11"><br>

        <input type="submit">
    </form>

    <script src="../js/tratamento-erros_usuarios.js"></script>
</body>
</html>