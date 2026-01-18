<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/src/styles/index.css">
    <title>iFoot</title>
</head>
<body>

    <div class="container">

        <div class="nav">

            <img src="./static/ifoot.png" alt="" id="ifoot-logo">
            
            <a href="" id="funcionamento">Como funciona</a>
            <a href="./src/php/sobre.php" id="sobre">Sobre</a>

            <div class="login">

                <a href="./src/php/login/login_jog.php">Entrar como jogador</a>
                <a href="./src/php/login/login_prop.php">Entrar como proprietário</a>

            </div>
            
        </div>

        
        <div class="body">

            <div class="registro">
                <h2>
                    Registre sua conta de <br> jogador
                </h2>

                <a href="./src/php/cadastro/cadastra_jogador.php">Registrar-se</a>

            </div>
        
            <div class="registro">
                <h2>
                    Registre sua conta de <br> proprietário
                </h2>

                <a href="./src/php/cadastro/cadastra_proprietario.php">Registrar-se</a>

            </div>
        </div>
    </div>

</body>
</html>