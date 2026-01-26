<?php

require_once "../../config.php";
require_once '../../authenticate_prop.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ENDERECO_QUAD = $_POST['ENDERECO_QUAD'];
    $CIDADE_QUAD = $_POST['CIDADE_QUAD'];
    $SIGLA_UF = $_POST['UF'];
    $NOME_MODAL = $_POST['NOME_MODAL'];
    $PRECO_HORA_QUAD = str_replace(['.', ','], ['', '.'], $_POST['PRECO_HORA_QUAD']);

    $ID_PROP = $_SESSION['id_prop'];

    //Query para descobrir o id correspondente à UF

    $query_1 = $pdo->prepare("SELECT ID_UF FROM UF WHERE SIGLA_UF = ?");
    $query_1->execute([$SIGLA_UF]);
    $result_1 = $query_1->fetch(PDO::FETCH_ASSOC);
    $ID_UF = $result_1['ID_UF'];

    // Query para descobrir o id correspondente à modalidade

    $query_2 = $pdo->prepare("SELECT ID_MODAL FROM MODALIDADES WHERE NOME_MODAL = ?");
    $query_2->execute([$NOME_MODAL]);
    $result_2 = $query_2->fetch(PDO::FETCH_ASSOC);
    $ID_MODAL = $result_2['ID_MODAL'];


    //Query para fazer a inserção de dados no banco

    $query_3 = $pdo->prepare("INSERT INTO QUADRAS (ENDERECO_QUAD, CIDADE_QUAD, ID_UF, ID_MODAL, PRECO_HORA_QUAD, ID_PROP) VALUES (?,?,?,?,?,?)");
    $query_3->execute([$ENDERECO_QUAD, $CIDADE_QUAD, $ID_UF, $ID_MODAL, $PRECO_HORA_QUAD, $ID_PROP]);


    header('Location: ./inicio_prop.php');
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
    <title>Cadastramento de quadra</title>
</head>

<body class="font-outfit font-medium not-italic text-[#6b6b6b]">

    <div class="bg-[#F0F0F0] w-full h-full min-h-screen overflow-x-hidden flex flex-col">

        <!-- Nav -->
        <div class="flex bg-gradient-to-b from-[#4ad658] to-green-500 h-20">
            <div class="w-1/2">
                <img src="/static/ifoot.png" alt="" class="h-20">
            </div>
        </div>

        <!-- Form -->
        <div class="flex h-full w-full justify-between p-72 pt-28 pb-0">

            <div class="flex flex-col justify-center gap-6">
                <h2 class="text-[26px]">
                    Cadastramento de quadra
                </h2>

                <form action="" method="post" id="cadastro" class="flex flex-col items-center gap-3">

                    <div class="-ml-4">
                        <label for="ENDERECO_QUAD">Endereço: </label>
                        <input type="text" name="ENDERECO_QUAD" id="ENDERECO_QUAD" class="border rounded-md border-gray-400 w-44 pl-1">
                    </div>

                    <div>
                        <label for="CIDADE_QUAD">Cidade: </label>
                        <input type="text" name="CIDADE_QUAD" id="CIDADE_QUAD" class="border rounded-md border-gray-400 w-44 pl-1">
                    </div>

                    <div class="-mr-6">
                        <label for="UF">UF: </label>
                        <select name="UF" id="UF" class="border rounded-md border-gray-400">
                            <option value="">Selecione</option>
                            <option value="AC">Acre</option>
                            <option value="AL">Alagoas</option>
                            <option value="AP">Amapá</option>
                            <option value="AM">Amazonas</option>
                            <option value="BA">Bahia</option>
                            <option value="CE">Ceará</option>
                            <option value="DF">Distrito Federal</option>
                            <option value="ES">Espírito Santo</option>
                            <option value="GO">Goiás</option>
                            <option value="MA">Maranhão</option>
                            <option value="MT">Mato Grosso</option>
                            <option value="MS">Mato Grosso do Sul</option>
                            <option value="MG">Minas Gerais</option>
                            <option value="PA">Pará</option>
                            <option value="PB">Paraíba</option>
                            <option value="PR">Paraná</option>
                            <option value="PE">Pernambuco</option>
                            <option value="PI">Piauí</option>
                            <option value="RJ">Rio de Janeiro</option>
                            <option value="RN">Rio Grande do Norte</option>
                            <option value="RS">Rio Grande do Sul</option>
                            <option value="RO">Rondônia</option>
                            <option value="RR">Roraima</option>
                            <option value="SC">Santa Catarina</option>
                            <option value="SP">São Paulo</option>
                            <option value="SE">Sergipe</option>
                            <option value="TO">Tocantins</option>
                        </select>
                    </div>

                    <div class="-ml-32 -mr-2">
                        <label for="NOME_MODAL">Modalidade: </label>
                        <select name="NOME_MODAL" id="NOME_MODAL" class="border rounded-md border-gray-400">
                            <option value="">Selecione</option>
                            <option value="CAMPO">Campo</option>
                            <option value="SOCIETY">Society</option>
                            <option value="QUADRA">Quadra</option>
                        </select>
                    </div>

                    <div class="-ml-36 mr-1">
                        <label for="PRECO_HORA_QUAD">Preço da Hora: </label>
                        <input type="text" name="PRECO_HORA_QUAD" id="PRECO_HORA_QUAD" value="0,00" oninput="formatarMoeda(this)"
                            class="border rounded-md border-gray-400 w-20 pl-1">
                    </div>
                </form>

                <div class="flex justify-between p-3 pt-0">
                    <a href="./inicio_prop.php" class="border rounded-lg w-24 h-9 flex text-center justify-center 
                     bg-white text-green-600"><button>Voltar</button></a>

                    <button type="submit" form="cadastro" class="border rounded-lg w-24 h-9 
                     text-white bg-green-600">Enviar</button>
                </div>

                <script src="../../../js/formata_preco_quadra.js"></script>
                <script src="../../../js/tratamento-erros_cad-quadra.js"></script>
            </div>

            <div class="flex flex-col justify-center items-center gap-5">
                <div class="w-52 h-48 bg-slate-300 border rounded-lg p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-full"
                        viewBox="0 0 448 512"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path fill="#ffffff" d="M64 80c-8.8 0-16 7.2-16 16l0 320c0 8.8 7.2 16 16 16l320 0c8.8 0 16-7.2 16-16l0-320c0-8.8-7.2-16-16-16L64 80zM0 96C0 60.7 28.7 32 64 32l320 0c35.3 0 64 28.7 64 64l0 320c0 35.3-28.7 64-64 64L64 480c-35.3 0-64-28.7-64-64L0 96zm128 32a32 32 0 1 1 0 64 32 32 0 1 1 0-64zm136 72c8.5 0 16.4 4.5 20.7 11.8l80 136c4.4 7.4 4.4 16.6 .1 24.1S352.6 384 344 384l-240 0c-8.9 0-17.2-5-21.3-12.9s-3.5-17.5 1.6-24.8l56-80c4.5-6.4 11.8-10.2 19.7-10.2s15.2 3.8 19.7 10.2l17.2 24.6 46.5-79c4.3-7.3 12.2-11.8 20.7-11.8z" />
                    </svg>
                </div>
                <button class="border rounded-lg w-44 h-10
                 bg-white text-green-600">Adicionar imagem</button>
            </div>
        </div>
    </div>
</body>

</html>