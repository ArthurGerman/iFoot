<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastramento</title>
</head>
<body>
    <h1>
        Cadastramento de usuário
    </h1>
    
    <form action="cadastra_jogador_action.php" method="post">

        <label for="NOME_JOG">Nome: </label>
        <input type="text" name="NOME_JOG" id="NOME_JOG"><br>
        
        <label for="EMAIL_JOG">E-mail: </label>
        <input type="email" name="EMAIL_JOG" id="EMAIL_JOG"><br>

        <label for="SENHA_JOG">Senha</label>
        <input type="password" name="SENHA_JOG" id="SENHA_JOG"><br>

        <label for="CPF_JOG">CPF: </label>
        <input type="text" name="CPF_JOG" id="CPF_JOG" maxlength="11"><br>

        <label for="CIDADE_JOG">Cidade: </label>
        <input type="text" name="CIDADE_JOG" id="CIDADE_JOG"><br>

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
        
        <label for="ENDERECO_JOG">Endereço: </label>
        <input type="endereco" name="ENDERECO_JOG" id="ENDERECO_JOG"><br>
        
        <label for="TEL_JOG">Telefone: </label>
        <input type="telefone" name="TEL_JOG" id="TEL_JOG" maxlength="11"><br>

        <input type="submit">
    </form>

    <script src="../js/tratamento-erros_usuarios.js"></script>
</body>
</html>