<?php
$host = 'localhost';
$db = 'ordenaflask';
$user = 'root';
$pass = '123456';

$conn = new mysqli($host, $user, $pass, $db);

header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization");
    
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        header('HTTP/1.1 200 OK');
        exit();
    }
    

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Falha na conexão com o banco de dados', 'error' => $conn->connect_error]));
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['service_name'], $data['description'], $data['price'], $data['duration'])) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

$sql = "INSERT INTO services (service_name, description, price, duration) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssdi", $data['service_name'], $data['description'], $data['price'], $data['duration']);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Serviço criado com sucesso']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao criar o serviço']);
}

$stmt->close();
$conn->close();
?>
