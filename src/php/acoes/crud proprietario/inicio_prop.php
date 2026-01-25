<?php 

    require_once "../../config.php";
    require_once '../../authenticate_prop.php';

    $ID_PROP = $_SESSION['id_prop'];

    $query = $pdo->prepare("
        SELECT QUADRAS.ID_QUAD, QUADRAS.PRECO_HORA_QUAD, QUADRAS.ENDERECO_QUAD, QUADRAS.CIDADE_QUAD, QUADRAS.STATUS_QUAD, UF.NOME_UF, MODALIDADES.NOME_MODAL
        FROM QUADRAS
        INNER JOIN UF ON QUADRAS.ID_UF = UF.ID_UF
        INNER JOIN MODALIDADES ON QUADRAS.ID_MODAL = MODALIDADES.ID_MODAL
        WHERE QUADRAS.ID_PROP = ?
        ORDER BY QUADRAS.ID_QUAD ASC
    ");
    $query->execute([$ID_PROP]);
    $quadras = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="shortcut icon" href="/static/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="/src/styles/global.css">
    <title>Início</title>
</head>
<body class=" font-outfit font-medium not-italic text-[#6b6b6b]">

    <div class="bg-[#F0F0F0] w-full h-full min-h-screen overflow-x-hidden flex flex-col">


        <!-- Nav -->
        <div class="flex bg-gradient-to-b from-[#4ad658] to-green-500 h-20">
            <div class="w-1/2">
                <img src="/static/ifoot.png" alt="" class="h-20">
            </div>

            <div class="flex w-1/2 h-20 items-center justify-end">
                <span id="btnMenu" class="material-symbols-outlined text-white text-[36px] mr-10 cursor-pointer">
                    menu
                </span>
            </div>
        </div>



        <div id="menuOverlay" class="fixed inset-0 bg-black/40 hidden z-40"></div>




        <div class="mt-4 w-full">
            <h1 class="text-[28px]  w-auto h-auto flex items-center justify-start ml-4">
                Suas quadras
            </h1>
        </div>

        
        <?php if (empty($quadras)): ?>
            <p>Não existem quadras cadastradas</p>
        <?php else: ?>

            <!-- CARDS QUE MOSTRAM AS PARTIDAS DISPONÍVEIS-->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 px-6 pb-20">
                <?php foreach ($quadras as $quadra): ?>
                    
                    <div class="flex bg-white rounded-xl shadow-md overflow-hidden h-60">

                        <!-- Imagem / placeholder -->
                        <div class="w-1/2 bg-gray-300 flex items-center justify-center">
                            <span class="text-gray-500">Imagem da quadra</span>
                        </div>

                        <!-- Conteúdo -->
                        <div class="w-1/2 bg-gradient-to-b from-[#4ad658] to-green-500 p-4 text-white flex flex-col justify-between">
                            
                            <div class="text-sm space-y-1 gap-10">
                                <p><strong>Endereço:</strong> <?= $quadra['ENDERECO_QUAD'] ?></p>
                                <p><strong>Cidade:</strong> <?= $quadra['CIDADE_QUAD'] ?></p>
                                <p><strong>Estado:</strong> <?= $quadra['NOME_UF'] ?></p>
                                <p><strong>Modalidade:</strong> <?= $quadra['NOME_MODAL'] ?></p>
                                <p><strong>Preço por hora:</strong> R$ <?= $quadra['PRECO_HORA_QUAD'] ?>/h</p>
                                <p><strong>Situação da quadra:</strong> <?= $quadra['STATUS_QUAD'] == 1 ? 'Ativa' : 'Inativa' ?></p>
                            </div>

                            <div class="flex flex-row gap-2">
                                <a href="./edita_quadra.php?id=<?= $quadra['ID_QUAD'] ?>" class="bg-white text-green-600 text-center py-2 rounded-md font-semibold hover:bg-gray-200 transition mt-2 w-1/3">
                                    Editar
                                </a>

                                <a href="./exclui_quadra.php?id=<?= $quadra['ID_QUAD'] ?>" class="bg-white text-green-600 text-center py-2 rounded-md font-semibold hover:bg-gray-200 transition mt-2 w-1/3">
                                    Excluir
                                </a>

                                <a href="./historico_quadra.php?id=<?= $quadra['ID_QUAD'] ?>" class="bg-white text-green-600 text-center py-2 rounded-md font-semibold hover:bg-gray-200 transition mt-2 w-1/3">
                                    Ver histórico
                                </a>
                            </div>


                        </div>
                    </div>

                <?php endforeach?>
            </div>
        
        <?php endif?>
    </div>
    






    



    <!-- Menu lateral flutuante-->

    <aside id="menuLateral" class="fixed top-0 right-0 h-full w-80 bg-green-500 text-white transform translate-x-full transition-transform duration-300 z-50 flex flex-col">

        <!-- Cabeçalho -->
        <div class="flex items-center justify-between p-4">
            <h2 class="text-xl font-semibold">Menu</h2>
            <span id="fecharMenu" class="material-symbols-outlined cursor-pointer">
                close
            </span>
        </div>

        <!-- Avatar -->
        <div class="flex justify-center my-6">
            <div class="w-24 h-24 rounded-full bg-white/30 flex items-center justify-center">
                <span class="material-symbols-outlined text-[64px]">
                    person
                </span>
            </div>
        </div>

        <!-- Opções -->
        <nav class="flex flex-col gap-3 px-4 text-sm">

            <a href="./update_prop.php" class="flex items-center gap-2 bg-white/20 hover:bg-white/30 p-2 rounded-lg">
                <span class="material-symbols-outlined">person</span> Perfil
            </a>

            <a href="./cadastra_quadra.php" class="flex items-center gap-2 bg-white/20 hover:bg-white/30 p-2 rounded-lg">
                <span class="material-symbols-outlined">add_circle</span> Cadastrar nova quadra
            </a>


            <a href="./calendario_partidas.php" class="flex items-center gap-2 bg-white/20 hover:bg-white/30 p-2 rounded-lg">
                <span class="material-symbols-outlined">event</span> Ver agenda de partidas
            </a>

            <a href="" class="flex items-center gap-2 bg-white/20 hover:bg-white/30 p-2 rounded-lg">
                <span class="material-symbols-outlined">help</span> Como usar
            </a>

            <a href="../../login/logout.php" class="flex items-center gap-2 bg-red-500 hover:bg-red-600 p-2 rounded-lg mt-4">
                <span class="material-symbols-outlined">logout</span> Sair da Conta
            </a>
        </nav>
    </aside>


    <script src="/src/js/menu_lateral_jog.js"></script>

</body>
</html>