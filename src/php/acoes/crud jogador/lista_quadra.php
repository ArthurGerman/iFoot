<?php

require_once '../../config.php';
require_once '../../authenticate_jog.php';

$mensagem = $_SESSION['mensagem_cad_partida'] ?? null;
$quadra_erro = $_SESSION['quadra_erro'] ?? null;
unset($_SESSION['mensagem_cad_partida'], $_SESSION['quadra_erro']);

$ID_JOG = $_SESSION['id_jog'];


$query = $pdo->prepare("
        SELECT QUADRAS.ID_QUAD,
            QUADRAS.ENDERECO_QUAD,
            QUADRAS.CIDADE_QUAD,
            QUADRAS.PRECO_HORA_QUAD,
            UF.NOME_UF,
            IMAGEM.PATH,
            MODALIDADES.NOME_MODAL

        FROM QUADRAS
        INNER JOIN UF ON QUADRAS.ID_UF = UF.ID_UF
        INNER JOIN IMAGEM ON QUADRAS.ID_IMAGEM = IMAGEM.ID_IMAGEM
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
                IMAGEM.PATH,
                MODALIDADES.NOME_MODAL

            FROM QUADRAS
            INNER JOIN UF ON QUADRAS.ID_UF = UF.ID_UF
            INNER JOIN IMAGEM ON QUADRAS.ID_IMAGEM = IMAGEM.ID_IMAGEM
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
        <div class="flex bg-gradient-to-b from-[#4ad658] to-green-500 h-20 items-center">
            <img src="/static/ifoot.png" alt="" class="h-20">

            <a href="./lista_partida.php" class="text-white ml-16 hover:text-gray-200">Partidas criadas por mim</a>
            <a href="./partidas_marcadas.php" class="text-white ml-6 hover:text-gray-200">Partidas marcadas</a>
        </div>




        <span class="material-symbols-outlined w-10 h-10 flex items-center justify-center rounded-xl bg-gray-300 hover:bg-gray-400 transition mt-4 ml-4">
            <a href="./inicio_jog.php" class="w-10 h-10 flex items-center justify-center rounded-xl">
                reply
            </a>
        </span>



        <div class="flex flex-row mt-6 pl-6">

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
            <p class="ml-12 mt-2">Não existem quadras disponíveis para os filtros que você aplicou.</p>

            <form action="" method="GET">
                <input type="submit" value="Excluir filtros" class="bg-gray-300 hover:bg-gray-400 trasnsition p-2 ml-12 mt-4 rounded-xl cursor-pointer">
            </form>

        <?php else: ?>

            <!-- CARDS QUE MOSTRAM AS QUADRAS DISPONÍVEIS-->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 px-12 pb-20">
                <?php foreach ($quadras as $quadra): ?>

                    <div class="flex bg-white rounded-xl shadow-md overflow-hidden h-52 w-[700px]">

                        <!-- Imagem / placeholder -->
                        <div class="w-[400px] bg-gray-300 flex items-center justify-center">
                            <img src="../../../../../storage/<?= $quadra['PATH'] ?>" alt="" class="w-full h-full object-cover">
                        </div>

                        <!-- Conteúdo -->
                        <div class="w-[300px] bg-gradient-to-b from-[#4ad658] to-green-500 p-4 text-white flex flex-col justify-between">

                            <div class="text-sm space-y-1 gap-10">
                                <p class="text-[22px] mb-2"><strong>Modalidade:</strong> <?= $quadra['NOME_MODAL'] ?></p>
                                <p><strong>Estado:</strong> <?= $quadra['NOME_UF'] ?></p>
                                <p><strong>Cidade:</strong> <?= $quadra['CIDADE_QUAD'] ?></p>
                                <p><strong>Endereço:</strong> <?= $quadra['ENDERECO_QUAD'] ?></p>
                                <p><strong>Preço:</strong> R$ <?= $quadra['PRECO_HORA_QUAD'] ?>/h</p>
                            </div>

                            <button onclick="showModal('myReserv-<?= $quadra['ID_QUAD'] ?>')" class="bg-white text-green-600 text-center py-2 rounded-md font-semibold hover:bg-gray-200 transition mt-2">
                                Reservar
                            </button>

                        </div>
                    </div>

                    <!-- modal editar reserva -->
                    <dialog id="myReserv-<?= $quadra['ID_QUAD'] ?>" class=" font-outfit font-medium not-italic text-white rounded-2xl">

                        <div class="bg-gradient-to-b from-[#4ad658] to-green-500 h-fit  p-10">
                            <h1 class="text-[28px]">
                                Cadastro da partida
                            </h1>


                            <form action="./cadastro_partida.php?id=<?= $quadra['ID_QUAD'] ?>" method="post" class="form_partida">

                                <div class="flex flex-col mt-2">
                                    <label>Data: </label>
                                    <input type="date" name="DATA_PTD" class="text-[#6b6b6b] h-9 px-3 rounded-md border border-gray-300 outline-none mb-3" required>

                                    <label>Início da Partida: </label>
                                    <input type="time" name="HORARIO_INICIO_PTD" class="text-[#6b6b6b] h-9 px-3 rounded-md border border-gray-300 outline-none mb-3" required>

                                    <label>Fim da Partida: </label>
                                    <input type="time" name="HORARIO_FIM_PTD" class="text-[#6b6b6b] h-9 px-3 rounded-md border border-gray-300 outline-none mb-3" required>
                                </div>


                                <div class="flex flex-row gap-4 mt-4">
                                    <button onclick="closeModal('myReserv-<?= $quadra['ID_QUAD'] ?>')" type="button" class="flex w-1/2 h-10 bg-white text-green-600 hover:bg-gray-200 justify-center items-center rounded-xl">Voltar</button>
                                    <button type="submit" class="flex cursor-pointer w-1/2 h-10 bg-white text-green-600 hover:bg-gray-200 justify-center items-center rounded-xl">Cadastrar</button>
                                </div>
                            </form>

                            <?php if (!empty($mensagem) && $quadra_erro == $quadra['ID_QUAD']) : ?>
                                <p id="msg" class="text-red-600 mt-4"><?= $mensagem ?></p>
                            <?php endif ?>
                            
                        </div>

                    </dialog>

                <?php endforeach; ?>
            </div>

        <?php endif; ?>

        <script src="/src/js/tratamento-erros_partida.js"></script>
        <script src="/src/js/some_mensagem.js"></script>
        <script src="/src/js/reserva_modal.js"></script>

    </div>

    <script src="/src/js/formata_preco_quadra.js"></script>
    <script src="/src/js/filtro.js"></script>



    <?php if (!empty($quadra_erro)) : ?>
        <script>
            window.addEventListener("load", () => {
                const modal = document.getElementById("myReserv-<?= $quadra_erro ?>");
                if(modal) modal.showModal();
            });
        </script>
    <?php endif; ?>
</body>

</html>