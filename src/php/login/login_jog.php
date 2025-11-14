<?php 
    require_once '../config.php';
    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $EMAIL_JOG = $_POST['EMAIL_JOG'];
        $SENHA_JOG = $_POST['SENHA_JOG'];
    
    
        $query = $pdo -> prepare("SELECT * FROM jogadores WHERE EMAIL_JOG = ? AND SENHA_JOG = ?");
        $query -> execute([$EMAIL_JOG, $SENHA_JOG]);
        $result = $query -> fetch(PDO::FETCH_ASSOC);

        
        if ($result) {
            $_SESSION['id_jog'] = $result['ID_JOG'];
            $_SESSION['name_jog'] = $result['NOME_JOG'];
            header('Location: /index.php');
        } else {
            echo "Email ou senha estão incorretos";
        }
        
        
        // if ($result) {
            //     //localização do crud do jogador. Como não tem ainda, vou redirecionar para o cadastro de equipe
            //     header("Location: ../cadastro/cadastra_equipe.php");
            // } else {
                //         echo "<p style='color:red'>Email ou senha inválidos!</p>";
                // }
            }
            
            
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
            <label for="EMAIL_JOG">Email: </label>
            <input type="email" name="EMAIL_JOG" id="EMAIL_JOG"><br>
            
            <label for="SENHA_JOG">Senha:</label>
            <input type="password" name="SENHA_JOG" id="SENHA_JOG"><br>
            
            <input type="submit">
        </form>
    </body>
    </html>