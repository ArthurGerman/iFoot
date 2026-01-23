<?php

    require_once '../../config.php';
    require_once '../../authenticate_prop.php';

    $mensagem_erro = ""; // Variável para armazenar a mensagem de erro que aparece caso o usuário tente cadastrar um email e/ou senha que já existem no banco de dados

    $ID_PROP = $_SESSION['id_prop'];

    $query = $pdo->prepare("SELECT * FROM PROPRIETARIOS WHERE ID_PROP = ?");
    $query->execute([$ID_PROP]);
    $results = $query->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $NOME_PROP = $_POST['NOME_PROP'];
        $EMAIL_PROP = $_POST['EMAIL_PROP'];
        $TEL_PROP = $_POST['TEL_PROP'];
        $SENHA_PROP = $_POST['SENHA_PROP']; // Senha nova

        //Query para verificar se o email cadastrado já existem
        $verifica_email = $pdo->prepare("SELECT 1 FROM PROPRIETARIOS WHERE EMAIL_PROP = ? AND ID_PROP != ?");

        $verifica_email->execute([$EMAIL_PROP, $ID_PROP]);

        if ($verifica_email->rowCount() > 0){

            $mensagem_erro =  "❌ Este e-mail já está sendo usado por outro usuário.<br>";

        } else{ //Bloco de alteração de dados

            if(!empty($SENHA_PROP)){ // Se a senha não estiver vazia, o sistema atualiza a senha pois não tem como alterar uma senha que já foi feito o hash
                
                $SENHA_PROP = password_hash($SENHA_PROP, PASSWORD_BCRYPT); //Senha nova com hash
                
                $query = $pdo -> prepare("
                    UPDATE PROPRIETARIOS
                    SET NOME_PROP = ?, 
                    EMAIL_PROP = ?,
                    TEL_PROP = ?,
                    SENHA_PROP = ?
        
                    WHERE ID_PROP = ?
                ");
                
                $query -> execute([
                    $NOME_PROP,
                    $EMAIL_PROP,
                    $TEL_PROP,
                    $SENHA_PROP,
        
                    $ID_PROP
                ]);

                
            } else{ // Se o usuário não colocou uma senha nova, o sistema não atualiza
                $query = $pdo -> prepare("
                    UPDATE PROPRIETARIOS
                    SET NOME_PROP = ?, 
                    EMAIL_PROP = ?,
                    TEL_PROP = ?
        
                    WHERE ID_PROP = ?
                    ");
                
                $query -> execute([
                    $NOME_PROP,
                    $EMAIL_PROP,
                    $TEL_PROP,
        
                    $ID_PROP
                ]);

            }

            header("Location: ../../login/login_prop.php");
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="shortcut icon" href="/static/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="/src/styles/global.css">
    <title>Atualização de dados</title>
</head>

<body>
    <h1>
        Atualização de dados do prorietário
    </h1>

    <a href="./inicio_prop.php">Voltar</a><br><br>

    <form action="" method="post">

        <label for="NOME_PROP">Nome: </label>
        <input type="text" name="NOME_PROP" id="NOME_PROP" value="<?=$results["NOME_PROP"]?>"><br>

        <label for="EMAIL_PROP">E-mail: </label>
        <input type="email" name="EMAIL_PROP" id="EMAIL_PROP" value="<?=$results["EMAIL_PROP"]?>"><br>

        <label for="CPF_PROP">CPF: </label>
        <input type="text" name="CPF_PROP" id="CPF_PROP" maxlength="11" value="<?=$results["CPF_PROP"]?>" disabled><br>

        <label for="TEL_PROP">Telefone: </label>
        <input type="telefone" name="TEL_PROP" id="TEL_PROP" maxlength="11" value="<?=$results["TEL_PROP"]?>"><br>

        <label for="SENHA_PROP">Senha: </label>
        <input type="password" name="SENHA_PROP" id="SENHA_PROP" placeholder="Digite uma nova senha(opcional)"><br>

        <input type="submit">
    </form>

    <?php if (!empty($mensagem_erro)) :?>
        <p style="color:red"><?= $mensagem_erro ?></p>
    <?php endif;?> 

    <script src="/src/js/tratamento-erros-update_prop.js"></script>
</body>
</html>