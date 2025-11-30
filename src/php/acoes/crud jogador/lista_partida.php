<?php 
    session_start();

    require_once '../../config.php';

    $ID_JOG = $_SESSION['id_jog'];

    // Consulta que junta a tabela intermediária com a tabela de partidas, quadras e modalidades
    $query = $pdo->prepare("
        SELECT 
        PARTIDAS.ID_PTD,
        PARTIDAS.DATA_PTD,
        PARTIDAS.HORARIO_INICIO_PTD,
        PARTIDAS.HORARIO_FIM_PTD,
        QUADRAS.ENDERECO_QUAD,
        QUADRAS.PRECO_HORA_QUAD,
        MODALIDADES.NOME_MODAL

        FROM JOGADOR_PARTIDA
        INNER JOIN PARTIDAS ON JOGADOR_PARTIDA.ID_PTD = PARTIDAS.ID_PTD
        INNER JOIN QUADRAS ON PARTIDAS.ID_QUAD = QUADRAS.ID_QUAD
        INNER JOIN MODALIDADES ON QUADRAS.ID_MODAL = MODALIDADES.ID_MODAL
        WHERE JOGADOR_PARTIDA.ID_JOG = ?;

    ");
    $query->execute([$ID_JOG]);
    $results = $query->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas partidas</title>
</head>
<body>
    <a href="./inicio_jog.php">Voltar</a><br>

    <h1>Suas partidas</h1>

    <table border="2">
        <thead>
            <tr>
                <th>Endereço</th>
                <th>Data</th>
                <th>Início</th>
                <th>Fim</th>
                <th>Modalidade</th>
                <th>Preço por hora</th>
                <th>Preço total</th>
                <th colspan="2">Ações</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($results as $partida): ?>
                <?php
                    // O código abaixo serve para fazer a lógica do preço da partida com base na quantidade de tempo

                    // Construir DateTime completos com a data da partida
                    $inicio = new DateTime($partida['DATA_PTD'] . ' ' . $partida['HORARIO_INICIO_PTD']);
                    $fim    = new DateTime($partida['DATA_PTD'] . ' ' . $partida['HORARIO_FIM_PTD']);

                    // Se o fim for menor que o início, avançar um dia (cruza meia-noite)
                    if ($fim < $inicio) {
                        $fim->modify('+1 day');
                    }

                    // Calcular duração em horas decimais
                    $segundos = $fim->getTimestamp() - $inicio->getTimestamp();
                    $duracao_horas = $segundos / 3600;

                    // Proteger contra valores inválidos
                    if ($duracao_horas < 0) {
                        $duracao_horas = 0;
                    }

                    // Preço total
                    $preco_total = $duracao_horas * (float)$partida['PRECO_HORA_QUAD'];

                ?>

                <tr>
                    <td><?= $partida['ENDERECO_QUAD'] ?></td>
                    <td><?= date('d-m-Y', strtotime($partida['DATA_PTD'])) ?></td>
                    <td><?= $partida['HORARIO_INICIO_PTD'] ?></td>
                    <td><?= $partida['HORARIO_FIM_PTD'] ?></td>
                    <td><?= $partida['NOME_MODAL'] ?></td>
                    <td><?= $partida['PRECO_HORA_QUAD'] ?></td>
                    <td><?= $preco_total ?></td>

                    <td><a href="">Editar</a></td>
                    <td><a href="">Excluir</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table><br>
    
</body>
</html>