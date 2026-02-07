<?php 

    require_once '../../config.php';
    require_once '../../authenticate_jog.php';

    $ID_JOG = $_SESSION['id_jog'];

    // Consulta que junta a tabela intermediária com a tabela de partidas, quadras e modalidades
    $query = $pdo->prepare("
        SELECT DISTINCT
        PARTIDAS.ID_PTD,
        PARTIDAS.DATA_PTD,
        PARTIDAS.HORARIO_INICIO_PTD,
        PARTIDAS.HORARIO_FIM_PTD,
        PARTIDAS.PRECO_TOTAL_PTD,
        PARTIDAS.ID_CRIADOR,
        QUADRAS.CIDADE_QUAD,
        QUADRAS.ENDERECO_QUAD,
        QUADRAS.PRECO_HORA_QUAD,
        IMAGEM.PATH,
        MODALIDADES.NOME_MODAL,
        MODALIDADES.QTD_MAX_JOG,
        UF.NOME_UF,

        (
            SELECT COUNT(*)
            FROM JOGADOR_PARTIDA
            WHERE JOGADOR_PARTIDA.ID_PTD = PARTIDAS.ID_PTD
        ) AS QTD_JOGADORES_ATUAIS

        FROM JOGADOR_PARTIDA
        INNER JOIN PARTIDAS ON JOGADOR_PARTIDA.ID_PTD = PARTIDAS.ID_PTD
        INNER JOIN QUADRAS ON PARTIDAS.ID_QUAD = QUADRAS.ID_QUAD
        INNER JOIN IMAGEM ON QUADRAS.ID_IMAGEM = IMAGEM.ID_IMAGEM
        INNER JOIN MODALIDADES ON QUADRAS.ID_MODAL = MODALIDADES.ID_MODAL
        INNER JOIN UF ON QUADRAS.ID_UF = UF.ID_UF
        WHERE JOGADOR_PARTIDA.ID_JOG = ?

    ");
    $query->execute([$ID_JOG]);
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
    <title>Partidas marcadas</title>
</head>
<body class=" font-outfit font-medium not-italic text-[#6b6b6b]">

    <div class="bg-[#F0F0F0] w-full min-h-screen overflow-x-hidden flex flex-col">


        <!-- Nav -->
        <div class="flex bg-gradient-to-b from-[#4ad658] to-green-500 h-20 items-center">
            <img src="/static/ifoot.png" alt="" class="h-20">

            <a href="./lista_quadra.php" class="text-white ml-16 hover:text-gray-200">Criar partida</a>
            <a href="./lista_partida.php" class="text-white ml-6 hover:text-gray-200">Partidas criadas por mim</a>
        </div>



        <span class="material-symbols-outlined w-10 h-10 flex items-center justify-center rounded-xl bg-gray-300 hover:bg-gray-400 transition mt-4 ml-4">
            <a href="./inicio_jog.php" class="w-10 h-10 flex items-center justify-center rounded-xl">
                reply
            </a>
        </span>

        <div class="mt-6 pl-6 w-full">
            <h1 class="text-[28px]  w-auto h-auto flex items-center justify-start ml-4">
                Todas as partidas marcadas
            </h1>
        </div>

    
        <?php if (empty($partidas)): ?>
            <p class="ml-12 mt-2">Não existem partidas marcadas</p>
        <?php else: ?>
    
            <!-- CARDS QUE MOSTRAM AS PARTIDAS DISPONÍVEIS-->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 px-12 pb-20">
                <?php foreach ($partidas as $partida): ?>
                    
                    <div class="flex bg-white rounded-xl shadow-md overflow-hidden h-[350px] w-[700px]">

                        <!-- Imagem / placeholder -->
                        <div class="w-[400px] bg-gray-300 flex items-center justify-center">
                            <img src="../../../../../storage/<?= $partida['PATH'] ?>" alt="" class="w-full h-full object-cover">
                        </div>

                        <!-- Conteúdo -->
                        <div class="w-[300px] bg-gradient-to-b from-[#4ad658] to-green-500 p-4 text-white flex flex-col justify-between">
                            
                            <div class="text-sm space-y-1 gap-10">

                                <!-- CÓDIGO PHP PARA EXIBIR A DATA E AS HORAS DA PARTIDA CORRETAMENTE-->
                                <?php
                                    $DATA_PTD = date('d/m/Y', strtotime($partida['DATA_PTD']));

                                    $HORARIO_INICIO_PTD = new DateTime($partida['HORARIO_INICIO_PTD']);
                                    $HORARIO_FIM_PTD = new DateTime($partida['HORARIO_FIM_PTD']);


                                    if ($HORARIO_FIM_PTD < $HORARIO_INICIO_PTD) {
                                        $HORARIO_FIM_PTD->modify('+1 day');
                                    }

                                    $intervalo = $HORARIO_INICIO_PTD->diff($HORARIO_FIM_PTD);

                                    $duracao= $intervalo->h . 'h';
                                    if ($intervalo->i > 0) {
                                        $duracao .= $intervalo->i;
                                    }

                                    $HORARIO_INICIO_PTD = $HORARIO_INICIO_PTD->format('H:i');
                                    $HORARIO_FIM_PTD = $HORARIO_FIM_PTD->format('H:i'); 
                                ?>


                                <p class="text-[22px] mb-2"><strong>Data:</strong> <?= $DATA_PTD ?></p>
                                <p><strong>Estado:</strong> <?= $partida['NOME_UF'] ?></p>
                                <p><strong>Cidade:</strong> <?= $partida['CIDADE_QUAD'] ?></p>
                                <p><strong>Endereço:</strong> <?= $partida['ENDERECO_QUAD'] ?></p>
                                <p><strong>Início:</strong> <?= $HORARIO_INICIO_PTD ?> h</p>
                                <p><strong>Fim:</strong> <?= $HORARIO_FIM_PTD ?> h</p>
                                <p><strong>Duração total:</strong> <?= $duracao ?></p>
                                <p><strong>Quantidade atual de jogadores:</strong> <?= $partida['QTD_JOGADORES_ATUAIS'] ?>/<?= $partida['QTD_MAX_JOG'] ?></p>
                                <p><strong>Modalidade:</strong> <?= $partida['NOME_MODAL'] ?></p>
                                <p><strong>Preço por hora: </strong> R$ <?= $partida['PRECO_HORA_QUAD'] ?>/h</p>
                                <p><strong>Preço total:</strong> R$ <?= $partida['PRECO_TOTAL_PTD'] ?></p> <!-- Preço final calculado com base nas horas-->
                            </div>

                            <div class="flex flex-row gap-2">
                                <?php if ($partida['ID_CRIADOR'] == $ID_JOG): ?>

                                    <a href="./edita_partida.php?id=<?= $partida['ID_PTD'] ?>" class="bg-white text-green-600 text-center py-2 rounded-md font-semibold hover:bg-gray-200 transition mt-2 w-1/2">
                                        Editar
                                    </a>

                                    <a href="./excluir_partida.php?id=<?= $partida['ID_PTD'] ?>" class="bg-white text-green-600 text-center py-2 rounded-md font-semibold hover:bg-gray-200 transition mt-2 w-1/2">
                                        Excluir
                                    </a>
                                <?php else: ?>

                                    <a href="./sair_partida.php?id=<?= $partida['ID_PTD'] ?>" class="bg-white text-green-600 text-center py-2 rounded-md font-semibold hover:bg-gray-200 transition mt-2 w-full">
                                        Sair
                                    </a>

                                <?php endif;?>

                            </div>

                        </div>
                    </div>

                <?php endforeach; ?>
            </div>
            
        <?php endif;?>
    </div>



</body>
</html>