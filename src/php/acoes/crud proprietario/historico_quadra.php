<?php 

    require_once "../../config.php";
    require_once '../../authenticate_prop.php';

    $ID_QUAD = $_GET['id'];

    $query = $pdo->prepare("SELECT * FROM PARTIDAS WHERE ID_QUAD = ?");
    $query->execute([$ID_QUAD]);
    $partidas = $query->fetchAll();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de quadras</title>
</head>
<body>
    <h1>
        Histórico de partidas da quadra
    </h1>

    <a href="./lista_quadras.php">Voltar</a><br><br>
    

    <?php if (empty($partidas)): ?>
        <p>Ainda não existem partidas cadastradas para essa quadra</p>
    <?php else: ?>

        <table border="2">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Horário início</th>
                    <th>Horário fim</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($partidas as $partida): ?>
                    <tr>
                        <td><?= $partida['DATA_PTD'] ?></td>
                        <td><?= $partida['HORARIO_INICIO_PTD'] ?></td>
                        <td><?= $partida['HORARIO_FIM_PTD'] ?></td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
        
        <br>

        <a href="./exporta_historico_quadra.php?id=<?= $ID_QUAD ?>" target="_blank">
            <button>Exportar para PDF</button>
        </a>
    <?php endif?>

</body>
</html>