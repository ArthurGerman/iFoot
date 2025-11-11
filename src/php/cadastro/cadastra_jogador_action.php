<?php 
    require_once "../config.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $NOME_JOG = $_POST['NOME_JOG'];
        $EMAIL_JOG = $_POST['EMAIL_JOG'];
        $CPF_JOG = $_POST['CPF_JOG'];
        $CIDADE_JOG = $_POST['CIDADE_JOG'];
        $ENDERECO_JOG = $_POST['ENDERECO_JOG'];
        $TEL_JOG = $_POST['TEL_JOG'];
        $SENHA_JOG = $_POST['SENHA_JOG'];

        $SIGLA_UF = $_POST['UF'];

        //Query para descobrir o id correspondente à UF

        $query1 = $pdo->prepare("SELECT ID_UF FROM UF WHERE SIGLA_UF = ?");
        $query1->execute([$SIGLA_UF]);
        $result = $query1->fetch(PDO::FETCH_ASSOC);

        $ID_UF = $result['ID_UF'];


        //Query para fazer a inserção de dados no banco

        $query2 = $pdo->prepare("INSERT INTO JOGADORES (NOME_JOG, EMAIL_JOG, CPF_JOG, CIDADE_JOG, ENDERECO_JOG, TEL_JOG, SENHA_JOG, ID_UF) VALUES (?,?,?,?,?,?,?,?)");
        $query2->execute([$NOME_JOG, $EMAIL_JOG, $CPF_JOG, $CIDADE_JOG, $ENDERECO_JOG, $TEL_JOG, $SENHA_JOG, $ID_UF]);
        

        echo "Olá $NOME_JOG ! Seus dados foram cadastrados com sucesso <br>";
        echo "<button><a href='cadastra_jogador.php'>Voltar</a></button>";
    } else{
        echo "<strong><p style='color:red'>Requisição inválida. Não usou método POST</p></strong>";
    }
?>