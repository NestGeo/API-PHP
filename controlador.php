<?php
require 'database.php';

header("Content-Type: application/json");

$requestMethod = $_SERVER["REQUEST_METHOD"];
$requestUri = str_replace("/api_crud/api", "", $_SERVER['REQUEST_URI']);


// CRUD
switch ($requestMethod) {
    case 'GET':
        echo $requestMethod;
        if (preg_match('/\/user\/(\d+)/', $requestUri, $matches)) {
            $userId = $matches[1];
            getUser($userId);
        } else {
            getUsers();
        }
        break;
    case 'POST':
        createUser();
        break;
    case 'PUT':
        if (preg_match('/\/user\/(\d+)/', $requestUri, $matches)) {
            $userId = $matches[1];
            updateUser($userId);
        }
        break;
    case 'DELETE':
        if (preg_match('/\/user\/(\d+)/', $requestUri, $matches)) {
            $userId = $matches[1];
            deleteUser($userId);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(["message" => "Método no permitido"]);
        break;
}

// FUNCIONES PARA CRUD
function getUsers() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM usuarios");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function getUser($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
}

function createUser() {
    global $pdo;
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, edad) VALUES (?, ?, ?)");
    if ($stmt->execute([$data['nombre'], $data['email'], $data['edad']])) {
        echo json_encode(["message" => "Usuario creado"]);
    }
}

function updateUser($id) {
    global $pdo;
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ?, edad = ? WHERE id = ?");
    if ($stmt->execute([$data['nombre'], $data['email'], $data['edad'], $id])) {
        echo json_encode(["message" => "Usuario actualizado"]);
    }
}

function deleteUser($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    if ($stmt->execute([$id])) {
        echo json_encode(["message" => "Usuario eliminado"]);
    }
}
?>
