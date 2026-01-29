<?php 
    session_start();

    require_once '../config.php';

    /* --------------------- ATENÇÃO -------------------------
    O trecho de código abaixo serve para a funcionalidadeque permite redirecionar o usuário diretamente para a página inicial do crud quando já está logado

    if(isset($_SESSION['id_prop'])){
         header('Location: /src/php/acoes/crud%20proprietario/inicio_prop.php');
    }else if(isset($_SESSION['id_jog'])) {
        header('Location: /src/php/acoes/crud%20jogador/inicio_jog.php');
    };*/

    $erro = ""; // Variável para que a mensagem de erro apareca depois do form

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $EMAIL_PROP = $_POST['EMAIL_PROP'];
        $SENHA_PROP = $_POST['SENHA_PROP'];
    
    
        $query = $pdo->prepare("SELECT * FROM PROPRIETARIOS WHERE EMAIL_PROP = ?");
        $query->execute([$EMAIL_PROP]);
        $proprietario = $query->fetch(PDO::FETCH_ASSOC);
    

        if ($proprietario && password_verify($SENHA_PROP, $proprietario['SENHA_PROP'])) {

            $_SESSION['id_prop'] = $proprietario['ID_PROP'];
            $_SESSION['name_prop'] = $proprietario['NOME_PROP'];

            header('Location: /src/php/acoes/crud%20proprietario/inicio_prop.php');
            
        } else {
            $erro = "❌ Email ou senha estão incorretos";
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
    <title>Login</title>
</head>
<body class=" font-outfit font-medium not-italic text-white">

    <div class="relative bg-gradient-to-b from-[#2ba438] to-[#14551a] w-screen h-screen flex justify-end items-center">

        <img src="/static/ifoot.png" alt="" class="absolute left-40 top-1/2 -translate-y-1/2 w-96">

        <div class="flex flex-col">

            <div class="bg-gradient-to-b from-[#4ad658] to-green-500 h-[400px] mr-52 p-10 rounded-2xl">
                
                <h1 class="text-[28px]">
                    Faça seu login de proprietário <br> aqui
                </h1>
    
                <br>
                
                <form action="" method="post" class="flex flex-col">


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
    
                    
                    <div class="flex justify-center gap-24 mt-6">
                        
                        <a href="/index.php" class="flex bg-white text-green-600 hover:bg-gray-200 text-[20px] h-12 w-28 rounded-2xl justify-center items-center">Voltar</a>
                        
                        <input type="submit" class="bg-white text-green-600 hover:bg-gray-200 text-[20px] h-12 w-28 rounded-2xl cursor-pointer">
                    </div>

                </form>
                
                
                <?php if ($erro): ?>
                    <p class="text-red-600 flex p-2 mt-2 justify-center"><?php echo $erro; ?></p>
                <?php endif; ?>
    
            </div>

            <p class="ml-10 mt-4 w-[408.54px]">
                Ainda não se cadastrou? 
                <a href="/src/php/cadastro/cadastra_proprietario.php" class="ml-16 mr-10 underline font-semibold">Cadastre-se aqui</a>
            </p>

        </div>
    </div>


    <script src="/src/js/tratamento-erros-login_prop.js"></script>
</body>
</html>