<?php

    require_once '../../config.php';
    require_once '../../authenticate_jog.php';

    $mensagem_erro = ""; // Variável para armazenar a mensagem de erro que aparece caso o usuário tente cadastrar um email e/ou senha que já existem no banco de dados

    $ID_JOG = $_SESSION['id_jog'];

    $query = $pdo->prepare("
        SELECT 
        JOGADORES.NOME_JOG,
        JOGADORES.EMAIL_JOG,
        JOGADORES.CPF_JOG,
        JOGADORES.CIDADE_JOG,
        JOGADORES.ENDERECO_JOG,
        JOGADORES.TEL_JOG,
        UF.SIGLA_UF

        FROM JOGADORES
        INNER JOIN UF ON JOGADORES.ID_UF = UF.ID_UF
        WHERE JOGADORES.ID_JOG = ?
    ");
    $query->execute([$ID_JOG]);
    $results = $query->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $NOME_JOG = $_POST['NOME_JOG'];
        $EMAIL_JOG = $_POST['EMAIL_JOG'];
        $CIDADE_JOG = $_POST['CIDADE_JOG'];
        $ENDERECO_JOG = $_POST['ENDERECO_JOG'];
        $TEL_JOG = $_POST['TEL_JOG'];
        $SENHA_JOG = $_POST['SENHA_JOG']; // Senha nova

        $SIGLA_UF = $_POST['UF'];

        $query2 = $pdo -> prepare("SELECT ID_UF FROM UF WHERE SIGLA_UF = ?");
        $query2 -> execute([$SIGLA_UF]);
        $ID_UF = $query2->fetchColumn();

        //Bloco de verificações de CPF e email para garantir que o usuário não altere para algum que já exista
        $verifica_email = $pdo->prepare("SELECT 1 FROM JOGADORES WHERE EMAIL_JOG = ? AND ID_JOG != ?");

        $verifica_email->execute([$EMAIL_JOG, $ID_JOG]);

        if ($verifica_email->rowCount() > 0){

            $mensagem_erro =  "❌ Este e-mail já está sendo usado por outro usuário.<br>";

        } else{ // Bloco de alteração de dados

            if(!empty($SENHA_JOG)){ // Se a senha não estiver vazia, o sistema atualiza a senha pois não tem como alterar uma senha que já foi feito o hash

                $SENHA_JOG = password_hash($SENHA_JOG, PASSWORD_BCRYPT); //Senha nova com hash

                $query3 = $pdo -> prepare("
                    UPDATE JOGADORES
                    SET NOME_JOG = ?, 
                    EMAIL_JOG = ?,
                    CIDADE_JOG = ?,
                    ENDERECO_JOG = ?,
                    TEL_JOG = ?,
                    SENHA_JOG = ?,
                    ID_UF = ?
        
                    WHERE ID_JOG = ?
                ");
                
                $query3 -> execute([
                    $NOME_JOG,
                    $EMAIL_JOG,
                    $CIDADE_JOG,
                    $ENDERECO_JOG,
                    $TEL_JOG,
                    $SENHA_JOG,
                    $ID_UF,
        
                    $ID_JOG
                ]);


            } else{ // Se o usuário não colocou uma senha nova, o sistema não atualiza
                $query3 = $pdo -> prepare("
                    UPDATE JOGADORES
                    SET NOME_JOG = ?, 
                    EMAIL_JOG = ?,
                    CIDADE_JOG = ?,
                    ENDERECO_JOG = ?,
                    TEL_JOG = ?,
                    ID_UF = ?
        
                    WHERE ID_JOG = ?
                ");
                
                $query3 -> execute([
                    $NOME_JOG,
                    $EMAIL_JOG,
                    $CIDADE_JOG,
                    $ENDERECO_JOG,
                    $TEL_JOG,
                    $ID_UF,
        
                    $ID_JOG
                ]);


            }
    
            header("Location: ../../login/login_jog.php");
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualização de dados</title>
</head>

<body>
    <h1>
        Atualização de dados do jogador
    </h1>

    <a href="./inicio_jog.php">Voltar</a><br><br>

    <form action="" method="post">

        <label for="NOME_JOG">Nome: </label>
        <input type="text" name="NOME_JOG" id="NOME_JOG" value="<?=$results["NOME_JOG"]?>"><br>

        <label for="EMAIL_JOG">E-mail: </label>
        <input type="email" name="EMAIL_JOG" id="EMAIL_JOG" value="<?=$results["EMAIL_JOG"]?>"><br>

        <label for="CPF_JOG">CPF: </label>
        <input type="text" name="CPF_JOG" id="CPF_JOG" maxlength="11" value="<?=$results["CPF_JOG"]?>" disabled><br>

        <label for="CIDADE_JOG">Cidade: </label>
        <input type="text" name="CIDADE_JOG" id="CIDADE_JOG" value="<?=$results["CIDADE_JOG"]?>"><br>

        <label for="UF">UF: </label>
        <select name="UF" id="UF">
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
        </select><br>

        <label for="ENDERECO_JOG">Endereço: </label>
        <input type="endereco" name="ENDERECO_JOG" id="ENDERECO_JOG" value="<?=$results["ENDERECO_JOG"]?>"><br>

        <label for="TEL_JOG">Telefone: </label>
        <input type="telefone" name="TEL_JOG" id="TEL_JOG" maxlength="11" value="<?=$results["TEL_JOG"]?>"><br>

        <label for="SENHA_JOG">Senha: </label>
        <input type="password" name="SENHA_JOG" id="SENHA_JOG" placeholder="Digite uma nova senha(opcional)"><br>

        <input type="submit">
    </form>

    <?php if (!empty($mensagem_erro)) :?>
        <p style="color:red"><?= $mensagem_erro ?></p>
    <?php endif;?> 

    <script src="/src/js/tratamento-erros-update_jog.js"></script>
</body>
</html>