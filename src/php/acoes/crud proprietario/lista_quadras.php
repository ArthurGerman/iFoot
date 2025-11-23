<?php 
    session_start();

    require_once "../../config.php";

    $ID_PROP = $_SESSION['id_prop'];

    $query = $pdo->prepare("
        SELECT QUADRAS.ID_QUAD, QUADRAS.PRECO_HORA_QUAD, QUADRAS.ENDERECO_QUAD, QUADRAS.CIDADE_QUAD, UF.NOME_UF, MODALIDADES.NOME_MODAL
        FROM QUADRAS
        INNER JOIN UF ON QUADRAS.ID_UF = UF.ID_UF
        INNER JOIN MODALIDADES ON QUADRAS.ID_MODAL = MODALIDADES.ID_MODAL
        WHERE QUADRAS.ID_PROP = ?
    ");
    $query->execute([$ID_PROP]);
    $quadras = $query->fetchAll(PDO::FETCH_ASSOC);

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
        Aqui estará a lista de quadras do usuário
    </h1>

    <a href="./inicio_prop.php">Voltar</a><br><br>

    <table border="2">
        <thead>
            <tr>
                <th>ID</th>
                <th>Preço por hora</th>
                <th>Endereço</th>
                <th>Cidade</th>
                <th>Estado</th>
                <th>Modalidade</th>
                <th colspan="2">Ações</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($quadras as $quadra): ?>
                <tr>
                    <td><?= $quadra['ID_QUAD'] ?></td>
                    <td><?= $quadra['PRECO_HORA_QUAD'] ?></td>
                    <td><?= $quadra['ENDERECO_QUAD'] ?></td>
                    <td><?= $quadra['CIDADE_QUAD'] ?></td>
                    <td><?= $quadra['NOME_UF'] ?></td>
                    <td><?= $quadra['NOME_MODAL'] ?></td>

                    <td><a href="">Editar</a></td> 
                    <td><a href="">Excluir</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>