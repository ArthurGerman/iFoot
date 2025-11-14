<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iFoot</title>
</head>
<body>
    <h1>
        Início do projeto
    </h1>

    <?php echo $_SESSION['id_jog'];?>
    
    <?php if (isset($_SESSION['id_jog'])) : ?>
        <h1>Olá <?php echo $_SESSION['name_jog'] ?></h1>    
    <?php else :?> 
        <h1>Não funcionou</h1>
    <?php endif; ?>
    
    <h2>
        Cadastro:
    </h2>
    <a href="./src/php/cadastro/cadastra_jogador.php">Jogador</a>
    <a href="./src/php/cadastro/cadastra_proprietario.php">Proprietário</a>
    <a href="./src/php/cadastro/cadastra_equipe.php">Equipe</a>
    <a href="./src/php/cadastro/cadastro_partida.php">Partida</a>

    <h2>
        Login:
    </h2>
    <a href="./src/php/login/login_prop.php">Proprietário</a>
    <a href="./src/php/login/login_jog.php">Jogador</a>
</body>
</html>