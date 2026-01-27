<?php 
    require_once '../../config.php';
    require_once '../../authenticate_jog.php';


    $ID_JOG = $_SESSION['id_jog'];
    $ID_PTD = $_GET['id'];


    // Inserção dos id do jogador e da partida na tabela intermediária
    $query = $pdo->prepare("INSERT INTO JOGADOR_PARTIDA (ID_JOG, ID_PTD) VALUES (?, ?)");
    $query->execute([$ID_JOG, $ID_PTD]);
    header('Location: ./partidas_marcadas.php');


?>