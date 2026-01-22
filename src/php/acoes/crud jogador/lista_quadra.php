<?php

    require_once '../../config.php';
    require_once '../../authenticate_jog.php';

    $ID_JOG = $_SESSION['id_jog'];


    $query = $pdo->prepare("
        SELECT QUADRAS.ID_QUAD,
            QUADRAS.ENDERECO_QUAD,
            QUADRAS.CIDADE_QUAD,
            QUADRAS.PRECO_HORA_QUAD,
            UF.NOME_UF,
            MODALIDADES.NOME_MODAL

        FROM QUADRAS
        INNER JOIN UF ON QUADRAS.ID_UF = UF.ID_UF
        INNER JOIN MODALIDADES ON QUADRAS.ID_MODAL = MODALIDADES.ID_MODAL
        WHERE QUADRAS.STATUS_QUAD = 1
    
    ");

    $query->execute();
    $quadras = $query->fetchAll(PDO::FETCH_ASSOC);



    // --------------- FILTRO ---------------
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $sql = "
            SELECT QUADRAS.ID_QUAD,
                QUADRAS.ENDERECO_QUAD,
                QUADRAS.CIDADE_QUAD,
                QUADRAS.PRECO_HORA_QUAD,
                UF.NOME_UF,
                MODALIDADES.NOME_MODAL

            FROM QUADRAS
            INNER JOIN UF ON QUADRAS.ID_UF = UF.ID_UF
            INNER JOIN MODALIDADES ON QUADRAS.ID_MODAL = MODALIDADES.ID_MODAL
            WHERE QUADRAS.STATUS_QUAD = 1
        ";

        $parametros = []; // Array que serve para juntar todos os dados que o usuário colocar no filtro. Pois o usuário pode não preencher completamente o filtro. Caso o usuário não preencha nenhum campo do filtro, o sistema retorna todas as quadras ativas.

        if (!empty($_POST['UF'])) {
            $sql .= " AND UF.SIGLA_UF = ?";
            $parametros[] = $_POST['UF'];
        }

        if (!empty($_POST['CIDADE_QUAD'])) {
            $sql .= " AND QUADRAS.CIDADE_QUAD LIKE ?";
            $parametros[] = '%' . $_POST['CIDADE_QUAD'] . '%';
        }

        if (!empty($_POST['NOME_MODAL'])) {
            $sql .= " AND MODALIDADES.NOME_MODAL = ?";
            $parametros[] = $_POST['NOME_MODAL'];
        }

        if (!empty($_POST['PRECO_HORA_QUAD_MIN'])) {
            $PRECO_HORA_QUAD_MIN = str_replace(',', '.', $_POST['PRECO_HORA_QUAD_MIN']);
            $sql .= " AND QUADRAS.PRECO_HORA_QUAD >= ?";
            $parametros[] = (float) $PRECO_HORA_QUAD_MIN;
        }

        if (!empty($_POST['PRECO_HORA_QUAD_MAX']) && $_POST['PRECO_HORA_QUAD_MAX'] !== '0,00') {
            $PRECO_HORA_QUAD_MAX = str_replace(',', '.', $_POST['PRECO_HORA_QUAD_MAX']);
            $sql .= " AND QUADRAS.PRECO_HORA_QUAD <= ?";
            $parametros[] = (float) $PRECO_HORA_QUAD_MAX;
        }

        $query = $pdo->prepare($sql);
        $query->execute($parametros);
        $quadras = $query->fetchAll(PDO::FETCH_ASSOC);
    }

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="shortcut icon" href="/static/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="/src/styles/global.css">
    <title>Cadastrar Partida</title>
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
        

        <a>
            <button onclick="history.back()">
                <span class="material-symbols-outlined w-10 h-10 flex items-center justify-center rounded-xl bg-gray-300 hover:bg-gray-400 transition mt-4 ml-4">reply</span>
            </button>
        </a>
    
        
    
        <div class="flex flex-row mt-4">

            <div class="w-1/2">
                <h1 class="text-[28px]  w-64 h-auto flex items-center justify-start ml-4">
                    Quadras disponíveis
                </h1>

            </div>

            <div class="w-1/2 flex justify-end">
                <button id="btnFiltro" class="border bg-gray-300 rounded-xl w-32 flex items-center justify-center p-2 mr-6 hover:bg-gray-400 transition ">
                    Filtro <span class="material-symbols-outlined">filter_alt</span>
                </button>

            </div>
        </div>




        <div id="overlayFiltro" class="fixed inset-0 bg-black/30 hidden z-40"></div> <!-- Div que escurece a tela quando abre a caixa de filtros-->

        <!-- CAIXA DO FILTRO -->
        <div id="caixaFiltro" class="fixed right-6 top-28 w-80 bg-gray-100 border border-gray-300 rounded-xl shadow-lg hidden z-50">

            <!-- Cabeçalho -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-300">
                <h2 class="font-semibold text-gray-700">Filtro</h2>
                <button id="fecharFiltro" class="text-gray-500 hover:text-gray-700">✕</button>
            </div>

            
            
            <form action="" method="post">
                
                <!-- Conteúdo do filtro-->
                <div class="p-4 space-y-4 text-sm text-gray-700">
                    
                    <div>
                        <p class="font-semibold mb-2">Local</p>
                        
                        <label for="UF" class="block mb-1">Estado</label>
                        <select id="UF" name="UF" class="w-full border rounded px-2 py-1">
                            <option value="" disabled selected>Selecione</option>
                            <option value="AC" <?= (isset($_POST['UF']) && $_POST['UF'] === 'AC') ? 'selected' : '' ?>>Acre</option>
                            <option value="AL" <?= (isset($_POST['UF']) && $_POST['UF'] === 'AL') ? 'selected' : '' ?>>Alagoas</option>
                            <option value="AP" <?= (isset($_POST['UF']) && $_POST['UF'] === 'AP') ? 'selected' : '' ?>>Amapá</option>
                            <option value="AM" <?= (isset($_POST['UF']) && $_POST['UF'] === 'AM') ? 'selected' : '' ?>>Amazonas</option>
                            <option value="BA" <?= (isset($_POST['UF']) && $_POST['UF'] === 'BA') ? 'selected' : '' ?>>Bahia</option>
                            <option value="CE" <?= (isset($_POST['UF']) && $_POST['UF'] === 'CE') ? 'selected' : '' ?>>Ceará</option>
                            <option value="DF" <?= (isset($_POST['UF']) && $_POST['UF'] === 'DF') ? 'selected' : '' ?>>Distrito Federal</option>
                            <option value="ES" <?= (isset($_POST['UF']) && $_POST['UF'] === 'ES') ? 'selected' : '' ?>>Espírito Santo</option>
                            <option value="GO" <?= (isset($_POST['UF']) && $_POST['UF'] === 'GO') ? 'selected' : '' ?>>Goiás</option>
                            <option value="MA" <?= (isset($_POST['UF']) && $_POST['UF'] === 'MA') ? 'selected' : '' ?>>Maranhão</option>
                            <option value="MT" <?= (isset($_POST['UF']) && $_POST['UF'] === 'MT') ? 'selected' : '' ?>>Mato Grosso</option>
                            <option value="MS" <?= (isset($_POST['UF']) && $_POST['UF'] === 'MS') ? 'selected' : '' ?>>Mato Grosso do Sul</option>
                            <option value="MG" <?= (isset($_POST['UF']) && $_POST['UF'] === 'MG') ? 'selected' : '' ?>>Minas Gerais</option>
                            <option value="PA" <?= (isset($_POST['UF']) && $_POST['UF'] === 'PA') ? 'selected' : '' ?>>Pará</option>
                            <option value="PB" <?= (isset($_POST['UF']) && $_POST['UF'] === 'PB') ? 'selected' : '' ?>>Paraíba</option>
                            <option value="PR" <?= (isset($_POST['UF']) && $_POST['UF'] === 'PR') ? 'selected' : '' ?>>Paraná</option>
                            <option value="PE" <?= (isset($_POST['UF']) && $_POST['UF'] === 'PE') ? 'selected' : '' ?>>Pernambuco</option>
                            <option value="PI" <?= (isset($_POST['UF']) && $_POST['UF'] === 'PI') ? 'selected' : '' ?>>Piauí</option>
                            <option value="RJ" <?= (isset($_POST['UF']) && $_POST['UF'] === 'RJ') ? 'selected' : '' ?>>Rio de Janeiro</option>
                            <option value="RN" <?= (isset($_POST['UF']) && $_POST['UF'] === 'RN') ? 'selected' : '' ?>>Rio Grande do Norte</option>
                            <option value="RS" <?= (isset($_POST['UF']) && $_POST['UF'] === 'RS') ? 'selected' : '' ?>>Rio Grande do Sul</option>
                            <option value="RO" <?= (isset($_POST['UF']) && $_POST['UF'] === 'RO') ? 'selected' : '' ?>>Rondônia</option>
                            <option value="RR" <?= (isset($_POST['UF']) && $_POST['UF'] === 'RR') ? 'selected' : '' ?>>Roraima</option>
                            <option value="SC" <?= (isset($_POST['UF']) && $_POST['UF'] === 'SC') ? 'selected' : '' ?>>Santa Catarina</option>
                            <option value="SP" <?= (isset($_POST['UF']) && $_POST['UF'] === 'SP') ? 'selected' : '' ?>>São Paulo</option>
                            <option value="SE" <?= (isset($_POST['UF']) && $_POST['UF'] === 'SE') ? 'selected' : '' ?>>Sergipe</option>
                            <option value="TO" <?= (isset($_POST['UF']) && $_POST['UF'] === 'TO') ? 'selected' : '' ?>>Tocantins</option>
                        </select>
                        
                        <label for="CIDADE_QUAD" class="block mt-2 mb-1">Cidade</label>
                        <input type="text" id="CIDADE_QUAD" name="CIDADE_QUAD" placeholder="Digite o nome da cidade" class="w-full border rounded px-2 py-1" value="<?= $_POST['CIDADE_QUAD'] ?? '' ?>">
                    </div>
                    
                    <div>
                        <p class="font-semibold mb-2">Tipo de modalidade</p>
                        
                        <select name="NOME_MODAL" id="NOME_MODAL">
                            <option value="" disabled selected>Selecione</option>
                            <option value="CAMPO" <?= (isset($_POST['NOME_MODAL']) && $_POST['NOME_MODAL'] === 'CAMPO') ? 'selected' : '' ?>>Campo</option>
                            <option value="SOCIETY" <?= (isset($_POST['NOME_MODAL']) && $_POST['NOME_MODAL'] === 'SOCIETY') ? 'selected' : '' ?>>Society</option>
                            <option value="QUADRA" <?= (isset($_POST['NOME_MODAL']) && $_POST['NOME_MODAL'] === 'QUADRA') ? 'selected' : '' ?>>Quadra</option>
                        </select><br>
                    </div>
                    
                    <div class="flex flex-col">
                        <p class="font-semibold mb-2">Preço</p>
                        
                        <div>
                            <label for="PRECO_HORA_QUAD_MIN" class="ml-2">Min:</label>
                            <input type="text" id="PRECO_HORA_QUAD_MIN" name="PRECO_HORA_QUAD_MIN" placeholder="0,00" class="w-1/2 border rounded px-2 py-1 ml-2" value="<?= $_POST['PRECO_HORA_QUAD_MIN'] ?? '0,00' ?>" oninput="formatarMoeda(this)">
                        </div>

                        <div>
                            <label for="PRECO_HORA_QUAD_MAX" class="ml-2">Max:</label>
                            <input type="text" id="PRECO_HORA_QUAD_MAX" name="PRECO_HORA_QUAD_MAX" placeholder="0,00" class="w-1/2 border rounded px-2 py-1 ml-1" value="<?= $_POST['PRECO_HORA_QUAD_MAX'] ?? '0,00' ?>" oninput="formatarMoeda(this)">
                        </div>
                    </div>
                    
                    <div class="flex justify-between pt-2">
                        <button type="button" id="limparFiltro" class="border px-4 py-1 rounded">Limpar</button>
                        <button type="submit" class="bg-green-500 text-white px-4 py-1 rounded">Aplicar</button>
                    </div>
                    
                </div>
            </form>
            
        </div>
        
        
        
        
        
        
        <!-- Mensagem de erro caso não existam quadras com os filtros que o jogador definiu-->
        <?php if (empty($quadras)): ?>
            <p class="ml-6 mt-2">Não existem quadras disponíveis para os filtros que você aplicou. Por favor exclua os filtros e tente novamente.</p>

            <form action="" method="GET">
                <input type="submit" value="Excluir filtros" class="bg-gray-300 hover:bg-gray-400 trasnsition p-2 ml-6 mt-4 rounded-xl cursor-pointer">
            </form>

        <?php else: ?>

            <!-- CARDS QUE MOSTRAM AS QUADRAS DISPONÍVEIS-->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 px-6">
                <?php foreach ($quadras as $quadra): ?>
                    
                    <div class="flex bg-white rounded-xl shadow-md overflow-hidden h-48">

                        <!-- Imagem / placeholder -->
                        <div class="w-1/2 bg-gray-300 flex items-center justify-center">
                            <span class="text-gray-500">Imagem</span>
                        </div>

                        <!-- Conteúdo -->
                        <div class="w-1/2 bg-gradient-to-b from-[#4ad658] to-green-500 p-4 text-white flex flex-col justify-between">
                            
                            <div class="text-sm space-y-1 gap-10">
                                <p><strong>Endereço:</strong> <?= $quadra['ENDERECO_QUAD'] ?></p>
                                <p><strong>Cidade:</strong> <?= $quadra['CIDADE_QUAD'] ?></p>
                                <p><strong>Estado:</strong> <?= $quadra['NOME_UF'] ?></p>
                                <p><strong>Modalidade:</strong> <?= $quadra['NOME_MODAL'] ?></p>
                                <p><strong>Preço:</strong> R$ <?= $quadra['PRECO_HORA_QUAD'] ?>/h</p>
                            </div>

                            <a href="./cadastro_partida.php?id=<?= $quadra['ID_QUAD'] ?>" class="bg-white text-green-600 text-center py-2 rounded-md font-semibold hover:bg-gray-200 transition mt-2">
                                Reservar
                            </a>

                        </div>
                    </div>

                <?php endforeach; ?>
            </div>

        <?php endif; ?>


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
            <a href="./update_jogador.php" class="flex items-center gap-2 bg-white/20 hover:bg-white/30 p-2 rounded-lg">
                <span class="material-symbols-outlined">person</span> Perfil
            </a>
            
            <a href="./lista_partida.php" class="flex items-center gap-2 bg-white/20 hover:bg-white/30 p-2 rounded-lg">
                <span class="material-symbols-outlined">sports_soccer</span> Partidas criadas por mim
            </a>
            
            <a href="" class="flex items-center gap-2 bg-white/20 hover:bg-white/30 p-2 rounded-lg">
                <span class="material-symbols-outlined">event</span> Partidas Marcadas
            </a>

            <a href="" class="flex items-center gap-2 bg-white/20 hover:bg-white/30 p-2 rounded-lg">
                <span class="material-symbols-outlined">help</span> Como usar
            </a>

            <!--<a href="" class="flex items-center gap-2 bg-white/20 hover:bg-white/30 p-2 rounded-lg">
                <span class="material-symbols-outlined">settings</span> Configurações
            </a>-->

            <a href="../../login/logout.php" class="flex items-center gap-2 bg-red-500 hover:bg-red-600 p-2 rounded-lg mt-4">
                <span class="material-symbols-outlined">logout</span> Sair da Conta
            </a>
        </nav>
    </aside>




    
    <script src="/src/js/formata_preco_quadra.js"></script>
    <script src="/src/js/filtro.js"></script>
    <script src="/src/js/menu_lateral_jog.js"></script>
</body>
</html>