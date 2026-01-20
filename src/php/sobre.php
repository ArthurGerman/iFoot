<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="/src/styles/global.css">
    <title>iFoot - Sobre</title>
</head>
<body class=" font-outfit font-medium not-italic text-white">

    <div class=" w-screen h-screen flex flex-col">

        <div class=" bg-gradient-to-b from-[#4ad658] to-green-500 flex flex-row items-center h-20 pl-24">

            <img src="/static/ifoot.png" alt="" id="ifoot-logo" class=" h-28">
            
            <a href="" id="funcionamento" class=" no-underline text-white p-24 -ml-10">Como funciona</a>

            <div class=" flex gap-7 pr-12 ml-auto">

                <a href="../php/login/login_jog.php" 
                class="flex p-5 border-2 border-solid border-white rounded-2xl h-10 items-center hover:bg-white hover:text-[#4ad658]"
                >Entrar como jogador</a>

                <a href="../php/login/login_prop.php" 
                class="flex p-5 border-2 border-solid border-white rounded-2xl h-10 items-center hover:bg-white hover:text-[#4ad658]"
                >Entrar como proprietário</a>

            </div>
            
        </div>

        <!-- bg-[url(/static/img_background.jpeg)] esse é outro metodo de colocar uma imagem com tailwind ou criando pelo config mesmo como esta abaixo bg-hero -->
        <div class=" bg-hero flex-1 bg-cover bg-center bg-no-repeat flex flex-col items-center p-4">  
            <div class=" h-5/6 w-7/12 bg-gradient-to-b from-[#4ad658] to-green-500 flex flex-col items-center m-12 border-2 border-solid border-[#4ad658] rounded-2xl p-3 gap-5">
                <h1 class=" text-2xl font-bold text-center text-white">Sobre</h1>
    
                <div class=" h-5/6 w-8/12 border-2 border-solid border-white rounded-2xl flex flex-col items-center gap-3 p-5 text-base">
                    <p class=" text-center">Projeto de alunos do 2° período do curso de Tecnologia em 
                        Sistemas para Internet, no período letivo 2025.2, orientado pela 
                        Professora Liliane Sales. O grupo é composto por:</p>

                    <div class="flex flex-col items-center">
                        <p>Arthur Germano;</p>
                        <p>Elder Macena;</p>
                        <p>Gabriel Luna;</p>
                        <p>Guilherme Evaristo;</p>
                        <p>Lucas Bezerra;</p>
                        <p>Thiago Henrique.</p>
                    </div>
                        
                    <p class=" text-center">Liguagens e ferramentas utilizadas: MySQL, PHP, JavaScript, Css, 
                        HTML, Copilot (integrado ao Visual Code), ChatGPT.</p>
                        
                    <div><h2>imagens</h2></div>
                </div>
    
                <a href="/index.php" class=" w-1/6 h-1/12 flex flex-col p-1 border-2 border-solid border-white rounded-2xl  items-center bg-white text-[#4ad658]">Voltar</a>
            </div>
        </div>
    </div>

</body>
</html>