<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$host = 'localhost';
$db = 'ordenaflask';
$user = 'root';
$pass = '123456';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['username']) || empty($data['fullname']) || empty($data['password']) || empty($data['email']) || empty($data['usertype'])) {
            echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios.']);
            exit;
        }

        $stmt = $pdo->prepare('INSERT INTO users (username, password_hash, email, usertype, full_name) 
                               VALUES (:username, :password, :email, :usertype, :fullname)');
        $stmt->execute([
            ':username' => $data['username'],
            ':fullname' => $data['fullname'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':email' => $data['email'],
            ':usertype' => $data['usertype'],
        ]);

        echo json_encode(['success' => true, 'message' => 'Usuário cadastrado com sucesso!']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
}
?>
