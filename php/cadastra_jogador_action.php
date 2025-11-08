<?php 
    require_once "config.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $CPF = $_POST['CPF'];
        $cidade = $_POST['cidade'];
        $UF = $_POST['UF'];
        $endereco = $_POST['endereco'];
        $telefone = $_POST['telefone'];

        $query = $pdo->prepare("INSERT INTO jogador (nome, email, CPF, cidade, UF, endereco, telefone) VALUES (?,?,?,?,?,?,?)");
        $query->execute([$nome, $email, $CPF, $cidade, $UF, $endereco, $telefone]);
        

        echo "Olá $nome ! Seus dados foram cadastrados com sucesso <br>";
        echo "<button><a href='cadastra_jogador.php'>Voltar</a></button>";
    } else{
        echo "<p style='color:red'>Requisição inválida. Não usou método POST</p>";
    }
?>