<?php

    require_once '../../config.php';
    require_once '../../authenticate_prop.php';

    $mensagem_erro = ""; // Variável para armazenar a mensagem de erro
    $mensagem_sucesso = ""; // Variável para armazenar a mensagem de sucesso

    $NOME_PROP = $_SESSION['name_prop'];
    $ID_PROP = $_SESSION['id_prop'];

    $query = $pdo->prepare("
        SELECT *
        FROM PROPRIETARIOS 
        LEFT JOIN IMAGEM ON PROPRIETARIOS.ID_IMAGEM = IMAGEM.ID_IMAGEM
        WHERE PROPRIETARIOS.ID_PROP = ?
    ");
    $query->execute([$ID_PROP]);
    $results = $query->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $NOME_PROP = $_POST['NOME_PROP'];
        $EMAIL_PROP = $_POST['EMAIL_PROP'];
        $TEL_PROP = $_POST['TEL_PROP'];
        $SENHA_PROP = $_POST['SENHA_PROP']; // Senha nova




        $CAMPOS_PROP = []; //Array que juntas apenas os campos que vão fazer o update
        $DADOS_PROP = []; //Array com os dados que foram alterados
        $reload = false; // Essa variável serve para o usuário ser redirecionado para o login apenas se ele mudar e-mail e/ou senha  





        //Query para verificar se o email cadastrado já existem
        $verifica_email = $pdo->prepare("SELECT 1 FROM PROPRIETARIOS WHERE EMAIL_PROP = ? AND ID_PROP != ?");

        $verifica_email->execute([$EMAIL_PROP, $ID_PROP]);

        if ($verifica_email->rowCount() > 0) {

            $_SESSION['mensagem_erro'] =  "❌ Este e-mail já está sendo usado por outro usuário.";


        } else { //Bloco de alteração de dados

            // Nome
            if ($NOME_PROP !== $results['NOME_PROP'] && $NOME_PROP !== '') {
                $CAMPOS_PROP[] = 'NOME_PROP = ?';
                $DADOS_PROP[] = $NOME_PROP;
            }

            // Email
            if ($EMAIL_PROP !== $results['EMAIL_PROP'] && $EMAIL_PROP !== '') {
                $CAMPOS_PROP[] = 'EMAIL_PROP = ?';
                $DADOS_PROP[] = $EMAIL_PROP;

                $reload = true;
            }

            // Telefone
            if ($TEL_PROP !== $results['TEL_PROP'] && $TEL_PROP !== '') {
                $CAMPOS_PROP[] = 'TEL_PROP = ?';
                $DADOS_PROP[] = $TEL_PROP;
            }

            // Senha (tratamento especial)
            if (!empty($SENHA_PROP)) {
                $CAMPOS_PROP[] = 'SENHA_PROP = ?';
                $DADOS_PROP[] = password_hash($SENHA_PROP, PASSWORD_BCRYPT);

                $reload = true;
            }




            if (!empty($_FILES['imagem']['name'])) {

                // 1. Apagar imagem antiga (se existir)
                if (!empty($results['ID_IMAGEM'])) {

                    $query = $pdo->prepare("SELECT PATH FROM IMAGEM WHERE ID_IMAGEM = ?");
                    $query->execute([$results['ID_IMAGEM']]);
                    $img = $query->fetch(PDO::FETCH_ASSOC);

                    if ($img) {
                        $arquivoAntigo = __DIR__ . '/../../../../storage/' . $img['PATH'];

                        if (file_exists($arquivoAntigo)) {
                            unlink($arquivoAntigo);
                        }

                        // Apaga do banco
                        $query = $pdo->prepare("DELETE FROM IMAGEM WHERE ID_IMAGEM = ?");
                        $query->execute([$results['ID_IMAGEM']]);
                    }
                }

                $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
                $novoNome = uniqid() . '.' . $extensao;
                $caminho = __DIR__ . '/../../../../storage/' . $novoNome;

                move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho);

                $query = $pdo->prepare("INSERT INTO IMAGEM (PATH) VALUES (?)");
                $query->execute([$novoNome]);
                $ID_IMAGEM = $pdo->lastInsertId();
                
                
                $CAMPOS_PROP[] = 'ID_IMAGEM = ?';
                $DADOS_PROP[] = $ID_IMAGEM;


            }

            if(empty($CAMPOS_PROP)){
                $_SESSION['mensagem_erro'] = "⚠️ Nenhuma informação foi alterada.";

            } else{
                $DADOS_PROP[] = $ID_PROP;

                $sql = "UPDATE PROPRIETARIOS SET " . implode(', ', $CAMPOS_PROP) . " WHERE ID_PROP = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($DADOS_PROP);

                //Mensagem de confirmação de sucesso na edição de dados
                $_SESSION['mensagem_sucesso'] = "✅ Dados alterados com sucesso!";

                //Atualiza sessão apenas dos campos alterados
                if ($NOME_PROP !== $results['NOME_PROP']) {
                    $_SESSION['name_prop'] = $NOME_PROP;
                }

                if ($reload){
                    $_SESSION = [];

                    session_destroy();

                    header("Location: ../../login/login_prop.php");
                    exit();

                } else{
                    header("Location: update_prop.php");
                    exit();
                }
            }


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
        <div class="flex bg-gradient-to-b from-[#4ad658] to-green-500 h-20 items-center">
            <a href="./inicio_prop.php">
                <img src="/static/ifoot.png" alt="" class="h-20">
            </a>

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
                Aqui estão as suas informações pessoais
            </h1>
        </div>


        <div class="flex mt-6 w-screen">
            <div class="w-1/2 flex">

                <form action="" id="form_update_prop" method="post" enctype="multipart/form-data">

                    <div class="space-y-4 w-96 ml-28">

                        <div class="flex flex-row w-full">
                            <div class="w-1/5 flex items-center">
                                <label for="NOME_PROP">Nome: </label>
                            </div>

                            <div class="w-4/5">
                                <input 
                                    type="text" 
                                    name="NOME_PROP" 
                                    id="NOME_PROP" 
                                    value="<?= $results["NOME_PROP"] ?>"
                                    class="w-full h-9 px-3 rounded-md border border-gray-300 outline-none">
                            </div>
                        </div>


                        
                        <div class="flex flex-row w-full">
                            <div class="w-1/5 flex items-center">
                                <label for="EMAIL_PROP">E-mail: </label>
                            </div>

                            <div class="w-4/5">
                                <input 
                                    type="email" 
                                    name="EMAIL_PROP" 
                                    id="EMAIL_PROP"
                                    value="<?= $results["EMAIL_PROP"] ?>"
                                    class="w-full h-9 px-3 rounded-md border border-gray-300 outline-none">
                            </div>
                        </div>


                        <div class="flex flex-row w-full">
                            <div class="w-1/5 flex items-center">
                                <label for="CPF_PROP">CPF: </label>
                            </div>

                            <div class="w-4/5">
                                <input 
                                    type="text" 
                                    name="CPF_PROP" 
                                    id="CPF_PROP" 
                                    maxlength="11"
                                    value="<?= $results["CPF_PROP"] ?>" disabled
                                    class="w-full h-9 px-3 rounded-md border border-gray-300 outline-none">
                            </div>
                        </div>


                        <div class="flex flex-row w-full">
                            <div class="w-1/5 flex items-center">
                                <label for="TEL_PROP">Telefone: </label>
                            </div>

                            <div class="w-4/5">
                                <input 
                                    type="telefone" 
                                    name="TEL_PROP" 
                                    id="TEL_PROP" 
                                    maxlength="11"
                                    value="<?= $results["TEL_PROP"] ?>"
                                    class="w-full h-9 px-3 rounded-md border border-gray-300 outline-none">
                            </div>
                        </div>


                        <div class="flex flex-row w-full">
                            <div class="w-1/5 flex items-center">
                                <label for="SENHA_PROP">Senha: </label>
                            </div>

                            <div class="w-4/5">
                                <input 
                                    type="password" 
                                    name="SENHA_PROP" 
                                    id="SENHA_PROP"
                                    placeholder="Digite uma nova senha(opcional)"
                                    class="w-full h-9 px-3 rounded-md border border-gray-300 outline-none">
                            </div>
                        </div>


                        <div class="flex justify-end">
                            <input type="submit" value="Salvar" class="bg-green-500 text-white px-4 py-1 rounded cursor-pointer">
                        </div>


                    </div>
        
        
                </form>
            </div>



            <!-- DIV COM A FOTO DE PERFIL -->
            <div class="w-1/2 flex flex-col items-center justify-center">
                <div class="w-72 h-72 rounded-full bg-gray-300 flex items-center justify-center overflow-hidden relative">
                    
                    <img src="<?= !empty($results['PATH']) ? '../../../../storage/' . $results['PATH'] : '' ?>" id="preview-imagem" class="w-full h-full object-cover <?= empty($results['PATH']) ? 'hidden' : '' ?>">

                    <span id="icone-person" class="material-symbols-outlined text-[80px] text-white absolute <?= !empty($results['PATH']) ? 'hidden' : '' ?>">
                        person
                    </span>
                </div>
                
                <label for="imagem" class="mt-4 bg-white hover:bg-gray-300 text-green-500 px-5 py-2 rounded-full cursor-pointer transition font-semibold">
                    Alterar imagem
                </label>
                <input 
                    type="file" 
                    id="imagem" 
                    form="form_update_prop" 
                    name="imagem" accept="image/*" 
                    class="hidden"
                >
            </div>
        </div>


        <div class="w-full flex mt-2">
            <div class="ml-24 w-full">
                <?php if (!empty($_SESSION['mensagem_erro'])) :?>
                    <p id="msg" class="text-red-500"><?= $_SESSION['mensagem_erro'] ?></p>
                    <?php unset($_SESSION['mensagem_erro']); ?>

                <?php elseif (!empty($_SESSION['mensagem_sucesso'])): ?>
                    <p id="msg" class="text-green-500"><?= $_SESSION['mensagem_sucesso'] ?></p>
                    <?php unset($_SESSION['mensagem_sucesso']); ?>

                <?php endif;?> 
            </div>
        </div>


    </div>




    <script src="/src/js/tratamento-erros-update_prop.js"></script>
    <script src="/src/js/some_mensagem.js"></script>
    <script src="/src/js/troca_icone_imagem.js"></script>
</body>
</html>