<?php 
    // Lista as partidas que ocorrerão no dia, além dos horários em que vão acontecer

    require_once "../../config.php";
    require_once '../../authenticate_prop.php';


    $ID_PROP = $_SESSION['id_prop'];
    $data = $_GET['data'];


    // Buscar partidas do dia vinculadas as quadras do proprietário
    $query = $pdo->prepare("
        SELECT 
            PARTIDAS.HORARIO_INICIO_PTD,
            PARTIDAS.HORARIO_FIM_PTD,
            PARTIDAS.ID_PTD,
            QUADRAS.ID_QUAD,
            QUADRAS.ENDERECO_QUAD,
            QUADRAS.CIDADE_QUAD

        FROM PARTIDAS
        INNER JOIN QUADRAS ON PARTIDAS.ID_QUAD = QUADRAS.ID_QUAD
        INNER JOIN PROPRIETARIOS ON QUADRAS.ID_PROP = PROPRIETARIOS.ID_PROP
        WHERE PARTIDAS.DATA_PTD = ?
        AND QUADRAS.ID_PROP = ?
        ORDER BY PARTIDAS.HORARIO_INICIO_PTD
    ");

    $query->execute([$data, $ID_PROP]);
    $partidas = $query->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="shortcut icon" href="/static/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="/src/styles/global.css">
    <title>Agenda do dia</title>
</head>
<body class=" font-outfit font-medium not-italic text-[#6b6b6b]">

    <div class="bg-[#F0F0F0] w-full h-full min-h-screen overflow-x-hidden flex flex-col">
        
        <!-- Nav -->
        <div class="flex bg-gradient-to-b from-[#4ad658] to-green-500 h-20">
            <img src="/static/ifoot.png" alt="" class="h-20">
        </div>
    
    
    
        <a href="./calendario_partidas.php">
            <button>
                <span class="material-symbols-outlined w-10 h-10 flex items-center justify-center rounded-xl bg-gray-300 hover:bg-gray-400 transition mt-4 ml-4">reply</span>
            </button>
        </a>


        <div class="mt-4 w-full">
            <h1 class="text-[28px]  w-auto h-auto flex items-center justify-start ml-4">
                Agenda do dia <?= date('d/m/Y', strtotime($data)) ?>
            </h1>
        </div>



        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 px-6 pb-20">
            <?php foreach ($partidas as $partida): ?>

                <?php
                    $inicio = new DateTime($partida['HORARIO_INICIO_PTD']);
                    $fim = new DateTime($partida['HORARIO_FIM_PTD']);

                    $intervalo = $inicio->diff($fim);

                    $horas = $intervalo->h;
                    $minutos = $intervalo->i;

                    $duracao = $horas.'h';

                    if ($minutos > 0) {
                        $duracao .= $minutos . 'min';
                    }
                ?>
                
                <div class="flex bg-white rounded-xl shadow-md overflow-hidden h-48">

                    <!-- Imagem / placeholder -->
                    <div class="w-1/2 bg-gray-300 flex items-center justify-center">
                        <span class="text-gray-500">Imagem da quadra</span>
                    </div>

                    <!-- Conteúdo -->
                    <div class="w-1/2 bg-gradient-to-b from-[#4ad658] to-green-500 p-4 text-white flex flex-col justify-between">
                        
                        <div class="text-sm space-y-1 gap-10">
                            <p><strong>ID:</strong> <?= $partida['ID_PTD'] ?></p>
                            <p><strong>Início:</strong> <?= substr($partida['HORARIO_INICIO_PTD'], 0, 5) ?> h</p>
                            <p><strong>Fim:</strong> <?= substr($partida['HORARIO_FIM_PTD'], 0, 5) ?> h</p>
                            <p><strong>Duração:</strong> <?= $duracao ?> </p>
                            <p><strong>Id da quadra:</strong> <?= $partida['ID_QUAD'] ?></p>
                            <p><strong>Endereço:</strong> <?= $partida['ENDERECO_QUAD'] ?></p>
                            <p><strong>Cidade:</strong> <?= $partida['CIDADE_QUAD'] ?></p>
                        </div>


                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    </div>
    

</body>
</html>