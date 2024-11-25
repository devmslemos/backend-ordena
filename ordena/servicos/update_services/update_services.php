<?php
$host = 'localhost';
$db = 'ordenaflask';
$user = 'root';
$pass = '123456';

$conn = new mysqli($host, $user, $pass, $db);

// Enable CORS for all domains (you can restrict this to specific domains for security purposes)
header("Access-Control-Allow-Origin: *"); // You can replace "*" with a specific domain, e.g., http://localhost:3000
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Allow specific methods
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allow the headers that you need

$data = json_decode(file_get_contents('php://input'), true);  // Pega os dados enviados no corpo da requisição

// Validação dos dados
if (!isset($data['service_id'], $data['service_name'], $data['description'], $data['price'], $data['duration'])) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

// Atualizar o serviço no banco de dados
$sql = "UPDATE services SET service_name = ?, description = ?, price = ?, duration = ? WHERE service_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssdii", $data['service_name'], $data['description'], $data['price'], $data['duration'], $data['service_id']);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Serviço atualizado com sucesso']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o serviço']);
}

$stmt->close();
$conn->close();
?>
