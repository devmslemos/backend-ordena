<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$host = 'localhost';
$db = 'ordenaflask';
$user = 'root';
$pass = '123456';

// Conectar ao banco de dados
$conn = new mysqli($host, $user, $pass, $db);

// Verifica se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Falha na conexão com o banco de dados', 'error' => $conn->connect_error]));
}

// Verifica se a requisição é um GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Pega a consulta da URL, se existir
    $query = isset($_GET['query']) ? "%" . $_GET['query'] . "%" : '%';  // Usa '%' por padrão para retornar todos os serviços

    // Consulta SQL para buscar todos os serviços
    $sql = "SELECT service_id, service_name, description, price, duration FROM services WHERE service_name LIKE ? OR description LIKE ?";
    
    // Preparar e executar a consulta
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $query, $query); // Binding do parâmetro para busca no nome e descrição
        $stmt->execute();
        
        // Pega os resultados da consulta
        $result = $stmt->get_result();
        $services = [];

        // Organiza os resultados em um array
        while ($row = $result->fetch_assoc()) {
            $services[] = $row;
        }

        // Retorna os dados dos serviços em formato JSON
        echo json_encode(['success' => true, 'data' => $services]);
        
        // Fecha a declaração e a conexão
        $stmt->close();
    } else {
        // Caso a preparação da consulta falhe
        echo json_encode(['success' => false, 'message' => 'Erro ao preparar a consulta']);
    }

    // Fecha a conexão com o banco
    $conn->close();
}
?>
