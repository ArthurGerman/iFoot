<?php

    require_once '../../config.php';
    require_once '../../authenticate_jog.php';

    $ID_JOG = $_SESSION['id_jog'];


    // Consulta para saber o id da UF onde o jogador reside
    $query1 = $pdo->prepare("SELECT ID_UF FROM JOGADORES WHERE ID_JOG = ?");
    $query1->execute([$ID_JOG]);
    $result = $query1->fetch(PDO::FETCH_ASSOC);

    $ID_UF_JOG = $result['ID_UF']; // Id do estado em que o jogador está cadastrado


    // Consulta para descobrir o nome da UF onde o jogador vive com base no id da consulta anterior
    $query2 = $pdo->prepare("SELECT NOME_UF FROM UF WHERE ID_UF = ?");
    $query2->execute([$ID_UF_JOG]);
    $result2 = $query2->fetch(PDO::FETCH_ASSOC);

    $NOME_UF_JOG = $result2['NOME_UF']; // Nome do estado em que o jogador mora. Essa linha é para apenas mostrar o estado no h1 mais abaixo no html
    
    $query3 = $pdo->prepare("
        SELECT 
        QUADRAS.ID_QUAD,
        QUADRAS.PRECO_HORA_QUAD, 
        QUADRAS.ENDERECO_QUAD, 
        QUADRAS.CIDADE_QUAD, 
        MODALIDADES.NOME_MODAL,
        UF.ID_UF

        FROM QUADRAS
        INNER JOIN MODALIDADES ON QUADRAS.ID_MODAL = MODALIDADES.ID_MODAL
        INNER JOIN UF ON QUADRAS.ID_UF = UF.ID_UF
        WHERE QUADRAS.ID_UF = ? AND QUADRAS.STATUS_QUAD = 1
    ");
    $query3->execute([$ID_UF_JOG]);
    $quadras = $query3->fetchAll(PDO::FETCH_ASSOC);

    
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Partida</title>
</head>

<body>

    <a href="./inicio_jog.php">Voltar</a><br>

    <h1>
        Quadras disponíveis em <?= $result2['NOME_UF'] ?>
    </h1>

    <?php if (empty($quadras)): ?>
        <p>Não existem quadras disponíveis para <?= $result2['NOME_UF'] ?></p>
    <?php else: ?>
        
        <form action="" method="post">
            <table border="2">
                <thead>
                    <tr>
                        <th>Endereço</th>
                        <th>Modalidade</th>
                        <th>Preço por hora</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($quadras as $quadra): ?>
                        <tr>
                            <td><?= $quadra['ENDERECO_QUAD'] ?></td>
                            <td><?= $quadra['NOME_MODAL'] ?></td>
                            <td><?= $quadra['PRECO_HORA_QUAD'] ?></td>

                            <td><a href="./cadastro_partida.php?id=<?= $quadra['ID_QUAD'] ?>">Reservar</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table><br>

        </form>
    <?php endif; ?>

</body>
</html>