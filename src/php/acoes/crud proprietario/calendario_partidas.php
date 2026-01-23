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
    <link rel="shortcut icon" href="/static/favicon.png" type="image/x-icon">
    <title>Calendário</title>
    <style>
        table {
            border-collapse: collapse;
            width: 60%;
            text-align: center;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px;
            height: 50px;
        }
        th {
            background-color: #f2f2f2;
        }
        .hoje {
            background-color: yellow;
            font-weight: bold;
        } 
        .domingo {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <button>
        <a href="./inicio_prop.php">Voltar</a>
    </button><br>

    <h2><?php echo $nomes_meses[$mes] . " de " . $ano; ?></h2>

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

        <input type="submit" value="Ver">
    </form>
    
    <br><br>
    

    <table>
        <tr>
            <th>Dom</th><th>Seg</th><th>Ter</th>
            <th>Qua</th><th>Qui</th><th>Sex</th><th>Sáb</th>
        </tr>
        <tr>
            <?php
                // Espaços antes do primeiro dia
                for ($i = 0; $i < $primeiro_dia_semana; $i++) {
                    echo "<td></td>";
                }

                // Preencher os dias
                $dia_semana = $primeiro_dia_semana;
                
                for ($dia = 1; $dia <= $dias_no_mes; $dia++) {

                    $data_completa = sprintf('%04d-%02d-%02d', $ano, $mes, $dia);
                    $tem_partida = in_array($data_completa, $datas_partidas);

                    $classes = []; // Array com os nomes das classes CSS

                    if ($data_completa === $data_hoje) {
                        $classes[] = 'hoje';
                    }

                    if ($dia_semana == 0) {
                        $classes[] = 'domingo';
                    }

                    $class_attr = !empty($classes) ? " class='" . implode(' ', $classes) . "'" : ""; // Linha que faz a concatenação das classes hoje/domingo para imprimir junto com o <td>. Isso serve para deixar os dias de domingo marcados de vermelho
                    echo "<td$class_attr>"; // Impressão dos dias





                    if ($tem_partida) {
                        echo "<a href='./dia_partida.php?data=$data_completa'>$dia</a>";
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
                        echo "<td></td>";
                    }
                }
            ?>
        </tr>
    </table>
</body>
</html>