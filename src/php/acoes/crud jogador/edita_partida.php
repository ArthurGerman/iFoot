<?php

    require_once '../../config.php';
    require_once '../../authenticate_jog.php';

    $ID_PTD = $_GET['id'];

    $mensagem = "";


    $query = $pdo -> prepare("SELECT * FROM PARTIDAS WHERE ID_PTD = ?");
    $query -> execute([$ID_PTD]);
    $partida = $query -> fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        date_default_timezone_set('America/Sao_Paulo');

        $DATA_PTD = $_POST['DATA_PTD'];
        $HORARIO_INICIO_PTD = $_POST['HORARIO_INICIO_PTD'];
        $HORARIO_FIM_PTD = $_POST['HORARIO_FIM_PTD'];


        $data_hora_agora = new DateTime();
        $data_original_partida = new DateTime($partida['DATA_PTD'] . ' ' . $partida['HORARIO_INICIO_PTD']); // Data e horário da partida que já foram cadastrados no banco
        $data_hora_partida = new DateTime($DATA_PTD . ' ' . $HORARIO_INICIO_PTD);


        if ($data_original_partida < $data_hora_agora) { // Se a data que a partida foi cadastrada já passou, não tem como editar mais os dados
            $mensagem = "❌ Esta partida já ocorreu e não pode mais ser editada.";
        }




        if(empty($mensagem)){ // Se a partida ainda não ocorreu, então ela pode ser editada

            if($DATA_PTD !== $partida['DATA_PTD'] || $HORARIO_INICIO_PTD !== $partida['HORARIO_INICIO_PTD'] || $HORARIO_FIM_PTD !== $partida['HORARIO_FIM_PTD']){
    
    
                if ($data_hora_partida < $data_hora_agora ) { // Testa se o usuário está tentando atualizar em uma data/horário que já passou
                    $mensagem = "❌ Não é permitido atualizar partidas em datas ou horários que já passaram.<br>";
                }
    
                if ($HORARIO_FIM_PTD <= $HORARIO_INICIO_PTD) { // Testa se o usuário está colocando o horário de fim antes do horário de início da partida
                    $mensagem .= "❌ O horário final deve ser maior que o horário inicial.";
                }
    
    
    
    
                // Query para saber se há conflito de horário com outra partida já cadastrada
                $stmt = $pdo->prepare("SELECT HORARIO_INICIO_PTD, HORARIO_FIM_PTD FROM PARTIDAS WHERE ID_QUAD = ? AND DATA_PTD = ? AND ID_PTD != ?");
                $stmt->execute([$partida['ID_QUAD'], $DATA_PTD, $ID_PTD]);
                $times = $stmt;
        
                $conflict = false;
        
                if ($stmt->rowCount() > 0) {
                    foreach ($times as $time) {
                        if ($HORARIO_INICIO_PTD < $time["HORARIO_FIM_PTD"] && $HORARIO_FIM_PTD > $time["HORARIO_INICIO_PTD"]) {
                            $conflict = true;
                            break;
                        }
                    };
                }
        
        
        
        
    
                // Bloco que verifica se tem conflito de horário com outras partidas e se não existe mensagem de erro. Se não ouver nenhum dos dois o update é feito
                if (empty($mensagem) && !$conflict){
        
        
                    //Bloco de código que faz todos os cálculos de preço total da partida
                    $query1 = $pdo->prepare("
                        SELECT QUADRAS.PRECO_HORA_QUAD 
                        FROM PARTIDAS
                        INNER JOIN QUADRAS ON PARTIDAS.ID_QUAD = QUADRAS.ID_QUAD
                        WHERE ID_PTD = ?
                    ");
                    $query1->execute([$ID_PTD]);
                    $dados_quadra = $query1->fetch(PDO::FETCH_ASSOC);
                    $PRECO_HORA_QUAD = (float) $dados_quadra['PRECO_HORA_QUAD'];
            
                    $HORARIO_INICIO_PTD_FORMAT = new DateTime($HORARIO_INICIO_PTD);
                    $HORARIO_FIM_PTD_FORMAT = new DateTime($HORARIO_FIM_PTD);
            
                    if ($HORARIO_FIM_PTD_FORMAT < $HORARIO_INICIO_PTD_FORMAT) { // Caso a partida seja em horários incomuns(ex: 22:00 até 01:00) esse if resolve
                        $HORARIO_FIM_PTD_FORMAT->modify('+1 day');
                    }
                    
                    $intervalo = $HORARIO_INICIO_PTD_FORMAT->diff($HORARIO_FIM_PTD_FORMAT); // Diferença de tempo
                    $duracao_horas = $intervalo->h + ($intervalo->i / 60); // Transformação do tempo em horas
                    
                    $PRECO_TOTAL_PTD = $duracao_horas * $PRECO_HORA_QUAD;
                
        
        
        
    
    
                    // Bloco de update dos dados
                    $query2 = $pdo -> prepare("
                        UPDATE PARTIDAS
                        SET DATA_PTD = ?, 
                        HORARIO_INICIO_PTD = ?,
                        HORARIO_FIM_PTD = ?,
                        PRECO_TOTAL_PTD = ?
            
                        WHERE ID_PTD = ?
                    ");
                    
                    $query2 -> execute([
                        $DATA_PTD,
                        $HORARIO_INICIO_PTD,
                        $HORARIO_FIM_PTD,
                        $PRECO_TOTAL_PTD,
            
                        $ID_PTD
                    ]);
                    
                    header("Location: ./lista_partida.php");
                    exit();
        
                } else{
                    if($conflict && empty($mensagem)){
                        $mensagem = "❌ Horário indisponível. Já existe uma partida nesse período.";
                    }
                }
    
            } else{
                $mensagem = "⚠️ Nenhuma informação foi alterada.";
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
    <title>Edição de partida</title>
</head>

<body class=" font-outfit font-medium not-italic text-white">

    <div class="relative bg-gradient-to-b from-[#2ba438] to-[#14551a] w-screen h-screen flex flex-col justify-center items-center">

        <div class="bg-gradient-to-b from-[#4ad658] to-green-500 h-[400px]  p-10 rounded-2xl">
            <h1 class="text-[28px]">
                Ediçao da partida
            </h1>


            <form action="" method="post">

                <div class="flex flex-col mt-2">
                    <label for="DATA_PTD">Data: </label>
                    <input type="date" name="DATA_PTD" id="DATA_PTD" value="<?= $partida['DATA_PTD'] ?>" class="text-[#6b6b6b] h-9 px-3 rounded-md border border-gray-300 outline-none mb-3">
            
                    <label for="HORARIO_INICIO_PTD">Início da Partida: </label>
                    <input type="time" name="HORARIO_INICIO_PTD" id="HORARIO_INICIO_PTD" value="<?= $partida['HORARIO_INICIO_PTD'] ?>" class="text-[#6b6b6b] h-9 px-3 rounded-md border border-gray-300 outline-none mb-3">
            
                    <label for="HORARIO_FIM_PTD">Fim da Partida: </label>
                    <input type="time" name="HORARIO_FIM_PTD" id="HORARIO_FIM_PTD" value="<?= $partida['HORARIO_FIM_PTD'] ?>" class="text-[#6b6b6b] h-9 px-3 rounded-md border border-gray-300 outline-none mb-3">
                </div>



                <div class="flex flex-row gap-4 mt-4">
                    <a href="./lista_partida.php" class="flex w-1/2 h-10 bg-white text-green-600 hover:bg-gray-200 justify-center items-center rounded-xl">Voltar</a>
                    <input type="submit" value="Editar" class="flex cursor-pointer w-1/2 h-10 bg-white text-green-600 hover:bg-gray-200 justify-center rounded-xl">
                </div>
            </form>

        </div>

        <div class="h-12 flex items-center justify-center mt-4">
            <?php if(!empty($mensagem)) :?>
                <p id="msg" class="text-red-400"><?= $mensagem ?></p>
            <?php endif?>
        </div>
    </div>



    <script src="/src/js/tratamento-erros_partida.js"></script>
    <script src="/src/js/some_mensagem.js"></script>
</body>

</html>