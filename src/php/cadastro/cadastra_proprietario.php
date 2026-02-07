<?php
    require_once "../config.php";

    $mensagem_erro = ""; // Variável para armazenar a mensagem de erro que aparece caso o usuário tente cadastrar um email e/ou senha que já existem no banco de dados

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $NOME_PROP = $_POST['NOME_PROP'];
        $EMAIL_PROP = $_POST['EMAIL_PROP'];
        $CPF_PROP = $_POST['CPF_PROP'];
        $TEL_PROP = $_POST['TEL_PROP'];
        $SENHA_PROP = password_hash($_POST['SENHA_PROP'], PASSWORD_BCRYPT);




        // Verificar se foi enviada uma imagem
        if (!empty($_FILES['imagem']['name'])) {
            $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $novoNome = uniqid() . '.' . $extensao;
            $caminho = __DIR__ . '../../../../storage/' . $novoNome;

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


        //Query para verificar se o CPF e/ou email cadastrados já existem
        // São duas verificações únicas pois possa ser que o email ou o CPF foram cadastrados iguais de maneira individual e não os dois juntos
        $verifica_email = $pdo->prepare("SELECT 1 FROM PROPRIETARIOS WHERE EMAIL_PROP = ?");
        $verifica_cpf = $pdo->prepare("SELECT 1 FROM PROPRIETARIOS WHERE CPF_PROP = ?");

        $verifica_email->execute([$EMAIL_PROP]);
        $verifica_cpf->execute([$CPF_PROP]);

        if ($verifica_cpf->rowCount() > 0 && $verifica_email->rowCount() > 0) {

            $mensagem_erro = "❌ Estes CPF e Email já estão cadastrados no sistema !";

        } else if ($verifica_email->rowCount() > 0){

            $mensagem_erro =  "❌ Este e-mail já está cadastrado no sistema !";

        } else if ($verifica_cpf->rowCount() > 0){

            $mensagem_erro =  "❌ Este CPF já está cadastrado no sistema !";

        } else{
            $query = $pdo->prepare("INSERT INTO PROPRIETARIOS (NOME_PROP, EMAIL_PROP, CPF_PROP, TEL_PROP, SENHA_PROP, ID_IMAGEM) VALUES (?,?,?,?,?,?)");
            $query->execute([$NOME_PROP, $EMAIL_PROP, $CPF_PROP, $TEL_PROP, $SENHA_PROP, $ID_IMAGEM]);

            // Redirecionar para login
            header('Location: ../login/login_prop.php');

            //echo "Olá $NOME_PROP ! Seus dados foram cadastrados com sucesso <br>";
            //echo "<button><a href='../login/login_prop.php'>Realizar login</a></button>";
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
    <title>Cadastro de Proprietário</title>
</head>
<body class="font-outfit font-medium not-italic text-white">

    <div class="bg-gradient-to-b from-[#2ba438] to-[#14551a] w-screen h-screen flex">
    
        <a href="../../../index.php">
            <img src="/static/ifoot.png" alt="" class="h-20">
        </a>

        <!-- DIV COM A FOTO DE PERFIL -->
        <div class="w-1/2 flex flex-col items-center justify-center">
            <div class="w-72 h-72 rounded-full bg-gray-300 flex items-center justify-center overflow-hidden relative">
                <span id="icone-person" class="material-symbols-outlined text-[80px] text-white absolute">
                    person
                </span>

                <img id="preview-imagem" class="hidden w-full h-full object-cover">
            </div>
            
            <label for="imagem" class="mt-4 bg-white hover:bg-gray-300 text-green-600 px-5 py-2 rounded-full cursor-pointer transition font-semibold">
                Adicionar imagem
            </label>
            <input 
                type="file" 
                id="imagem" 
                form="form_cadastro_prop" 
                name="imagem" accept="image/*" 
                class="hidden"
            >
        </div>

        <!-- Seção do Formulário -->
        <div class="w-1/2 flex items-center justify-center">

            <div class="bg-gradient-to-b from-[#4ad658] to-green-500 h-[600px] ml-20 p-10 rounded-2xl">
    
                <h1 class="text-[28px]">
                    Faça seu cadastro de proprietário <br> aqui
                </h1>
    
                <br>
    
                <!-- Formulário de Cadastro -->
                <form action="" id="form_cadastro_prop" method="post" class="flex flex-col" enctype="multipart/form-data">
    
                    <!-- Campo Nome -->
                    <div class="flex flex-row mb-3 gap-2">
    
                        <div class="w-1/6 flex items-center justify-end">
                            <label for="NOME_PROP">Nome:</label>
                        </div>
                        <div class="w-5/6 flex items-center">
                            <input 
                                type="text" 
                                name="NOME_PROP" 
                                id="NOME_PROP" 
                                placeholder="Nome" 
                                class="px-4 bg-white border-[3px] border-solid border-gray-300 rounded-2xl w-full h-12 outline-none placeholder-gray-300 text-[#6b6b6b]"
                            >
                        </div>
    
                    </div>

                    <!-- Campo E-mail -->
                    <div class="flex flex-row mb-3 gap-2">
    
                        <div class="w-1/6 flex items-center justify-end">
                            <label for="EMAIL_PROP">E-mail:</label>
                        </div>
                        <div class="w-5/6 flex items-center">
                            <input 
                                type="email" 
                                name="EMAIL_PROP" 
                                id="EMAIL_PROP"
                                placeholder="E-mail"
                                class="px-4 bg-white border-[3px] border-solid border-gray-300 rounded-2xl w-full h-12 outline-none placeholder-gray-300 text-[#6b6b6b]"
                            >
                        </div>
    
                    </div>
                    
                    <!-- Campo CPF -->
                    <div class="flex flex-row mb-3 gap-2">
    
                        <div class="w-1/6 flex items-center justify-end">
                            <label for="CPF_PROP">CPF:</label>
                        </div>
                        <div class="w-5/6 flex items-center">
                            <input 
                                type="text" 
                                name="CPF_PROP" 
                                id="CPF_PROP" 
                                maxlength="11"
                                placeholder="Digite apenas os números"
                                class="px-4 bg-white border-[3px] border-solid border-gray-300 rounded-2xl w-full h-12 outline-none placeholder-gray-300 text-[#6b6b6b]"
                            >
                        </div>
                    </div>
                    
                    <!-- Campo Telefone -->
                    <div class="flex flex-row mb-3 gap-2">
    
                        <div class="w-1/6 flex items-center justify-end">
                            <label for="TEL_PROP">Telefone:</label>
                        </div>
                        <div class="w-5/6 flex items-center">
                            <input 
                                type="telefone" 
                                name="TEL_PROP" 
                                id="TEL_PROP" 
                                maxlength="11"
                                placeholder="Telefone (DDD + números)"
                                class="px-4 bg-white border-[3px] border-solid border-gray-300 rounded-2xl w-full h-12 outline-none placeholder-gray-300 text-[#6b6b6b]"
                            >
                        </div>
    
                    </div>
                    

                    <!-- Campo Senha -->
                    <div class="flex flex-row mb-3 gap-2">
    
                        <div class="w-1/6 flex items-center justify-end">
                            <label for="SENHA_PROP">Senha:</label>
                        </div>
                        <div class="w-5/6 flex items-center">
                            <input 
                                type="password" 
                                name="SENHA_PROP" 
                                id="SENHA_PROP"
                                placeholder="Digite a senha"
                                class="px-4 bg-white border-[3px] border-solid border-gray-300 rounded-2xl w-full h-12 outline-none placeholder-gray-300 text-[#6b6b6b]"
                            >
                        </div>
                    </div>
    

    
                    <!-- Botões de Ação -->
                    <div class="flex justify-end gap-[105px] mt-8 mr-2">
    
                        <a href="/index.php" class="flex bg-white text-green-600 hover:bg-gray-200 text-[20px] h-12 w-28 rounded-2xl justify-center items-center transition">Voltar</a>
        
                        <input type="submit" value="Cadastrar" class="bg-white text-green-600 hover:bg-gray-200 text-[20px] h-12 w-28 rounded-2xl cursor-pointer transition">
                    </div>
                </form>
    
                <?php if (!empty($mensagem_erro)) :?>
                    <p class="text-red-600 flex p-2 mb-1 justify-center mt-2"><?= $mensagem_erro ?></p>
                <?php endif;?>
            </div>
        </div>

    </div>


    <script src="/src/js/tratamento-erros_prop.js"></script>
    <script src="/src/js/troca_icone_imagem.js"></script>
</body>
</html>