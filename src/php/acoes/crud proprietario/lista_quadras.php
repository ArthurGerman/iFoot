<?php 

    require_once "../../config.php";
    require_once '../../authenticate_prop.php';

    $ID_PROP = $_SESSION['id_prop'];

    $query = $pdo->prepare("
        SELECT QUADRAS.ID_QUAD, QUADRAS.PRECO_HORA_QUAD, QUADRAS.ENDERECO_QUAD, QUADRAS.CIDADE_QUAD, QUADRAS.STATUS_QUAD, UF.NOME_UF, MODALIDADES.NOME_MODAL
        FROM QUADRAS
        INNER JOIN UF ON QUADRAS.ID_UF = UF.ID_UF
        INNER JOIN MODALIDADES ON QUADRAS.ID_MODAL = MODALIDADES.ID_MODAL
        WHERE QUADRAS.ID_PROP = ?
        ORDER BY QUADRAS.ID_QUAD ASC
    ");
    $query->execute([$ID_PROP]);
    $quadras = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="shortcut icon" href="/static/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="/src/styles/global.css">
    <title>Lista de quadras</title>
</head>
<body>
    <h1>
        Suas quadras
    </h1>

    <a href="./inicio_prop.php">Voltar</a><br><br>
    
    <?php if (empty($quadras)): ?>
        <p>Não existem quadras cadastradas</p>
    <?php else: ?>

        <table border="2">
            <thead>
                <tr>
                    <th>Endereço</th>
                    <th>Cidade</th>
                    <th>Estado</th>
                    <th>Modalidade</th>
                    <th>Preço por hora</th>
                    <th>Situação</th>
                    <th colspan="3">Ações</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($quadras as $quadra): ?>
                    <tr>
                        <td><?= $quadra['ENDERECO_QUAD'] ?></td>
                        <td><?= $quadra['CIDADE_QUAD'] ?></td>
                        <td><?= $quadra['NOME_UF'] ?></td>
                        <td><?= $quadra['NOME_MODAL'] ?></td>
                        <td><?= $quadra['PRECO_HORA_QUAD'] ?></td>
                        <td><?= $quadra['STATUS_QUAD'] == 1 ? 'A' : 'I' ?></td>

                        <td><a href="./edita_quadra.php?id=<?= $quadra['ID_QUAD'] ?>">Editar</a></td> 
                        <td><a href="./exclui_quadra.php?id=<?= $quadra['ID_QUAD'] ?>">Excluir</a></td>
                        <td><a href="./historico_quadra.php?id=<?= $quadra['ID_QUAD'] ?>">Ver histórico</a></td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>