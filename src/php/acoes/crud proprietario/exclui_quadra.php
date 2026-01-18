<?php 

    require_once "../../config.php";
    require_once '../../authenticate_prop.php';

    $ID_QUAD = $_GET['id'];

    // Verifica se existe alguma partida cadastrada na quadra
    $query1 = $pdo->prepare("SELECT 1 FROM PARTIDAS WHERE ID_QUAD = ? LIMIT 1");
    $query1->execute([$ID_QUAD]);
    $partida = $query1->fetch();

    if ($partida) {
        echo 'Não é possível excluir a quadra, pois existem partidas cadastradas nela. <br>';
        echo '<button><a href="./lista_quadras.php">Voltar</a></button>';
    } else{

        // Se não houver partidas, exclui a quadra
        $query2 = $pdo->prepare("DELETE FROM QUADRAS WHERE ID_QUAD = ?");
        $query2->execute([$ID_QUAD]);
    
        echo 'Quadra excluída com sucesso! <br>';
        echo '<button><a href="./lista_quadras.php">Voltar</a></button>';
    }

?>