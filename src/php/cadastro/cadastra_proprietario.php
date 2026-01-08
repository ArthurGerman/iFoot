<?php
    require_once "../config.php";

    $mensagem_erro = ""; // Variável para armazenar a mensagem de erro que aparece caso o usuário tente cadastrar um email e/ou senha que já existem no banco de dados

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $NOME_PROP = $_POST['NOME_PROP'];
        $EMAIL_PROP = $_POST['EMAIL_PROP'];
        $CPF_PROP = $_POST['CPF_PROP'];
        $TEL_PROP = $_POST['TEL_PROP'];
        $SENHA_PROP = password_hash($_POST['SENHA_PROP'], PASSWORD_BCRYPT);


        //Query para verificar se o CPF e/ou email cadastrados já existem
        // São duas verificações únicas pois possa ser que o email ou o CPF foram cadastrados iguais de maneira individual e não os dois juntos
        $verifica_email = $pdo->prepare("SELECT 1 FROM PROPRIETARIOS WHERE EMAIL_PROP = ?");
        $verifica_cpf = $pdo->prepare("SELECT 1 FROM PROPRIETARIOS WHERE CPF_PROP = ?");

        $verifica_email->execute([$EMAIL_PROP]);
        $verifica_cpf->execute([$CPF_PROP]);

        if ($verifica_cpf->rowCount() > 0 && $verifica_email->rowCount() > 0) {

            $mensagem_erro = "❌ CPF e Email já estão cadastrados no sistema.<br>";

        } else if ($verifica_email->rowCount() > 0){

            $mensagem_erro =  "❌ Este e-mail já está cadastrado no sistema.<br>";

        } else if ($verifica_cpf->rowCount() > 0){

            $mensagem_erro =  "❌ Este CPF já está cadastrado no sistema.<br>";

        } else{
            $query = $pdo->prepare("INSERT INTO PROPRIETARIOS (NOME_PROP, EMAIL_PROP, CPF_PROP, TEL_PROP, SENHA_PROP) VALUES (?,?,?,?,?)");
            $query->execute([$NOME_PROP, $EMAIL_PROP, $CPF_PROP, $TEL_PROP, $SENHA_PROP]);

            header('Location: ../login/login_prop.php');

            //echo "Olá $NOME_PROP ! Seus dados foram cadastrados com sucesso <br>";
            //echo "<button><a href='../login/login_prop.php'>Realizar login</a></button>";
        }

    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastramento</title>
</head>
<body>
    <h1>
        Cadastramento de Proprietário
    </h1>

    <a href="/index.php">Voltar</a><br><br>
    
    <form action="" method="post">

        <label for="NOME_PROP">Nome: </label>
        <input type="text" name="NOME_PROP" id="NOME_PROP"><br>
        
        <label for="EMAIL_PROP">E-mail: </label>
        <input type="email" name="EMAIL_PROP" id="EMAIL_PROP"><br>
        
        <label for="CPF_PROP">CPF: </label>
        <input type="text" name="CPF_PROP" id="CPF_PROP" maxlength="11"><br>
        
        <label for="TEL_PROP">Telefone: </label>
        <input type="telefone" name="TEL_PROP" id="TEL_PROP" maxlength="11"><br>
        
        <label for="SENHA_PROP">Senha: </label>
        <input type="password" name="SENHA_PROP" id="SENHA_PROP"><br>

        <input type="submit">
    </form>

    <?php if (!empty($mensagem_erro)) :?>
        <p style="color:red"><?= $mensagem_erro ?></p>
    <?php endif;?> 

    <script src="/src/js/tratamento-erros_prop.js"></script>
</body>
</html>