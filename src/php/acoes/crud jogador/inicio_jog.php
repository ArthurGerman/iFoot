<?php 

    require_once '../../config.php';
    require_once '../../authenticate_jog.php';

    $ID_JOG = $_SESSION['id_jog'];

    // Lista apenas as partidas que outros usuários criaram e que ele não entrou
    $query = $pdo->prepare("
        SELECT PARTIDAS.ID_PTD,
            PARTIDAS.DATA_PTD,
            PARTIDAS.HORARIO_INICIO_PTD,
            PARTIDAS.HORARIO_FIM_PTD,
            PARTIDAS.PRECO_TOTAL_PTD,
            QUADRAS.ENDERECO_QUAD,
            QUADRAS.CIDADE_QUAD,
            QUADRAS.PRECO_HORA_QUAD,
            UF.NOME_UF,
            MODALIDADES.NOME_MODAL

        FROM PARTIDAS
        INNER JOIN QUADRAS ON PARTIDAS.ID_QUAD = QUADRAS.ID_QUAD
        INNER JOIN UF ON QUADRAS.ID_UF = UF.ID_UF
        INNER JOIN MODALIDADES ON QUADRAS.ID_MODAL = MODALIDADES.ID_MODAL
        WHERE PARTIDAS.ID_CRIADOR != ?
        AND NOT EXISTS (
            SELECT 1
            FROM JOGADOR_PARTIDA
            WHERE JOGADOR_PARTIDA.ID_PTD = PARTIDAS.ID_PTD
            AND JOGADOR_PARTIDA.ID_JOG = ?
        )
    
    ");

    $query->execute([$ID_JOG, $ID_JOG]);
    $partidas = $query->fetchAll(PDO::FETCH_ASSOC);



    // --------------- FILTRO ---------------
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $sql = "
            SELECT PARTIDAS.ID_PTD,
                PARTIDAS.DATA_PTD,
                PARTIDAS.HORARIO_INICIO_PTD,
                PARTIDAS.HORARIO_FIM_PTD,
                PARTIDAS.PRECO_TOTAL_PTD,
                QUADRAS.ENDERECO_QUAD,
                QUADRAS.CIDADE_QUAD,
                QUADRAS.PRECO_HORA_QUAD,
                UF.NOME_UF,
                MODALIDADES.NOME_MODAL

            FROM PARTIDAS
            INNER JOIN QUADRAS ON PARTIDAS.ID_QUAD = QUADRAS.ID_QUAD
            INNER JOIN UF ON QUADRAS.ID_UF = UF.ID_UF
            INNER JOIN MODALIDADES ON QUADRAS.ID_MODAL = MODALIDADES.ID_MODAL
            WHERE PARTIDAS.ID_CRIADOR != ?
            AND NOT EXISTS (
                SELECT 1
                FROM JOGADOR_PARTIDA
                WHERE JOGADOR_PARTIDA.ID_PTD = PARTIDAS.ID_PTD
                AND JOGADOR_PARTIDA.ID_JOG = ?
            )
        ";

        $parametros = [$ID_JOG, $ID_JOG]; // Array que serve para juntar todos os dados que o usuário colocar no filtro. Pois o usuário pode não preencher completamente o filtro. Caso o usuário não preencha nenhum campo do filtro, o sistema retorna todas as partidas ativas que ele não participa e que não foram criadas por ele.

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

        if (!empty($_POST['DATA_PTD'])) {
            $sql .= " AND PARTIDAS.DATA_PTD = ?";
            $parametros[] = $_POST['DATA_PTD'];
        }

        if (!empty($_POST['HORARIO_INICIO_PTD'])) {
            $sql .= " AND PARTIDAS.HORARIO_INICIO_PTD >= ?";
            $parametros[] = $_POST['HORARIO_INICIO_PTD'];
        }

        if (!empty($_POST['HORARIO_FIM_PTD'])) {
            $sql .= " AND PARTIDAS.HORARIO_FIM_PTD <= ?";
            $parametros[] = $_POST['HORARIO_FIM_PTD'];
        }

        if (!empty($_POST['PRECO_TOTAL_PTD_MIN'])) {
            $PRECO_TOTAL_PTD_MIN = str_replace(',', '.', $_POST['PRECO_TOTAL_PTD_MIN']);
            $sql .= " AND PARTIDAS.PRECO_TOTAL_PTD >= ?";
            $parametros[] = (float) $PRECO_TOTAL_PTD_MIN;
        }

        if (!empty($_POST['PRECO_TOTAL_PTD_MAX']) && $_POST['PRECO_TOTAL_PTD_MAX'] !== '0,00') {
            $PRECO_TOTAL_PTD_MAX = str_replace(',', '.', $_POST['PRECO_TOTAL_PTD_MAX']);
            $sql .= " AND PARTIDAS.PRECO_TOTAL_PTD <= ?";
            $parametros[] = (float) $PRECO_TOTAL_PTD_MAX;
        }

        $query = $pdo->prepare($sql);
        $query->execute($parametros);
        $partidas = $query->fetchAll(PDO::FETCH_ASSOC);
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
        
        <div class="flex flex-row mt-4">

            <div class="w-2/3">
                <h1 class="text-[28px]  w-auto h-auto flex items-center justify-start ml-4">
                    Olá <?= $_SESSION['name_jog'] ?>! Abaixo estão as últimas partidas criadas por outros jogadores
                </h1>

            </div>

            <div class="w-1/3 flex justify-end">
                <button id="btnFiltro" class="border bg-gray-300 rounded-xl w-32 flex items-center justify-center p-2 mr-6 hover:bg-gray-400 transition ">
                    Filtro <span class="material-symbols-outlined">filter_alt</span>
                </button>

            </div>
        </div>


        <div id="overlayFiltro" class="fixed inset-0 bg-black/30 hidden z-40"></div> <!-- Div que escurece a tela quando abre a caixa de filtros-->

        <!-- CAIXA DO FILTRO -->
        <div id="caixaFiltro" class="fixed right-6 top-16 w-80 bg-gray-100 border border-gray-300 rounded-xl shadow-lg hidden z-50">

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

                    <div>
                        <p class="font-semibold mb-2">Data</p>

                        <input type="date" id="DATA_PTD" name="DATA_PTD" class="w-1/2 border rounded px-2 py-1 ml-2" value="<?= $_POST['DATA_PTD'] ?? '' ?>">
                    </div>

                    <div>
                        <p class="font-semibold mb-2">Horário</p>

                        <div>
                            <label for="HORARIO_INICIO_PTD" class="ml-2">Início:</label>
                            <input type="time" id="HORARIO_INICIO_PTD" name="HORARIO_INICIO_PTD" class="w-1/2 border rounded px-2 py-1 ml-2" value="<?= $_POST['HORARIO_INICIO_PTD'] ?? '' ?>">
                        </div>

                        <div>
                            <label for="HORARIO_FIM_PTD" class="ml-2">Fim:</label>
                            <input type="time" id="HORARIO_FIM_PTD" name="HORARIO_FIM_PTD" class="w-1/2 border rounded px-2 py-1 ml-[18px]" value="<?= $_POST['HORARIO_FIM_PTD'] ?? '' ?>">
                        </div>
                    </div>
                    
                    <div class="flex flex-col">
                        <p class="font-semibold mb-2">Preço (filtro pelo preço total da partida)</p>
                        
                        <div>
                            <label for="PRECO_TOTAL_PTD_MIN" class="ml-2">Min:</label>
                            <input type="text" id="PRECO_TOTAL_PTD_MIN" name="PRECO_TOTAL_PTD_MIN" placeholder="0,00" class="w-1/2 border rounded px-2 py-1 ml-2" value="<?= $_POST['PRECO_TOTAL_PTD_MIN'] ?? '0,00' ?>" oninput="formatarMoeda(this)">
                        </div>

                        <div>
                            <label for="PRECO_TOTAL_PTD_MAX" class="ml-2">Max:</label>
                            <input type="text" id="PRECO_TOTAL_PTD_MAX" name="PRECO_TOTAL_PTD_MAX" placeholder="0,00" class="w-1/2 border rounded px-2 py-1 ml-1" value="<?= $_POST['PRECO_TOTAL_PTD_MAX'] ?? '0,00' ?>" oninput="formatarMoeda(this)">
                        </div>
                    </div>
                    
                    <div class="flex justify-between pt-2">
                        <button type="button" id="limparFiltro" class="border px-4 py-1 rounded">Limpar</button>
                        <button type="submit" class="bg-green-500 text-white px-4 py-1 rounded">Aplicar</button>
                    </div>
                    
                </div>
            </form>
            
        </div>





        <!-- Mensagem de erro caso não existam partidas com os filtros que o jogador definiu-->
        <?php if (empty($partidas)): ?>
            <p class="ml-6 mt-2">Não existem partidas disponíveis.</p>

            <form action="" method="GET">
                <input type="submit" value="Excluir filtros" class="bg-gray-300 hover:bg-gray-400 trasnsition p-2 ml-6 mt-4 rounded-xl cursor-pointer">
            </form>

        <?php else: ?>

            <!-- CARDS QUE MOSTRAM AS PARTIDAS DISPONÍVEIS-->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 px-6 pb-20">
                <?php foreach ($partidas as $partida): ?>
                    
                    <div class="flex bg-white rounded-xl shadow-md overflow-hidden h-80">

                        <!-- Imagem / placeholder -->
                        <div class="w-1/2 bg-gray-300 flex items-center justify-center">
                            <span class="text-gray-500">Imagem da quadra</span>
                        </div>

                        <!-- Conteúdo -->
                        <div class="w-1/2 bg-gradient-to-b from-[#4ad658] to-green-500 p-4 text-white flex flex-col justify-between">
                            
                            <div class="text-sm space-y-1 gap-10">
                                <p><strong>Endereço:</strong> <?= $partida['ENDERECO_QUAD'] ?></p>
                                <p><strong>Cidade:</strong> <?= $partida['CIDADE_QUAD'] ?></p>
                                <p><strong>Estado:</strong> <?= $partida['NOME_UF'] ?></p>
                                <p><strong>Modalidade:</strong> <?= $partida['NOME_MODAL'] ?></p>




                                <!-- CÓDIGO PHP PARA EXIBIR A DATA E AS HORAS DA PARTIDA CORRETAMENTE-->
                                <?php
                                    $DATA_PTD = date('d/m/Y', strtotime($partida['DATA_PTD']));

                                    $HORARIO_INICIO_PTD = new DateTime($partida['HORARIO_INICIO_PTD']);
                                    $HORARIO_FIM_PTD = new DateTime($partida['HORARIO_FIM_PTD']);


                                    if ($HORARIO_FIM_PTD < $HORARIO_INICIO_PTD) {
                                        $HORARIO_FIM_PTD->modify('+1 day');
                                    }

                                    $intervalo = $HORARIO_INICIO_PTD->diff($HORARIO_FIM_PTD);

                                    $duracao= $intervalo->h . 'h';
                                    if ($intervalo->i > 0) {
                                        $duracao .= $intervalo->i;
                                    }

                                    $HORARIO_INICIO_PTD = $HORARIO_INICIO_PTD->format('H:i');
                                    $HORARIO_FIM_PTD = $HORARIO_FIM_PTD->format('H:i'); 
                                ?>


                                <p><strong>Duração:</strong> <?= $duracao ?>min</p>
                                <p><strong>Data:</strong> <?= $DATA_PTD ?></p>
                                <p><strong>Início:</strong> <?= $HORARIO_INICIO_PTD ?> hr</p>
                                <p><strong>Fim:</strong> <?= $HORARIO_FIM_PTD ?> hr</p>
                                <p><strong>Preço por hora: </strong> R$ <?= $partida['PRECO_HORA_QUAD'] ?>/h</p>
                                <p><strong>Preço total:</strong> R$ <?= $partida['PRECO_TOTAL_PTD'] ?></p> <!-- Preço final calculado com base nas horas-->
                            </div>

                            <a href="./entra_partida.php?id=<?= $partida['ID_PTD'] ?>" class="bg-white text-green-600 text-center py-2 rounded-md font-semibold hover:bg-gray-200 transition mt-2">
                                Entrar
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

            <a href="./lista_quadra.php" class="flex items-center gap-2 bg-white/20 hover:bg-white/30 p-2 rounded-lg">
                <span class="material-symbols-outlined">add_circle</span> Criar Partida
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
    <script src="/src/js/menu_lateral.js"></script>
</body>
</html>