<?php 
    require_once '../config.php';
    session_start();

    /* --------------------- ATENÇÃO -------------------------
    O trecho de código abaixo serve para a funcionalidadeque permite redirecionar o usuário diretamente para a página inicial do crud quando já está logado

    if(isset($_SESSION['id_prop'])){
         header('Location: /src/php/acoes/crud%20proprietario/inicio_prop.php');
    }else if(isset($_SESSION['id_jog'])) {
        header('Location: /src/php/acoes/crud%20jogador/inicio_jog.php');
    };*/

    $erro = ""; // Variável para que a mensagem de erro apareca depois do form

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $EMAIL_PROP = $_POST['EMAIL_PROP'];
        $SENHA_PROP = $_POST['SENHA_PROP'];
    
    
        $query = $pdo->prepare("SELECT * FROM PROPRIETARIOS WHERE EMAIL_PROP = ?");
        $query->execute([$EMAIL_PROP]);
        $proprietario = $query->fetch(PDO::FETCH_ASSOC);
    

        if ($proprietario && password_verify($SENHA_PROP, $proprietario['SENHA_PROP'])) {

            $_SESSION['id_prop'] = $proprietario['ID_PROP'];
            $_SESSION['name_prop'] = $proprietario['NOME_PROP'];

            header('Location: /src/php/acoes/crud%20proprietario/inicio_prop.php');
            
        } else {
            $erro = "Email ou senha estão incorretos";
        }
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

    <a href="/index.php">Voltar a Home</a><br>

    <h1>Login de proprietário</h1>

    <form action="" method="post">
        <label for="EMAIL_PROP">Email: </label>
        <input type="email" name="EMAIL_PROP" id="EMAIL_PROP"><br>

        <label for="SENHA_PROP">Senha:</label>
        <input type="password" name="SENHA_PROP" id="SENHA_PROP"><br>

        <input type="submit">
    </form>

    <?php if ($erro): ?>
        <p style="color:red"><?php echo $erro; ?></p>
    <?php endif; ?>
</body>
</html>