<?php
$host = 'localhost';
$db = 'ordenaflask';
$user = 'root';
$pass = '123456';

$conn = new mysqli($host, $user, $pass, $db);

header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$data = json_decode(file_get_contents('php://input'), true);  // Pega os dados enviados no corpo da requisição

// Validação dos dados
if (!isset($data['service_id'])) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

// Deletar o serviço no banco de dados
$sql = "DELETE FROM services WHERE service_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $data['service_id']);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Serviço deletado com sucesso']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao deletar o serviço']);
}

$stmt->close();
$conn->close();
?>
