<?php 

    require_once "../../config.php";
    require_once '../../authenticate_prop.php';

    $ID_QUAD = $_GET['id'];

    $query = $pdo->prepare("SELECT * FROM PARTIDAS WHERE ID_QUAD = ?");
    $query->execute([$ID_QUAD]);
    $partidas = $query->fetchAll();

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
<body class=" font-outfit font-medium not-italic text-[#6b6b6b]">

    <div class="bg-[#F0F0F0] w-full min-h-screen overflow-x-hidden flex flex-col">

        <!-- Nav -->
        <div class="flex bg-gradient-to-b from-[#4ad658] to-green-500 h-20">
            <img src="/static/ifoot.png" alt="" class="h-20">
        </div>
    
    
    
        <a href="./inicio_prop.php">
            <button>
                <span class="material-symbols-outlined w-10 h-10 flex items-center justify-center rounded-xl bg-gray-300 hover:bg-gray-400 transition mt-4 ml-4">reply</span>
            </button>
        </a>


        <div class="mt-4 w-full">
            <h1 class="text-[28px]  w-auto h-auto flex items-center justify-start ml-4">
                Histórico de partidas da quadra
            </h1>
        </div>



        <?php if (empty($partidas)): ?>
            <p>Ainda não existem partidas cadastradas para essa quadra</p>
        <?php else: ?>

            <div class="px-4 mt-12 flex justify-center">

                <div class="overflow-hidden rounded-xl shadow-md w-full max-w-3xl bg-yellow-500">


                    <table class="w-full border-collapse bg-gray-200">

                        <thead>
                            <tr class="bg-gradient-to-b from-green-400 to-green-600 text-white">
                                <th class="px-4 py-2 text-center font-semibold border-r border-green-500">Data</th>
                                <th class="px-4 py-2 text-center font-semibold border-r border-green-500">Horário início</th>
                                <th class="px-4 py-2 text-center font-semibold border-r border-green-500">Horário fim</th>
                            </tr>
                        </thead>
                
                        <tbody>
                            <?php foreach ($partidas as $partida): ?>
                                <tr class="border-t border-gray-400 text-gray-700 text-sm">
                                    <td class="px-4 py-2 text-center border-r border-gray-400"><?= date('d/m/Y', strtotime($partida['DATA_PTD'])) ?></td>
                                    <td class="px-4 py-2 text-center border-r border-gray-400"><?= substr($partida['HORARIO_INICIO_PTD'], 0, 5) ?> h</td>
                                    <td class="px-4 py-2 text-center"><?= substr($partida['HORARIO_FIM_PTD'], 0, 5) ?> h</td>
                                </tr>
                            <?php endforeach; ?>
                
                        </tbody>
                    </table>

                </div>

            </div>


            <div class="flex justify-center mt-8">
                <a href="./exporta_historico_quadra.php?id=<?= $ID_QUAD ?>" target="_blank">
                    <button class="bg-green-500 text-white px-4 py-1 rounded">Exportar para PDF</button>
                </a>
            </div>
        <?php endif?>


    </div>


</body>
</html>