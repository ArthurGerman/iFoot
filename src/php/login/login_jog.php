<?php 
    require_once '../config.php';
    session_start();

    /* --------------------- ATENÇÃO -------------------------
    O trecho de código abaixo serve para a funcionalidadeque permite redirecionar o usuário diretamente para a página inicial do crud quando já está logado
    
    if(isset($_SESSION['id_jog'])){
        header('Location: /src/php/acoes/crud%20jogador/inicio_jog.php');
    }else if(isset($_SESSION['id_prop'])) {
        header('Location: /src/php/acoes/crud%20proprietario/inicio_prop.php');
    };*/

    $erro = ""; // Variável para que a mensagem de erro apareca depois do form

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $EMAIL_JOG = $_POST['EMAIL_JOG'];
        $SENHA_JOG = $_POST['SENHA_JOG'];
    
    
        $query = $pdo -> prepare("SELECT * FROM jogadores WHERE EMAIL_JOG = ?");
        $query -> execute([$EMAIL_JOG]);
        $jogador = $query->fetch(PDO::FETCH_ASSOC);
        
        
        if ($jogador && password_verify($SENHA_JOG, $jogador['SENHA_JOG'])) {

            $_SESSION['id_jog'] = $jogador['ID_JOG'];
            $_SESSION['name_jog'] = $jogador['NOME_JOG'];

            header('Location: /src/php/acoes/crud%20jogador/inicio_jog.php');

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

    <h1>Login de jogador</h1>

    <form action="" method="post">
        <label for="EMAIL_JOG">Email: </label>
        <input type="email" name="EMAIL_JOG" id="EMAIL_JOG"><br>
        
        <label for="SENHA_JOG">Senha:</label>
        <input type="password" name="SENHA_JOG" id="SENHA_JOG"><br>
        
        <input type="submit">
    </form>

    <?php if ($erro): ?>
        <p style="color:red"><?php echo $erro; ?></p>
    <?php endif; ?>
</body>
</html>