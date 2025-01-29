<?php
declare(strict_types=1);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Povolit Vue přístup
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$pdo = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Inicializace DB
$pdo->exec("CREATE TABLE IF NOT EXISTS TodoItems (
    Id TEXT PRIMARY KEY,   
    Title TEXT NOT NULL,   
    Content TEXT NOT NULL, 
    State INTEGER NOT NULL 
)");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':  // 🟢 READ
        $stmt = $pdo->query("SELECT * FROM TodoItems");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'POST': // 🟢 CREATE
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("INSERT INTO TodoItems (Id, Title, Content, State) VALUES (?, ?, ?, ?)");
        $stmt->execute([uniqid(), $data['Title'], $data['Content'], $data['State']]);
        echo json_encode(["message" => "Created"]);
        break;

    case 'PUT':  // 🟢 UPDATE
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("UPDATE TodoItems SET Title = ?, Content = ?, State = ? WHERE Id = ?");
        $stmt->execute([$data['Title'], $data['Content'], $data['State'], $data['Id']]);
        echo json_encode(["message" => "Updated"]);
        break;

    case 'DELETE': // 🟢 DELETE
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("DELETE FROM TodoItems WHERE Id = ?");
        $stmt->execute([$data['Id']]);
        echo json_encode(["message" => "Deleted"]);
        break;

    default:
        echo json_encode(["message" => "Method Not Allowed"]);
}
