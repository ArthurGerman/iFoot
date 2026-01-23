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




        $query1 = $pdo->prepare("
            SELECT QUADRAS.PRECO_HORA_QUAD 
            FROM PARTIDAS
            INNER JOIN QUADRAS ON PARTIDAS.ID_QUAD = QUADRAS.ID_QUAD
            WHERE ID_PTD = ?
        ");
        $query1->execute([$ID_PTD]);
        $dados_quadra = $query1->fetch(PDO::FETCH_ASSOC);
        $PRECO_HORA_QUAD = (float) $dados_quadra['PRECO_HORA_QUAD'];

        $HORARIO_INICIO_PTD_FORMAT = new DateTime($HORARIO_INICIO_PTD);
        $HORARIO_FIM_PTD_FORMAT = new DateTime($HORARIO_FIM_PTD);

        if ($HORARIO_FIM_PTD_FORMAT < $HORARIO_INICIO_PTD_FORMAT) { // Caso a partida seja em horários incomuns(ex: 22:00 até 01:00) esse if resolve
            $HORARIO_FIM_PTD_FORMAT->modify('+1 day');
        }
        
        $intervalo = $HORARIO_INICIO_PTD_FORMAT->diff($HORARIO_FIM_PTD_FORMAT); // Diferença de tempo
        $duracao_horas = $intervalo->h + ($intervalo->i / 60); // Transformação do tempo em horas
        
        $PRECO_TOTAL_PTD = $duracao_horas * $PRECO_HORA_QUAD;
    

        $query2 = $pdo -> prepare("
            UPDATE PARTIDAS
            SET DATA_PTD = ?, 
            HORARIO_INICIO_PTD = ?,
            HORARIO_FIM_PTD = ?,
            PRECO_TOTAL_PTD = ?

            WHERE ID_PTD = ?
        ");
        
        $query2 -> execute([
            $DATA_PTD,
            $HORARIO_INICIO_PTD,
            $HORARIO_FIM_PTD,
            $PRECO_TOTAL_PTD,

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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="shortcut icon" href="/static/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="/src/styles/global.css">
    <title>Edição de partida</title>
</head>

<body class=" font-outfit font-medium not-italic text-white">

    <div class="relative bg-gradient-to-b from-[#2ba438] to-[#14551a] w-screen h-screen flex flex-col justify-center items-center">

        <div class="bg-gradient-to-b from-[#4ad658] to-green-500 h-[400px]  p-10 rounded-2xl">
            <h1 class="text-[28px]">
                Ediçao da partida
            </h1>


            <form action="" method="post">

                <div class="flex flex-col mt-2">
                    <label for="DATA_PTD">Data: </label>
                    <input type="date" name="DATA_PTD" id="DATA_PTD" value="<?= $partida['DATA_PTD'] ?>" class="text-[#6b6b6b] h-9 px-3 rounded-md border border-gray-300 outline-none mb-3">
            
                    <label for="HORARIO_INICIO_PTD">Início da Partida: </label>
                    <input type="time" name="HORARIO_INICIO_PTD" id="HORARIO_INICIO_PTD" value="<?= $partida['HORARIO_INICIO_PTD'] ?>" class="text-[#6b6b6b] h-9 px-3 rounded-md border border-gray-300 outline-none mb-3">
            
                    <label for="HORARIO_FIM_PTD">Fim da Partida: </label>
                    <input type="time" name="HORARIO_FIM_PTD" id="HORARIO_FIM_PTD" value="<?= $partida['HORARIO_FIM_PTD'] ?>" class="text-[#6b6b6b] h-9 px-3 rounded-md border border-gray-300 outline-none mb-3">
                </div>



                <div class="flex flex-row gap-4 mt-4">
                    <a href="./lista_partida.php" class="flex w-1/2 h-10 bg-white text-green-600 hover:bg-gray-200 justify-center items-center rounded-xl">Voltar</a>
                    <input type="submit" value="Editar" class="flex cursor-pointer w-1/2 h-10 bg-white text-green-600 hover:bg-gray-200 justify-center rounded-xl">
                </div>
            </form>

        </div>

    </div>



    <script src="/src/js/tratamento-erros_partida.js"></script>
    <script src="/src/js/some_mensagem.js"></script>
</body>

</html>