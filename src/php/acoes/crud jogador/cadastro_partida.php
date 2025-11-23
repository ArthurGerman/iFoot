<?php
require_once '../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $DATA_PTD = $_POST['DATA_PTD'];
    $HORARIO_INICIO_PTD = $_POST['HORARIO_INICIO_PTD'];
    $HORARIO_FIM_PTD = $_POST['HORARIO_FIM_PTD'];

    // valor temporario
    $ID_QUAD = 1;

    $query = $pdo->prepare("INSERT INTO PARTIDAS (DATA_PTD, HORARIO_INICIO_PTD, HORARIO_FIM_PTD, ID_QUAD) VALUES (?, ?, ?, ?)");
    $query->execute([$DATA_PTD, $HORARIO_INICIO_PTD, $HORARIO_FIM_PTD, $ID_QUAD]);

    echo "Partida cadastrada com sucesso!";
    //Onde vai mostrar as informções da partida
    echo "<button><a href=''>Ver partida</a></button>";
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

        <form action="" method="post">

            <label for="DATA_PTD">Data: </label>
            <input type="date" name="DATA_PTD" id="DATA_PTD"><br>

            <label for="HORARIO_INICIO_PTD">inicio da Partida: </label>
            <input type="time" name="HORARIO_INICIO_PTD" id="HORARIO_INICIO_PTD"><br>

            <label for="HORARIO_FIM_PTD">fim da Partida: </label>
            <input type="time" name="HORARIO_FIM_PTD" id="HORARIO_FIM_PTD"><br>

            <input type="submit">
        </form>

        <a href="./inicio_jog.php">Voltar</a><br><br>

        <script src="/src/js/tratamento-erros_partida.js"></script>
    </body>

    </html>

<?php
}
?>