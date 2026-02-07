<?php

require_once "../../config.php";
require_once '../../authenticate_prop.php';


$ID_QUAD = $_GET['id']; //Id da quadra a ser editada

$query = $pdo->prepare("
    SELECT * FROM QUADRAS
    
    INNER JOIN IMAGEM ON QUADRAS.ID_IMAGEM = IMAGEM.ID_IMAGEM
    WHERE ID_QUAD = ?
");
$query->execute([$ID_QUAD]);
$quadra = $query->fetch(PDO::FETCH_ASSOC);

// Buscar os estados (UF)
$query2 = $pdo->prepare("SELECT * FROM UF");
$query2->execute();
$ufs = $query2->fetchAll(PDO::FETCH_ASSOC);

$erro = ''; // Variável de erro que exibe a mensagem caso o proprietário tente inativar a quadra que tem partidas.

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ENDERECO_QUAD = $_POST['ENDERECO_QUAD'];
    $CIDADE_QUAD = $_POST['CIDADE_QUAD'];
    $SIGLA_UF = $_POST['UF'];
    $NOME_MODAL = $_POST['NOME_MODAL'];
    $PRECO_HORA_QUAD = str_replace(['.', ','], ['', '.'], $_POST['PRECO_HORA_QUAD']);
    $STATUS_QUAD = $_POST['STATUS_QUAD'];

    if ($STATUS_QUAD == 0) {
        $verifica = $pdo->prepare("SELECT 1 FROM PARTIDAS WHERE ID_QUAD = ? LIMIT 1");
        $verifica->execute([$ID_QUAD]);
        if ($verifica->fetch()) {
            $erro = "❌ Não é possível inativar a quadra, pois existem partidas cadastradas nela.";
        }
    }

    if ($erro == '') {

        $ID_IMAGEM = $quadra['ID_IMAGEM'];
        
        if (!empty($_FILES['imagem']['name'])) {

            // 1. Apagar imagem antiga (se existir)
            if (!empty($quadra['ID_IMAGEM'])) {

                $query = $pdo->prepare("SELECT PATH FROM IMAGEM WHERE ID_IMAGEM = ?");
                $query->execute([$quadra['ID_IMAGEM']]);
                $img = $query->fetch(PDO::FETCH_ASSOC);

                if ($img) {
                    $arquivoAntigo = __DIR__ . '/../../../../storage/' . $img['PATH'];

                    if (file_exists($arquivoAntigo)) {
                        unlink($arquivoAntigo);
                    }

                    // Apaga do banco
                    $query = $pdo->prepare("DELETE FROM IMAGEM WHERE ID_IMAGEM = ?");
                    $query->execute([$quadra['ID_IMAGEM']]);
                }
            }

            $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $novoNome = uniqid() . '.' . $extensao;
            $caminho = __DIR__ . '/../../../../storage/' . $novoNome;

            move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho);

            $query = $pdo->prepare("INSERT INTO IMAGEM (PATH) VALUES (?)");
            $query->execute([$novoNome]);
            $ID_IMAGEM = $pdo->lastInsertId();
            
        }


        $query3 = $pdo->prepare("
                UPDATE QUADRAS
                SET ENDERECO_QUAD = ?, 
                CIDADE_QUAD = ?, 
                ID_UF = ?, 
                ID_MODAL = ?, 
                PRECO_HORA_QUAD = ?, 
                STATUS_QUAD = ?,
                ID_IMAGEM = ?
    
                WHERE ID_QUAD = ?
            ");

        $query3->execute([
            $ENDERECO_QUAD,
            $CIDADE_QUAD,
            $SIGLA_UF,
            $NOME_MODAL,
            $PRECO_HORA_QUAD,
            $STATUS_QUAD,
            $ID_IMAGEM,

            $ID_QUAD
        ]);

        header("Location: ./inicio_prop.php");
    }
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
    <title>Edição de quadra</title>
</head>

<body class="font-outfit font-medium not-italic text-[#6b6b6b]">

    <div class="bg-[#F0F0F0] w-full h-full min-h-screen overflow-x-hidden flex flex-col">

        <!-- Nav -->
        <div class="flex bg-gradient-to-b from-[#4ad658] to-green-500 h-20 items-center">
            <img src="/static/ifoot.png" alt="" class="h-20">

            <a href="./cadastra_quadra.php" class="text-white ml-16 hover:text-gray-200">Cadastrar nova quadra</a>
            <a href="./calendario_partidas.php" class="text-white ml-6 hover:text-gray-200">Ver agenda de partidas</a>
        </div>


        <span class="material-symbols-outlined w-10 h-10 flex items-center justify-center rounded-xl bg-gray-300 hover:bg-gray-400 transition mt-4 ml-4">
            <a href="./inicio_prop.php" class="w-10 h-10 flex items-center justify-center rounded-xl">
                reply
            </a>
        </span>


        <div class="mt-6 pl-6 w-full">
            <h1 class="text-[28px]  w-auto h-auto flex items-center justify-start ml-4">
                Edição dos dados da quadra
            </h1>
        </div>


        <div class="flex mt-6 w-screen">

            <div class="w-1/2 flex">
    
                <form action="" method="post" id="form_update_quadra" class="" enctype="multipart/form-data">

                    <div class="space-y-4 w-96 ml-28">

                        <div class="flex flex-row w-full gap-2">
                            <div class="w-[100px] flex items-center">
                                <label for="ENDERECO_QUAD">Endereço: </label>

                            </div>

                            <div class="w-auto">
                                <input type="text" name="ENDERECO_QUAD" id="ENDERECO_QUAD"
                                    value="<?= $quadra['ENDERECO_QUAD'] ?>"
                                    class="w-full h-9 px-3 rounded-md border border-gray-300 outline-none">
                            </div>
                        </div>
        
                        <div class="flex flex-row w-full gap-2">
                            <div class="w-[100px] flex items-center">
                                <label for="CIDADE_QUAD">Cidade: </label>
                            </div>

                            <div class="w-auto">
                                <input type="text" name="CIDADE_QUAD" id="CIDADE_QUAD"
                                    value="<?= $quadra['CIDADE_QUAD'] ?>"
                                    class="w-full h-9 px-3 rounded-md border border-gray-300 outline-none">
                            </div>
                        </div>
        
                        <div class="flex flex-row w-full gap-2">
                            <div class="w-[100px] flex items-center">
                                <label for="UF">UF: </label>
                            </div>

                            <div class="w-auto">
                                <select name="UF" id="UF" class="w-full h-9 px-3 rounded-md border border-gray-300 outline-none">
                                    <option value="">Selecione</option>
                                    <?php foreach ($ufs as $uf): ?>
                                        <option value="<?= $uf['ID_UF'] ?>" <?= $quadra['ID_UF'] == $uf['ID_UF'] ? 'selected' : '' ?>>
                                            <?= $uf['NOME_UF'] ?>
                                        </option>
                                    <?php endforeach; ?>
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
                                    <option value="1" <?= $quadra['ID_MODAL'] == 1 ? 'selected' : '' ?>>Campo</option>
                                    <option value="2" <?= $quadra['ID_MODAL'] == 2 ? 'selected' : '' ?>>Society</option>
                                    <option value="3" <?= $quadra['ID_MODAL'] == 3 ? 'selected' : '' ?>>Quadra</option>
                                </select>
                            </div>
                        </div>
        


                        <div class="flex flex-row w-full gap-2">
                            <div class="w-[100px] flex items-center">
                                <label for="PRECO_HORA_QUAD">Preço (R$/h) : </label>
                            </div>

                            <div class="w-auto">
                                <input type="text" name="PRECO_HORA_QUAD" id="PRECO_HORA_QUAD"
                                    value="<?= number_format($quadra['PRECO_HORA_QUAD'], 2, ',', '.') ?>"
                                    oninput="formatarMoeda(this)"
                                    class="w-24 h-9 px-3 rounded-md border border-gray-300 outline-none">
                            </div>
        
                        </div>
        
                        <div class="flex flex-row w-full gap-2">
                            <div class="w-[100px] flex items-center ">
                                <label for="STATUS_QUAD">Situação: </label>
                            </div>

                            <div class="w-auto">
                                <select name="STATUS_QUAD" id="STATUS_QUAD" class="w-24 h-9 px-3 rounded-md border border-gray-300 outline-none">
                                    <option value="1" <?= $quadra['STATUS_QUAD'] == 1 ? 'selected' : '' ?>>Ativa</option>
                                    <option value="0" <?= $quadra['STATUS_QUAD'] == 0 ? 'selected' : '' ?>>Inativa</option>
                                </select>
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

                    <img src="<?= !empty($quadra['PATH']) ? '../../../../storage/' . $quadra['PATH'] : '' ?>" id="preview-imagem" class="w-full h-full object-cover <?= empty($quadra['PATH']) ? 'hidden' : '' ?>">

                    <span id="icone-person" class="material-symbols-outlined text-[80px] text-white absolute <?= !empty($quadra['PATH']) ? 'hidden' : '' ?>">
                        stadium
                    </span>
                </div>
                

                <label for="imagem" class="mt-4 bg-white hover:bg-gray-300 text-green-500 px-5 py-2 rounded-full cursor-pointer transition font-semibold">
                    Alterar imagem
                </label>
                <input 
                    type="file" 
                    id="imagem" 
                    form="form_update_quadra" 
                    name="imagem" accept="image/*" 
                    class="hidden"
                >
            </div>
            
            
            
        </div>


        
        <div class="flex flex-col w-full ml-28 mt-6">
            <?php if (!empty($erro)): ?>
                <p id="msg" class="text-red-500"><?= $erro ?></p>
            <?php endif; ?>
        </div>
        
    </div>
    
    
    
    <script src="/src/js/formata_preco_quadra.js"></script>
    <script src="/src/js/tratamento-erros_cad-quadra.js"></script>
    <script src="/src/js/some_mensagem.js"></script>
    <script src="/src/js/troca_icone_imagem.js"></script>
</body>
</html>