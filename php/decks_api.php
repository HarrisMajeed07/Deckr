<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
require_once "db.php";
$method = $_SERVER["REQUEST_METHOD"];

if ($method === "OPTIONS") { exit; }

if ($method === "GET") {
    if (isset($_GET['id'])) {
        $id   = (int) $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM decks WHERE id = ?");
        $stmt->execute([$id]);
        $deck = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($deck ?: []);
    } else {
        $stmt = $pdo->query("SELECT * FROM decks ORDER BY last_edited DESC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}
elseif ($method === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $pdo->prepare("INSERT INTO decks (name, description) VALUES (?, ?)");
    $stmt->execute([$data["name"], $data["description"]]);
    echo json_encode(["id" => $pdo->lastInsertId(), "message" => "Deck created"]);
}

elseif ($method === "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $pdo->prepare("UPDATE decks SET name=?, description=? WHERE id=?");
    $stmt->execute([$data["name"], $data["description"], $data["id"]]);
    echo json_encode(["message" => "Deck updated"]);
}

elseif ($method === "DELETE") {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $pdo->prepare("DELETE FROM decks WHERE id=?");
    $stmt->execute([$data["id"]]);
    echo json_encode(["message" => "Deck deleted"]);
}
?>