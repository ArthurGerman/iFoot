<?php 

    require_once "../../config.php";
    require_once '../../authenticate_prop.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $ENDERECO_QUAD = $_POST['ENDERECO_QUAD'];
        $CIDADE_QUAD = $_POST['CIDADE_QUAD'];
        $SIGLA_UF = $_POST['UF'];
        $NOME_MODAL = $_POST['NOME_MODAL'];
        $PRECO_HORA_QUAD = str_replace(['.', ','], ['', '.'], $_POST['PRECO_HORA_QUAD']);
        
        $ID_PROP = $_SESSION['id_prop'];

        //Query para descobrir o id correspondente à UF

        $query_1 = $pdo->prepare("SELECT ID_UF FROM UF WHERE SIGLA_UF = ?");
        $query_1->execute([$SIGLA_UF]);
        $result_1 = $query_1->fetch(PDO::FETCH_ASSOC);
        $ID_UF = $result_1['ID_UF'];

        // Query para descobrir o id correspondente à modalidade

        $query_2 = $pdo->prepare("SELECT ID_MODAL FROM MODALIDADES WHERE NOME_MODAL = ?");
        $query_2->execute([$NOME_MODAL]);
        $result_2 = $query_2->fetch(PDO::FETCH_ASSOC);
        $ID_MODAL = $result_2['ID_MODAL'];


        //Query para fazer a inserção de dados no banco

        $query_3 = $pdo->prepare("INSERT INTO QUADRAS (ENDERECO_QUAD, CIDADE_QUAD, ID_UF, ID_MODAL, PRECO_HORA_QUAD, ID_PROP) VALUES (?,?,?,?,?,?)");
        $query_3->execute([$ENDERECO_QUAD, $CIDADE_QUAD, $ID_UF, $ID_MODAL, $PRECO_HORA_QUAD, $ID_PROP]);
        

        header('Location: ./lista_quadras.php');
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
    <title>Cadastramento de quadra</title>
</head>
<body>
    <h2>
        Cadastramento de quadra
    </h2>

    <a href="./inicio_prop.php">Voltar</a><br><br>

    <form action="" method="post">

        
        <label for="ENDERECO_QUAD">Endereço: </label>
        <input type="text" name="ENDERECO_QUAD" id="ENDERECO_QUAD"><br>
        
        <label for="CIDADE_QUAD">Cidade: </label>
        <input type="text" name="CIDADE_QUAD" id="CIDADE_QUAD"><br>
        
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
        
        <label for="NOME_MODAL">Modalidade: </label>
        <select name="NOME_MODAL" id="NOME_MODAL">
            <option value="">Selecione</option>
            <option value="CAMPO">Campo</option>
            <option value="SOCIETY">Society</option>
            <option value="QUADRA">Quadra</option>
        </select><br>
        
        <label for="PRECO_HORA_QUAD">Preço da hora: </label>
        <input type="text" name="PRECO_HORA_QUAD" id="PRECO_HORA_QUAD" value="0,00" oninput="formatarMoeda(this)"><br>

        <input type="submit">
    </form>

    <script src="../../../js/formata_preco_quadra.js"></script>
    <script src="../../../js/tratamento-erros_cad-quadra.js"></script>
</body>
</html>