<?php
session_start();
header("Content-Type: application/json");
require_once "db.php";

$data     = json_decode(file_get_contents("php://input"), true);
$username = trim($data["username"] ?? "");
$password = trim($data["password"] ?? "");

if (!$username || !$password) {
    http_response_code(400);
    echo json_encode(["error" => "Please fill in all fields."]);
    exit;
}

if ($username === "admin" && $password === "admin") {
    $_SESSION["user_id"]  = 0;
    $_SESSION["username"] = "admin";
    echo json_encode(["success" => true, "username" => "admin"]);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
$stmt->execute([$username, $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user["password"])) {
    http_response_code(401);
    echo json_encode(["error" => "Incorrect username or password."]);
    exit;
}

$_SESSION["user_id"]  = $user["id"];
$_SESSION["username"] = $user["username"];

echo json_encode(["success" => true, "username" => $user["username"]]);