<?php 

    require_once '../../config.php';
    require_once '../../authenticate_jog.php';

    $ID_JOG = $_SESSION['id_jog'];

    // Consulta que junta a tabela intermediária com a tabela de partidas, quadras e modalidades
    $query = $pdo->prepare("
        SELECT 
        PARTIDAS.ID_PTD,
        PARTIDAS.DATA_PTD,
        PARTIDAS.HORARIO_INICIO_PTD,
        PARTIDAS.HORARIO_FIM_PTD,
        PARTIDAS.PRECO_TOTAL_PTD,
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
    $partidas = $query->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/static/favicon.png" type="image/x-icon">
    <title>Minhas partidas</title>
</head>
<body>
    <a href="./inicio_jog.php">Voltar</a><br>

    <h1>Suas partidas</h1>

    <?php if (empty($partidas)): ?>
        <p>Não existem partidas cadastradas</p>
    <?php else: ?>

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
                <?php foreach ($partidas as $partida): ?>
                    <tr>
                        <td><?= $partida['ENDERECO_QUAD'] ?></td>
                        <td><?= date('d-m-Y', strtotime($partida['DATA_PTD'])) ?></td>
                        <td><?= $partida['HORARIO_INICIO_PTD'] ?></td>
                        <td><?= $partida['HORARIO_FIM_PTD'] ?></td>
                        <td><?= $partida['NOME_MODAL'] ?></td>
                        <td><?= $partida['PRECO_HORA_QUAD'] ?></td>
                        <td><?= $partida['PRECO_TOTAL_PTD'] ?></td>

                        <td><a href="./edita_partida.php?id=<?= $partida['ID_PTD'] ?>">Editar</a></td>
                        <td><a href="./excluir_partida.php?id=<?= $partida['ID_PTD'] ?>">Excluir</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table><br>
        
    <?php endif;?>
    
</body>
</html>