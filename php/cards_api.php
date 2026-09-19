<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
require_once "db.php";

$method = $_SERVER["REQUEST_METHOD"];
if ($method === "OPTIONS") { exit; }

if ($method === "GET") {
    $deck_id = (int) ($_GET["deck_id"] ?? 0);
    if (!$deck_id) {
        echo json_encode([]);
        exit;
    }
    $stmt = $pdo->prepare("SELECT * FROM cards WHERE deck_id = ? ORDER BY id ASC");
    $stmt->execute([$deck_id]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

elseif ($method === "POST") {
    $data    = json_decode(file_get_contents("php://input"), true);
    $deck_id = (int) $data["deck_id"];
    $cards   = $data["cards"] ?? [];

    $del = $pdo->prepare("DELETE FROM cards WHERE deck_id = ?");
    $del->execute([$deck_id]);

    $ins = $pdo->prepare("INSERT INTO cards (deck_id, term, definition) VALUES (?, ?, ?)");
    foreach ($cards as $card) {
        $ins->execute([$deck_id, $card["term"], $card["definition"]]);
    }

    echo json_encode(["message" => "Cards saved"]);
}
?>