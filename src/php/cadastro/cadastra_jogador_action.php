<?php 
    require_once "../config.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $NOME_JOG = $_POST['NOME_JOG'];
        $EMAIL_JOG = $_POST['EMAIL_JOG'];
        $SENHA_JOG = $_POST['SENHA_JOG']; 
        $CPF_JOG = $_POST['CPF_JOG'];
        $CIDADE_JOG = $_POST['CIDADE_JOG'];
        $ENDERECO_JOG = $_POST['ENDERECO_JOG'];
        $TEL_JOG = $_POST['TEL_JOG'];

        $query = $pdo->prepare("INSERT INTO jogadores (NOME_JOG, EMAIL_JOG, SENHA_JOG, CPF_JOG, CIDADE_JOG, ENDERECO_JOG, TEL_JOG) VALUES (?,?,?,?,?,?,?)");
        $query->execute([$NOME_JOG, $EMAIL_JOG, $SENHA_JOG, $CPF_JOG, $CIDADE_JOG, $ENDERECO_JOG, $TEL_JOG]);
        

        echo "Olá $NOME_JOG ! Seus dados foram cadastrados com sucesso <br>";
        echo "<button><a href='cadastra_jogador.php'>Voltar</a></button>";
    } else{
        echo "<strong><p style='color:red'>Requisição inválida. Não usou método POST</p></strong>";
    }
?>