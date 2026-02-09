<?php

require_once '../../config.php';
require_once '../../authenticate_jog.php';

$mensagem = $_SESSION['mensagem_update_partida'] ?? null;
$partida_erro = $_SESSION['partida_erro'] ?? null;
unset($_SESSION['mensagem_update_partida'], $_SESSION['partida_erro']);

$ID_JOG = $_SESSION['id_jog'];

// Consulta que junta a tabela intermediária com a tabela de partidas, quadras e modalidades
$query = $pdo->prepare("
        SELECT 
        PARTIDAS.ID_PTD,
        PARTIDAS.DATA_PTD,
        PARTIDAS.HORARIO_INICIO_PTD,
        PARTIDAS.HORARIO_FIM_PTD,
        PARTIDAS.PRECO_TOTAL_PTD,
        QUADRAS.CIDADE_QUAD,
        QUADRAS.ENDERECO_QUAD,
        QUADRAS.PRECO_HORA_QUAD,
        MODALIDADES.NOME_MODAL,
        MODALIDADES.QTD_MAX_JOG,
        IMAGEM.PATH,
        UF.NOME_UF,
        COUNT(JOGADOR_PARTIDA.ID_JOG) AS QTD_JOGADORES_ATUAIS

        FROM PARTIDAS
        LEFT JOIN JOGADOR_PARTIDA ON JOGADOR_PARTIDA.ID_PTD = PARTIDAS.ID_PTD
        INNER JOIN QUADRAS ON PARTIDAS.ID_QUAD = QUADRAS.ID_QUAD
        INNER JOIN IMAGEM ON QUADRAS.ID_IMAGEM = IMAGEM.ID_IMAGEM
        INNER JOIN MODALIDADES ON QUADRAS.ID_MODAL = MODALIDADES.ID_MODAL
        INNER JOIN UF ON QUADRAS.ID_UF = UF.ID_UF
        WHERE PARTIDAS.ID_CRIADOR = ?

        GROUP BY PARTIDAS.ID_PTD

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
    <title>Minhas partidas</title>
</head>

<body class=" font-outfit font-medium not-italic text-[#6b6b6b]">

    <div class="bg-[#F0F0F0] w-full min-h-screen overflow-x-hidden flex flex-col">


        <!-- Nav -->
        <div class="flex bg-gradient-to-b from-[#4ad658] to-green-500 h-20 items-center">
            <a href="./inicio_jog.php">
                <img src="/static/ifoot.png" alt="" class="h-20">
            </a>

            <a href="./lista_quadra.php" class="text-white ml-16 hover:text-gray-200">Criar partida</a>
            <a href="./partidas_marcadas.php" class="text-white ml-6 hover:text-gray-200">Partidas marcadas</a>
        </div>



        <span class="material-symbols-outlined w-10 h-10 flex items-center justify-center rounded-xl bg-gray-300 hover:bg-gray-400 transition mt-4 ml-4">
            <a href="./inicio_jog.php" class="w-10 h-10 flex items-center justify-center rounded-xl">
                reply
            </a>
        </span>

        <div class="mt-6 pl-6 w-full">
            <h1 class="text-[28px]  w-auto h-auto flex items-center justify-start ml-4">
                Partidas criadas por você
            </h1>
        </div>


        <?php if (empty($partidas)): ?>
            <p class="ml-12 mt-2">Não existem partidas cadastradas.</p>
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
                                <p class="text-[22px] mb-2"><strong>Modalidade:</strong> <?= $partida['NOME_MODAL'] ?></p>
                                <p><strong>Estado:</strong> <?= $partida['NOME_UF'] ?></p>
                                <p><strong>Cidade:</strong> <?= $partida['CIDADE_QUAD'] ?></p>
                                <p><strong>Endereço:</strong> <?= $partida['ENDERECO_QUAD'] ?></p>




                                <!-- CÓDIGO PHP PARA EXIBIR A DATA E AS HORAS DA PARTIDA CORRETAMENTE-->
                                <?php
                                $DATA_PTD = date('d/m/Y', strtotime($partida['DATA_PTD']));

                                $HORARIO_INICIO_PTD = new DateTime($partida['HORARIO_INICIO_PTD']);
                                $HORARIO_FIM_PTD = new DateTime($partida['HORARIO_FIM_PTD']);


                                if ($HORARIO_FIM_PTD < $HORARIO_INICIO_PTD) {
                                    $HORARIO_FIM_PTD->modify('+1 day');
                                }

                                $intervalo = $HORARIO_INICIO_PTD->diff($HORARIO_FIM_PTD);

                                $duracao = $intervalo->h . 'h';
                                if ($intervalo->i > 0) {
                                    $duracao .= $intervalo->i;
                                }

                                $HORARIO_INICIO_PTD = $HORARIO_INICIO_PTD->format('H:i');
                                $HORARIO_FIM_PTD = $HORARIO_FIM_PTD->format('H:i');
                                ?>


                                <p><strong>Data:</strong> <?= $DATA_PTD ?></p>
                                <p><strong>Início:</strong> <?= $HORARIO_INICIO_PTD ?> h</p>
                                <p><strong>Fim:</strong> <?= $HORARIO_FIM_PTD ?> h</p>
                                <p><strong>Duração total:</strong> <?= $duracao ?></p>
                                <p><strong>Quantidade atual de jogadores:</strong> <?= $partida['QTD_JOGADORES_ATUAIS'] ?>/<?= $partida['QTD_MAX_JOG'] ?></p>
                                <p><strong>Preço por hora: </strong> R$ <?= $partida['PRECO_HORA_QUAD'] ?>/h</p>
                                <p><strong>Preço total:</strong> R$ <?= $partida['PRECO_TOTAL_PTD'] ?></p> <!-- Preço final calculado com base nas horas-->
                            </div>

                            <div class="flex flex-row gap-2">
                                <button onclick="showModal('myReservEdit-<?= $partida['ID_PTD'] ?>')" class="bg-white text-green-600 text-center py-2 rounded-md font-semibold hover:bg-gray-200 transition mt-2 w-1/2">
                                    Editar
                                </button>

                                <button onclick="showModal('myReservDel-<?= $partida['ID_PTD'] ?>')" class="bg-white text-green-600 text-center py-2 rounded-md font-semibold hover:bg-gray-200 transition mt-2 w-1/2">
                                    Excluir
                                </button>
                            </div>

                        </div>

                        <!-- modal para edição de partida -->
                        <dialog id='myReservEdit-<?= $partida['ID_PTD'] ?>' class=" font-outfit font-medium not-italic text-white rounded-2xl w-[370px]">
                            <div class="bg-gradient-to-b from-[#4ad658] to-green-500 h-fit p-10 rounded-2xl">
                                <h1 class="text-[28px]">
                                    Ediçao da partida
                                </h1>


                                <form action="./edita_partida.php?id=<?= $partida['ID_PTD'] ?>" method="post" class="form_partida">

                                    <div class="flex flex-col mt-2">
                                        <label>Data: </label>
                                        <input type="date" name="DATA_PTD" value="<?= $partida['DATA_PTD'] ?>" class="text-[#6b6b6b] h-9 px-3 rounded-md border border-gray-300 outline-none mb-3" required>

                                        <label>Início da Partida: </label>
                                        <input type="time" name="HORARIO_INICIO_PTD" value="<?= $partida['HORARIO_INICIO_PTD'] ?>" class="text-[#6b6b6b] h-9 px-3 rounded-md border border-gray-300 outline-none mb-3" required>

                                        <label>Fim da Partida: </label>
                                        <input type="time" name="HORARIO_FIM_PTD" value="<?= $partida['HORARIO_FIM_PTD'] ?>" class="text-[#6b6b6b] h-9 px-3 rounded-md border border-gray-300 outline-none mb-3" required>
                                    </div>

                                    <div class="flex flex-row gap-4 mt-4">
                                        <button onclick="closeModal('myReservEdit-<?= $partida['ID_PTD'] ?>', true)" type="button" class="flex w-1/2 h-10 bg-white text-green-600 hover:bg-gray-200 justify-center items-center rounded-xl">Voltar</button>
                                        <button type="submit" class="flex cursor-pointer w-1/2 h-10 bg-white text-green-600 hover:bg-gray-200 justify-center items-center rounded-xl">Editar</button>
                                    </div>
                                </form>

                                <?php if (!empty($mensagem) && $partida_erro == $partida['ID_PTD']) : ?>
                                    <p id="msg" class="text-red-600 mt-4"><?= $mensagem ?></p>
                                <?php endif ?>
                                
                            </div>

                        </dialog>

                        <!-- modal para exclusão de partida -->
                        <dialog id='myReservDel-<?= $partida['ID_PTD'] ?>' class=" font-outfit font-medium not-italic w-1/4 text-white rounded-2xl">
                            <div class="bg-gradient-to-b from-[#4ad658] to-green-500 h-fit p-10 rounded-2xl">
                                <h1 class="text-2xl mb-12">
                                    Realmente Deseja Excluir esta Partida?
                                </h1>

                                <div class="flex flex-row gap-4 mt-4">
                                    <button onclick="closeModal('myReservDel-<?= $partida['ID_PTD'] ?>')" type="button" class="flex w-3/5 h-14 bg-white text-xl text-green-600 hover:bg-gray-200 justify-center items-center rounded-xl">Cancelar</button>
                                    <a href="./excluir_partida.php?id=<?= $partida['ID_PTD'] ?>" class="flex w-3/5 h-14 bg-white text-xl text-green-600 hover:bg-gray-200 justify-center items-center rounded-xl"><button type="submit" >Confirmar</button></a>
                                </div>
                            </div>
                        </dialog>

                        <script src="/src/js/tratamento-erros_partida.js"></script>
                        <script src="/src/js/some_mensagem.js"></script>
                        <script src="/src/js/reserva_modal.js"></script>
                    </div>

                <?php endforeach; ?>
            </div>

        <?php endif; ?>
    </div>

    
    <?php if (!empty($partida_erro)): ?>
        <script>
            window.addEventListener("load", () => {
                setTimeout(() => {
                    showModal("myReservEdit-<?= $partida_erro ?>");
                }, 50);
            });
        </script>
    <?php endif; ?>
</body>

</html>