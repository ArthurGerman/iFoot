<?php

    // Código específico para excluir o jogador da partida que ele entrou. Essa partida que ele entrou foi criada por outro jogador
    require_once '../../config.php';
    require_once '../../authenticate_jog.php';


    $ID_JOG = $_SESSION['id_jog'];
    $ID_PTD = $_GET['id'];


    $query = $pdo->prepare("DELETE FROM JOGADOR_PARTIDA WHERE ID_JOG = ? AND ID_PTD = ?");
    $query->execute([$ID_JOG, $ID_PTD]);
    header('Location: ./partidas_marcadas.php');

?>