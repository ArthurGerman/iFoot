<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="shortcut icon" href="/static/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="/src/styles/global.css">
    <title>iFoot</title>
</head>
<body class=" font-outfit font-medium not-italic text-white">

    <div class="w-full min-h-dvh bg-hero flex flex-col">

        <!-- Nav -->
        <div class="flex bg-gradient-to-b from-[#4ad658] to-green-500 h-20">

            <div class="w-1/2 flex flex-row items-center">

                <img src="/static/ifoot.png" alt="" class="h-20 ml-20">

                <a href="./src/php/sobre.php" class="ml-10" id="sobre">Sobre</a>
            </div>

            <div class="flex w-1/2 h-20 pr-20 gap-7 items-center justify-end">

                <a href="./src/php/login/login_jog.php" 
                class="flex p-5 border-2 border-solid border-white rounded-2xl h-10 items-center hover:bg-white hover:text-[#4ad658]"
                >Entrar como jogador</a>
                
                <a href="./src/php/login/login_prop.php" 
                class="flex p-5 border-2 border-solid border-white rounded-2xl h-10 items-center hover:bg-white hover:text-[#4ad658]"
                >Entrar como proprietário</a>
                
            </div>
        </div>

        
        <div class="flex flex-row flex-1">

            <div class="w-4/6 flex px-28 py-32">
                <div class="flex flex-col w-full h-full rounded-2xl">

                    <h1 class="text-[35px] mt-36">
                        Sua próxima partida começa aqui
                    </h1>  
                
                
                    <p class="text-[20px] mt-6">
                        O iFoot nasceu para simplificar o futebol. Nossa plataforma conecta
                        jogadores e proprietários de quadras de forma inteligente, eliminando
                        a perda de tempo com pesquisas presenciais. Encontre os melhores
                        gramados da sua região, compare preços em tempo real e organize
                        sua partida com total autonomia.
                    </p>
                </div>
            </div>



            <div class="w-2/6 flex flex-col justify-center items-center gap-12 pr-12">

                <div class="flex flex-col items-center justify-top w-[400px] h-[230px] bg-gradient-to-b from-[#4ad658] to-[#2d8d36] rounded-2xl">

                    <h2 class="w-[300px] mt-8 text-[25px]">
                        Registre sua conta de <br> jogador
                    </h2>
    
                    <a href="./src/php/cadastro/cadastra_jogador.php" class="flex justify-center items-center h-[50px] w-[300px] text-[#4ad658] bg-white hover:bg-[#4ad658] hover:text-white rounded-2xl text-[16px] mt-12">Registrar-se</a>
    
                </div>
            
                <div class="flex flex-col items-center justify-top w-[400px] h-[230px] bg-gradient-to-b from-[#4ad658] to-[#2d8d36] rounded-2xl">

                    <h2 class="w-[300px] mt-8 text-[25px]">
                        Registre sua conta de <br> proprietário
                    </h2>
    
                    <a href="./src/php/cadastro/cadastra_proprietario.php" class="flex justify-center items-center h-[50px] w-[300px] text-[#4ad658] bg-white hover:bg-[#4ad658] hover:text-white rounded-2xl text-[16px] mt-12">Registrar-se</a>
    
                </div>
            </div>
        </div>
    </div>

</body>
</html>