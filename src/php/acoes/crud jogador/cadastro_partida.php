<?php
    session_start();

    require_once '../../config.php';

    $ID_JOG = $_SESSION['id_jog'];
    $ID_QUAD = $_GET['id'];


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        date_default_timezone_set('America/Sao_Paulo');

        $DATA_PTD = $_POST['DATA_PTD'];
        $HORARIO_INICIO_PTD = $_POST['HORARIO_INICIO_PTD'];
        $HORARIO_FIM_PTD = $_POST['HORARIO_FIM_PTD'];

        $data_hora_agora = new DateTime();
        $data_hora_partida = new DateTime($DATA_PTD . ' ' . $HORARIO_INICIO_PTD);

        if ($data_hora_partida < $data_hora_agora) { // Testa se o usuário está tentando cadastrar em uma data/horário que já passou
            echo "<p style='color:red;'>❌ Não é permitido cadastrar partidas em datas ou horários que já passaram.</p>";
            echo "<button type='button' onclick='history.back()'>Voltar</button>";
            exit;
        }
        if ($HORARIO_FIM_PTD <= $HORARIO_INICIO_PTD) { // Testa se o usuário está colocando o horário de fim antes do horário de início da partida
            echo "<p style='color:red;'>❌ O horário final deve ser maior que o horário inicial.</p>";
            echo "<button type='button' onclick='history.back()'>Voltar</button>";
            exit;
        }

        $stmt = $pdo->prepare("SELECT HORARIO_INICIO_PTD, HORARIO_FIM_PTD FROM PARTIDAS WHERE ID_QUAD = ? AND DATA_PTD = ?");
        $stmt->execute([$ID_QUAD, $DATA_PTD]);
        $times = $stmt;

        $conflict = false;

        if ($stmt->rowCount() > 0) {
            foreach ($times as $time) {
                if ($HORARIO_INICIO_PTD < $time["HORARIO_FIM_PTD"] && $HORARIO_FIM_PTD > $time["HORARIO_INICIO_PTD"]) {
                    $conflict = true;
                    break;
                }
            };
        }

        if (!$conflict) {

            $query2 = $pdo->prepare("INSERT INTO PARTIDAS (DATA_PTD, HORARIO_INICIO_PTD, HORARIO_FIM_PTD, ID_QUAD) VALUES (?, ?, ?, ?)");
            $query2->execute([$DATA_PTD, $HORARIO_INICIO_PTD, $HORARIO_FIM_PTD, $ID_QUAD]);

            $ID_PTD = $pdo->lastInsertId(); // Comando para descobrir o id da última quadra a qual o usuário fez a reserva
        
            // Query para inserir na tabela intermediária o id do jogador e da partida

            $query3 = $pdo->prepare("INSERT INTO JOGADOR_PARTIDA (ID_JOG, ID_PTD) VALUES (?, ?)");
            $query3->execute([$ID_JOG, $ID_PTD]);


            echo "Partida cadastrada com sucesso!<br>";
            //Onde vai mostrar as informções da partida
            echo "<button><a href='./lista_partida.php'>Ver minhas partidas</a></button>";
        } else {
            echo "<p style='color:red;'>❌ Horário indisponível. Já existe uma partida nesse período.</p>";
            echo "<button type='button' onclick='history.back()'>Voltar</button>";
        };

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

        <a href="./lista_quadra.php">Voltar</a><br><br>

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