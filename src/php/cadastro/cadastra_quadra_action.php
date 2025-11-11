<?php
require_once "../config.php";

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<strong><p style='color:red'>Requisição inválida. Não usou método POST</p></strong>";
    exit;
}

$PRECO_HORA_QUAD = $_POST['PRECO_HORA_QUAD'] ?? '';
$ENDERECO_QUAD   = $_POST['ENDERECO_QUAD'] ?? '';
$CIDADE_QUAD     = $_POST['CIDADE_QUAD'] ?? '';
$ID_PROP         = (int)($_POST['ID_PROP'] ?? 0);
$ID_UF           = (int)($_POST['ID_UF'] ?? 0);
$ID_MODAL        = (int)($_POST['ID_MODAL'] ?? 0);

if (empty($PRECO_HORA_QUAD) || empty($ENDERECO_QUAD) || empty($CIDADE_QUAD) || $ID_PROP<=0 || $ID_UF<=0 || $ID_MODAL<=0) {
    echo "Dados incompletos.";
    exit;
}

try {

    $stmt = $pdo->prepare("SELECT ID_PROP FROM proprietarios WHERE ID_PROP = ?");
    $stmt->execute([$ID_PROP]);
    if ($stmt->rowCount() === 0) {
        echo "Proprietário não encontrado.";
        exit;
    }

    $stmt = $pdo->prepare("SELECT ID_MODAL FROM modalidades WHERE ID_MODAL = ?");
    $stmt->execute([$ID_MODAL]);
    if ($stmt->rowCount() === 0) {
        echo "Modalidade não encontrada.";
        exit;
    }

    $ins = $pdo->prepare("INSERT INTO quadras (PRECO_HORA_QUAD, ENDERECO_QUAD, CIDADE_QUAD, ID_PROP, ID_UF, ID_MODAL) VALUES (?,?,?,?,?,?)");
    $ins->execute([$PRECO_HORA_QUAD, $ENDERECO_QUAD, $CIDADE_QUAD, $ID_PROP, $ID_UF, $ID_MODAL]);

    echo "Quadra cadastrada com sucesso<br>";
    echo "<button><a href='cadastra_quadra.php'>Voltar</a></button>";
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>