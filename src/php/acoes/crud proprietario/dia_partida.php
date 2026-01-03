<?php 
    // Lista as partidas que ocorrerão no dia, além dos horários em que vão acontecer

    session_start();

    require_once "../../config.php";


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
    <title>Agenda do dia</title>
</head>
<body>
    
    <button>
        <a href="./calendario_partidas.php">Voltar</a>
    </button>

    <h2>Agenda do dia <?= date('d/m/Y', strtotime($data)) ?></h2>

    <table border="2">
        <thead>
            <tr>
                <th>Id da partida</th>
                <th>Início</th>
                <th>Fim</th>
                <th>Id da quadra</th>
                <th>Endereço</th>
                <th>Cidade</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($partidas as $partida): ?>
                <tr>
                    <td><?= $partida['ID_PTD'] ?></td>
                    <td><?= $partida['HORARIO_INICIO_PTD'] ?></td>
                    <td><?= $partida['HORARIO_FIM_PTD'] ?></td>
                    <td><?= $partida['ID_QUAD'] ?></td>
                    <td><?= $partida['ENDERECO_QUAD'] ?></td>
                    <td><?= $partida['CIDADE_QUAD'] ?></td>

                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>

</body>
</html>