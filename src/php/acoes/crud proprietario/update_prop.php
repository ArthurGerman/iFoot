<?php
    session_start();
    require_once '../../config.php';

    $ID_PROP = $_SESSION['id_prop'];

    $query = $pdo->prepare("SELECT * FROM PROPRIETARIOS WHERE ID_PROP = ?");
    $query->execute([$ID_PROP]);
    $results = $query->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $NOME_PROP = $_POST['NOME_PROP'];
        $EMAIL_PROP = $_POST['EMAIL_PROP'];
        $CPF_PROP = $_POST['CPF_PROP'];
        $TEL_PROP = $_POST['TEL_PROP'];
        $SENHA_PROP = $_POST['SENHA_PROP'];

        $query = $pdo -> prepare("
            UPDATE PROPRIETARIOS
            SET NOME_PROP = ?, 
            EMAIL_PROP = ?,
            CPF_PROP = ?,
            TEL_PROP = ?,
            SENHA_PROP = ?

            WHERE ID_PROP = ?
        ");
        
        $query -> execute([
            $NOME_PROP,
            $EMAIL_PROP,
            $CPF_PROP,
            $TEL_PROP,
            $SENHA_PROP,

            $ID_PROP
        ]);

        header("Location: ./inicio_prop.php");
    }
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <input type="text" name="CPF_PROP" id="CPF_PROP" maxlength="11" value="<?=$results["CPF_PROP"]?>"><br>

        <label for="TEL_PROP">Telefone: </label>
        <input type="telefone" name="TEL_PROP" id="TEL_PROP" maxlength="11" value="<?=$results["TEL_PROP"]?>"><br>

        <label for="SENHA_PROP">Senha: </label>
        <input type="text" name="SENHA_PROP" id="SENHA_PROP" value="<?=$results["SENHA_PROP"]?>"><br>

        <input type="submit">
    </form>

    <script src="/src/js/tratamento-erros_prop.js"></script>
</body>

</html>