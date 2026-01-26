<?php

require_once '../../config.php';
require_once '../../authenticate_prop.php';

$mensagem_erro = ""; // Variável para armazenar a mensagem de erro que aparece caso o usuário tente cadastrar um email e/ou senha que já existem no banco de dados

$ID_PROP = $_SESSION['id_prop'];

$query = $pdo->prepare("SELECT * FROM PROPRIETARIOS WHERE ID_PROP = ?");
$query->execute([$ID_PROP]);
$results = $query->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $NOME_PROP = $_POST['NOME_PROP'];
    $EMAIL_PROP = $_POST['EMAIL_PROP'];
    $TEL_PROP = $_POST['TEL_PROP'];
    $SENHA_PROP = $_POST['SENHA_PROP']; // Senha nova

    //Query para verificar se o email cadastrado já existem
    $verifica_email = $pdo->prepare("SELECT 1 FROM PROPRIETARIOS WHERE EMAIL_PROP = ? AND ID_PROP != ?");

    $verifica_email->execute([$EMAIL_PROP, $ID_PROP]);

    if ($verifica_email->rowCount() > 0) {

        $mensagem_erro =  "❌ Este e-mail já está sendo usado por outro usuário.<br>";
    } else { //Bloco de alteração de dados

        if (!empty($SENHA_PROP)) { // Se a senha não estiver vazia, o sistema atualiza a senha pois não tem como alterar uma senha que já foi feito o hash

            $SENHA_PROP = password_hash($SENHA_PROP, PASSWORD_BCRYPT); //Senha nova com hash

            $query = $pdo->prepare("
                    UPDATE PROPRIETARIOS
                    SET NOME_PROP = ?, 
                    EMAIL_PROP = ?,
                    TEL_PROP = ?,
                    SENHA_PROP = ?
        
                    WHERE ID_PROP = ?
                ");

            $query->execute([
                $NOME_PROP,
                $EMAIL_PROP,
                $TEL_PROP,
                $SENHA_PROP,

                $ID_PROP
            ]);
        } else { // Se o usuário não colocou uma senha nova, o sistema não atualiza
            $query = $pdo->prepare("
                    UPDATE PROPRIETARIOS
                    SET NOME_PROP = ?, 
                    EMAIL_PROP = ?,
                    TEL_PROP = ?
        
                    WHERE ID_PROP = ?
                    ");

            $query->execute([
                $NOME_PROP,
                $EMAIL_PROP,
                $TEL_PROP,

                $ID_PROP
            ]);
        }

        header("Location: ../../login/login_prop.php");
        exit();
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
    <title>Atualização de dados</title>
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
                    Atualização de dados do prorietário
                </h2>

                <form action="" method="post" id="cadastro" class="flex flex-col items-end gap-3">

                    <div>
                        <label for="NOME_PROP">Nome: </label>
                        <input type="text" name="NOME_PROP" id="NOME_PROP" value="<?= $results["NOME_PROP"] ?>"
                            class="border rounded-md border-gray-400 w-80 p-1">
                    </div>

                    <div>
                        <label for="EMAIL_PROP">E-mail: </label>
                        <input type="email" name="EMAIL_PROP" id="EMAIL_PROP"
                            value="<?= $results["EMAIL_PROP"] ?>"
                            class="border rounded-md border-gray-400 w-80 p-1">
                    </div>

                    <div>
                        <label for="CPF_PROP">CPF: </label>
                        <input type="text" name="CPF_PROP" id="CPF_PROP" maxlength="11"
                            value="<?= $results["CPF_PROP"] ?>" disabled
                            class="border rounded-md border-gray-400 w-80 p-1">
                    </div>

                    <div>
                        <label for="TEL_PROP">Telefone: </label>
                        <input type="telefone" name="TEL_PROP" id="TEL_PROP" maxlength="11"
                            value="<?= $results["TEL_PROP"] ?>"
                            class="border rounded-md border-gray-400 w-80 p-1">
                    </div>

                    <div>
                        <label for="SENHA_PROP">Senha: </label>
                        <input type="password" name="SENHA_PROP" id="SENHA_PROP"
                            placeholder="Digite uma nova senha(opcional)"
                            class="border rounded-md border-gray-400 w-80 p-1">
                    </div>
                </form>

                <?php if (!empty($mensagem_erro)) : ?>
                    <p style="color:red"><?= $mensagem_erro ?></p>
                <?php endif; ?>

                <div class="flex justify-between p-3 pt-0">
                    <a href="./inicio_prop.php" class="border rounded-lg w-24 h-9 flex text-center justify-center 
                     bg-white text-green-600"><button>Voltar</button></a>

                    <button type="submit" form="cadastro" class="border rounded-lg w-24 h-9 
                     text-white bg-green-600">Comfirmar</button>
                </div>

                <script src="/src/js/tratamento-erros-update_prop.js"></script>
            </div>

            <div class="flex flex-col justify-center items-center gap-5">
                <div class="w-52 h-48 bg-slate-300 border rounded-full p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class=" size-full"
                        viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path d="M240 192C240 147.8 275.8 112 320 112C364.2 112 400 147.8 400 192C400 236.2 364.2 272 320 272C275.8 272 240 236.2 240 192zM448 192C448 121.3 390.7 64 320 64C249.3 64 192 121.3 192 192C192 262.7 249.3 320 320 320C390.7 320 448 262.7 448 192zM144 544C144 473.3 201.3 416 272 416L368 416C438.7 416 496 473.3 496 544L496 552C496 565.3 506.7 576 520 576C533.3 576 544 565.3 544 552L544 544C544 446.8 465.2 368 368 368L272 368C174.8 368 96 446.8 96 544L96 552C96 565.3 106.7 576 120 576C133.3 576 144 565.3 144 552L144 544z" />
                    </svg>
                </div>
                <button class="border rounded-lg w-44 h-10
                 bg-white text-green-600">Mudar imagem</button>
            </div>
        </div>
    </div>
</body>

</html>