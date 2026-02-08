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
            $mensagem = "❌ Esta partida já ocorreu e não pode <br> mais ser editada.";

            $_SESSION['mensagem_update_partida'] = $mensagem;
            $_SESSION['partida_erro'] = $ID_PTD;

            header("Location: ./lista_partida.php");
            exit;
        }




        if(empty($mensagem)){ // Se a partida ainda não ocorreu, então ela pode ser editada

            if($DATA_PTD !== $partida['DATA_PTD'] || $HORARIO_INICIO_PTD !== $partida['HORARIO_INICIO_PTD'] || $HORARIO_FIM_PTD !== $partida['HORARIO_FIM_PTD']){
    
    
                if ($data_hora_partida < $data_hora_agora ) { // Testa se o usuário está tentando atualizar em uma data/horário que já passou
                    $mensagem = "❌ Não é permitido atualizar partidas <br> em datas ou horários que já passaram.";
                }
    
                elseif ($HORARIO_FIM_PTD <= $HORARIO_INICIO_PTD) { // Testa se o usuário está colocando o horário de fim antes do horário de início da partida
                    $mensagem = "❌ O horário final deve ser maior que <br> o horário inicial.";
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
                        $mensagem = "❌ Horário indisponível. Já existe uma <br> partida nesse período.";
                    }

                    $_SESSION['mensagem_update_partida'] = $mensagem;
                    $_SESSION['partida_erro'] = $ID_PTD;

                    header("Location: ./lista_partida.php");
                    exit;
                }
    
            } else{
                $mensagem = "⚠️ Nenhuma informação foi alterada.";

                $_SESSION['mensagem_update_partida'] = $mensagem;
                $_SESSION['partida_erro'] = $ID_PTD;

                header("Location: ./lista_partida.php");
                exit;
            }
        }




    }

?>