<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="shortcut icon" href="/static/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="/src/styles/global.css">
    <title>Página do proprietário</title>
</head>
<body>
    <h1>
        Painel do proprietário
    </h1>

    <ul>
        <li>
            <a href="./cadastra_quadra.php">Cadastrar nova quadra</a>
        </li>
        <li>
            <a href="./lista_quadras.php">Ver minhas quadras</a>
        </li>
        <li>
            <a href="./calendario_partidas.php">Ver partidas agendadas para minhas quadras</a>
        </li>
        <li>
            <a href="./update_prop.php">Editar meus dados</a>
        </li>
    </ul>

    <form action="../../login/logout.php">
        <button type="submit">Sair (Logout)</button>
    </form>

</body>
</html>