<?php
require_once "../config.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $NOME_PROP = $_POST['NOME_PROP'];
        $EMAIL_PROP = $_POST['EMAIL_PROP'];
        $SENHA_PROP = $_POST['SENHA_PROP'];
        $CPF_PROP = $_POST['CPF_PROP'];
        $TEL_PROP = $_POST['TEL_PROP'];

        $query = $pdo->prepare("INSERT INTO PROPRIETARIOS (NOME_PROP, EMAIL_PROP, SENHA_PROP, CPF_PROP, TEL_PROP) VALUES (?,?,?,?,?)");
        $query->execute([$NOME_PROP, $EMAIL_PROP, $SENHA_PROP, $CPF_PROP, $TEL_PROP]);
        

        echo "Olá $NOME_PROP ! Seus dados foram cadastrados com sucesso <br>";
        echo "<button><a href='cadastra_jogador.php'>Voltar</a></button>";
    } else{
        echo "<strong><p style='color:red'>Requisição inválida. Não usou método POST</p></strong>";
    }

?>