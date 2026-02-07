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



    // Verificar se foi enviada uma imagem
    if (!empty($_FILES['imagem']['name'])) {
        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $novoNome = uniqid() . '.' . $extensao;
        $caminho = __DIR__ . '../../../../../storage/' . $novoNome;

        // Mover o arquivo para a pasta storage
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho)) {
            // Inserir o caminho da imagem na tabela imagens
            $query = $pdo->prepare("INSERT INTO IMAGEM (path) VALUES (?)");
            $query->execute([$novoNome]);
            $ID_IMAGEM = $pdo->lastInsertId();
        }
    } else {
        $ID_IMAGEM = null;
    }





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

    $query_3 = $pdo->prepare("INSERT INTO QUADRAS (ENDERECO_QUAD, CIDADE_QUAD, ID_UF, ID_MODAL, PRECO_HORA_QUAD, ID_PROP, ID_IMAGEM) VALUES (?,?,?,?,?,?,?)");
    $query_3->execute([$ENDERECO_QUAD, $CIDADE_QUAD, $ID_UF, $ID_MODAL, $PRECO_HORA_QUAD, $ID_PROP, $ID_IMAGEM]);


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
        <div class="flex bg-gradient-to-b from-[#4ad658] to-green-500 h-20 items-center">
            <img src="/static/ifoot.png" alt="" class="h-20">

            <a href="./calendario_partidas.php" class="text-white ml-16 hover:text-gray-200">Ver agenda de partidas</a>
        </div>


        <span class="material-symbols-outlined w-10 h-10 flex items-center justify-center rounded-xl bg-gray-300 hover:bg-gray-400 transition mt-4 ml-4">
            <a href="./inicio_prop.php" class="w-10 h-10 flex items-center justify-center rounded-xl">
                reply
            </a>
        </span>



        <div class="mt-6 pl-6 w-full">
            <h1 class="text-[28px]  w-auto h-auto flex items-center justify-start ml-4">
                Cadastramento de quadra
            </h1>
        </div>


        <div class="flex mt-6 w-screen">

            <div class="w-1/2 flex">
                
                <form action="" method="post" id="form_cadastro_quadra" class="" enctype="multipart/form-data">

                    <div class="space-y-4 w-96 ml-28">

                        <div class="flex flex-row w-full gap-2">
                            <div class="w-[100px] flex items-center">
                                <label for="ENDERECO_QUAD">Endereço: </label>

                            </div>

                            <div class="w-auto">
                                <input type="text" name="ENDERECO_QUAD" id="ENDERECO_QUAD" class="w-full h-9 px-3 rounded-md border border-gray-300 outline-none">
                            </div>
                        </div>
            
                        <div class="flex flex-row w-full gap-2">
                            <div class="w-[100px] flex items-center">
                                <label for="CIDADE_QUAD">Cidade: </label>
                            </div>

                            <div class="w-auto">
                                <input type="text" name="CIDADE_QUAD" id="CIDADE_QUAD" class="w-full h-9 px-3 rounded-md border border-gray-300 outline-none">
                            </div>
                        </div>
            
                        <div class="flex flex-row w-full gap-2">
                            <div class="w-[100px] flex items-center">
                                <label for="UF">UF: </label>
                            </div>

                            <div class="w-auto">
                                <select name="UF" id="UF" class="w-full h-9 px-3 rounded-md border border-gray-300 outline-none">
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
                        </div>
            
                        <div class="flex flex-row w-full gap-2">
                            <div class="w-[100px] flex items-center">
                                <label for="NOME_MODAL">Modalidade: </label>
                            </div>

                            <div class="w-auto">
                                <select name="NOME_MODAL" id="NOME_MODAL" class="w-28 h-9 px-3 rounded-md border border-gray-300 outline-none">
                                    <option value="">Selecione</option>
                                    <option value="CAMPO">Campo</option>
                                    <option value="SOCIETY">Society</option>
                                    <option value="QUADRA">Quadra</option>
                                </select>
                            </div>
                        </div>
            
                        <div class="flex flex-row w-full gap-2">
                            <div class="w-[100px] flex items-center">
                                <label for="PRECO_HORA_QUAD">Preço (R$/h): </label>
                            </div>

                            <div class="w-auto">
                                <input type="text" name="PRECO_HORA_QUAD" id="PRECO_HORA_QUAD" value="0,00" oninput="formatarMoeda(this)"
                                    class="w-24 h-9 px-3 rounded-md border border-gray-300 outline-none">
                            </div>

                        </div>

                       <div class="flex justify-end mr-10">
                            <input type="submit" value="Salvar" class="bg-green-500 text-white px-4 py-1 rounded cursor-pointer">
                        </div>

                    </div>
                </form>


            </div>




            
            <!-- DIV COM A FOTO DA QUADRA -->
            <div class="w-1/2 flex flex-col items-center justify-center">
                <div class="w-72 h-72 rounded-full bg-gray-300 flex items-center justify-center overflow-hidden relative">

                    <span id="icone-person" class="material-symbols-outlined text-[80px] text-white absolute">
                        stadium
                    </span>

                    <img id="preview-imagem" class="hidden w-full h-full object-cover">
                </div>
                

                <label for="imagem" class="mt-4 bg-white hover:bg-gray-300 text-green-500 px-5 py-2 rounded-full cursor-pointer transition font-semibold">
                    Adicionar imagem
                </label>
                <input 
                    type="file" 
                    id="imagem" 
                    form="form_cadastro_quadra" 
                    name="imagem" accept="image/*" 
                    class="hidden"
                >
            </div>

        </div>

    </div>
        
    
    <script src="/src/js/formata_preco_quadra.js"></script>
    <script src="/src/js/tratamento-erros_cad-quadra.js"></script>
    <script src="/src/js/troca_icone_imagem.js"></script>
</body>
</html>