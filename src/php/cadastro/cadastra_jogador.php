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
            $mensagem_erro = "❌ CPF e Email já estão cadastrados no sistema.<br>";

        } else if ($verifica_email->rowCount() > 0){
            $mensagem_erro =  "❌ Este e-mail já está cadastrado no sistema.<br>";

        } else if ($verifica_cpf->rowCount() > 0){
            $mensagem_erro =  "❌ Este CPF já está cadastrado no sistema.<br>";

        } else{

            //Query para fazer a inserção de dados no banco
    
            $query2 = $pdo->prepare("INSERT INTO JOGADORES (NOME_JOG, EMAIL_JOG, CPF_JOG, CIDADE_JOG, ENDERECO_JOG, TEL_JOG, SENHA_JOG, ID_UF) VALUES (?,?,?,?,?,?,?,?)");
            $query2->execute([$NOME_JOG, $EMAIL_JOG, $CPF_JOG, $CIDADE_JOG, $ENDERECO_JOG, $TEL_JOG, $SENHA_JOG, $ID_UF]);
            
            header('Location: ../login/login_jog.php');
    
            //echo "Olá $NOME_JOG ! Seus dados foram cadastrados com sucesso <br>";
            //echo "<button><a href='../login/login_jog.php'>Login</a></button>";
        }

    }
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastramento</title>
</head>
<body>
    <h1>
        Cadastramento de usuário
    </h1>

    <a href="/index.php">Voltar</a><br><br>
    
    <form action="" method="post">

        <label for="NOME_JOG">Nome: </label>
        <input type="text" name="NOME_JOG" id="NOME_JOG"><br>
        
        <label for="EMAIL_JOG">E-mail: </label>
        <input type="email" name="EMAIL_JOG" id="EMAIL_JOG"><br>

        <label for="CPF_JOG">CPF: </label>
        <input type="text" name="CPF_JOG" id="CPF_JOG" maxlength="11"><br>

        <label for="CIDADE_JOG">Cidade: </label>
        <input type="text" name="CIDADE_JOG" id="CIDADE_JOG"><br>

        <label for="UF">UF: </label>
        <select name="UF" id="UF">
            <option value="">Selecione</option>
            <option value="AC">Acre</option>
            <option value="AL">Alagoas</option>
            <option value="AP">Amapá</option>
            <option value="AM">Amazonas</option>
            <option value="BA">Bahia</option>
            <option value="CE">Ceará</option>
            <option value="DF">Distrito Federal</option>
            <option value="ES">Espírito Santo</option>
            <option value="GO">Goiás</option>
            <option value="MA">Maranhão</option>
            <option value="MT">Mato Grosso</option>
            <option value="MS">Mato Grosso do Sul</option>
            <option value="MG">Minas Gerais</option>
            <option value="PA">Pará</option>
            <option value="PB">Paraíba</option>
            <option value="PR">Paraná</option>
            <option value="PE">Pernambuco</option>
            <option value="PI">Piauí</option>
            <option value="RJ">Rio de Janeiro</option>
            <option value="RN">Rio Grande do Norte</option>
            <option value="RS">Rio Grande do Sul</option>
            <option value="RO">Rondônia</option>
            <option value="RR">Roraima</option>
            <option value="SC">Santa Catarina</option>
            <option value="SP">São Paulo</option>
            <option value="SE">Sergipe</option>
            <option value="TO">Tocantins</option>
        </select><br>
        
        <label for="ENDERECO_JOG">Endereço: </label>
        <input type="endereco" name="ENDERECO_JOG" id="ENDERECO_JOG"><br>
        
        <label for="TEL_JOG">Telefone: </label>
        <input type="telefone" name="TEL_JOG" id="TEL_JOG" maxlength="11"><br>
        
        <label for="SENHA_JOG">Senha</label>
        <input type="password" name="SENHA_JOG" id="SENHA_JOG"><br>
        
        <input type="submit">
    </form>

    <?php if (!empty($mensagem_erro)) :?>
        <p style="color:red"><?= $mensagem_erro ?></p>
    <?php endif;?> 

    <script src="/src/js/tratamento-erros_jog.js"></script>
</body>
</html>