<?php 
    require_once '../config.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $EMAIL_PROP = $_POST['EMAIL_PROP'];
        $SENHA_PROP = $_POST['SENHA_PROP'];
    
    
        $query = $pdo->prepare("SELECT * FROM PROPRIETARIOS WHERE EMAIL_PROP = ? AND SENHA_PROP = ?");
        $query->execute([$EMAIL_PROP, $SENHA_PROP]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            $_SESSION['id_prop'] = $result['ID_PROP'];
            $_SESSION['name_prop'] = $result['NOME_PROP'];
            header('Location: /index.php');
        } else {
            echo "Email ou senha estão incorretos";
        }
    }
    //     if ($result) {
    //         //localização do crud do proprietário. Como não tem ainda, vou redirecionar para o cadastro de quadra
    //         header("Location: ../cadastro/cadastra_quadra.php");
    //     } else {
    //             echo "<p style='color:red'>Email ou senha inválidos!</p>";
    //     }
    // }

    
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="" method="post">
        <label for="EMAIL_PROP">Email: </label>
        <input type="email" name="EMAIL_PROP" id="EMAIL_PROP"><br>

        <label for="SENHA_PROP">Senha:</label>
        <input type="password" name="SENHA_PROP" id="SENHA_PROP"><br>

        <input type="submit">
    </form>
</body>
</html>