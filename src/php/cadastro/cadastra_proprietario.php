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

        <label for="TEL_PROP">Telefone: </label>
        <input type="telefone" name="TEL_PROP" id="TEL_PROP" maxlength="11"><br>

        <input type="submit">
    </form>

    <script src="../js/tratamento-erros_usuarios.js"></script>
</body>
</html>