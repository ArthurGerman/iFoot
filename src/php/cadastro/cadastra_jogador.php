<?php 
    require_once "../config.php";

    $mensagem_erro = ""; // Variável para armazenar a mensagem de erro que aparece caso o usuário tente cadastrar um email e/ou CPF que já existem no banco de dados

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $NOME_JOG = $_POST['NOME_JOG'];
        $EMAIL_JOG = $_POST['EMAIL_JOG'];
        $CPF_JOG = $_POST['CPF_JOG'];
        $CIDADE_JOG = $_POST['CIDADE_JOG'];
        $ENDERECO_JOG = $_POST['ENDERECO_JOG'];
        $TEL_JOG = $_POST['TEL_JOG'];
        $SENHA_JOG = password_hash($_POST['SENHA_JOG'], PASSWORD_BCRYPT);

        $SIGLA_UF = $_POST['UF'];

        //Query para descobrir o id correspondente à UF

        $query1 = $pdo->prepare("SELECT ID_UF FROM UF WHERE SIGLA_UF = ?");
        $query1->execute([$SIGLA_UF]);
        $result = $query1->fetch(PDO::FETCH_ASSOC);

        $ID_UF = $result['ID_UF'];


        //Query para verificar se o CPF e/ou email cadastrados já existem
        // São duas verificações únicas pois possa ser que o email ou o CPF foram cadastrados iguais de maneira individual e não os dois juntos
        $verifica_email = $pdo->prepare("SELECT 1 FROM JOGADORES WHERE EMAIL_JOG = ?");
        $verifica_cpf = $pdo->prepare("SELECT 1 FROM JOGADORES WHERE CPF_JOG = ?");

        $verifica_email->execute([$EMAIL_JOG]);
        $verifica_cpf->execute([$CPF_JOG]);

        if ($verifica_cpf->rowCount() > 0 && $verifica_email->rowCount() > 0) {
            $mensagem_erro = "❌ Estes CPF e Email já estão cadastrados no sistema !";

        } else if ($verifica_email->rowCount() > 0){
            $mensagem_erro =  "❌ Este e-mail já está cadastrado no sistema !";

        } else if ($verifica_cpf->rowCount() > 0){
            $mensagem_erro =  "❌ Este CPF já está cadastrado no sistema !";

        } else{

            //Query para fazer a inserção de dados no banco
    
            $query2 = $pdo->prepare("INSERT INTO JOGADORES (NOME_JOG, EMAIL_JOG, CPF_JOG, CIDADE_JOG, ENDERECO_JOG, TEL_JOG, SENHA_JOG, ID_UF) VALUES (?,?,?,?,?,?,?,?)");
            $query2->execute([$NOME_JOG, $EMAIL_JOG, $CPF_JOG, $CIDADE_JOG, $ENDERECO_JOG, $TEL_JOG, $SENHA_JOG, $ID_UF]);
            
            header('Location: ../login/login_jog.php');
    
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
    <title>Cadastro de Jogador</title>
</head>
<body class="font-outfit font-medium not-italic text-white">

    <div class="bg-gradient-to-b from-[#2ba438] to-[#14551a] w-screen h-screen flex">

        <!-- Seção do Formulário -->
        <div class="w-1/2 flex items-center justify-center">

            <div class="bg-gradient-to-b from-[#4ad658] to-green-500 h-[700px] ml-20 p-10 rounded-2xl">
    
                <h1 class="text-[28px]">
                    Faça seu cadastro de jogador aqui
                </h1>
    
                <br>
            
                <!-- Formulario de Cadastro -->
                <form action="" method="post" class="flex flex-col">
    
                    <!-- Campo Nome -->
                    <div class="flex flex-row mb-3 gap-2">
    
                        <div class="w-1/6 flex items-center justify-end">
                            <label for="NOME_JOG">Nome:</label>
                        </div>
                        <div class="w-5/6 flex items-center">
                            <input 
                                type="text" 
                                name="NOME_JOG" 
                                id="NOME_JOG" 
                                placeholder="Digite seu nome"
                                class="px-4 bg-white border-[3px] border-solid border-gray-300 rounded-2xl w-full h-12 outline-none placeholder-gray-300 text-[#6b6b6b]"
                            >
                        </div>
    
                    </div>
    
                    <!-- Campo E-mail -->
                    <div class="flex flex-row mb-3 gap-2">
    
                        <div class="w-1/6 flex items-center justify-end">
                            <label for="EMAIL_JOG">E-mail:</label>
                        </div>
                        <div class="w-5/6 flex items-center">
                            <input 
                                type="email" 
                                name="EMAIL_JOG" 
                                id="EMAIL_JOG"
                                placeholder="E-mail"
                                class="px-4 bg-white border-[3px] border-solid border-gray-300 rounded-2xl w-full h-12 outline-none placeholder-gray-300 text-[#6b6b6b]"
                            >
                        </div>
    
                    </div>
            
                    <!-- Campo CPF -->
                    <div class="flex flex-row mb-3 gap-2">
    
                        <div class="w-1/6 flex items-center justify-end">
                            <label for="CPF_JOG">CPF:</label>
                        </div>
                        <div class="w-5/6 flex items-center">
                            <input 
                                type="text" 
                                name="CPF_JOG" 
                                id="CPF_JOG" 
                                maxlength="11"
                                placeholder="Digite apenas os números"
                                class="px-4 bg-white border-[3px] border-solid border-gray-300 rounded-2xl w-full h-12 outline-none placeholder-gray-300 text-[#6b6b6b]"
                            >
                        </div>
    
                    </div>
            
                    <!-- Campo Cidade -->
                    <div class="flex flex-row mb-3 gap-2">
    
                        <div class="w-1/6 flex items-center justify-end">
                            <label for="CIDADE_JOG">Cidade:</label>
                        </div>
                        <div class="w-5/6 flex items-center">
                            <input 
                                type="text" 
                                name="CIDADE_JOG" 
                                id="CIDADE_JOG"
                                placeholder="Cidade"
                                class="px-4 bg-white border-[3px] border-solid border-gray-300 rounded-2xl w-full h-12 outline-none placeholder-gray-300 text-[#6b6b6b]"
                            >
                        </div>
    
                    </div>
    
                    <!-- Campo Estado -->
                    <div class="flex flex-row mb-3 gap-2">
    
                        <div class="w-1/6 flex items-center justify-end">
                            <label for="UF">Estado:</label>
                        </div>
                        <div class="w-5/6 flex items-center">
                            <select name="UF" id="UF" class="px-4 bg-white border-[3px] border-solid border-gray-300 rounded-2xl w-full h-12 outline-none placeholder-gray-300 cursor-pointer text-gray-300">
                                <option value="" disabled selected>Selecione</option>
                                <option value="AC" class="text-black">Acre</option>
                                <option value="AL" class="text-black">Alagoas</option>
                                <option value="AP" class="text-black">Amapá</option>
                                <option value="AM" class="text-black">Amazonas</option>
                                <option value="BA" class="text-black">Bahia</option>
                                <option value="CE" class="text-black">Ceará</option>
                                <option value="DF" class="text-black">Distrito Federal</option>
                                <option value="ES" class="text-black">Espírito Santo</option>
                                <option value="GO" class="text-black">Goiás</option>
                                <option value="MA" class="text-black">Maranhão</option>
                                <option value="MT" class="text-black">Mato Grosso</option>
                                <option value="MS" class="text-black">Mato Grosso do Sul</option>
                                <option value="MG" class="text-black">Minas Gerais</option>
                                <option value="PA" class="text-black">Pará</option>
                                <option value="PB" class="text-black">Paraíba</option>
                                <option value="PR" class="text-black">Paraná</option>
                                <option value="PE" class="text-black">Pernambuco</option>
                                <option value="PI" class="text-black">Piauí</option>
                                <option value="RJ" class="text-black">Rio de Janeiro</option>
                                <option value="RN" class="text-black">Rio Grande do Norte</option>
                                <option value="RS" class="text-black">Rio Grande do Sul</option>
                                <option value="RO" class="text-black">Rondônia</option>
                                <option value="RR" class="text-black">Roraima</option>
                                <option value="SC" class="text-black">Santa Catarina</option>
                                <option value="SP" class="text-black">São Paulo</option>
                                <option value="SE" class="text-black">Sergipe</option>
                                <option value="TO" class="text-black">Tocantins</option>
                            </select>
                        </div>
    
                    </div>
    
                    
    
                    <!-- Campo Endereço -->
                    <div class="flex flex-row mb-3 gap-2">
    
                        <div class="w-1/6 flex items-center justify-end">
                            <label for="ENDERECO_JOG">Endereço: </label>
                        </div>
                        <div class="w-5/6 flex items-center">
                            <input 
                                type="endereco" 
                                name="ENDERECO_JOG" 
                                id="ENDERECO_JOG"
                                placeholder="Endereço"
                                class="px-4 bg-white border-[3px] border-solid border-gray-300 rounded-2xl w-full h-12 outline-none placeholder-gray-300 text-[#6b6b6b]"
                            >
                        </div>
    
                    </div>
    
                    
                    <!-- Campo Telefone -->
                    <div class="flex flex-row mb-3 gap-2">
    
                        <div class="w-1/6 flex items-center justify-end">
                            <label for="TEL_JOG">Telefone:</label>
                        </div>
                        <div class="w-5/6 flex items-center">
                            <input 
                                type="telefone" 
                                name="TEL_JOG" 
                                id="TEL_JOG" 
                                maxlength="11"
                                placeholder="Telefone (DDD + números)"
                                class="px-4 bg-white border-[3px] border-solid border-gray-300 rounded-2xl w-full h-12 outline-none placeholder-gray-300 text-[#6b6b6b]"
                            >
                        </div>
    
                    </div>
                    
                    <!-- Campo Senha -->
                    <div class="flex flex-row mb-3 gap-2">
    
                        <div class="w-1/6 flex items-center justify-end">
                            <label for="SENHA_JOG">Senha:</label>
                        </div>
                        <div class="w-5/6 flex items-center">
                            <input 
                                type="password" 
                                name="SENHA_JOG" 
                                id="SENHA_JOG" 
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
                    <p class="text-red-600 flex p-2 mt-2 justify-center"><?= $mensagem_erro ?></p>
                <?php endif;?> 
            </div>
        </div>


        <!-- DIV COM A FOTO DE PERFIL -->
        <div class="w-1/2 flex flex-col items-center justify-center">
            <div class="w-72 h-72 rounded-full bg-gray-300 flex items-center justify-center">
                <span class="material-symbols-outlined text-[80px] text-white">
                    person
                </span>
            </div>
            
            <button class="mt-4 text-sm text-green-600 hover:underline transition">
                Mudar Imagem
            </button>

        </div>

    </div>

    <script src="/src/js/tratamento-erros_jog.js"></script>
    <script>
        const select_UF = document.getElementById('UF');

        select_UF.addEventListener('change', function () {
            if (this.value !== '') {
            this.classList.remove('text-gray-300');
            this.classList.add('text-[#6b6b6b]'); // mesma cor dos inputs
            }
        });
    </script>
</body>
</html>