<?php

    require_once "../../config.php";
    require_once '../../authenticate_prop.php';

    // Definir mês e ano atuais
    date_default_timezone_set('America/Sao_Paulo');

    // Definir mês e ano atuais
    $mes = isset($_GET['mes']) ? (int)$_GET['mes'] : date('n');
    $ano = date('Y');

    $data_hoje = date('Y-m-d');

    $ID_PROP = $_SESSION['id_prop']; // Id do proprietário

    // Query para buscar os dias que ocorrerão as partidas
    $query = $pdo->prepare("
        SELECT DISTINCT PARTIDAS.DATA_PTD

        FROM PARTIDAS

        INNER JOIN QUADRAS ON PARTIDAS.ID_QUAD = QUADRAS.ID_QUAD
        INNER JOIN PROPRIETARIOS ON QUADRAS.ID_PROP = PROPRIETARIOS.ID_PROP
        WHERE PROPRIETARIOS.ID_PROP = ? AND 
        MONTH(PARTIDAS.DATA_PTD) = ? AND 
        YEAR(PARTIDAS.DATA_PTD) = ?;

    ");
    $query->execute([$ID_PROP, $mes, $ano]);    
    $datas_partidas = $query->fetchAll(PDO::FETCH_COLUMN);


    // Número de dias no mês
    $dias_no_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

    // Primeiro dia da semana (0 = domingo, 1 = segunda, etc.)
    $primeiro_dia_semana = date('w', strtotime("$ano-$mes-01"));

    // Nomes dos meses em português
    $nomes_meses = [
        1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril",
        5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto",
        9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro"
    ];

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="shortcut icon" href="/static/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="/src/styles/global.css">
    <title>Calendário</title>
</head>
<body class=" font-outfit font-medium not-italic text-[#6b6b6b]">

    <div class="bg-[#F0F0F0] w-full h-full min-h-screen overflow-x-hidden flex flex-col">

        <!-- Nav -->
        <div class="flex bg-gradient-to-b from-[#4ad658] to-green-500 h-20 items-center">
            <img src="/static/ifoot.png" alt="" class="h-20">

            <a href="./cadastra_quadra.php" class="text-white ml-16 hover:text-gray-200">Cadastrar nova quadra</a>
        </div>
    
    
    
        <span class="material-symbols-outlined w-10 h-10 flex items-center justify-center rounded-xl bg-gray-300 hover:bg-gray-400 transition mt-4 ml-4">
            <a href="./inicio_prop.php" class="w-10 h-10 flex items-center justify-center rounded-xl">
                reply
            </a>
        </span>


        <div class="mt-6 pl-6 w-full">
            <h1 class="text-[28px]  w-auto h-auto flex items-center justify-start ml-4">
                Agenda para <?php echo $nomes_meses[$mes] . " de " . $ano; ?>
            </h1>
        </div>
    

        <div class="flex ml-14 mt-6">
            <form action="">
                <label for="mes">Mês: </label>
                <select name="mes" id="mes">
                    <?php
                        foreach ($nomes_meses as $numero => $nome) {
                            $selected = ($numero == $mes) ? 'selected' : '';
                            echo "<option value='$numero' $selected>$nome</option>";
                        }
                    ?>
                </select>
        
                <input type="submit" value="Ver" class="bg-green-500 text-white px-2 ml-2 rounded">
            </form>
        </div>
        
    
        <table class="w-[60%] border-collapse text-center ml-14 mt-6">
            <tr class="text-white">
                <th class="bg-green-500 border border-gray-400 py-2">Dom</th>
                <th class="bg-green-500 border border-gray-400 py-2">Seg</th>
                <th class="bg-green-500 border border-gray-400 py-2">Ter</th>
                <th class="bg-green-500 border border-gray-400 py-2">Qua</th>
                <th class="bg-green-500 border border-gray-400 py-2">Qui</th>
                <th class="bg-green-500 border border-gray-400 py-2">Sex</th>
                <th class="bg-green-500 border border-gray-400 py-2">Sáb</th>
            </tr>
            <tr>
                <?php
                    // Espaços antes do primeiro dia
                    for ($i = 0; $i < $primeiro_dia_semana; $i++) {
                        echo "<td class='border border-gray-400 h-12 w-20'></td>";
                    }
    
                    // Preencher os dias
                    $dia_semana = $primeiro_dia_semana;
                    
                    for ($dia = 1; $dia <= $dias_no_mes; $dia++) {
    
                        $data_completa = sprintf('%04d-%02d-%02d', $ano, $mes, $dia);
                        $tem_partida = in_array($data_completa, $datas_partidas);
    
                        $classes = [
                            'border border-gray-400',
                            'h-12',
                            'w-20',
                            'align-middle'
                        ]; // Array com os nomes das classes CSS
    
                        if ($data_completa === $data_hoje) {
                            $classes[] = 'bg-yellow-300 font-bold';
                        }

                        if ($tem_partida) {
                            $classes[] = 'text-blue-600 font-bold hover:bg-blue-100';
                        }
    
                        elseif ($dia_semana == 0) {
                            $classes[] = 'text-red-500 font-semibold';
                        }

    
                        $class_attr = !empty($classes) ? " class='" . implode(' ', $classes) . "'" : ""; // Linha que faz a concatenação das classes hoje/domingo para imprimir junto com o <td>. Isso serve para deixar os dias de domingo marcados de vermelho
                        echo "<td$class_attr>"; // Impressão dos dias
    
    
    
    
    
                        if ($tem_partida) {
                            echo "<a class='flex justify-center items-center w-full h-full' href='./dia_partida.php?data=$data_completa'>$dia</a>";
                        } else {
                            echo $dia;
                        }
    
                        echo "</td>";
    
                        $dia_semana++;
                        if ($dia_semana == 7) {
                            echo "</tr><tr>";
                            $dia_semana = 0;
                        }
                    }
    
                    // Completar a última semana
                    if ($dia_semana > 0) {
                        for ($i = $dia_semana; $i < 7; $i++) {
                            echo "<td class='border border-gray-400 h-12 w-20'></td>";
                        }
                    }
                ?>
            </tr>
        </table>

        <div class="ml-14 mt-1">
            <p>*Dias marcados como azul são dias que possuem partidas</p>
        </div>
    </div>




</body>
</html>