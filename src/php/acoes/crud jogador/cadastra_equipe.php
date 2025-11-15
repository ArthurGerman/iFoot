<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $NOME_EQP = $_POST['NOME_EQP'];

    $query = $pdo -> prepare("INSERT INTO equipes (NOME_EQP) VALUES (?)");
    $query -> execute([$NOME_EQP]);

    echo "A equipe $NOME_EQP foi cadastrada com sucesso!";
    //Onde vai mostrar todas as equipes cadastradas
    echo "<button><a href=''>Ver equipes</a></button>";
} else {
?> 

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastramento de equipe</title>
</head>
<body>
    <h1>
        Cadastramento de equipe
    </h1>
    
    <form action="" method="post">

        <label for="NOME_EQP">Nome: </label>
        <input type="text" name="NOME_EQP" id="NOME_EQP"><br>

        <input type="submit">
    </form>

    <script src=""></script> <!-- O arquivo de tratamento de erro dos proprietários ainda vai ser feito-->
</body>
</html>

<?php
}
?>