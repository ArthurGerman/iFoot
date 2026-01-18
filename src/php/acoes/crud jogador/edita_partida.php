<?php

    require_once '../../config.php';
    require_once '../../authenticate_jog.php';

    $ID_PTD = $_GET['id'];

    $query = $pdo -> prepare("SELECT * FROM PARTIDAS WHERE ID_PTD = ?");
    $query -> execute([$ID_PTD]);
    $partida = $query -> fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $DATA_PTD = $_POST['DATA_PTD'];
        $HORARIO_INICIO_PTD = $_POST['HORARIO_INICIO_PTD'];
        $HORARIO_FIM_PTD = $_POST['HORARIO_FIM_PTD'];
    

        $query2 = $pdo -> prepare("
            UPDATE PARTIDAS
            SET DATA_PTD = ?, 
            HORARIO_INICIO_PTD = ?,
            HORARIO_FIM_PTD = ?

            WHERE ID_PTD = ?
        ");
        
        $query2 -> execute([
            $DATA_PTD,
            $HORARIO_INICIO_PTD,
            $HORARIO_FIM_PTD,

            $ID_PTD
        ]);

        header("Location: ./lista_partida.php");
    }

?>

<!DOCTYPE html>
    <html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edição de partida</title>
    </head>

    <body>
        <h1>
            Ediçao da partida
        </h1>

        <a href="./inicio_jog.php">Voltar</a><br><br>

        <form action="" method="post">

            <label for="DATA_PTD">Data: </label>
            <input type="date" name="DATA_PTD" id="DATA_PTD" value="<?= $partida['DATA_PTD'] ?>"><br>

            <label for="HORARIO_INICIO_PTD">inicio da Partida: </label>
            <input type="time" name="HORARIO_INICIO_PTD" id="HORARIO_INICIO_PTD" value="<?= $partida['HORARIO_INICIO_PTD'] ?>"><br>

            <label for="HORARIO_FIM_PTD">fim da Partida: </label>
            <input type="time" name="HORARIO_FIM_PTD" id="HORARIO_FIM_PTD" value="<?= $partida['HORARIO_FIM_PTD'] ?>"><br>

            <input type="submit">
        </form>


        <script src="/src/js/tratamento-erros_partida.js"></script>
    </body>

    </html>