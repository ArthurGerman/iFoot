<?php
    require '../../../../vendor/autoload.php'; // Carrega o autoloader do Composer
    require_once '../../config.php'; // Conexão com o banco de dados
    require_once '../../authenticate_prop.php';

    use Dompdf\Dompdf;
    use Dompdf\Options;

    // Configurações do Dompdf
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);


    $ID_QUAD = $_GET['id'];

    // Consulta ao banco de dados para obter as consultas
    $query = $pdo->prepare("SELECT * FROM PARTIDAS WHERE ID_QUAD = ?");
    $query->execute([$ID_QUAD]);
    $partidas = $query->fetchAll(PDO::FETCH_ASSOC);

    $html = '
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Histórico de partidas</title>
        <style>
            body { font-family: Arial, sans-serif; font-size: 12px; }
            h1 { font-size: 18px; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #000; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
        </style>
    </head>
    <body>

        <h1>Detalhes das partidas</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Horário (Início)</th>
                    <th>Horário (Fim)</th>
                </tr>
            </thead>
            <tbody>';
                foreach ($partidas as $partida) {
                    $html .= '<tr>
                        <td>' . $partida['ID_PTD'] . '</td>
                        <td>' . date('d/m/Y', strtotime($partida['DATA_PTD'])) . '</td>
                        <td>' . $partida['HORARIO_INICIO_PTD'] . '</td>
                        <td>' . $partida['HORARIO_FIM_PTD'] . '</td>
                    </tr>';
                }
    $html .= '
            </tbody>
        </table>
    </body>
    </html>';

    // Carrega o HTML no Dompdf
    $dompdf->loadHtml($html);

    // Define o tamanho do papel e a orientação
    $dompdf->setPaper('A4', 'portrait');

    // Renderiza o PDF
    $dompdf->render();

    // Envia o PDF para o navegador
    $dompdf->stream("lista_partidas.pdf", ["Attachment" => false]);
 ?>