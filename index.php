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

    <?php if (isset($_SESSION['id_jog'])) {
        echo $_SESSION['id_jog'];
    }elseif (isset($_SESSION['id_prop'])){
        echo $_SESSION['id_prop'];
    }else {
        echo "sem login";
    }
    ?>
    <?php if (isset($_SESSION['id_jog'])) : ?>
        <h1>Olá <?php echo $_SESSION['name_jog'] ?></h1>    
    <?php elseif(isset($_SESSION['id_prop'])) : ?>
        <h1>Olá <?php echo $_SESSION['name_prop'] ?></h1>
    <?php else :?>
        <h1>Você não está logado</h1>
    <?php endif; ?>
    
    <h2>
        Cadastro:
    </h2>

    <ul>
        <li>
            <a href="./src/php/cadastro/cadastra_jogador.php">Jogador</a>
        </li>
        <li>
            <a href="./src/php/cadastro/cadastra_proprietario.php">Proprietário</a>
        </li>
    </ul>

    <hr>
    <!--<a href="./src/php/cadastro/cadastra_equipe.php">Equipe</a>--><!--Deixei comentado porque essas funcionalidades são da parte do crud do jogador-->
    <!--<a href="./src/php/cadastro/cadastro_partida.php">Partida</a>-->

    <h2>
        Login:
    </h2>

    <ul>
        <li>
            <a href="./src/php/login/login_prop.php">Proprietário</a>
        </li>
        <li>
            <a href="./src/php/login/login_jog.php">Jogador</a>
        </li>
    </ul>


    <!--<form action="./src/php/login/logout.php">
        <button type="submit">Sair (Logout)</button>
    </form>-->
</body>
</html>