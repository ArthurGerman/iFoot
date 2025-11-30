<?php
    session_start();

    require_once '../../config.php';

    $ID_JOG = $_SESSION['id_jog'];
    $ID_QUAD = $_GET['id'];


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $DATA_PTD = $_POST['DATA_PTD'];
        $HORARIO_INICIO_PTD = $_POST['HORARIO_INICIO_PTD'];
        $HORARIO_FIM_PTD = $_POST['HORARIO_FIM_PTD'];


        $query1 = $pdo->prepare("INSERT INTO PARTIDAS (DATA_PTD, HORARIO_INICIO_PTD, HORARIO_FIM_PTD, ID_QUAD) VALUES (?, ?, ?, ?)");
        $query1->execute([$DATA_PTD, $HORARIO_INICIO_PTD, $HORARIO_FIM_PTD, $ID_QUAD]);

        $ID_PTD = $pdo->lastInsertId(); // Comando para descobrir o id da última quadra a qual o usuário fez a reserva
        
        // Query para inserir na tabela intermediária o id do jogador e da partida

        $query2 = $pdo->prepare("INSERT INTO JOGADOR_PARTIDA (ID_JOG, ID_PTD) VALUES (?, ?)");
        $query2->execute([$ID_JOG, $ID_PTD]);


        echo "Partida cadastrada com sucesso!<br>";
        //Onde vai mostrar as informções da partida
        echo "<button><a href='./lista_partida.php'>Ver minhas partidas</a></button>";
    } else {
?>
    <!DOCTYPE html>
    <html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cadastrar Partida</title>
    </head>

    <body>
        <h1>
            Cadastro da partida
        </h1>

        <a href="./inicio_jog.php">Voltar</a><br><br>

        <form action="" method="post">

            <label for="DATA_PTD">Data: </label>
            <input type="date" name="DATA_PTD" id="DATA_PTD"><br>

            <label for="HORARIO_INICIO_PTD">inicio da Partida: </label>
            <input type="time" name="HORARIO_INICIO_PTD" id="HORARIO_INICIO_PTD"><br>

            <label for="HORARIO_FIM_PTD">fim da Partida: </label>
            <input type="time" name="HORARIO_FIM_PTD" id="HORARIO_FIM_PTD"><br>

            <input type="submit">
        </form>


        <script src="/src/js/tratamento-erros_partida.js"></script>
    </body>

    </html>

<?php
}
?>