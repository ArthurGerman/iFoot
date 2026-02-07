<?php 
    session_start();

    require_once '../config.php';

    $erro = ""; // Variável para que a mensagem de erro apareca depois do form

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $EMAIL_JOG = $_POST['EMAIL_JOG'];
        $SENHA_JOG = $_POST['SENHA_JOG'];
    
    
        $query = $pdo -> prepare("SELECT * FROM jogadores WHERE EMAIL_JOG = ?");
        $query -> execute([$EMAIL_JOG]);
        $jogador = $query->fetch(PDO::FETCH_ASSOC);
        
        
        if ($jogador && password_verify($SENHA_JOG, $jogador['SENHA_JOG'])) {

            $_SESSION['id_jog'] = $jogador['ID_JOG'];
            $_SESSION['name_jog'] = $jogador['NOME_JOG'];

            header('Location: /src/php/acoes/crud%20jogador/inicio_jog.php');

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
<body class="font-outfit font-medium not-italic text-white">

    <div class="relative bg-gradient-to-b from-[#2ba438] to-[#14551a] w-screen h-screen flex justify-end items-center">

        <img src="/static/ifoot.png" alt="" class="absolute left-40 top-1/2 -translate-y-1/2 w-96">

        <div class="flex flex-col">

            <div class="bg-gradient-to-b from-[#4ad658] to-green-500 h-[400px] mr-52 p-10 rounded-2xl">
            
                <h1 class="text-[28px]">
                    Faça seu login de jogador <br> aqui
                </h1>

                <br>
            
                <form action="" method="post" class="flex flex-col">

                    <!-CAMPO E-MAIL->
                    <div class="flex flex-row mb-3 gap-2">

                        <div class="w-1/6 flex items-center justify-end">
                            <label for="EMAIL_JOG">E-mail</label>
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
                    

                    <!-- Campo da senha -->
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
                    
                    <!--Botões de Ação-->
                    <div class="flex justify-end gap-6 mt-6">

                        <a href="/index.php" class="flex bg-white text-green-600 hover:bg-gray-200 text-[20px] h-12 w-28 rounded-2xl justify-center items-center">Voltar</a>
                        
                        <input type="submit" class="bg-white text-green-600 hover:bg-gray-200 text-[20px] h-12 w-28 rounded-2xl cursor-pointer">
                    </div>

                </form>
            
                <?php if ($erro): ?>
                    <p id="msg" class="text-red-600 flex p-2 mt-2 justify-center"><?php echo $erro; ?></p>
                <?php endif; ?>

            </div>

            <p class="ml-10 mt-4 w-[375px]">
                Ainda não se cadastrou? 
                <a href="/src/php/cadastro/cadastra_jogador.php" class="ml-13 mr-6 underline font-semibold hover:text-gray-200 transition">Cadastre-se aqui</a>
            </p>

        </div>
    </div>



    <script src="/src/js/tratamento-erros-login_jog.js"></script>
    <script src="/src/js/some_mensagem.js"></script>
</body>
</html>