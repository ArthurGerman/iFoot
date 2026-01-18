<?php

    require_once '../../config.php';
    require_once '../../authenticate_jog.php';

    $ID_PTD = $_GET['id'];

    $query = $pdo -> prepare("DELETE FROM JOGADOR_PARTIDA WHERE ID_PTD = ?");

    $query -> execute([$ID_PTD]);

    $query2 = $pdo -> prepare("DELETE FROM PARTIDAS WHERE ID_PTD = ?");

    $query2 -> execute([$ID_PTD]);

    header('Location: lista_partida.php');
?>