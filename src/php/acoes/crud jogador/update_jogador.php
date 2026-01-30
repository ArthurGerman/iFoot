<?php

    require_once '../../config.php';
    require_once '../../authenticate_jog.php';

    $mensagem_erro = ""; // Variável para armazenar a mensagem de erro
    $mensagem_sucesso = ""; // Variável para armazenar a mensagem de sucesso

    $NOME_JOG = $_SESSION['name_jog'];
    $ID_JOG = $_SESSION['id_jog'];

    $query = $pdo->prepare("
        SELECT 
        JOGADORES.NOME_JOG,
        JOGADORES.EMAIL_JOG,
        JOGADORES.CPF_JOG,
        JOGADORES.CIDADE_JOG,
        JOGADORES.ENDERECO_JOG,
        JOGADORES.TEL_JOG,
        JOGADORES.ID_UF,
        UF.SIGLA_UF

        FROM JOGADORES
        INNER JOIN UF ON JOGADORES.ID_UF = UF.ID_UF
        WHERE JOGADORES.ID_JOG = ?
    ");
    $query->execute([$ID_JOG]);
    $results = $query->fetch(PDO::FETCH_ASSOC);

    // ****IMPORTANTE: Da forma que está aqui em baixo, o update só será feito dos dados que foram modificados. Se nenhum dado foi modificado, o update não será feito 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
        $NOME_JOG = $_POST['NOME_JOG'];
        $EMAIL_JOG = $_POST['EMAIL_JOG'];
        $CIDADE_JOG = $_POST['CIDADE_JOG'];
        $SIGLA_UF = $_POST['UF'];
        $ENDERECO_JOG = $_POST['ENDERECO_JOG'];
        $TEL_JOG = $_POST['TEL_JOG'];
        $SENHA_JOG = $_POST['SENHA_JOG']; // Senha nova


        
        $query2 = $pdo -> prepare("SELECT ID_UF FROM UF WHERE SIGLA_UF = ?");
        $query2 -> execute([$SIGLA_UF]);
        $ID_UF = $query2->fetchColumn();



        $CAMPOS_JOG = []; //Array que juntas apenas os campos que vão fazer o update
        $DADOS_JOG = []; //Array com os dados que foram alterados
        $reload = false; // Essa variável serve para o usuário ser redirecionado para o login apenas se ele mudar e-mail e/ou senha  





        //Bloco de verificações de email para garantir que o usuário não altere para algum que já exista
        $verifica_email = $pdo->prepare("SELECT 1 FROM JOGADORES WHERE EMAIL_JOG = ? AND ID_JOG != ?");

        $verifica_email->execute([$EMAIL_JOG, $ID_JOG]);

        if ($verifica_email->rowCount() > 0){

            $_SESSION['mensagem_erro'] =  "❌ Este e-mail já está sendo usado por outro usuário.<br>";


        } else{ // Bloco de alteração de dados

            // Nome
            if ($NOME_JOG !== $results['NOME_JOG'] && $NOME_JOG !== '') {
                $CAMPOS_JOG[] = 'NOME_JOG = ?';
                $DADOS_JOG[] = $NOME_JOG;
            }

            // Email
            if ($EMAIL_JOG !== $results['EMAIL_JOG'] && $EMAIL_JOG !== '') {
                $CAMPOS_JOG[] = 'EMAIL_JOG = ?';
                $DADOS_JOG[] = $EMAIL_JOG;

                $reload = true;
            }

            // Cidade
            if ($CIDADE_JOG !== $results['CIDADE_JOG'] && $CIDADE_JOG !== '') {
                $CAMPOS_JOG[] = 'CIDADE_JOG = ?';
                $DADOS_JOG[] = $CIDADE_JOG;
            }

            // Endereço
            if ($ENDERECO_JOG !== $results['ENDERECO_JOG'] && $ENDERECO_JOG !== '') {
                $CAMPOS_JOG[] = 'ENDERECO_JOG = ?';
                $DADOS_JOG[] = $ENDERECO_JOG;
            }

            // Telefone
            if ($TEL_JOG !== $results['TEL_JOG'] && $TEL_JOG !== '') {
                $CAMPOS_JOG[] = 'TEL_JOG = ?';
                $DADOS_JOG[] = $TEL_JOG;
            }

            // UF
            if ($ID_UF !== $results['ID_UF']) {
                $CAMPOS_JOG[] = 'ID_UF = ?';
                $DADOS_JOG[] = $ID_UF;
            }

            // Senha (tratamento especial)
            if (!empty($SENHA_JOG)) {
                $CAMPOS_JOG[] = 'SENHA_JOG = ?';
                $DADOS_JOG[] = password_hash($SENHA_JOG, PASSWORD_BCRYPT);

                $reload = true;
            }

            if(empty($CAMPOS_JOG)){
                $_SESSION['mensagem_erro'] = "⚠️ Nenhuma informação foi alterada.";

            } else{
                $DADOS_JOG[] = $ID_JOG;

                $sql = "UPDATE JOGADORES SET " . implode(', ', $CAMPOS_JOG) . " WHERE ID_JOG = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($DADOS_JOG);

                //Mensagem de confirmação de sucesso na edição de dados
                $_SESSION['mensagem_sucesso'] = "✅ Dados alterados com sucesso!";

                //Atualiza sessão apenas dos campos alterados
                if ($NOME_JOG !== $results['NOME_JOG']) {
                    $_SESSION['name_jog'] = $NOME_JOG;
                }

                if ($reload){
                    $_SESSION = [];

                    session_destroy();

                    header("Location: ../../login/login_jog.php");
                    exit();

                } else{
                    header("Location: update_jogador.php");
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

<body class=" font-outfit font-medium not-italic text-[#6b6b6b]">

    <div class="bg-[#F0F0F0] w-full min-h-screen overflow-x-hidden flex flex-col">


        <!-- Nav -->
        <div class="flex bg-gradient-to-b from-[#4ad658] to-green-500 h-20">
            <img src="/static/ifoot.png" alt="" class="h-20">
        </div>

    
    
        <a href="./inicio_jog.php">
            <button>
                <span class="material-symbols-outlined w-10 h-10 flex items-center justify-center rounded-xl bg-gray-300 hover:bg-gray-400 transition mt-4 ml-4">reply</span>
            </button>
        </a>

        <div class="mt-6 pl-6 w-full">
            <h1 class="text-[28px]  w-auto h-auto flex items-center justify-start ml-4">
                Aqui estão as suas informações pessoais
            </h1>
        </div>
    

        <div class="flex mt-6 w-screen">

            <div class="w-1/2 flex">

                <form action="" method="post" class="">

                    <div class="space-y-4 w-96 ml-28">
                        
                        <div class="flex flex-row w-full">
                            <div class="w-1/5 flex items-center">
                                <label for="NOME_JOG">Nome: </label>

                            </div>

                            <div class="w-4/5">
                                <input 
                                    type="text" 
                                    name="NOME_JOG" 
                                    id="NOME_JOG" 
                                    class="w-full h-9 px-3 rounded-md border border-gray-300 outline-none" 
                                    value="<?=$results["NOME_JOG"]?>"
                                >
                            </div>
                        </div>

                        
                        <div class="flex flex-row w-full">
                            <div class="w-1/5 flex items-center">
                                <label for="EMAIL_JOG">E-mail: </label>
                            </div>

                            <div class="w-4/5">
                                <input 
                                    type="email" 
                                    name="EMAIL_JOG" 
                                    id="EMAIL_JOG"
                                    class="w-full h-9 px-3 rounded-md border border-gray-300 outline-none" 
                                    value="<?=$results["EMAIL_JOG"]?>"
                                >
                            </div>
                        </div>


                        <div class="flex flex-row w-full">
                            <div class="w-1/5 flex items-center">
                                <label for="CPF_JOG">CPF: </label>
                            </div>

                            <div class="w-4/5">
                                <input 
                                    type="text" 
                                    name="CPF_JOG" 
                                    id="CPF_JOG" 
                                    maxlength="11"
                                    class="w-full h-9 px-3 rounded-md border border-gray-300 outline-none" 
                                    value="<?=$results["CPF_JOG"]?>" 
                                    disabled
                                >
                            </div>
                        </div>


                        <div class="flex flex-row w-full">
                            <div class="w-1/5 flex items-center">
                                <label for="CIDADE_JOG">Cidade: </label>
                            </div>

                            <div class="w-4/5">
                                <input 
                                    type="text" 
                                    name="CIDADE_JOG" 
                                    id="CIDADE_JOG"
                                    class="w-full h-9 px-3 rounded-md border border-gray-300 outline-none" 
                                    value="<?=$results["CIDADE_JOG"]?>"
                                >
                            </div>
                        </div>


                        <div class="flex flex-row w-full">
                            <div class="w-1/5 flex items-center">
                                <label for="UF">UF: </label>
                            </div>

                            <div class="w-4/5">
                                <select name="UF" id="UF" class="w-full h-9 px-3 rounded-md border border-gray-300 outline-none">
                                    <option value="" <?= ($results["SIGLA_UF"] == "") ? 'selected' : '' ?>>Selecione</option>
                                    <option value="AC" <?= ($results["SIGLA_UF"] == "AC") ? 'selected' : '' ?>>Acre</option>
                                    <option value="AL" <?= ($results["SIGLA_UF"] == "AL") ? 'selected' : '' ?>>Alagoas</option>
                                    <option value="AP" <?= ($results["SIGLA_UF"] == "AP") ? 'selected' : '' ?>>Amapá</option>
                                    <option value="AM" <?= ($results["SIGLA_UF"] == "AM") ? 'selected' : '' ?>>Amazonas</option>
                                    <option value="BA" <?= ($results["SIGLA_UF"] == "BA") ? 'selected' : '' ?>>Bahia</option>
                                    <option value="CE" <?= ($results["SIGLA_UF"] == "CE") ? 'selected' : '' ?>>Ceará</option>
                                    <option value="DF" <?= ($results["SIGLA_UF"] == "DF") ? 'selected' : '' ?>>Distrito Federal</option>
                                    <option value="ES" <?= ($results["SIGLA_UF"] == "ES") ? 'selected' : '' ?>>Espírito Santo</option>
                                    <option value="GO" <?= ($results["SIGLA_UF"] == "GO") ? 'selected' : '' ?>>Goiás</option>
                                    <option value="MA" <?= ($results["SIGLA_UF"] == "MA") ? 'selected' : '' ?>>Maranhão</option>
                                    <option value="MT" <?= ($results["SIGLA_UF"] == "MT") ? 'selected' : '' ?>>Mato Grosso</option>
                                    <option value="MS" <?= ($results["SIGLA_UF"] == "MS") ? 'selected' : '' ?>>Mato Grosso do Sul</option>
                                    <option value="MG" <?= ($results["SIGLA_UF"] == "MG") ? 'selected' : '' ?>>Minas Gerais</option>
                                    <option value="PA" <?= ($results["SIGLA_UF"] == "PA") ? 'selected' : '' ?>>Pará</option>
                                    <option value="PB" <?= ($results["SIGLA_UF"] == "PB") ? 'selected' : '' ?>>Paraíba</option>
                                    <option value="PR" <?= ($results["SIGLA_UF"] == "PR") ? 'selected' : '' ?>>Paraná</option>
                                    <option value="PE" <?= ($results["SIGLA_UF"] == "PE") ? 'selected' : '' ?>>Pernambuco</option>
                                    <option value="PI" <?= ($results["SIGLA_UF"] == "PI") ? 'selected' : '' ?>>Piauí</option>
                                    <option value="RJ" <?= ($results["SIGLA_UF"] == "RJ") ? 'selected' : '' ?>>Rio de Janeiro</option>
                                    <option value="RN" <?= ($results["SIGLA_UF"] == "RN") ? 'selected' : '' ?>>Rio Grande do Norte</option>
                                    <option value="RS" <?= ($results["SIGLA_UF"] == "RS") ? 'selected' : '' ?>>Rio Grande do Sul</option>
                                    <option value="RO" <?= ($results["SIGLA_UF"] == "RO") ? 'selected' : '' ?>>Rondônia</option>
                                    <option value="RR" <?= ($results["SIGLA_UF"] == "RR") ? 'selected' : '' ?>>Roraima</option>
                                    <option value="SC" <?= ($results["SIGLA_UF"] == "SC") ? 'selected' : '' ?>>Santa Catarina</option>
                                    <option value="SP" <?= ($results["SIGLA_UF"] == "SP") ? 'selected' : '' ?>>São Paulo</option>
                                    <option value="SE" <?= ($results["SIGLA_UF"] == "SE") ? 'selected' : '' ?>>Sergipe</option>
                                    <option value="TO" <?= ($results["SIGLA_UF"] == "TO") ? 'selected' : '' ?>>Tocantins</option>
                                </select>
                            </div>
                        </div>


                        <div class="flex flex-row w-full">
                            <div class="w-1/5 flex items-center">
                                <label for="ENDERECO_JOG">Endereço: </label>
                            </div>

                            <div class="w-4/5">
                                <input 
                                    type="endereco" 
                                    name="ENDERECO_JOG" 
                                    id="ENDERECO_JOG"
                                    class="w-full h-9 px-3 rounded-md border border-gray-300 outline-none"
                                    value="<?=$results["ENDERECO_JOG"]?>"
                                >
                            </div>
                        </div>

                        
                        <div class="flex flex-row w-full">
                            <div class="w-1/5 flex items-center">
                                <label for="TEL_JOG">Telefone: </label>
                            </div>

                            <div class="w-4/5">
                                <input 
                                    type="telefone" 
                                    name="TEL_JOG" 
                                    id="TEL_JOG" 
                                    maxlength="11"
                                    class="w-full h-9 px-3 rounded-md border border-gray-300 outline-none" 
                                    value="<?=$results["TEL_JOG"]?>"
                                >
                            </div>
                        </div>
                        
                        
                        <div class="flex flex-row w-full">
                            <div class="w-1/5 flex items-center">
                                <label for="SENHA_JOG">Senha: </label>
                            </div>

                            <div class="w-4/5">
                                <input 
                                    type="password" 
                                    name="SENHA_JOG" 
                                    id="SENHA_JOG" 
                                    placeholder="Digite uma nova senha(opcional)"
                                    class="w-full h-9 px-3 rounded-md border border-gray-300 outline-none"
                                >
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
                <div class="w-72 h-72 rounded-full bg-gray-300 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[80px] text-white">
                        person
                    </span>
                </div>
                
                <button class="mt-4 text-sm text-green-600 hover:underline">
                    Mudar Imagem
                </button>
            </div>
        </div>
        
        
        <div class="w-full flex mt-2">
            <div class="ml-20 w-full">
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






    <script src="/src/js/tratamento-erros-update_jog.js"></script>
    <script src="/src/js/some_mensagem.js"></script>
</body>
</html>