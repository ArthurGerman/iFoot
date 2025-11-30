<?php 
    session_start();

    require_once "../../config.php";


    $ID_QUAD = $_GET['id']; //Id da quadra a ser editada

    $query = $pdo->prepare("SELECT * FROM QUADRAS WHERE ID_QUAD = ?");
    $query->execute([$ID_QUAD]);
    $quadra = $query->fetch(PDO::FETCH_ASSOC);

    // Buscar os estados (UF)
    $query2 = $pdo->prepare("SELECT * FROM UF");
    $query2->execute();
    $ufs = $query2->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $ENDERECO_QUAD = $_POST['ENDERECO_QUAD'];
        $CIDADE_QUAD = $_POST['CIDADE_QUAD'];
        $SIGLA_UF = $_POST['UF'];
        $NOME_MODAL = $_POST['NOME_MODAL'];
        $PRECO_HORA_QUAD = str_replace(['.', ','], ['', '.'], $_POST['PRECO_HORA_QUAD']);
        $STATUS_QUAD = $_POST['STATUS_QUAD'];

        $query3 = $pdo->prepare("
            UPDATE QUADRAS
            SET ENDERECO_QUAD = ?, 
            CIDADE_QUAD = ?, 
            ID_UF = ?, 
            ID_MODAL = ?, 
            PRECO_HORA_QUAD = ?, 
            STATUS_QUAD = ?

            WHERE ID_QUAD = ?
        ");

        $query3->execute([
            $ENDERECO_QUAD, 
            $CIDADE_QUAD, 
            $SIGLA_UF, 
            $NOME_MODAL, 
            $PRECO_HORA_QUAD, 
            $STATUS_QUAD, 

            $ID_QUAD
        ]);

        header("Location: ./lista_quadras.php");
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver quadra</title>
</head>
<body>

    <h1>
        Edição de quadra
    </h1>

    <a href="./lista_quadras.php">Voltar</a>

    <form action="" method="post">
        
        <label for="ENDERECO_QUAD">Endereço: </label>
        <input type="text" name="ENDERECO_QUAD" id="ENDERECO_QUAD" value="<?= $quadra['ENDERECO_QUAD'] ?>"><br>

        <label for="CIDADE_QUAD">Cidade: </label>
        <input type="text" name="CIDADE_QUAD" id="CIDADE_QUAD" value="<?= $quadra['CIDADE_QUAD'] ?>"><br>
        
        <label for="UF">UF: </label>
        <select name="UF" id="UF">
            <option value="">Selecione</option>
            <?php foreach ($ufs as $uf): ?>
                <option value="<?= $uf['ID_UF'] ?>" <?= $quadra['ID_UF'] == $uf['ID_UF'] ? 'selected' : '' ?>>
                    <?= $uf['NOME_UF'] ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label for="NOME_MODAL">Modalidade: </label>
        <select name="NOME_MODAL" id="NOME_MODAL">
            <option value="">Selecione</option>
            <option value="1" <?= $quadra['ID_MODAL'] == 1 ? 'selected' : '' ?>>Campo</option>
            <option value="2" <?= $quadra['ID_MODAL'] == 2 ? 'selected' : '' ?>>Society</option>
            <option value="3" <?= $quadra['ID_MODAL'] == 3 ? 'selected' : '' ?>>Quadra</option>
        </select><br>

        <label for="PRECO_HORA_QUAD">Preço da hora: </label>
        <input type="text" name="PRECO_HORA_QUAD" id="PRECO_HORA_QUAD" value="<?= number_format($quadra['PRECO_HORA_QUAD'], 2, ',', '.') ?>" oninput="formatarMoeda(this)"><br>

        <label for="STATUS_QUAD">Situação: </label>
        <select name="STATUS_QUAD" id="STATUS_QUAD">
            <option value="1" <?= $quadra['STATUS_QUAD'] == 1 ? 'selected' : '' ?>>Ativa</option>
            <option value="0" <?= $quadra['STATUS_QUAD'] == 0 ? 'selected' : '' ?>>Inativa</option>
        </select><br>

        <input type="submit" value="Editar">
    </form>

    <script src="../../../js/formata_preco_quadra.js"></script>
    <script src="../../../js/tratamento-erros_cad-quadra.js"></script>
</body>
</html>