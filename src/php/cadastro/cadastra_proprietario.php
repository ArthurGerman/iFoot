<?php
    require_once "../config.php";

    $mensagem_erro = ""; // Variável para armazenar a mensagem de erro que aparece caso o usuário tente cadastrar um email e/ou senha que já existem no banco de dados

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $NOME_PROP = $_POST['NOME_PROP'];
        $EMAIL_PROP = $_POST['EMAIL_PROP'];
        $CPF_PROP = $_POST['CPF_PROP'];
        $TEL_PROP = $_POST['TEL_PROP'];
        $SENHA_PROP = password_hash($_POST['SENHA_PROP'], PASSWORD_BCRYPT);


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
            $query = $pdo->prepare("INSERT INTO PROPRIETARIOS (NOME_PROP, EMAIL_PROP, CPF_PROP, TEL_PROP, SENHA_PROP) VALUES (?,?,?,?,?)");
            $query->execute([$NOME_PROP, $EMAIL_PROP, $CPF_PROP, $TEL_PROP, $SENHA_PROP]);

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
    <link rel="shortcut icon" href="/static/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="/src/styles/global.css">
    <title>Cadastramento</title>
</head>
<body class=" font-outfit font-medium not-italic text-white">

    <div class="relative bg-gradient-to-b from-[#2ba438] to-[#14551a] w-screen h-screen flex justify-end items-center">

        <img src="/static/ifoot.png" alt="" class="absolute left-40 top-1/2 -translate-y-1/2 w-96">
        
        <div class="bg-gradient-to-b from-[#4ad658] to-green-500 h-[600px] mr-20 p-10 rounded-2xl">

            <h1 class="text-[28px]">
                Faça seu cadastro de proprietário <br> aqui
            </h1>

            <br>

            
            <form action="" method="post" class="flex flex-col">
        
                <input 
                    type="text" 
                    name="NOME_PROP" 
                    id="NOME_PROP" 
                    placeholder="Nome" 
                    class="px-4 mb-4 bg-transparent border-[3px] border-solid border-white rounded-2xl h-12 text-white outline-none placeholder-white"
                >
                
                <input 
                    type="email" 
                    name="EMAIL_PROP" 
                    id="EMAIL_PROP"
                    placeholder="E-mail"
                    class="px-4 mb-4 bg-transparent border-[3px] border-solid border-white rounded-2xl h-12 text-white outline-none placeholder-white"
                >
                
                <input 
                    type="text" 
                    name="CPF_PROP" 
                    id="CPF_PROP" 
                    maxlength="11"
                    placeholder="CPF"
                    class="px-4 mb-4 bg-transparent border-[3px] border-solid border-white rounded-2xl h-12 text-white outline-none placeholder-white"
                >
                
                <input 
                    type="telefone" 
                    name="TEL_PROP" 
                    id="TEL_PROP" 
                    maxlength="11"
                    placeholder="Telefone"
                    class="px-4 mb-4 bg-transparent border-[3px] border-solid border-white rounded-2xl h-12 text-white outline-none placeholder-white"
                >
                
                <input 
                    type="password" 
                    name="SENHA_PROP" 
                    id="SENHA_PROP"
                    placeholder="Digite a senha"
                    class="px-4 mb-4 bg-transparent border-[3px] border-solid border-white rounded-2xl h-12 text-white outline-none placeholder-white"
                >


                
                <div class="flex justify-center gap-24 mt-8">

                    <a href="/index.php" class="flex bg-white text-green-600 hover:bg-gray-200 text-[20px] h-12 w-28 rounded-2xl justify-center items-center">Voltar</a>
    
                    <input type="submit" value="Cadastrar" class="bg-white text-green-600 hover:bg-gray-200 text-[20px] h-12 w-28 rounded-2xl cursor-pointer">
                </div>
            </form>

            <?php if (!empty($mensagem_erro)) :?>
                <p class="text-red-600 flex p-2 mt-2 justify-center"><?= $mensagem_erro ?></p>
            <?php endif;?>
        </div>
    </div>


    <script src="/src/js/tratamento-erros_prop.js"></script>
</body>
</html>